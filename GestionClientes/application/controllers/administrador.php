<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

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
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        //Configuracion de la Plantilla
        $this->template->write_view('login',$this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Previmed');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'pages/home');
        $this->template->render();
    }
    function Usuarios(){
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        
        //Configuracion Grocery_CRUD listado de usuarios
        $crud= new Grocery_CRUD();
        $crud->set_table('users');
        $crud->set_subject("Usuarios");
        $crud->set_relation('identificadorRol', 'roles', 'nombreRol');
        $crud->columns('username','email','created','identificadorRol');
        $crud->display_as('identificadorRol','Rol');
        $crud->display_as('created','Fecha Creacion');
        $crud->edit_fields('username','email','identificadorRol');
        $crud->set_rules('username','Username','trim|required|xss_clean|min_length[6]|max_length[50]');
        $crud->set_rules('email','Email','required|trim|xss_clean|valid_email|max_length[100]');
        $crud->unique_fields('username','email');
        $crud->unset_add();
        $output=$crud->render();
        
        //Configuracion de la Plantilla
        $this->template->write_view('login',$this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Previmed');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/usuarios',$output);
        $this->template->render();
    }

}