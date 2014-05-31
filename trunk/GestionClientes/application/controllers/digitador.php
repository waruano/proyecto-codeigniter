<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class digitador extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            if ($this->tank_auth->get_rol() != 1)
                redirect('');
        }
    }
    
    function documentos()
    {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 6;
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('documento');
        $crud->set_subject("Documentos");
        $crud->columns('EMPID', 'NUMERO', 'TIPO', 'ESTADO');
        $crud->display_as('EMPID', 'Asignado a');
        $crud->display_as('NUMERO', 'Número');
        $crud->display_as('TIPO', 'Tipo');
        
        $crud->set_relation('EMPID','Persona','{Nombres} {Apellidos}', array('TIPOPERSONA' => '2'));  
        
        $crud->edit_fields('EMPID', 'NUMERO', 'TIPO', 'ESTADO');
        $crud->required_fields('NUMERO', 'TIPO', 'ESTADO');
        $crud->add_fields('EMPID', 'NUMERO', 'TIPO', 'ESTADO'); 
        $crud->field_type('NUMERO','integer');
        $crud->field_type('TIPO', 'dropdown', array('1' => 'Contrato', '2' => 'Recibo de Caja'));
        $crud->field_type('ESTADO', 'dropdown', array('1' => 'Asignado', '2' => 'Reportado', '3' => 'Anulado'));
        
        //$crud->add_action('Costos', '', 'Administrador/costosplan');
        //$crud->add_action('Tarifas', base_url() . 'images/money.png', 'Costos','',array($this,'direccion_planes'));
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Planes');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'digitador/documentos', $output);
        $this->template->render();
    }

    function pagos()
    {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 8;
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('pago');
        $crud->set_subject("Pagos");
        $crud->columns('RECID', 'TITID', 'VALOR', 'FECHA');
        $crud->display_as('RECID', 'Recibo de caja');
        $crud->display_as('TITID', 'Titular de contrato');
        $crud->display_as('VALOR', 'Valor pagado');
        $crud->display_as('FECHA', 'Fecha de pago');
        
        $crud->set_relation('RECID','Documento','{Numero}', array('Estado' => '1'));        
        $crud->set_relation('TITID','Persona','{Nombres} {Apellidos}', array('TIPOPERSONA' => '0'));        
        
        $crud->edit_fields('RECID', 'TITID', 'VALOR', 'FECHA');
        $crud->required_fields('RECID', 'TITID', 'VALOR', 'FECHA');
        $crud->add_fields('RECID', 'TITID', 'VALOR', 'FECHA');
        
        $crud->field_type('FECHAHASTA', 'date');
        $crud->field_type('VALOR', 'integer');
        
        //$crud->add_action('Costos', '', 'Administrador/costosplan');
        //$crud->add_action('Tarifas', base_url() . 'images/money.png', 'Costos','',array($this,'direccion_planes'));
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Planes');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'digitador/pagos', $output);
        $this->template->render();
    }
}
