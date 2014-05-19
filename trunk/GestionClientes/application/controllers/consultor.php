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
        
        $strSQL = "select 'Estado' as Estado, documento.Numero as NumeroContrato, CONCAT(persona.Nombres, ' ', persona.Apellidos) as NombreTitular, 
            persona.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, documento.Numero as ID
            from persona left join contrato on contrato.TITID = persona.ID left join documento on documento.ID = contrato.DOCID
            where contrato.ESTADO = 0";
       
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
        return 'costosplan/' . $primary_key;
    }
}