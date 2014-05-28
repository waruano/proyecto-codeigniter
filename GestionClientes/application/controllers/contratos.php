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
        
        if ($this->is_session_started() === FALSE)
            session_start();
        $titularId = $_SESSION['_aux_var'];
        
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 3;
        $data['step_wizard'] = 2;
        //informacion del titular
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
            $data['titularContrato'] = '';
        }
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();

        //configuracion de la tabla
        $crud->set_table('contrato');
        $crud->set_subject("Contrato");
        $crud->where('TITID', $valTitId);
        $crud->set_relation('PLANID', 'plan', '{NOMBRE} - {FORMAPAGO}');
        if ($state == 'add')
            $crud->set_relation('DOCID', 'documento', 'NUMERO', array('TIPO' => '1', 'ESTADO' => '1'));
        else {
            $crud->set_relation('DOCID', 'documento', 'NUMERO');
        }

        //Renombrado de los campos
        $crud->display_as('TITID', 'Titular ');
        $crud->display_as('PLANID', 'Plan');
        $crud->display_as('TIPOCONTRATO', 'Tipo de Contrato');
        $crud->display_as('FECHAINICIO', 'Fecha de Inicio');
        $crud->display_as('DOCID', 'Documento');
        $crud->display_as('ESTADO', 'Estado');
        $crud->columns('PLANID', 'TIPOCONTRATO', 'FECHAINICIO', 'DOCID');
        //add fields
        $crud->add_fields('TITID', 'PLANID', 'TIPOCONTRATO', 'FECHAINICIO', 'DOCID', 'ESTADO');
        //edit fields
        $crud->edit_fields('TITID', 'PLANID', 'TIPOCONTRATO', 'FECHAINICIO', 'DOCID', 'ESTADO');
        //callbacks
        $crud->field_type('TIPOCONTRATO', 'dropdown', array(0 => 'Nuevo', 1 => 'Adición', 2 => 'Reactivación', 3 => 'Reemplazo'));
        //$crud->field_type('ESTADO', 'dropdown', array(0 => 'Inactivo', 1 => 'Activo'));
        $crud->field_type('TITID', 'hidden', $valTitId);
        $crud->field_type('ESTADO', 'hidden', '1');
        $crud->callback_after_insert(array($this, '_callback_after_insert_contrato'));
        $crud->callback_before_insert(array($this, '_callback_before_insert_contrato'));
        $crud->buttons_form('sinGuardar');
        $crud->unset_back_to_list();
        $output = $crud->render();
        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/contratos', $output);
        $this->template->render();
    }

    function contratosEdit($titularId) {       
        session_start();
        $_SESSION['_aux_var'] = $titularId;
        $_SESSION['_aux_wizard'] = false;
        
        $this->index();
    }

    function _callback_after_insert_contrato($post_array) {
        $data = array('ESTADO' => '2');
        $this->db->where('ID', $post_array['DOCID']);
        $this->db->update('documento', $data);
        return true;
    }

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
           
        $data['selectedoption'] = 3;
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();
        //definicion de los botones del form           
        if ($state == 'add')
            $crud->buttons_form("siguienteTitular");
       
        //informacion de Usuario
         $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();

        //configuracion de la tabla
        $crud->set_table('titular');
        $crud->set_subject("Titulares");
        //redireccionamiento para siguiente
        if (!$this->is_session_started())
            session_start();
        if (isset($_SESSION['success_titular']) && $_SESSION['success_titular'] = true) {
            unset($_SESSION['success_titular']);
            redirect('administrador/contactos/');
        }
        //definicion de los campos
        //$crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS','PAIS','CIUDAD', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO','BENEFICIARIO', 'PERMITEUSODATOS');
        $crud->fields('TIPODOC', 'NODOCUMENTO', 'FECHANACIMIENTO', 'GENERO', 'NOMBRES', 'APELLIDOS', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'TELMOVIL', 'EMAIL', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO','BENEFICIARIO', 'PERMITEUSODATOS');
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
        //$crud->field_type('PAIS', 'dropdown', $this->listaPaises());
        $crud->field_type('TIPODOC', 'dropdown', array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera'));
        $crud->field_type('GENERO', 'dropdown', array(0 => 'Masculino', 1 => 'Femenino'));
        $crud->field_type('ESTADOCIVIL', 'dropdown', array(0 => 'Soltero', 1 => 'Casado', 2 => 'Divorciado', 3 => 'Unión Libre', 4 => 'Viudo'));
        $crud->field_type('OCUPACION', 'dropdown', array(0 => 'Empleado', 1 => 'Independiente', 2 => 'Jubilado', 3 => 'Ama de Casa', 4 => 'Estudiante', 5 => 'Desempleado'));
        $crud->field_type('COMOUBICOSERVICIO', 'dropdown', array(0 => 'Referido', 1 => 'Eventos', 2 => 'Convenio Especial', 3 => 'Directorio Telefónico', 4 => 'Servicio Médico Atendido', 5 => 'Medios de Comunicación'));
        //definicion de las reglas
        $crud->required_fields('NOMBRES');
        //callbacks
        $crud->callback_after_insert(array($this,'_callback_after_guardar_titular'));
        
        //acciones desde el crud
        
        $crud->add_action('Contratos', base_url() . 'images/contrato.png', 'Contratos', '', array($this, 'direccion_contratos'));
        $crud->add_action('Beneficiarios', base_url() . 'images/people.png', 'Beneficiarios', '', array($this, 'direccion_beneficiarios'));
        $crud->add_action('Contactos', base_url() . 'images/phone.png', 'Contactos', '', array($this, 'direccion_contactos'));

        //Rederizacion del CRUD
        $crud->unset_read();
        $output = $crud->render();
        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Titulares');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/titulares', $output);
        $this->template->render();
    }

    function direccion_contratos($primary_key, $row) {
        return base_url() . 'contratos/contratosEdit/' . $primary_key;
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
        //echo 'here';
        //echo '<script>alert("'.$primary_key.'");</script>';
        $_SESSION['success_titular'] = true;
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
        $data['selectedoption'] = 3;
        $data['step_wizard'] = 1;
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        //informacion del titular
        if ($this->is_session_started() === FALSE)
            session_start();
        $titularId = $_SESSION['_aux_var'];
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
            $data['titularContrato'] = '';
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
        $crud->unset_back_to_list();

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
        //$crud->callback_insert(array($this, '_callback_guardar_beneficiario'));
        //$crud->callback_update(array($this, '_callback_actualizar_beneficiario'));

        //definicion de las columnas a mostrar
        $crud->columns('NODOCUMENTO', 'NOMBRES', 'APELLIDOS', 'EPS');

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
    
    function listaPaises()
    {
        $paises = array(
		'Afghanistan' => 'Afghanistan' ,
            'Albania' => 'Albania' ,
            'Algeria' => 'Algeria' ,
            'Andorra' => 'Andorra' ,
            'Angola' => 'Angola' ,
            'Antigua and Barbuda' => 'Antigua and Barbuda' ,
            'Argentina' => 'Argentina' ,
            'Armenia' => 'Armenia' ,
            'Australia' => 'Australia' ,
            'Austria' => 'Austria' ,
            'Azerbaijan' => 'Azerbaijan' ,
            'Bahamas' => 'Bahamas' ,
            'Bahrain' => 'Bahrain' ,
            'Bangladesh' => 'Bangladesh' ,
            'Barbados' => 'Barbados' ,
            'Belarus' => 'Belarus' ,
            'Belgium' => 'Belgium' ,
            'Belize' => 'Belize' ,
            'Benin' => 'Benin' ,
            'Bhutan' => 'Bhutan' ,
            'Bolivia' => 'Bolivia' ,
            'Bosnia and Herzegovina' => 'Bosnia and Herzegovina' ,
            'Botswana' => 'Botswana' ,
            'Brazil' => 'Brazil' ,
            'Brunei' => 'Brunei' ,
            'Bulgaria' => 'Bulgaria' ,
            'Burkina Faso' => 'Burkina Faso' ,
            'Burundi' => 'Burundi' ,
            'Cambodia' => 'Cambodia' ,
            'Cameroon' => 'Cameroon' ,
            'Canada' => 'Canada' ,
            'Cape Verde' => 'Cape Verde' ,
            'Central African Republic' => 'Central African Republic' ,
            'Chad' => 'Chad' ,
            'Chile' => 'Chile' ,
            'China' => 'China' ,
            'Colombia' => 'Colombia' ,
            'Comoros' => 'Comoros' ,
            'Congo (Brazzaville)' => 'Congo (Brazzaville)' ,
            'Congo' => 'Congo' ,
            'Costa Rica' => 'Costa Rica' ,
            'Cote dIvoire' => 'Cote dIvoire' ,
            'Croatia' => 'Croatia' ,
            'Cuba' => 'Cuba' ,
            'Cyprus' => 'Cyprus' ,
            'Czech Republic' => 'Czech Republic' ,
            'Denmark' => 'Denmark' ,
            'Djibouti' => 'Djibouti' ,
            'Dominica' => 'Dominica' ,
            'Dominican Republic' => 'Dominican Republic' ,
            'East Timor (Timor Timur)' => 'East Timor (Timor Timur)' ,
            'Ecuador' => 'Ecuador' ,
            'Egypt' => 'Egypt' ,
            'El Salvador' => 'El Salvador' ,
            'Equatorial Guinea' => 'Equatorial Guinea' ,
            'Eritrea' => 'Eritrea' ,
            'Estonia' => 'Estonia' ,
            'Ethiopia' => 'Ethiopia' ,
            'Fiji' => 'Fiji' ,
            'Finland' => 'Finland' ,
            'France' => 'France' ,
            'Gabon' => 'Gabon' ,
            'Gambia The' => 'Gambia The' ,
            'Georgia' => 'Georgia' ,
            'Germany' => 'Germany' ,
            'Ghana' => 'Ghana' ,
            'Greece' => 'Greece' ,
            'Grenada' => 'Grenada' ,
            'Guatemala' => 'Guatemala' ,
            'Guinea' => 'Guinea' ,
            'Guinea-Bissau' => 'Guinea-Bissau' ,
            'Guyana' => 'Guyana' ,
            'Haiti' => 'Haiti' ,
            'Honduras' => 'Honduras' ,
            'Hungary' => 'Hungary' ,
            'Iceland' => 'Iceland' ,
            'India' => 'India' ,
            'Indonesia' => 'Indonesia' ,
            'Iran' => 'Iran' ,
            'Iraq' => 'Iraq' ,
            'Ireland' => 'Ireland' ,
            'Israel' => 'Israel' ,
            'Italy' => 'Italy' ,
            'Jamaica' => 'Jamaica' ,
            'Japan' => 'Japan' ,
            'Jordan' => 'Jordan' ,
            'Kazakhstan' => 'Kazakhstan' ,
            'Kenya' => 'Kenya' ,
            'Kiribati' => 'Kiribati' ,
            'Korea North' => 'Korea North' ,
            'Korea South' => 'Korea South' ,
            'Kuwait' => 'Kuwait' ,
            'Kyrgyzstan' => 'Kyrgyzstan' ,
            'Laos' => 'Laos' ,
            'Latvia' => 'Latvia' ,
            'Lebanon' => 'Lebanon' ,
            'Lesotho' => 'Lesotho' ,
            'Liberia' => 'Liberia' ,
            'Libya' => 'Libya' ,
            'Liechtenstein' => 'Liechtenstein' ,
            'Lithuania' => 'Lithuania' ,
            'Luxembourg' => 'Luxembourg' ,
            'Macedonia' => 'Macedonia' ,
            'Madagascar' => 'Madagascar' ,
            'Malawi' => 'Malawi' ,
            'Malaysia' => 'Malaysia' ,
            'Maldives' => 'Maldives' ,
            'Mali' => 'Mali' ,
            'Malta' => 'Malta' ,
            'Marshall Islands' => 'Marshall Islands' ,
            'Mauritania' => 'Mauritania' ,
            'Mauritius' => 'Mauritius' ,
            'Mexico' => 'Mexico' ,
            'Micronesia' => 'Micronesia' ,
            'Moldova' => 'Moldova' ,
            'Monaco' => 'Monaco' ,
            'Mongolia' => 'Mongolia' ,
            'Morocco' => 'Morocco' ,
            'Mozambique' => 'Mozambique' ,
            'Myanmar' => 'Myanmar' ,
            'Namibia' => 'Namibia' ,
            'Nauru' => 'Nauru' ,
            'Nepa' => 'Nepa' ,
            'Netherlands' => 'Netherlands' ,
            'New Zealand' => 'New Zealand' ,
            'Nicaragua' => 'Nicaragua' ,
            'Niger' => 'Niger' ,
            'Nigeria' => 'Nigeria' ,
            'Norway' => 'Norway' ,
            'Oman' => 'Oman' ,
            'Pakistan' => 'Pakistan' ,
            'Palau' => 'Palau' ,
            'Panama' => 'Panama' ,
            'Papua New Guinea' => 'Papua New Guinea' ,
            'Paraguay' => 'Paraguay' ,
            'Peru' => 'Peru' ,
            'Philippines' => 'Philippines' ,
            'Poland' => 'Poland' ,
            'Portugal' => 'Portugal' ,
            'Qatar' => 'Qatar' ,
            'Romania' => 'Romania' ,
            'Russia' => 'Russia' ,
            'Rwanda' => 'Rwanda' ,
            'Saint Kitts and Nevis' => 'Saint Kitts and Nevis' ,
            'Saint Lucia' => 'Saint Lucia' ,
            'Saint Vincent' => 'Saint Vincent' ,
            'Samoa' => 'Samoa' ,
            'San Marino' => 'San Marino' ,
            'Sao Tome and Principe' => 'Sao Tome and Principe' ,
            'Saudi Arabia' => 'Saudi Arabia' ,
            'Senegal' => 'Senegal' ,
            'Serbia and Montenegro' => 'Serbia and Montenegro' ,
            'Seychelles' => 'Seychelles' ,
            'Sierra Leone' => 'Sierra Leone' ,
            'Singapore' => 'Singapore' ,
            'Slovakia' => 'Slovakia' ,
            'Slovenia' => 'Slovenia' ,
            'Solomon Islands' => 'Solomon Islands' ,
            'Somalia' => 'Somalia' ,
            'South Africa' => 'South Africa' ,
            'Spain' => 'Spain' ,
            'Sri Lanka' => 'Sri Lanka' ,
            'Sudan' => 'Sudan' ,
            'Suriname' => 'Suriname' ,
            'Swaziland' => 'Swaziland' ,
            'Sweden' => 'Sweden' ,
            'Switzerland' => 'Switzerland' ,
            'Syria' => 'Syria' ,
            'Taiwan' => 'Taiwan' ,
            'Tajikistan' => 'Tajikistan' ,
            'Tanzania' => 'Tanzania' ,
            'Thailand' => 'Thailand' ,
            'Togo' => 'Togo' ,
            'Tonga' => 'Tonga' ,
            'Trinidad and Tobago' => 'Trinidad and Tobago' ,
            'Tunisia' => 'Tunisia' ,
            'Turkey' => 'Turkey' ,
            'Turkmenistan' => 'Turkmenistan' ,
            'Tuvalu' => 'Tuvalu' ,
            'Uganda' => 'Uganda' ,
            'Ukraine' => 'Ukraine' ,
            'United Arab Emirates' => 'United Arab Emirates' ,
            'United Kingdom' => 'United Kingdom' ,
            'United States' => 'United States' ,
            'Uruguay' => 'Uruguay' ,
            'Uzbekistan' => 'Uzbekistan' ,
            'Vanuatu' => 'Vanuatu' ,
            'Vatican City' => 'Vatican City' ,
            'Venezuela' => 'Venezuela' ,
            'Vietnam' => 'Vietnam' ,
            'Yemen' => 'Yemen' ,
            'Zambia' => 'Zambia' ,
            'Zimbabwe' => 'Zimbabwe' 
	);
	return $paises;
    }
    

}

?>