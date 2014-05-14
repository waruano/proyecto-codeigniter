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
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Previmed');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'pages/home');
        $this->template->render();
    }

    function Usuarios() {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();

        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('users');
        $crud->set_subject("Usuarios");
        $crud->set_relation('identificadorRol', 'roles', 'nombreRol');
        $crud->columns('username', 'email', 'created', 'identificadorRol');
        $crud->display_as('identificadorRol', 'Rol');
        $crud->display_as('created', 'Fecha Creacion');
        $crud->display_as('conf_password', 'Confirmar Contraseña');
        $crud->display_as('password', 'Contraseña');
        $crud->edit_fields('username', 'email', 'identificadorRol');        
        $crud->field_type('password', 'password');
        $crud->field_type('conf_password', 'password');
        $crud->required_fields('username', 'password', 'conf_password', 'email','identificadorRol');
        $crud->add_fields('username', 'password', 'conf_password', 'email', 'identificadorRol');
        $crud->unset_read();
        $crud->unique_fields('username', 'email');
        $crud->set_rules('username', 'Username', 'required|trim|xss_clean|min_length[6]|max_length[50]');
        $crud->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|max_length[100]');
        $crud->set_rules('password', 'Contraseña', 'required|trim|xss_clean|max_length[255]|matches[conf_password]');
        $crud->set_rules('conf_password', 'Confirmar Contraseña', 'required|trim|xss_clean|max_length[255]');
        $crud->unique_fields('username', 'email');
        $crud->callback_insert(array($this, 'registrar_usuario_callback'));
        $crud->unset_back_to_list();
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Usuarios');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/usuarios', $output);
        $this->template->render();
    }

    function registrar_usuario_callback($post_array) {
        echo '<script>alert("callback");</script>';
        $data['errors'] = array();
        $email_activation = $this->config->item('email_activation', 'tank_auth');
        if (!is_null($data = $this->tank_auth->create_user_rol($post_array['username'], $post_array['email'], $post_array['password'], $email_activation, $post_array['identificadorRol']))) {         // success
            $data['site_name'] = $this->config->item('website_name', 'tank_auth');
            unset($data['password']); // Clear password (just for any case)
        } else {
            $errors = $this->tank_auth->get_error_message();
            foreach ($errors as $k => $v)
                $data['errors'][$k] = $this->lang->line($v);
        }
    }
    
    function planes()
    {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('plan');
        $crud->set_subject("planes");        
        $crud->columns('NOMBRE','FORMAPAGO', 'PERIODICIDAD', 'TIPOPLAN', 'NOMBRECONVENIO');
        $crud->display_as('NOMBRE', 'Nombre Plan');
        $crud->display_as('FORMAPAGO', 'Forma de Pago');
        $crud->display_as('PERIODICIDAD', 'Periodicidad');
        $crud->display_as('TIPOPLAN', 'Tipo de Plan');
        $crud->display_as('NOMBRECONVENIO', 'Nombre de Convenio');
        $crud->edit_fields('NOMBRE', 'FORMAPAGO', 'PERIODICIDAD', 'TIPOPLAN', 'NOMBRECONVENIO');        
        $crud->required_fields('NOMBRE', 'FORMAPAGO', 'PERIODICIDAD', 'TIPOPLAN');
        $crud->add_fields('NOMBRE','FORMAPAGO', 'PERIODICIDAD', 'TIPOPLAN', 'NOMBRECONVENIO');
        $crud->unset_read();
        $crud->unique_fields('NOMBRE');
                
        $crud->field_type('FORMAPAGO','dropdown',array('1' => 'Domicilio', '2' => 'Debito Automático','3' => 'Convenio' ));
        $crud->field_type('PERIODICIDAD','dropdown',array('1' => 'Mensual', '2' => 'Trimestral','3' => 'Semestral','4' => 'Anual' ));
        $crud->field_type('TIPOPLAN','dropdown',array('1' => 'Individual', '2' => 'Familiar','3' => 'Convenio'));
        
        $crud->unset_back_to_list();
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Planes');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/planes', $output);
        $this->template->render();
    }
    
    

}