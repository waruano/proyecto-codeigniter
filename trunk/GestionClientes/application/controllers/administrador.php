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
        $data['selectedoption'] = 0;
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
        $data['selectedoption'] = 1;
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        //configuracion de la tabla
        $crud->set_table('users');
        $crud->set_subject("Usuarios");
        //relacion Usuario tiene Rol
        $crud->set_relation('identificadorRol', 'roles', 'nombreRol');
        //renombrado de atributos
        $crud->display_as('identificadorRol', 'Rol');
        $crud->display_as('created', 'Fecha Creacion');
        $crud->display_as('conf_password', 'Confirmar Contraseña');
        $crud->display_as('password', 'Contraseña');
        $crud->columns('username', 'email', 'created', 'identificadorRol');
        //obtenemos la accion a ejecutar (add,edit,view)
        $state = $crud->getState();
        switch ($state) {
            case 'add':
                //atributos que se podran ingresar
                $crud->add_fields('username', 'password', 'conf_password', 'email', 'identificadorRol');
                $crud->field_type('password', 'password');
                $crud->field_type('conf_password', 'password');
                $crud->set_rules('password', 'Contraseña', 'required|trim|xss_clean|max_length[255]|matches[conf_password]');
                $crud->set_rules('conf_password', 'Confirmar Contraseña', 'required|trim|xss_clean|max_length[255]');
                break;
            case 'list':
                //Columnas a mostrar en la lista
                break;
            default:
                //atributos que se podran editar y visualizar
                $crud->edit_fields('username', 'email', 'identificadorRol');
                break;
        }
        $crud->required_fields('username', 'password', 'conf_password', 'email', 'identificadorRol');
        $crud->unset_back_to_list();
        $crud->unique_fields('username', 'email');
        $crud->set_rules('username', 'Username', 'required|trim|xss_clean|min_length[6]|max_length[50]');
        $crud->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|max_length[100]');
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

    function planes() {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 2;
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('plan');
        $crud->set_subject("planes");
        $crud->columns('NOMBRE', 'NUMBENEFICIARIOS', 'NOMBRECONVENIO');
        $crud->display_as('NOMBRE', 'Nombre Plan');
        
        $crud->display_as('NUMBENEFICIARIOS', 'Número de beneficiarios');
        $crud->display_as('NOMBRECONVENIO', 'Nombre de Convenio');
        $crud->edit_fields('NOMBRE', 'NUMBENEFICIARIOS', 'NOMBRECONVENIO');
        $crud->required_fields('NOMBRE', 'NUMBENEFICIARIOS');
        $crud->add_fields('NOMBRE', 'NUMBENEFICIARIOS', 'NOMBRECONVENIO');
        $crud->unset_read();        

        //$crud->field_type('FORMAPAGO', 'dropdown', array('1' => 'Domicilio', '2' => 'Debito Automático', '3' => 'Convenio'));
        //$crud->field_type('PERIODICIDAD', 'dropdown', array('1' => 'Mensual', '2' => 'Trimestral', '3' => 'Semestral', '4' => 'Anual'));
        //$crud->field_type('TIPOPLAN', 'dropdown', array('1' => 'Individual', '2' => 'Familiar', '3' => 'Convenio'));
        
        $crud->field_type('NUMBENEFICIARIOS', 'integer');
        //$crud->add_action('Costos', '', 'Administrador/costosplan');
        $crud->add_action('Tarifas', base_url() . 'images/money.png', 'Costos','',array($this,'direccion_planes'));
        $crud->add_action('Adicionar Contratos', base_url() . 'images/contrato.png', 'Contratos', '', array($this, 'direccion_contratosWizard'));
        
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Planes');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/planes', $output);
        $this->template->render();
    }
    
    function direccion_contratosWizard($primary_key, $row) {
        return base_url() . 'contratos/contratosWizard/' . $primary_key;
    }

    function direccion_contratos($primary_key, $row) {
        return base_url() . 'contratos/contratosEdit/' . $primary_key;
    }

    function direccion_planes($primary_key, $row) {
        return base_url() . 'administrador/costosplan/' . $primary_key;
    }

    function contactosEdit($titularId)
    {
        session_start();
        $_SESSION['_aux_var'] = $titularId;
        $_SESSION['_aux_wizard'] = false;
        $this->contactos();
    }    
    function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    
    function contactos() {   
        if ($this->is_session_started() === FALSE)
            session_start();
        //informacion del contrato y del plan
        if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {
            //informacion del Contrato y el Plan
            $contrato_id = $_SESSION['_aux_primary_key'];
            $this->load->model('contratosModel');
            $select_contrato = $this->contratosModel->get_contrato($contrato_id);
            if ($select_contrato != null) {
                $_aux_str = "Tipo de Contrato no Definido";
                switch ($select_contrato->TIPOCONTRATO) {
                    case '1':
                        $_aux_str = "Nuevo";
                        break;
                    case '2':
                        $_aux_str = "Adición";
                        break;
                    case '3':
                        $_aux_str = "Reactivación";
                        break;
                    case '4':
                        $_aux_str = "Reemplazo";
                        break;
                }
                $data['contrato_tipo'] = $_aux_str;
                unset($_aux_str);
                $_aux_str = "Periodicidad de Contrato No Definida";
                switch ($select_contrato->PERIODICIDAD) {
                    case '1':
                        $_aux_str = "Mensual";
                        break;
                    case '2':
                        $_aux_str = "Semestral";
                        break;
                    case '3':
                        $_aux_str = "Anual";
                        break;
                }
                $data['contrato_periodicidad'] = $_aux_str;
                unset($_aux_str);
                $data['contrato_fechaInicio'] = $select_contrato->FECHAINICIO;
                $select_plan = $this->contratosModel->get_plan($select_contrato->PLANID);
                if ($select_plan != null) {
                    $data['plan_nombre'] = $select_plan->NOMBRE;
                    $data['plan_beneficiarios'] = $select_plan->NUMBENEFICIARIOS;
                    $data['plan_convenio'] = $select_plan->NOMBRECONVENIO;
                }
            } else {
                $data['contrato_tipo'] = "Tipo de Contrato No Definido";
                $data['contrato_periodicidad'] = "Periodicidad de Contrato No Definida";
                $data['contrato_fechaInicio'] = "Fecha de Inicio No Definida";
                $data['plan_nombre'] = 'No se ha Especificado el Plan';
                $data['plan_beneficiarios'] = '';
                $data['plan_convenio'] = '';
            }
            //vista del wizard para agregar titular
            $content = 'Administrador/contactos_wizard';
            
        } else {
            $content = 'Administrador/contactos';
        }     
        $titularId = $_SESSION['_aux_var'];            
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 3;
        $data['step_wizard']=0;

        $valTitId = $titularId;
       $query = $this->db->query("SELECT NOMBRES, APELLIDOS, NODOCUMENTO, documento.numero 
                                   FROM Titular Left Join CONTRATO on Contrato.TitId = Titular.ID   and contrato.estado = 1
                                   left join documento on documento.id = contrato.docId   WHERE Titular.ID = " . $valTitId);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['titularFullName'] = $row->NOMBRES . ' ' . $row->APELLIDOS;
            $data['titularIdentificacion'] = $row->NODOCUMENTO;
            $data['titularContrato'] = $row->numero;
        } else {
            $data['titularFullName'] = 'Titular sin definir o no existe';
            $data['titularIdentificacion'] = '';
            $data['titularContrato'] =  '';
        }
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('contacto');
        $crud->where('TITID', $valTitId);
        $crud->set_subject("Contactos");
        $crud->columns('NOMBRECOMPLETO', 'PARENTESCO', 'TELDOMICILIO', 'TELMOVIL');
        $crud->display_as('NOMBRECOMPLETO', 'Nombre completo');
        $crud->display_as('PARENTESCO', 'Parentesco');        
        $crud->display_as('TELDOMICILIO', 'Teléfono domicilio');
        $crud->display_as('TELMOVIL', 'Teléfono móvil');
        $crud->edit_fields('NOMBRECOMPLETO', 'PARENTESCO', 'TELDOMICILIO', 'TELMOVIL', 'TITID');
        $crud->required_fields('NOMBRECOMPLETO', 'PARENTESCO', 'TELMOVIL');
        $crud->add_fields('NOMBRECOMPLETO', 'PARENTESCO', 'TELDOMICILIO', 'TELMOVIL', 'TITID');
        $crud->unset_read();
        
        $crud->field_type('TITID', 'hidden', $valTitId);
        $crud->field_type('TELDOMICILIO', 'integer' );
        $crud->field_type('TELMOVIL', 'integer' );
        
        $crud->buttons_form('sinGuardar');
        $crud->unset_back_to_list();
        
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Contactos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', $content, $output);
        $this->template->render();
    }

    function costosplan($planid) {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 2;

        $query = $this->db->query("SELECT NOMBRE FROM PLAN WHERE ID = " . $planid);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['planFullName'] = $row->NOMBRE;
        } else {
            $data['planFullName'] = 'Plan sin definir o no existe';
        }

        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('costoplan');
        $crud->where('PLANID', $planid);
        $crud->set_subject("Tarifa");
        $crud->columns('COSTOAFILIACION', 'COSTOPAGOMES', 'COSTOPAGOSEMESTRE', 'COSTOPAGOANIO', 'FECHADESDE', 'FECHAHASTA');
        $crud->display_as('COSTOAFILIACION', 'Costo afiliación');
        $crud->display_as('COSTOPAGOMES', 'Costo mensual');
        $crud->display_as('COSTOPAGOSEMESTRE', 'Costo semestral');
        $crud->display_as('COSTOPAGOANIO', 'Costo anual');
        $crud->display_as('FECHADESDE', 'Aplica desde');
        $crud->display_as('FECHAHASTA', 'Aplica hasta');
        $crud->edit_fields('COSTOAFILIACION', 'COSTOPAGOMES', 'COSTOPAGOSEMESTRE', 'COSTOPAGOANIO', 'FECHADESDE', 'FECHAHASTA', 'PLANID');
        $crud->required_fields('COSTOAFILIACION', 'COSTOPAGOMES', 'COSTOPAGOSEMESTRE', 'COSTOPAGOANIO', 'FECHADESDE', 'FECHAHASTA');
        $crud->add_fields('COSTOAFILIACION', 'COSTOPAGOMES', 'COSTOPAGOSEMESTRE', 'COSTOPAGOANIO', 'FECHADESDE', 'FECHAHASTA', 'PLANID');
        $crud->unset_read();
        $crud->field_type('PLANID', 'hidden', $planid);
        $crud->field_type('COSTOAFILIACION', 'integer');
        $crud->field_type('COSTOPAGOMES', 'integer');
        $crud->field_type('COSTOPAGOSEMESTRE', 'integer');
        $crud->field_type('COSTOPAGOANIO', 'integer');
        $crud->field_type('FECHADESDE', 'date');
        $crud->field_type('FECHAHASTA', 'date');


        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Costos por plan');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/costosplan', $output);
        $this->template->render();
    }
    
    
    function empleados() {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 7;
        //Configuracion Grocery_CRUD listado de usuarios
        $crud = new Grocery_CRUD();
        $crud->set_table('persona');
        $crud->where('TIPOPERSONA', '2');
        $crud->set_subject("Empleados");
        $crud->columns('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL','EMAIL');
        $crud->display_as('NOMBRES', 'Nombres');
        $crud->display_as('APELLIDOS', 'Apellidos');
        $crud->display_as('TIPODOC', 'Tipo documento');
        $crud->display_as('NODOCUMENTO', 'No documento');
        $crud->display_as('TELMOVIL', 'Teléfono móvil');
        $crud->display_as('EMAIL', 'E-mail');
        $crud->edit_fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL','EMAIL','TIPOPERSONA');
        $crud->required_fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL','EMAIL');
        $crud->add_fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL','EMAIL','TIPOPERSONA');
        $crud->unset_read();
        $crud->field_type('NODOCUMENTO','integer');
        $crud->field_type('TELMOVIL','integer');
        $crud->field_type('TIPOPERSONA', 'hidden', '2');
        $crud->field_type('TIPODOC', 'dropdown', array(1 => 'Cedula de Ciudadnia', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera'));
        $output = $crud->render();

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Planes');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/empleados', $output);
        $this->template->render();
    }
}

