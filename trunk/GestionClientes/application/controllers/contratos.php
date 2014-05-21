<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contratos extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
        $this->load->model('contratosModel');
    }

    function index() {
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 5;
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();
        //configuracion de la tabla
        $crud->set_table('contrato');
        $crud->set_subject("Contrato");
        $crud->set_relation('TITID', 'persona', '{NOMBRES}{APELLIDOS} - {NODOCUMENTO}', array('TIPOPERSONA' => '0'));        
        $crud->set_relation('PLANID', 'plan', '{NOMBRE} - {FORMAPAGO}');
        $crud->set_relation('DOCID', 'documento', 'NUMERO', array('TIPO' => '1', 'ESTADO' => '1'));
        //Renombrado de los campos
        $crud->display_as('TITID', 'Titular ');
        $crud->display_as('PLANID', 'Plan');
        $crud->display_as('TIPOCONTRATO', 'Tipo de Contrato');
        $crud->display_as('FECHAINICIO', 'Fecha de Inicio');
        $crud->display_as('DOCID', 'Documento');
        $crud->display_as('ESTADO', 'Estado');
        //$crud->display_as('Opciones', 'Para Crear Un Nuevo Titular');
        //add fields
        $crud->add_fields('TITID','PLANID','TIPOCONTRATO','FECHAINICIO','DOCID','ESTADO');        
        //callbacks
        $crud->field_type('TIPOCONTRATO', 'dropdown', array(0 => 'Nuevo', 1 => 'Adición', 2 => 'Reactivación', 3 => 'Reemplazo'));
        $crud->field_type('ESTADO', 'dropdown', array(0 => 'Inactivo', 1 => 'Activo'));
        //$crud->callback_add_field('Opciones', array($this, '_callback_add_field_Titular'));
        $output = $crud->render();
        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Titulares');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/contratos', $output);
        $this->template->render();
    }

    function _callback_add_field_Titular() {
        return '<input class="btn btn-large" type="button" onclick="window.location='."'".base_url().'contratos/titulares/true'."'".'" value="Click Aqui"/>';
    }

    function titulares($contratos=false) {
        $data['selectedoption'] = 3;
        if($contratos){
            $_SESSION['to_contratos']=true;
        }else{
            if(isset($_SESSION['to_contratos']))
            unset($_SESSION['to_contratos']);
        }
        $session_rol = $this->tank_auth->get_rol();
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();
        //configuracion de la tabla
        $crud->set_table('titular');
        $crud->set_subject("Titulares");
        //definicion de los botones del form
        if ($state == 'add')
            $crud->buttons_form("siguienteTitular");
        session_start();
        //redireccionamiento para siguiente
        if (isset($_SESSION['success']) && $_SESSION['success'] = true) {
            unset($_SESSION['success']);
            redirect('administrador/contactos/');
        }
        //definicion de los campos
        $crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 
                'NOMBRES', 'APELLIDOS', 
                'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 
                'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 
                'EMAIL', 
                'BENEFICIARIO',
                'NOHIJOS', 'NODEPENDIENTES', 
                'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'PERMITEUSODATOS');
        //renombrado de los campos
        $crud->display_as('NOMBRES', 'Nombres');
        $crud->display_as('APELLIDOS', 'Apellidos');
        $crud->display_as('TIPODOC', 'Tipo Documento');
        $crud->display_as('NODOCUMENTO', 'Numero Documento');
        $crud->display_as('TELMOVIL', 'Telefono Movil');
        $crud->display_as('EMAIL', 'Email');
        $crud->display_as('PAIS', 'Pais');
        $crud->display_as('CIUDAD', 'Ciudad');
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
        $crud->display_as('NODEPENDIENTES', 'Numero de Pendientes');
        $crud->display_as('ESTRATO', 'Estrato');
        $crud->display_as('ESTADOCIVIL', 'Estado Civil');
        $crud->display_as('OCUPACION', 'Ocupación');
        $crud->display_as('EPS', 'Eps');
        $crud->display_as('COMOUBICOSERVICIO', 'Como Ubicar Servicio');
        $crud->display_as('PERMITEUSODATOS', 'Permitir Uso De Datos');
        //Llamado a funciones callback
        $crud->callback_insert(array($this, '_callback_guardar_titular'));
        $crud->callback_update(array($this, '_callback_actualizar_titular'));
        $crud->callback_before_delete(array($this, '_callback_borrar_titular'));
        //definicion de las columnas a mostrar
        $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');
        $crud->callback_column('NOMBRES', array($this, '_callback_column_nombres'));
        $crud->callback_column('APELLIDOS', array($this, '_callback_column_apellidos'));
        $crud->callback_column('NODOCUMENTO', array($this, '_callback_column_nodocumento'));
        //llamados para llenar los campos de persona
        $crud->callback_edit_field('NOMBRES', array($this, '_callback_field_nombres'));
        $crud->callback_edit_field('APELLIDOS', array($this, '_callback_field_apellidos'));
        $crud->callback_edit_field('TIPODOC', array($this, '_callback_field_tipodoc'));
        $crud->callback_edit_field('NODOCUMENTO', array($this, '_callback_field_nodocumento'));
        $crud->callback_edit_field('TELMOVIL', array($this, '_callback_field_telmovil'));
        $crud->callback_edit_field('EMAIL', array($this, '_callback_field_email'));
        //definicion de tipos de los campos
        $crud->field_type('TIPODOC', 'dropdown', array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera'));
        $crud->field_type('GENERO', 'dropdown', array(0 => 'Masculino', 1 => 'Femenino'));       
        
        $crud->field_type('ESTADOCIVIL', 'dropdown', array(0 => 'Soltero', 1 => 'Casado', 2 => 'Divorciado', 3 => 'Unión Libre', 4 => 'Viudo'));
        $crud->field_type('OCUPACION', 'dropdown', array(0 => 'Empleado', 1 => 'Independiente', 2 => 'Jubilado', 3 => 'Ama de Casa', 4 => 'Estudiante', 5 => 'Desempleado'));
        $crud->field_type('COMOUBICOSERVICIO', 'dropdown', array(0 => 'Referido', 1 => 'Eventos', 2 => 'Convenio Especial', 3 => 'Directorio Telefónico', 4 => 'Servicio Médico Atendido', 5 => 'Medios de Comunicación'));
        //definicion de las reglas
        $crud->required_fields('NOMBRES');
        
        $crud->add_action('Contactos', base_url() . 'images/phone.png', 'Contactos','',array($this,'direccion_contactos'));
        $crud->add_action('Beneficiarios', base_url() . 'images/people.png', 'Beneficiarios','',array($this,'direccion_beneficiarios'));
        
        //Rederizacion del CRUD
        $output = $crud->render();
        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Titulares');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/titulares', $output);
        $this->template->render();
    }
    
    function direccion_contactos($primary_key, $row) {
        return base_url() . 'administrador/contactosEdit/' . $primary_key;
    }
    function direccion_beneficiarios($primary_key, $row) {
        return base_url() . 'contratos/beneficiariosEdit/' . $primary_key;
    }
    
    function add_field_callback_1()
    {
            return ' <input type="radio" name="sex" value="0" /> Masculino
                     <input type="radio" name="sex" value="1" /> Femenino';
    }

    function _callback_guardar_titular($post_array) {
        $data['NOMBRES'] = $post_array['NOMBRES'];
        $data['APELLIDOS'] = $post_array['APELLIDOS'];
        $data['TIPODOC'] = $post_array['TIPODOC'];
        $data['NODOCUMENTO'] = $post_array['NODOCUMENTO'];
        $data['TELMOVIL'] = $post_array['TELMOVIL'];
        $data['EMAIL'] = $post_array['EMAIL'];
        $data['TIPOPERSONA'] = 0;
        unset($post_array['NOMBRES'], $post_array['APELLIDOS'], $post_array['TIPODOC'], $post_array['NODOCUMENTO'], $post_array['TELMOVIL'], $post_array['EMAIL']);
        $titular['ID'] = $this->contratosModel->add_persona($data);
        $titular['PAIS'] = $post_array["ID"];
        $titular["PAIS"] = $post_array["PAIS"];
        $titular["CIUDAD"] = $post_array["CIUDAD"];
        $titular["BENEFICIARIO"] = $post_array["BENEFICIARIO"];
        $titular["FECHANACIMIENTO"] = $post_array["FECHANACIMIENTO"];
        $titular["GENERO"] = $post_array["GENERO"];
        $titular["COBRODIRECCION"] = $post_array["COBRODIRECCION"];
        $titular["COBROBARRIO"] = $post_array["COBROBARRIO"];
        $titular["COBROMUNICIPIO"] = $post_array["COBROMUNICIPIO"];
        $titular["COBRODEPTO"] = $post_array["COBRODEPTO"];
        $titular["DOMIDIRECCION"] = $post_array["DOMIDIRECCION"];
        $titular["DOMIBARRIO"] = $post_array["DOMIBARRIO"];
        $titular["DOMIMUNICIPIO"] = $post_array["DOMIMUNICIPIO"];
        $titular["DOMIDEPTO"] = $post_array["DOMIDEPTO"];
        $titular["TELDOMICILIO"] = $post_array["TELDOMICILIO"];
        $titular["TELOFICINA"] = $post_array["TELOFICINA"];
        $titular["NOHIJOS"] = $post_array["NOHIJOS"];
        $titular["NODEPENDIENTES"] = $post_array["NODEPENDIENTES"];
        $titular["ESTRATO"] = $post_array["ESTRATO"];
        $titular["ESTADOCIVIL"] = $post_array["ESTADOCIVIL"];
        $titular["OCUPACION"] = $post_array["OCUPACION"];
        $titular["EPS"] = $post_array["EPS"];
        $titular["COMOUBICOSERVICIO"] = $post_array["COMOUBICOSERVICIO"];
        $titular["PERMITEUSODATOS"] = $post_array["PERMITEUSODATOS"];
        $this->contratosModel->add_titular($titular);
        session_start();
        $_SESSION['_aux_var'] = $titular['ID'];
        $_SESSION['_aux_wizard'] = true;
        return $titular['ID'];
    }

    function _callback_actualizar_titular($post_array, $primaryKey) {
        $data['NOMBRES'] = $post_array['NOMBRES'];
        $data['APELLIDOS'] = $post_array['APELLIDOS'];
        $data['TIPODOC'] = $post_array['TIPODOC'];
        $data['NODOCUMENTO'] = $post_array['NODOCUMENTO'];
        $data['TELMOVIL'] = $post_array['TELMOVIL'];
        $data['EMAIL'] = $post_array['EMAIL'];
        $data['TIPOPERSONA'] = 0;
        unset($post_array['NOMBRES'], $post_array['APELLIDOS'], $post_array['TIPODOC'], $post_array['NODOCUMENTO'], $post_array['TELMOVIL'], $post_array['EMAIL']);
        $update = $this->contratosModel->update_persona($data, $primaryKey);
        if ($update) {
            $titular['PAIS'] = $post_array["ID"];
            $titular["PAIS"] = $post_array["PAIS"];
            $titular["CIUDAD"] = $post_array["CIUDAD"];
            $titular["BENEFICIARIO"] = $post_array["BENEFICIARIO"];
            $titular["FECHANACIMIENTO"] = $post_array["FECHANACIMIENTO"];
            $titular["GENERO"] = $post_array["GENERO"];
            $titular["COBRODIRECCION"] = $post_array["COBRODIRECCION"];
            $titular["COBROBARRIO"] = $post_array["COBROBARRIO"];
            $titular["COBROMUNICIPIO"] = $post_array["COBROMUNICIPIO"];
            $titular["COBRODEPTO"] = $post_array["COBRODEPTO"];
            $titular["DOMIDIRECCION"] = $post_array["DOMIDIRECCION"];
            $titular["DOMIBARRIO"] = $post_array["DOMIBARRIO"];
            $titular["DOMIMUNICIPIO"] = $post_array["DOMIMUNICIPIO"];
            $titular["DOMIDEPTO"] = $post_array["DOMIDEPTO"];
            $titular["TELDOMICILIO"] = $post_array["TELDOMICILIO"];
            $titular["TELOFICINA"] = $post_array["TELOFICINA"];
            $titular["NOHIJOS"] = $post_array["NOHIJOS"];
            $titular["NODEPENDIENTES"] = $post_array["NODEPENDIENTES"];
            $titular["ESTRATO"] = $post_array["ESTRATO"];
            $titular["ESTADOCIVIL"] = $post_array["ESTADOCIVIL"];
            $titular["OCUPACION"] = $post_array["OCUPACION"];
            $titular["EPS"] = $post_array["EPS"];
            $titular["COMOUBICOSERVICIO"] = $post_array["COMOUBICOSERVICIO"];
            $titular["PERMITEUSODATOS"] = $post_array["PERMITEUSODATOS"];
            return $this->contratosModel->update_titular($titular, $primaryKey);
        } else {
            return $update;
        }
    }

    function _callback_borrar_titular($prymaryKey) {
        echo '<script>alert("entro");</script>';
        $this->contratosModel->delete_titular($prymaryKey);
        return $this->contratosModel->delete_persona($prymaryKey);
    }

    function _callback_field_nombres($value, $primaryKey) {
        $persona = $this->contratosModel->get_persona($primaryKey);
        return'<input id="field-NOMBRES" name="NOMBRES" type="text" value="' . $persona->NOMBRES . '"  />';
    }

    function _callback_field_apellidos($value, $primaryKey) {
        $persona = $this->contratosModel->get_persona($primaryKey);
        return'<input id="field-APELLIDOS" name="APELLIDOS" type="text" value="' . $persona->APELLIDOS . '"  />';
    }

    function _callback_field_tipodoc($value, $primaryKey) {
        $opciones = array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cédula Extrangera');
        $propiedades = 'id="field-TIPODOC" class="chosen-select" data-placeholder= "Seleccionar Tipo Documento"';
        $persona = $this->contratosModel->get_persona($primaryKey);
        $result = form_dropdown("TIPODOC", $opciones, $persona->TIPODOC, $propiedades);
        return $result;
    }

    function _callback_field_nodocumento($value, $primaryKey) {
        $persona = $this->contratosModel->get_persona($primaryKey);
        return'<input id="field-NODOCUMENTO" name="NODOCUMENTO" type="text" value="' . $persona->NODOCUMENTO . '"  />';
    }

    function _callback_field_telmovil($value, $primaryKey) {
        $persona = $this->contratosModel->get_persona($primaryKey);
        return'<input id="field-TELMOVIL" name="TELMOVIL" type="text" value="' . $persona->TELMOVIL . '"  />';
    }

    function _callback_field_email($value, $primaryKey) {
        $persona = $this->contratosModel->get_persona($primaryKey);
        return'<input id="field-EMAIL" name="EMAIL" type="text" value="' . $persona->EMAIL . '"  />';
    }

    function _callback_column_nombres($value, $row) {
        $persona = $this->contratosModel->get_persona($row->ID);
        return $persona->NOMBRES;
    }

    function _callback_column_apellidos($value, $row) {
        $persona = $this->contratosModel->get_persona($row->ID);
        return $persona->APELLIDOS;
    }

    function _callback_column_nodocumento($value, $row) {
        $persona = $this->contratosModel->get_persona($row->ID);
        return $persona->NODOCUMENTO;
    }

     function beneficiariosEdit($titularId)
    {
        session_start();
        $_SESSION['_aux_var'] = $titularId;
        $_SESSION['_aux_wizard'] = false;
        $this->beneficiarios();
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
    
    function beneficiarios() {
        $data['selectedoption'] = 3;
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        //informacion del titular
        if ( $this->is_session_started() === FALSE ) session_start();
        $titularId = $_SESSION['_aux_var'];
        $valTitId = $titularId;
                
        $query = $this->db->query("SELECT NOMBRES, APELLIDOS, NODOCUMENTO, documento.numero 
                                   FROM PERSONA Left Join CONTRATO on Contrato.TitId = Persona.ID and contrato.estado = 1
                                   left join documento on documento.id = contrato.docId   WHERE Persona.ID = " . $valTitId);
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
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();
        //configuracion de la tabla
        $crud->set_table('beneficiario');
        $crud->set_subject("Beneficiarios");
        $crud->where('TITID', $valTitId);

        //definicion de los botones del form
        $crud->buttons_form('sinGuardar');

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

        //Llamado a funciones callback
        $crud->callback_insert(array($this, '_callback_guardar_beneficiario'));
        $crud->callback_update(array($this, '_callback_actualizar_beneficiario'));

        //definicion de las columnas a mostrar
        $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');
        $crud->callback_column('NOMBRES', array($this, '_callback_column_nombres'));
        $crud->callback_column('APELLIDOS', array($this, '_callback_column_apellidos'));
        $crud->callback_column('NODOCUMENTO', array($this, '_callback_column_nodocumento'));

        //llamados para llenar los campos de persona
        $crud->callback_edit_field('NOMBRES', array($this, '_callback_field_nombres'));
        $crud->callback_edit_field('APELLIDOS', array($this, '_callback_field_apellidos'));
        $crud->callback_edit_field('TIPODOC', array($this, '_callback_field_tipodoc'));
        $crud->callback_edit_field('NODOCUMENTO', array($this, '_callback_field_nodocumento'));
        $crud->callback_edit_field('TELMOVIL', array($this, '_callback_field_telmovil'));
        $crud->callback_edit_field('EMAIL', array($this, '_callback_field_email'));

        //definicion de tipos de los campos
        $crud->field_type('TITID', 'hidden', $valTitId);
        $crud->field_type('TIPODOC', 'dropdown', array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera'));
        $crud->field_type('GENERO', 'dropdown', array(0 => 'Masculino', 1 => 'Femenino'));       
        
        $crud->field_type('ESTADOCIVIL', 'dropdown', array(0 => 'Soltero', 1 => 'Casado', 2 => 'Divorciado', 3 => 'Unión Libre', 4 => 'Viudo'));
        $crud->field_type('OCUPACION', 'dropdown', array(0 => 'Empleado', 1 => 'Independiente', 2 => 'Jubilado', 3 => 'Ama de Casa', 4 => 'Estudiante', 5 => 'Desempleado'));
        
        
        //definicion de las reglas
        $crud->required_fields('NOMBRES');

        //Rederizacion del CRUD
        $output = $crud->render();

        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Beneficiarios');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/beneficiarios', $output);
        $this->template->render();
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
        return $titular['ID'];
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