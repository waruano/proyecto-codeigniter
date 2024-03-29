<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contratos extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } elseif ($this->tank_auth->get_rol() != 1 && $this->tank_auth->get_rol() != 2) {
            redirect('');
        } else {
            $this->load->model('contratosModel');
        }
    }

    function index() {
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol == 1 || $session_rol == 2) {
            if ($this->is_session_started() === FALSE)
                session_start();
            $plan_id = $_SESSION['_aux_var'];

            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 2;

            //informacion del Plan
            $this->load->model('contratosModel');
            $select_plan = $this->contratosModel->get_plan($plan_id);

            if ($select_plan != null) {
                $data['plan_nombre'] = $select_plan->NOMBRE;
                $data['plan_beneficiarios'] = $select_plan->NUMBENEFICIARIOS;
                $data['plan_convenio'] = $select_plan->NOMBRECONVENIO;
                $data['debito'] = $select_plan->DEBITOAUTOMATICO;
            } else {
                $data['plan_nombre'] = 'No se ha Especificado el Plan';
                $data['plan_beneficiarios'] = '';
                $data['plan_convenio'] = '';
            }

            //creacion  del crud
            $crud = new Grocery_CRUD();
            $crud->where("PLANID", $plan_id);
            //restriccion de acciones
            if ($session_rol == 2) {
                //$crud->unset_edit();
                $crud->unset_delete();
            }
            if ($session_rol == 1) {
                $crud->add_action(' Terminar Contrato', base_url() . 'images/stop.png', 'Terminar Contrato', 'personalizada', array($this, 'direccion_terminar_contrato'));
            }
            $state = $crud->getState();

            //redireccionamiento para siguiente
            if (!$this->is_session_started())
                session_start();
            if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {
                //definicion de los botones del formulario
                $crud->buttons_form('siguienteContratos');
                if (isset($_SESSION['success_contrato']) && $_SESSION['success_contrato'] = true) {
                    unset($_SESSION['success_contrato']);

                    if (isset($_SESSION['_aux_primary_key'])) {
                        if ($_SESSION['_aux_primary_key'] == 1) {
                            unset($_SESSION['_aux_primary_key']);
                            redirect('contratos/titulares/add');
                        } else {
                            $aux_primary = $_SESSION['_aux_primary_key'];
                            unset($_SESSION['_aux_primary_key']);
                            redirect('contratos/titulares/edit/' . $aux_primary);
                        }
                    } else {
                        redirect('contratos/titulares/add');
                    }
                }
                $crud->set_relation('DOCID', 'DOCUMENTO', 'NUMERO', array('TIPO' => '1', 'ESTADO' => '1'));

                $crud->field_type('ESTADO', 'hidden', '1');
            } else {
                $crud->buttons_form('sinGuardar');
                $crud->set_relation('DOCID', 'DOCUMENTO', 'NUMERO', array('TIPO' => '1'));
                $crud->field_type('ESTADO', 'dropdown', array(0 => 'No', 1 => 'Si'));
                $crud->unset_add();
                //$crud->unset_edit();
                //$crud->unset_delete();
            }

            //configuracion de la tabla
            $crud->set_table('CONTRATO');
            $crud->set_subject("Contrato");
            $crud->set_relation('TITID', 'TITULAR', '{NODOCUMENTO} {NOMBRES} {APELLIDOS}', null, 'ID ASC');
            $crud->set_relation('PLANID', 'PLAN', 'NOMBRE');
            //Renombrado de los campos
            $crud->display_as('PLANID', 'Plan');
            $crud->display_as('TIPOCONTRATO', 'Tipo de Contrato');
            $crud->display_as('PERIODICIDAD', 'Periodicidad');
            $crud->display_as('FECHAINICIO', 'Fecha de Inicio');
            $crud->display_as('DOCID', 'Documento');
            $crud->display_as('ESTADO', '¿Activo?');
            $crud->display_as('TITID', 'Titular');
            $crud->columns('TITID', 'ESTADO', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID');
            //definicion de las reglas
            $crud->required_fields('TIPOCONTRATO', 'TITID', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID');

            //campos en formulario para agregar
            $crud->add_fields('PLANID', 'TITID', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID', 'ESTADO');
            //campos en formaulario para editar
            $crud->edit_fields('PLANID', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'ESTADO');

            //tipos de los campos
            $crud->field_type('TIPOCONTRATO', 'dropdown', array(1 => 'Nuevo', 2 => 'Adición', 3 => 'Reactivación', 4 => 'Reemplazo'));
            if ($select_plan->DEBITOAUTOMATICO == 0)
                $crud->field_type('PERIODICIDAD', 'dropdown', array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual'));
            else {
                $crud->field_type('PERIODICIDAD', 'dropdown', array(1 => 'Mensual'));
            }
            //$crud->field_type('PLANID', 'hidden', $plan_id);

            //callback
            $crud->callback_after_insert(array($this, '_callback_after_insert_contrato'));
            $crud->callback_before_insert(array($this, '_callback_before_insert_contrato'));
            //unsets
            $crud->unset_back_to_list();
            $crud->unset_read();
            //renderizacion del crud
            $output = $crud->render();
            //configuracion de la plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Contratos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Administrador/contratos', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }

    function direccion_terminar_contrato($primary_key, $row) {
        return base_url() . 'index.php/contratos/terminar_contrato/' . $primary_key;
    }

    function terminar_contrato($primary_key) {
        $this->db->where('ID', $primary_key);
        $this->db->where('ESTADO', '1');
        $factual = getdate();
        $valor = $factual['year'] . '-' . $factual['mon'] . '-' . $factual['mday'];
        $_estado = array('ESTADO' => '0', 'FECHAFIN' => $valor);
        $this->db->update('CONTRATO', $_estado);
        redirect('contratos');
    }

    function contratosEdit($primary_key) {
        session_start();
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = false;
        $this->index();
    }

    function contratosWizard($primary_key) {
        session_start();
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
        redirect('contratos/index/add');
    }

    function _callback_after_insert_contrato($post_array, $primary_key) {
        $titularId = $post_array['TITID'];
        $data = array('ESTADO' => '2');
        $this->db->where('ID', $post_array['DOCID']);
        $this->db->update('DOCUMENTO', $data);
        $_SESSION['_aux_primary_key'] = $titularId;
        $_SESSION['success_contrato'] = true;
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
        return true;
    }

    //solo para cuando se ingresan titulares antes del contrato
    function _callback_before_insert_contrato($post_array) {
        $titularId = $post_array['TITID'];
        if ($titularId != 1) {
            $this->db->where('TITID', $titularId);
            $contratos = $this->db->get('CONTRATO');
            list($día, $mes, $año) = split('/', $post_array["FECHAINICIO"]);
            $valor = $año . '-' . $mes . '-' . $día;
            foreach ($contratos->result() as $contrato) {
                $_estado = array('ESTADO' => '0', 'FECHAFIN' => $valor);
                $this->db->where('ID', $contrato->ID);
                $this->db->where('ESTADO', '1');
                $this->db->update('CONTRATO', $_estado);
            }
        }
        return $post_array;
    }

    function titulares() {

        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        
        if ($session_rol == 1 || $session_rol == 2) {


            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();

            $data['selectedoption'] = 3;
            //creacion  del crud
            $crud = new Grocery_CRUD();
            //restriccion de acciones
            if ($session_rol == 2) {
                $crud->unset_delete();
            }
            //estado del crud
            $state = $crud->getState();

            //obtener contrato
            if ($this->is_session_started() === FALSE)
                session_start();
            if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {

                //informacion del Contrato y el Plan  
                $data['selectedoption'] = 2;
                $contrato_id = $_SESSION['_aux_var'];
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

                //definicion de los botones del formulario
                $crud->buttons_form('siguienteTitular');

                //para guardar informacion del contrato luego de salir
                $crud->callback_after_insert(array($this, '_callback_after_guardar_titular'));
                $crud->callback_after_update(array($this, '_callback_after_actualizar_titular'));
                //vista del wizard para agregar titular
                $content = 'Administrador/add_titulares_wizard';

                if (isset($_SESSION['success_titular']) && $_SESSION['success_titular']) {
                    unset($_SESSION['success_titular']);
                    redirect('administrador/contactos/');
                }
                $crud->edit_fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'BENEFICIARIO', 'PERMITEUSODATOS');
            } else {
                //callback despues de guardar
                $crud->callback_after_insert(array($this, '_callback_after_insert_titular'));
                //callback despues de actualizar
                $crud->callback_after_update(array($this, '_callback_after_insert_titular'));

                //$crud->buttons_form('sinGuardar');
                $crud->unset_add();
                $content = 'Administrador/titulares';
                $crud->edit_fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'PERMITEUSODATOS');
            }
            $crud->where('ID !=', '1');
            //configuracion de la tabla
            $crud->set_table('TITULAR');
            $crud->set_subject("Titulares");
            //definicion de los campos
            //$crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS','PAIS','CIUDAD', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO','BENEFICIARIO', 'PERMITEUSODATOS');
            $crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROMUNICIPIO', 'COBROBARRIO',  'DOMIDIRECCION', 'DOMIMUNICIPIO', 'DOMIBARRIO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'BENEFICIARIO', 'PERMITEUSODATOS');
            
            $crud->add_fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROMUNICIPIO', 'COBROBARRIO',  'DOMIDIRECCION', 'DOMIMUNICIPIO', 'DOMIBARRIO',  'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'BENEFICIARIO', 'PERMITEUSODATOS');
            //renombrado de los campos
            $crud->display_as('NOMBRES', 'Nombres');
            $crud->display_as('APELLIDOS', 'Apellidos');
            $crud->display_as('TIPODOC', 'Tipo Documento');
            $crud->display_as('NODOCUMENTO', 'Numero Documento');
            $crud->display_as('TELMOVIL', 'Telefono Movil');
            $crud->display_as('EMAIL', 'Email');
            //$crud->display_as('PAIS', 'Pais');
            //$crud->display_as('CIUDAD', 'Ciudad');
            $crud->display_as('BENEFICIARIO', 'Es Beneficiario');
            $crud->display_as('FECHANACIMIENTO', 'Fecha de Nacimiento');
            $crud->display_as('GENERO', 'Género');
            $crud->display_as('COBRODIRECCION', 'Dirección de Cobro');
            $crud->display_as('COBROBARRIO', 'Barrio (dir cobro)');
            $crud->display_as('COBROMUNICIPIO', 'Ciudad (dir cobro)');
            /* $crud->display_as('COBRODEPTO', 'Departamento'); */
            $crud->display_as('DOMIDIRECCION', 'Dirección de Domicilio');
            
            $crud->display_as('DOMIBARRIO', 'Barrio (dir domicilio)');
            $crud->display_as('DOMIMUNICIPIO', 'Ciudad (dir domicilio)');/*
            $crud->display_as('DOMIDEPTO', 'Departamento'); */
            $crud->display_as('TELDOMICILIO', 'Telefono de Domicilio');
            $crud->display_as('TELOFICINA', 'Telefono de Oficina');
            $crud->display_as('NOHIJOS', 'Numero de Hijos');
            $crud->display_as('NODEPENDIENTES', 'Personas a cargo (no hijos)');
            $crud->display_as('ESTRATO', 'Estrato');
            $crud->display_as('ESTADOCIVIL', 'Estado Civil');
            $crud->display_as('OCUPACION', 'Ocupación');
            $crud->display_as('EPS', 'Eps');
            $crud->display_as('COMOUBICOSERVICIO', '¿Como ubicó el servicio?');
            $crud->display_as('PERMITEUSODATOS', 'Permitir Uso De Datos');
            //definicion de las columnas a mostrar
            $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');
            //definicion de tipos de los campos
            $crud->field_type('NODOCUMENTO', 'integer');
            $crud->field_type('NODEPENDIENTES', 'integer');
            $crud->field_type('ESTRATO', 'integer');
            $crud->field_type('NODOCUMENTO', 'integer');
            $crud->field_type('TELDOMICILIO', 'integer');
            $crud->field_type('TELOFICINA', 'integer');
            $crud->field_type('TELMOVIL', 'integer');
            $crud->field_type('TIPODOC', 'dropdown', array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera', 4 => 'Registro Civil'));
            $crud->field_type('GENERO', 'dropdown', array(1 => 'Masculino', 2 => 'Femenino'));
            $crud->field_type('ESTADOCIVIL', 'dropdown', array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo'));
            $crud->field_type('ESTRATO', 'dropdown', array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6));
            $crud->field_type('OCUPACION', 'dropdown', array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado'));
            $crud->field_type('COMOUBICOSERVICIO', 'dropdown', array(1 => 'Referido', 2 => 'Eventos', 3 => 'Convenio Especial', 4 => 'Directorio Telefónico', 5 => 'Servicio Médico Atendido', 6 => 'Medios de Comunicación'));
            
            $crud->field_type('DOMIBARRIO', 'dropdown', array('' => ''));
            $crud->field_type('COBROBARRIO', 'dropdown', array('' => ''));
            $crud->field_type('DOMIMUNICIPIO', 'dropdown', $this->ObtenerListadoCiudades());
            $crud->field_type('COBROMUNICIPIO', 'dropdown', $this->ObtenerListadoCiudades());
            $crud->field_type('EPS', 'dropdown', $this->ObtenerListadoEPS());
            
            
            //definicion de las reglas
            $crud->required_fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'BENEFICIARIO', 'FECHANACIMIENTO', 'GENERO', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'PERMITEUSODATOS','TELMOVIL');
            $crud->set_rules('EMAIL', 'E-mail', 'trim|xss_clean|valid_email|max_length[100]');

//acciones desde el crud
            $crud->add_action('Beneficiarios', base_url() . 'images/people.png', 'Beneficiarios', '', array($this, 'direccion_beneficiarios'));
            $crud->add_action('Contactos', base_url() . 'images/phone.png', 'Contactos', '', array($this, 'direccion_contactos'));
            $crud->add_action('Cargos Adicionales', base_url() . 'images/money.png', 'Costos Adicionales', '', array($this, 'direccion_cargos'));
            
            $crud->unset_read();
            //Rederizacion del CRUD
            $output = $crud->render();

            $valor = $this->uri->segment(4);
            if($valor != "")
            {
                if (! ($this->uri->segment(4) === FALSE))
                {                
                    $strSQL = 'SELECT DOMIBARRIO, COBROBARRIO FROM TITULAR WHERE ID = ' . $this->uri->segment(4);
                    $qresult = $this->db->query($strSQL);
                    if($qresult->num_rows() > 0)
                    {
                        $result = $qresult->row(0);
                        $data['DOMIBARRIO'] = str_replace(" ", "_", $result->DOMIBARRIO);  
                        $data['COBROBARRIO'] = str_replace(" ", "_", $result->COBROBARRIO);  

                    }
                }    
            }
            else
            {
                $data['DOMIBARRIO'] = "";  
                    $data['COBROBARRIO'] = "";  
            }
            
            //configuracion de la plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Titulares');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', $content, $output);
            $this->template->render();
        } else {
            redirect('');
        }
        
    }

    function direccion_cargos($primary_key, $row) {
        return base_url() . 'index.php/administrador/otroscargos/' . $primary_key;
    }
    
    function direccion_contactos($primary_key, $row) {
        return base_url() . 'index.php/administrador/contactosEdit/' . $primary_key;
    }

    function direccion_beneficiarios($primary_key, $row) {
        return base_url() . 'index.php/contratos/beneficiariosEdit/' . $primary_key;
    }

    function add_field_callback_1() {
        return ' <input type="radio" name="sex" value="0" /> Masculino
                     <input type="radio" name="sex" value="1" /> Femenino';
    }

    function _callback_after_guardar_titular($post_array, $primary_key) {
        if (!$this->is_session_started())
            session_start();
        $data = array('TITID' => $primary_key);
        $this->db->where('ID', $_SESSION['_aux_var']);
        $this->db->update('CONTRATO', $data);
        $_SESSION['success_titular'] = true;
        $_SESSION['_aux_primary_key'] = $_SESSION['_aux_var'];
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
        $this->actualizar_contador_beneficiarios($primary_key);
        return true;
    }

    function _callback_after_actualizar_titular($post_array, $primary_key) {
        if (!$this->is_session_started())
            session_start();
        $_SESSION['success_titular'] = true;
        $_SESSION['_aux_primary_key'] = $_SESSION['_aux_var'];
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
        $this->actualizar_contador_beneficiarios($primary_key);
        return true;
    }

    function _callback_after_insert_titular($post_array, $primary_key) {
        $this->actualizar_contador_beneficiarios($primary_key);
        return true;
    }

    function beneficiariosEdit($titularId) {
        session_start();
        $_SESSION['_aux_var'] = $titularId;
        $_SESSION['_aux_wizard'] = false;
        $this->beneficiarios();
    }

    function is_session_started() {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    function beneficiarios() {
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();

        if ($session_rol == 1 || $session_rol == 2) {

            $data['selectedoption'] = 3;
            $data['step_wizard'] = 1;
            //informacion de Usuario
            $session_rol = $this->tank_auth->get_rol();
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            if ($this->is_session_started() === FALSE)
                session_start();
            //informacion del titular
            $titularId = $_SESSION['_aux_var'];
            $valTitId = $titularId;
            $query = $this->db->query("
                SELECT NOMBRES, APELLIDOS, NODOCUMENTO, DOCUMENTO.NUMERO, BENEFICIARIO, PLAN.NUMBENEFICIARIOS,
                TITULAR.ESTRATO, TITULAR.DOMIDIRECCION, TITULAR.DOMIBARRIO, TITULAR.TELDOMICILIO, TITULAR.DOMIMUNICIPIO
                FROM TITULAR Left Join CONTRATO on CONTRATO.TITID = TITULAR.ID   and CONTRATO.ESTADO = 1
                left join DOCUMENTO on DOCUMENTO.ID = CONTRATO.DOCID   
                left join PLAN on PLAN.ID = CONTRATO.PLANID 
                WHERE TITULAR.ID = " . $valTitId);
            if ($query->num_rows() > 0) {
                $row = $query->row(0);
                $data['titularFullName'] = $row->NOMBRES . ' ' . $row->APELLIDOS;
                $data['titularIdentificacion'] = $row->NODOCUMENTO;
                if ($row->NUMERO == "") {
                    $data['titularContrato'] = "Sin Contrato Activo";
                    $data['plan_beneficiarios'] = FALSE;
                } else {
                    $data['titularContrato'] = $row->NUMERO;
                    $data['plan_beneficiarios'] = $row->NUMBENEFICIARIOS;
                }
                $data['estrato'] = $row->ESTRATO;
                $data['cobrodireccion'] = $row->DOMIDIRECCION;
                $data['cobrobarrio'] = $row->DOMIBARRIO;
                $data['teldomicilio'] = $row->TELDOMICILIO;
                $data['cobromunicipio'] = $row->DOMIMUNICIPIO;
            } else {
                $data['titularFullName'] = 'Titular sin definir o no existe';
                $data['titularIdentificacion'] = '';
                $data['titularContrato'] = '';
                $data['plan_beneficiarios'] = 1;
                $data['estrato'] = '';
                $data['cobrodireccion'] = '';
                $data['cobrobarrio'] = '';
                $data['teldomicilio'] = '';
                $data['cobromunicipio'] = '';
            }
            //creacion  del crud
            $crud = new Grocery_CRUD();
            $index_id = 5;
            //informacion del contrato y del plan
            if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {
                $index_id = 4;
                $this->load->model('contratosmodel');
                $crud->buttons_form('sinGuardar');
                //comprobacion para beneficiarios
                $_beneficiarios = $this->contratosmodel->get_beneficiarios($titularId);
                $row = $query->row(0);
                if ($row->BENEFICIARIO == 1) {
                    $total_beneficiarios = 1;
                } else {
                    $total_beneficiarios = 0;
                }
                if ($_beneficiarios != null) {
                    $total_beneficiarios+=$_beneficiarios->num_rows();
                }
                $data['total_beneficiarios'] = $total_beneficiarios;
                //informacion del Contrato y el Plan
                $contrato_id = $_SESSION['_aux_primary_key'];
                
                $select_contrato = $this->contratosmodel->get_contrato($contrato_id);
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
                    $select_plan = $this->contratosmodel->get_plan($select_contrato->PLANID);
                    if ($select_plan != null) {
                        $data['plan_nombre'] = $select_plan->NOMBRE;
                        $data['plan_ilimitado'] = $select_plan->BENEFICIARIOSILIMITADOS;
                        if ($select_plan->BENEFICIARIOSILIMITADOS == 0 && $total_beneficiarios >= $select_plan->NUMBENEFICIARIOS) {
                            $crud->unset_add();
                        }
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
                $content = 'Administrador/beneficiarios_wizard';
            } else {
                $this->load->model('contratosmodel');
                $_beneficiarios = $this->contratosmodel->get_beneficiarios($titularId);
                $row = $query->row(0);
                if ($row->BENEFICIARIO == 1) {
                    $total_beneficiarios = 1;
                } else {
                    $total_beneficiarios = 0;
                }
                if ($_beneficiarios != null) {
                    $total_beneficiarios+=$_beneficiarios->num_rows();
                }
                $data['total_beneficiarios'] = $total_beneficiarios;
                $content = 'Administrador/beneficiarios';
                
                if(! ($data['plan_beneficiarios'] > $total_beneficiarios && $session_rol == 1) ){ 
                    $crud->unset_add();                     
                }
            }
            $crud->unset_delete();
            $crud->unset_read();
            
            //restringir acciones
            if ($session_rol == 2) {
                $crud->unset_edit();
                $crud->unset_delete();
            } else if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {
                $crud->add_action('Eliminar Beneficiario', base_url() . 'images/close.png', 'Eliminar Beneficiario', 'personalizada', array($this, 'direccion_eliminar_beneficiario'));
            }
            
            $state = $crud->getState();
            //configuracion de la tabla
            $crud->set_table('BENEFICIARIO');
            $crud->set_subject("Beneficiarios");
            $crud->where('TITID', $valTitId);

            //callback despues de eliminar
            $crud->callback_after_delete(array($this, 'after_delete_callback_beneficiarios'));
            //callback despues de guardar
            $crud->callback_after_insert(array($this, '_callback_after_insert_beneficiarios'));
            //callback despues de actualizar
            $crud->callback_after_update(array($this, '_callback_after_insert_beneficiarios'));
            //definicion de los botones del form
            //$crud->buttons_form('sinGuardar');
            $crud->unset_back_to_list();
            //$crud->unset_jquery_ui();
            //definicion de los campos
            $crud->fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'GENERO', 'EMAIL', 'TITID', 'FECHANACIMIENTO', 'TELMOVIL', 'DIRECCION', 'MUNICIPIO', 'BARRIO', 'ESTRATODOMICILIO', 'TELDOMICILIO', 'TELOFICINA', 'EPS', 'NOHIJOS', 'OCUPACION', 'ESTADOCIVIL');

            //renombrado de los campos
            $crud->display_as('NOMBRES', 'Nombres');
            $crud->display_as('APELLIDOS', 'Apellidos');
            $crud->display_as('TIPODOC', 'Tipo Documento');
            $crud->display_as('NODOCUMENTO', 'Numero Documento');
            $crud->display_as('TELMOVIL', 'Telefono Movil');
            $crud->display_as('EMAIL', 'Email');
            $crud->display_as('FECHANACIMIENTO', 'Fecha de Nacimiento');
            $crud->display_as('GENERO', 'Genero');
            $crud->display_as('ESTRATODOMICILIO', 'Estrato Domicilio');
            $crud->display_as('DIRECCION', 'Direccion');
            $crud->display_as('MUNICIPIO', 'Ciudad');            
            $crud->display_as('BARRIO', 'Barrio');
//            $crud->display_as('MUNICIPIO', 'Municipio');
  //          $crud->display_as('DEPTO', 'Departamento');
            $crud->display_as('TELDOMICILIO', 'Telefono de Domicilio');
            $crud->display_as('TELOFICINA', 'Telefono de Oficina');
            $crud->display_as('EPS', 'Eps');
            $crud->display_as('NOHIJOS', 'Numero de Hijos');
            $crud->display_as('OCUPACION', 'Ocupacion');
            $crud->display_as('ESTADOCIVIL', 'Estado Civil');

            
            $crud->field_type('MUNICIPIO', 'dropdown', $this->ObtenerListadoCiudades());
            //definicion de las columnas a mostrar
            $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');
            $crud->required_fields('NOMBRES', 'TIPODOC', 'NODOCUMENTO', 'APELLIDOS', 'FECHANACIMIENTO', 'GENERO', 'ESTRATODOMICILIO', 'DIRECCION', 'BARRIO', 'EPS', 'NOHIJOS', 'OCUPACION', 'ESTADOCIVIL');
            $crud->set_rules('EMAIL', 'E-mail', 'trim|xss_clean|valid_email|max_length[100]');
                
            //definicion de tipos de los campos

            $crud->field_type('NODOCUMENTO', 'integer');
            $crud->field_type('TELDOMICILIO', 'integer');
            $crud->field_type('TELOFICINA', 'integer');
            $crud->field_type('TELMOVIL', 'integer');

            $crud->field_type('TITID', 'hidden', $valTitId);
            
            $crud->field_type('TIPODOC', 'dropdown', array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera',4 => 'Registro Civil'));
            $crud->field_type('GENERO', 'dropdown', array(1 => 'Masculino', 2 => 'Femenino'));
            $crud->field_type('ESTADOCIVIL', 'dropdown', array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo'));
            $crud->field_type('OCUPACION', 'dropdown', array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado'));
            $crud->field_type('ESTRATODOMICILIO', 'dropdown', array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6));
            $crud->field_type('BARRIO', 'dropdown', array('' => ''));
            $crud->field_type('EPS', 'dropdown', $this->ObtenerListadoEPS());
            
//Rederizacion del CRUD
            $output = $crud->render();
            
            $valor = $this->uri->segment($index_id);
            
            if($valor != "")
            { 
                if (! ($this->uri->segment($index_id) === FALSE))
                {
                    $strSQL = 'SELECT BARRIO FROM BENEFICIARIO WHERE ID = ' . $valor;
                    
                    $qresult = $this->db->query($strSQL);
                    if($qresult->num_rows() > 0)
                    {
                        $result = $qresult->row(0);
                        $data['BARRIO'] = str_replace(" ", "_", $result->BARRIO);                      
                    }
                }
            }      
             else
            {
                $data['BARRIO'] = "";  
            }
            
            //configuracion de la plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Beneficiarios');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', $content, $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }

    function finalizar_wizard() {
        if ($this->is_session_started() === FALSE)
                session_start();
        if (isset($_SESSION['_aux_primary_key']) && isset($_SESSION['_aux_var']) && isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {
            
            //informacion del titular
            $titularId = $_SESSION['_aux_var'];
            $valTitId = $titularId;
            $query = $this->db->query("SELECT NOMBRES, APELLIDOS, NODOCUMENTO, DOCUMENTO.NUMERO,  BENEFICIARIO, PLAN.NUMBENEFICIARIOS
                                   FROM TITULAR Left Join CONTRATO on CONTRATO.TITID = TITULAR.ID   and CONTRATO.ESTADO = 1
                                   left join DOCUMENTO on DOCUMENTO.ID = CONTRATO.DOCID   
                                   left join PLAN on PLAN.ID = CONTRATO.PLANID 
                                   WHERE TITULAR.ID = " . $valTitId);
            if ($query->num_rows() > 0) {
                $row = $query->row(0);
            }
            $_beneficiarios = $this->contratosModel->get_beneficiarios($titularId);
            $row = $query->row(0);
            if ($row->BENEFICIARIO == 1) {
                $total_beneficiarios = 1;
            } else {
                $total_beneficiarios = 0;
            }
            if ($_beneficiarios != null) {
                $total_beneficiarios+=$_beneficiarios->num_rows();
            }
            $total_beneficiarios;
            //informacion del Contrato y el Plan
            $contrato_id = $_SESSION['_aux_primary_key'];
            $this->load->model('contratosmodel');
            $select_contrato = $this->contratosmodel->get_contrato($contrato_id);
            $select_plan = $this->contratosmodel->get_plan($select_contrato->PLANID);
            if ($select_plan->BENEFICIARIOSILIMITADOS == 1 && $total_beneficiarios < $select_plan->NUMBENEFICIARIOS) {
                redirect('contratos/beneficiarios');
            }else{
                redirect('home/remap/administrador/planes');
            }
        }else
            redirect('home/remap/administrador/planes');
    }

    function _callback_after_insert_beneficiarios($post_array, $primary_key) {
        $titularId = $post_array['TITID'];
        $this->actualizar_contador_beneficiarios($titularId);
        return true;
    }

    function actualizar_contador_beneficiarios($titular_id) {
        $strQuery = "SELECT ID  FROM CONTRATO WHERE CONTRATO.TITID = " . $titular_id . " AND CONTRATO.ESTADO = 1 ";
        $qcontrato = $this->db->query($strQuery);
        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);

            $strSQL = "SELECT case when TITULAR.BENEFICIARIO = 1 then 1 else 0 end + ifnull(subconsulta.contador,0) as totalbeneficiarios
                        FROM TITULAR left join (
                            select count(*) contador, BENEFICIARIO.TITID from BENEFICIARIO where BENEFICIARIO.TITID = " . $titular_id . " 
                            group by BENEFICIARIO.TITID) subconsulta
                        on subconsulta.TITID = TITULAR.ID where TITULAR.ID = " . $titular_id;
            $qcantidad = $this->db->query($strSQL);
            if ($qcantidad->num_rows() > 0) {
                $cantidad = $qcantidad->row(0);
                $strUpdate = "UPDATE CONTRATO SET NUMBENEFICIARIOS = " . $cantidad->totalbeneficiarios . " WHERE ID = " . $contrato->ID;
                $this->db->query($strUpdate);
            }
        }
    }

    function direccion_eliminar_beneficiario($primary_key, $row) {
        return base_url() . 'index.php/contratos/eliminar_beneficiario/' . $primary_key;
    }

    function eliminar_beneficiario($primary_key) {
        $this->db->delete('BENEFICIARIO', array('ID' => $primary_key));
        redirect('contratos/beneficiarios');
    }

    function after_delete_callback_beneficiarios($primary_key) {
        //redirect('contratos/beneficiarios');
        return true;
    }

    function _callback_guardar_beneficiario($post_array) {
        $data['NOMBRES'] = $post_array['NOMBRES'];
        $data['APELLIDOS'] = $post_array['APELLIDOS'];
        $data['TIPODOC'] = $post_array['TIPODOC'];
        $data['NODOCUMENTO'] = $post_array['NODOCUMENTO'];
        $data['TELMOVIL'] = $post_array['TELMOVIL'];
        $data['EMAIL'] = $post_array['EMAIL'];
        $data['TIPOPERSONA'] = 1;
        unset($post_array['NOMBRES'], $post_array['APELLIDOS'], $post_array['TIPODOC'], $post_array['NODOCUMENTO'], $post_array['TELMOVIL'], $post_array['EMAIL']);
        $titular['ID'] = $this->contratosModel->add_persona($data);
        $titular['TITID'] = $post_array["TITID"];
        $titular["FECHANACIMIENTO"] = $post_array["FECHANACIMIENTO"];
        $titular["GENERO"] = $post_array["GENERO"];
        $titular["ESTRATODOMICILIO"] = $post_array["ESTRATODOMICILIO"];
        $titular["DIRECCION"] = $post_array["DIRECCION"];
        $titular["BARRIO"] = $post_array["BARRIO"];
        $titular["MUNICIPIO"] = $post_array["MUNICIPIO"];
        $titular["DEPTO"] = $post_array["DEPTO"];
        $titular["TELDOMICILIO"] = $post_array["TELDOMICILIO"];
        $titular["TELOFICINA"] = $post_array["TELOFICINA"];
        $titular["EPS"] = $post_array["EPS"];
        $titular["NOHIJOS"] = $post_array["NOHIJOS"];
        $titular["OCUPACION"] = $post_array["OCUPACION"];
        $titular["ESTADOCIVIL"] = $post_array["ESTADOCIVIL"];
        $this->contratosModel->add_beneficiario($titular);
        return $titular->ID;
    }

    function _callback_actualizar_beneficiario($post_array, $primaryKey) {
        $data['NOMBRES'] = $post_array['NOMBRES'];
        $data['APELLIDOS'] = $post_array['APELLIDOS'];
        $data['TIPODOC'] = $post_array['TIPODOC'];
        $data['NODOCUMENTO'] = $post_array['NODOCUMENTO'];
        $data['TELMOVIL'] = $post_array['TELMOVIL'];
        $data['EMAIL'] = $post_array['EMAIL'];
        $data['TIPOPERSONA'] = 1;
        unset($post_array['NOMBRES'], $post_array['APELLIDOS'], $post_array['TIPODOC'], $post_array['NODOCUMENTO'], $post_array['TELMOVIL'], $post_array['EMAIL']);
        $update = $this->contratosModel->update_persona($data, $primaryKey);
        if ($update) {
            $titular['ID'] = $this->contratosModel->add_persona($data);
            $titular['TITID'] = $post_array["TITID"];
            $titular["FECHANACIMIENTO"] = $post_array["FECHANACIMIENTO"];
            $titular["GENERO"] = $post_array["GENERO"];
            $titular["ESTRATODOMICILIO"] = $post_array["ESTRATODOMICILIO"];
            $titular["DIRECCION"] = $post_array["DIRECCION"];
            $titular["BARRIO"] = $post_array["BARRIO"];
            $titular["MUNICIPIO"] = $post_array["MUNICIPIO"];
            $titular["DEPTO"] = $post_array["DEPTO"];
            $titular["TELDOMICILIO"] = $post_array["TELDOMICILIO"];
            $titular["TELOFICINA"] = $post_array["TELOFICINA"];
            $titular["EPS"] = $post_array["EPS"];
            $titular["NOHIJOS"] = $post_array["NOHIJOS"];
            $titular["OCUPACION"] = $post_array["OCUPACION"];
            $titular["ESTADOCIVIL"] = $post_array["ESTADOCIVIL"];
            return $this->contratosModel->update_beneficiario($titular, $primaryKey);
        } else {
            return $update;
        }
    }
    
    function ObtenerListadoCiudades()
    {
        $this->load->library('DireccionUtils');
        $DireccionUtils = new DireccionUtils();        
        return $DireccionUtils->ObtenerListadoCiudades();
    }
    
    function ObtenerListadoEPS()
    {
        $this->load->library('DireccionUtils');
        $DireccionUtils = new DireccionUtils();        
        return $DireccionUtils->ObtenerEPS();
    }
        
    
}

?>
