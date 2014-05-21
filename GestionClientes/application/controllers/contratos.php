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
        //creacion  del crud
        $crud = new Grocery_CRUD();
        $state = $crud->getState();
        //configuracion de la tabla
        $crud->set_table('contrato');
        $crud->set_subject("Contrato");
        $crud->set_relation('TITID', 'persona', '{NOMBRES}{APELLIDOS}-{NODOCUMENTO}');
        $crud->set_relation('PLANID', 'plan', '{NOMBRE}-{FORMAPAGO}');
        $crud->set_relation('DOCID', 'documento', 'NUMERO', array('TIPO' => '1', 'ESTADO' => '1'));
        //Renombrado de los campos
        $crud->display_as('TITID', 'Titular');
        $crud->display_as('PLANID', 'Plan');
        $crud->display_as('TIPOCONTRATO', 'Tipo de Contrato');
        $crud->display_as('FECHAINICIO', 'Fecha de Inicio');
        $crud->display_as('DOCID', 'Documento');
        $crud->display_as('ESTADO', 'Estado');
        $crud->display_as('Opciones', 'Para Crear Un Nuevo Titular');
        //add fields
        $crud->add_fields('TITID','Opciones','PLANID','TIPOCONTRATO','FECHAINICIO','DOCID','ESTADO');
        //callbacks
        $crud->callback_add_field('Opciones', array($this, '_callback_add_field_Titular'));
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
        $crud->fields('NOMBRES', 'APELLIDOS', 'TIPODOC', 'NODOCUMENTO', 'TELMOVIL', 'EMAIL', 'PAIS', 'CIUDAD', 'BENEFICIARIO', 'FECHANACIMIENTO', 'GENERO', 'COBRODIRECCION', 'COBROBARRIO', 'COBROMUNICIPIO', 'COBRODEPTO', 'DOMIDIRECCION', 'DOMIBARRIO', 'DOMIMUNICIPIO', 'DOMIDEPTO', 'TELDOMICILIO', 'TELOFICINA', 'NOHIJOS', 'NODEPENDIENTES', 'ESTRATO', 'ESTADOCIVIL', 'OCUPACION', 'EPS', 'COMOUBICOSERVICIO', 'PERMITEUSODATOS');
        //renombrado de los campos
        $crud->display_as('NOMBRES', 'Nombres');
        $crud->display_as('APELLIDOS', 'Apellidos');
        $crud->display_as('TIPODOC', 'Tipo Documento');
        $crud->display_as('NODOCUMENTO', 'Numero Documento');
        $crud->display_as('TELMOVIL', 'Telefono Movil');
        $crud->display_as('EMAIL', 'Email');
        $crud->display_as('PAIS', 'Pais');
        $crud->display_as('CIUDAD', 'Ciudad');
        $crud->display_as('BENEFICIARIO', 'Beneficiario');
        $crud->display_as('FECHANACIMIENTO', 'Fecha de Nacimiento');
        $crud->display_as('GENERO', 'Genero');
        $crud->display_as('COBRODIRECCION', 'Dirección de Cobro');
        $crud->display_as('COBROBARRIO', 'Barrio de Cobro');
        $crud->display_as('COBROMUNICIPIO', 'Municipio de Cobro');
        $crud->display_as('COBRODEPTO', 'Departamento de Cobro');
        $crud->display_as('DOMIDIRECCION', 'Dirección de Domicilio');
        $crud->display_as('DOMIBARRIO', 'Barrio de Domicilio');
        $crud->display_as('DOMIMUNICIPIO', 'Municipio de Domicilio');
        $crud->display_as('DOMIDEPTO', 'Departamento de Domicilio');
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
        $crud->field_type('TIPODOC', 'dropdown', array(0 => 'Cedula de Ciudadnia', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera'));
        //definicion de las reglas
        $crud->required_fields('NOMBRES');
        //Rederizacion del CRUD
        $output = $crud->render();
        //configuracion de la plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Titulares');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Administrador/titulares', $output);
        $this->template->render();
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
        $opciones = array(0 => 'Cedula de Ciudadnia', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera');
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

    function beneficiarios() {
        //informacion de Usuario
        $session_rol = $this->tank_auth->get_rol();
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        //informacion del titular
        session_start();
        $titularId = $_SESSION['_aux_var'];
        $valTitId = $titularId;
        $query = $this->db->query("SELECT NOMBRES, APELLIDOS, NODOCUMENTO FROM PERSONA WHERE ID = " . $valTitId);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['titularFullName'] = $row->NOMBRES . ' ' . $row->APELLIDOS;
        } else {
            $data['titularFullName'] = 'Titular sin definir o no existe';
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