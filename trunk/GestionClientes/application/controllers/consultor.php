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
        // se obtienen los datos del filtro
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
        $crud->basic_model->set_query_str(
           "select 'Estado', documento.Numero as NumeroContrato, persona.Nombres + ' ' + persona.Apellidos as NombreTitular, 
            persona.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion
            from persona inner join contrato on contrato.TITID = persona.ID inner join documento on documento.ID = contrato.DOCID
            where contrato.ESTADO = 0"); //Query text here
        $output = $crud->render();
        
        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Búsqueda de Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'consultor/consulta', $output);
        $this->template->render();
    }
}