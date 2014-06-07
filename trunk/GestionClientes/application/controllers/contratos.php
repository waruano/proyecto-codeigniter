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
            } else {
                $data['plan_nombre'] = 'No se ha Especificado el Plan';
                $data['plan_beneficiarios'] = '';
                $data['plan_convenio'] = '';
            }

            //creacion  del crud
            $crud = new Grocery_CRUD();
            
            //restriccion de acciones
            if($session_rol==2){
                $crud->unset_edit();
                $crud->unset_delete();
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
                    redirect('contratos/titulares/add');
                }
            } else {
                $crud->buttons_form('sinGuardar');
            }

            //configuracion de la tabla
            $crud->set_table('contrato');
            $crud->set_subject("Contrato");
            $crud->set_relation('DOCID', 'documento', 'NUMERO', array('TIPO' => '1', 'ESTADO' => '1'));

            //Renombrado de los campos
            $crud->display_as('PLANID', 'Plan');
            $crud->display_as('TIPOCONTRATO', 'Tipo de Contrato');
            $crud->display_as('PERIODICIDAD', 'Periodicidad');
            $crud->display_as('FECHAINICIO', 'Fecha de Inicio');
            $crud->display_as('DOCID', 'Documento');
            $crud->display_as('ESTADO', 'Estado');
            $crud->columns('PLANID', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID');
            //definicion de las reglas
            $crud->required_fields('TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID');

            //campos en formulario para agregar
            $crud->add_fields('PLANID', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID', 'ESTADO');
            //campos en formaulario para editar
            $crud->edit_fields('PLANID', 'TIPOCONTRATO', 'PERIODICIDAD', 'FECHAINICIO', 'DOCID', 'ESTADO');

            //tipos de los campos
            $crud->field_type('TIPOCONTRATO', 'dropdown', array(1 => 'Nuevo', 2 => 'Adición', 3 => 'Reactivación', 4 => 'Reemplazo'));
            $crud->field_type('PERIODICIDAD', 'dropdown', array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual'));
            $crud->field_type('PLANID', 'hidden', $plan_id);
            $crud->field_type('ESTADO', 'hidden', '1');

            //callback
            $crud->callback_after_insert(array($this, '_callback_after_insert_contrato'));
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
        $data = array('ESTADO' => '2');
        $this->db->where('ID', $post_array['DOCID']);
        $this->db->update('documento', $data);
        $_SESSION['success_contrato'] = true;
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
        return true;
    }

    //solo para cuando se ingresan titulares antes del contrato
    function _callback_before_insert_contrato($post_array) {
        $titularId = $post_array['TITID'];
        $this->db->where('TITID', $titularId);
        $contratos = $this->db->get('contrato');
        foreach ($contratos->result() as $contrato) {
            $_estado = array('ESTADO' => '0');
            $this->db->where('ID', $contrato->ID);
            $this->db->update('contrato', $_estado);
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
            if ($session_rol == 2) {
                $crud->unset_edit();
                $crud->unset_delete();
            }
            //estado del crud
            $state = $crud->getState();

            //obtener contrato
            if ($this->is_session_started() === FALSE)
                session_start();
            if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard']) {

                //informacion del Contrato y el Plan
                $contrato_id = $_SESSION['_aux_var'];
                $this->load->model('contratosModel');
                $select_contrato = $this->contratosModel->get_contrato($contrato_id);
                if ($select_contrato != null) {
                    $data['selectedoption'] = 2;
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

                //vista del wizard para agregar titular
                $content = 'Administrador/add_titulares_wizard';

                if (isset($_SESSION['success_titular']) && $_SESSION['success_titular']) {
                    unset($_SESSION['success_titular']);
                    redirect('administrador/contactos/');
                }
            } else {
                $crud->buttons_form('sinGuardar');
                $content = 'Administrador/titulares';
            }
            //configuracion de la tabla
            $crud->set_table('titular');
            $crud->set_subject("Titulares");
            //definicion de los campos
            //$crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS','PAIS','CIUDAD', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO','BENEFICIARIO', 'PERMITEUSODATOS');
            $crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'BENEFICIARIO', 'PERMITEUSODATOS');
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
            $crud->display_as('COBRODIRECCION', 'Dirección de Cobro / Correspondencia');
            $crud->display_as('COBROBARRIO', 'Barrio');
            $crud->display_as('COBROMUNICIPIO', 'Municipio');
            $crud->display_as('COBRODEPTO', 'Departamento');
            $crud->display_as('DOMIDIRECCION', 'Dirección de Domicilio');
            $crud->display_as('DOMIBARRIO', 'Barrio');
            $crud->display_as('DOMIMUNICIPIO', 'Municipio');
            $crud->display_as('DOMIDEPTO', 'Departamento');
            $crud->display_as('TELDOMICILIO', 'Telefono de Domicilio');
            $crud->display_as('TELOFICINA', 'Telefono de Oficina');
            $crud->display_as('NOHIJOS', 'Numero de Hijos');
            $crud->display_as('NODEPENDIENTES', 'Personas a cargo (no hijos)');
            $crud->display_as('ESTRATO', 'Estrato');
            $crud->display_as('ESTADOCIVIL', 'Estado Civil');
            $crud->display_as('OCUPACION', 'Ocupación');
            $crud->display_as('EPS', 'Eps');
            $crud->display_as('COMOUBICOSERVICIO', 'Como Ubicar Servicio');
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
            $crud->field_type('TIPODOC', 'dropdown', array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera'));
            $crud->field_type('GENERO', 'dropdown', array(1 => 'Masculino', 2 => 'Femenino'));
            $crud->field_type('ESTADOCIVIL', 'dropdown', array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo'));
            $crud->field_type('OCUPACION', 'dropdown', array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado'));
            $crud->field_type('COMOUBICOSERVICIO', 'dropdown', array(1 => 'Referido', 2 => 'Eventos', 3 => 'Convenio Especial', 4 => 'Directorio Telefónico', 5 => 'Servicio Médico Atendido', 6 => 'Medios de Comunicación'));

            //definicion de las reglas
            $crud->required_fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'EMAIL', 'BENEFICIARIO', 'FECHANACIMIENTO', 'GENERO', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'PERMITEUSODATOS');
            $crud->set_rules('EMAIL', 'E-mail', 'required|trim|xss_clean|valid_email|max_length[100]');
            $crud->set_rules('NOHIJOS', 'No ', 'required|trim|xss_clean|valid_email|max_length[100]');
//acciones desde el crud
            $crud->add_action('Beneficiarios', base_url() . 'images/people.png', 'Beneficiarios', '', array($this, 'direccion_beneficiarios'));
            $crud->add_action('Contactos', base_url() . 'images/phone.png', 'Contactos', '', array($this, 'direccion_contactos'));

            //Rederizacion del CRUD
            $output = $crud->render();

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

    function direccion_contactos($primary_key, $row) {
        return base_url() . 'administrador/contactosEdit/' . $primary_key;
    }

    function direccion_beneficiarios($primary_key, $row) {
        return base_url() . 'contratos/beneficiariosEdit/' . $primary_key;
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
        $this->db->update('contrato', $data);
        $_SESSION['success_titular'] = true;
        $_SESSION['_aux_primary_key'] = $_SESSION['_aux_var'];
        $_SESSION['_aux_var'] = $primary_key;
        $_SESSION['_aux_wizard'] = true;
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
            $query = $this->db->query("SELECT NOMBRES, APELLIDOS, NODOCUMENTO, documento.numero,  BENEFICIARIO, plan.NUMBENEFICIARIOS
                                   FROM Titular Left Join CONTRATO on Contrato.TitId = Titular.ID   and contrato.estado = 1
                                   left join documento on documento.id = contrato.docId   
                                   left join plan on plan.id = contrato.planid 
                                   WHERE Titular.ID = " . $valTitId);
            if ($query->num_rows() > 0) {
                $row = $query->row(0);
                $data['titularFullName'] = $row->NOMBRES . ' ' . $row->APELLIDOS;
                $data['titularIdentificacion'] = $row->NODOCUMENTO;
                $data['titularContrato'] = $row->numero;
                $data['plan_beneficiarios'] = $row->NUMBENEFICIARIOS;
            } else {
                $data['titularFullName'] = 'Titular sin definir o no existe';
                $data['titularIdentificacion'] = '';
                $data['titularContrato'] = '';
                $data['plan_beneficiarios'] = 1;
            }
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
                //comprobacion para beneficiarios
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
                $data['total_beneficiarios'] = $total_beneficiarios;
                //vista del wizard para agregar titular
                $content = 'Administrador/beneficiarios_wizard';
            } else {
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
                $data['total_beneficiarios'] = $total_beneficiarios;
                $content = 'Administrador/beneficiarios';
            }
            //creacion  del crud
            $crud = new Grocery_CRUD();
            //restringir acciones
            if ($session_rol == 2) {
                $crud->unset_edit();
                $crud->unset_delete();
            }
            $crud->unset_read();
            $state = $crud->getState();
            //configuracion de la tabla
            $crud->set_table('beneficiario');
            $crud->set_subject("Beneficiarios");
            $crud->where('TITID', $valTitId);
            $crud->add_action('Eliminar Beneficiario', base_url() . 'images/close.png', 'Eliminar Beneficiario', '', array($this, 'direccion_eliminar_beneficiario'));
            $crud->unset_delete();
            //callback despues de eliminar
            $crud->callback_after_delete(array($this, 'after_delete_callback_beneficiarios'));
            //definicion de los botones del form
            $crud->buttons_form('sinGuardar');
            $crud->unset_back_to_list();
            //$crud->unset_jquery_ui();
            //definicion de los campos
            $crud->fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL', 'EMAIL', 'TITID', 'FECHANACIMIENTO', 'GENERO', 'ESTRATODOMICILIO', 'DIRECCION', 'BARRIO', 'MUNICIPIO', 'DEPTO', 'TELDOMICILIO', 'TELOFICINA', 'EPS', 'NOHIJOS', 'OCUPACION', 'ESTADOCIVIL');

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
            $crud->display_as('BARRIO', 'Barrio');
            $crud->display_as('MUNICIPIO', 'Municipio');
            $crud->display_as('DEPTO', 'Departamento');
            $crud->display_as('TELDOMICILIO', 'Telefono de Domicilio');
            $crud->display_as('TELOFICINA', 'Telefono de Oficina');
            $crud->display_as('EPS', 'Eps');
            $crud->display_as('NOHIJOS', 'Numero de Hijos');
            $crud->display_as('OCUPACION', 'Ocupacion');
            $crud->display_as('ESTADOCIVIL', 'Estado Civil');

            //definicion de las columnas a mostrar
            $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');
            $crud->required_fields('NOMBRES', 'TIPODOC', 'NODOCUMENTO', 'APELLIDOS','EMAIL','FECHANACIMIENTO','GENERO','ESTRATODOMICILIO','DIRECCION','BARRIO','MUNICIPIO','DEPTO','TELDOMICILIO','EPS','NOHIJOS','OCUPACION','ESTADOCIVIL');
            $crud->set_rules('EMAIL', 'E-mail', 'required|trim|xss_clean|valid_email|max_length[100]');
            
            //definicion de tipos de los campos

            $crud->field_type('NODOCUMENTO', 'integer');
            $crud->field_type('TELDOMICILIO', 'integer');
            $crud->field_type('TELOFICINA', 'integer');
            $crud->field_type('TELMOVIL', 'integer');

            $crud->field_type('TITID', 'hidden', $valTitId);
            $crud->field_type('TIPODOC', 'dropdown', array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera'));
            $crud->field_type('GENERO', 'dropdown', array(1 => 'Masculino', 2 => 'Femenino'));
            $crud->field_type('ESTADOCIVIL', 'dropdown', array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo'));
            $crud->field_type('OCUPACION', 'dropdown', array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado'));

            //Rederizacion del CRUD
            $output = $crud->render();

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
    function direccion_eliminar_beneficiario($primary_key,$row){
        return base_url().'contratos/eliminar_beneficiario/'.$primary_key;
    }
    function eliminar_beneficiario($primary_key){
        $this->db->delete('beneficiario',array('ID'=>$primary_key));
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

}

?>
