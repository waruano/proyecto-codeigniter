<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Consultor extends CI_Controller {

     function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            if ($this->tank_auth->get_rol() != 1)
                redirect('');
        }
    }

    function index() {
        
    }
    
    function consultar()
    {        
        $data['validentificacion'] = $this->input->post('identificacion');
        $data['valnocontrato'] = $this->input->post('numeroContrato');        
        $data['valtitular'] = $this->input->post('nombreTitular');
        
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 4;  
        
        $crud = new grocery_CRUD();
        $crud->set_model('Custom_query_model');
        $crud->set_table('persona'); //Change to your table name
        
        $strSQL = "select 'Estado' as Estado, documento.Numero as NumeroContrato, CONCAT(titular.Nombres, ' ', ifnull(titular.Apellidos, '')) as NombreTitular, 
            titular.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, titular.id as ID
            from titular left join contrato on contrato.TITID = titular.ID left join documento on documento.ID = contrato.DOCID
            where contrato.ESTADO = 1";
       
        if($data['valtitular'] != '' )
        {
            $strSQL = $strSQL . " AND CONCAT(persona.Nombres, ' ', persona.Apellidos) like '%" . $data['valtitular'] . "%' ";
        }        
        
        if($data['validentificacion'] != '' )
        {
            $strSQL = $strSQL . " AND persona.NoDocumento like '%" . $data['validentificacion'] . "%' ";
        }  
        
        if( is_numeric($data['valnocontrato']))
        {
            $strSQL = $strSQL . " AND documento.Numero = " . $data['valnocontrato'] . " ";
        }
                
        $crud->basic_model->set_query_str( $strSQL  ); //Query text here
        $crud->columns('Estado', 'NumeroContrato', 'NombreTitular', 'Identificacion', 'FechaAfiliacion');
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->add_action('Detalles', base_url() . 'images/magnifier.png', 'Detalles','',array($this,'direccion_contratos'));
        $crud->unset_jquery();       
        $output = $crud->render();
        
        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Búsqueda de Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'consultor/consulta', $output);
        $this->template->render();
    }
    
    function direccion_contratos($primary_key , $row)
    {
        return base_url() . 'consultor/detalleTitulares/' . $primary_key;
    }
    
    
    function detalleTitulares($noContrato)
    {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 4;  
        
        $valTitId = $noContrato;
        $query = $this->db->query("SELECT *
                                   FROM titular Left Join CONTRATO on Contrato.TitId = titular.ID   and contrato.estado = 1
                                   left join documento on documento.id = contrato.docId   WHERE titular.id = " . $valTitId);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['titular'] = $row;
            
        } else {
            $data['titular']  = null;
        }
        
        /// Se consultan los beneficiarios del titular
        $qbeneficiarios = $this->db->query("SELECT * FROM BENEFICIARIO WHERE titId = " . $valTitId );
        if($qbeneficiarios->num_rows() > 0 )
        {
            $data['beneficiarios']  = $qbeneficiarios->result() ;
            $data['tienebeneficiarios'] = true;
        }
        else {
            $data['tienebeneficiarios'] = false;
        }
        
        /// Se consultan los contactos del titular
        $qcontactos = $this->db->query("SELECT * FROM CONTACTO WHERE titId = " . $valTitId );
        if($qcontactos->num_rows() > 0 )
        {
            $data['contactos']  = $qcontactos->result() ;        
            $data['tienecontactos'] = true;
        }
        else {
            $data['tienecontactos'] = false;
        }
        
        /// Se consulta la informacion del contrato y los pagos realizados por el titular desde la vigencia del contrato
        $qcontrato = $this->db->query("SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, PLAN.FORMAPAGO, PLAN.PERIODICIDAD, PLAN.TIPOPLAN, 
                                        PLAN.NOMBRECONVENIO, DOCUMENTO.NUMERO, DOCUMENTO.TIPO
                                        FROM CONTRATO INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
                                        INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID WHERE contrato.ESTADO = 1 AND TITID = " .$valTitId );
        if($qcontrato->num_rows() > 0 )
        {
            $contrato = $query->row(0);
            $data['contrato'] = $contrato;
            $data['tienecontrato'] = true;
            
            $qpagos = $this->db->query("SELECT * FROM PAGO WHERE TITID = " . $valTitId . " AND FECHA >= " . $contrato->FECHAINICIO);
            
        }
        else
        {
            $data['tienecontrato'] = false;
        }
        
        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Búsqueda de Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'consultor/detalleTitulares');
        $this->template->render();
    }
}


