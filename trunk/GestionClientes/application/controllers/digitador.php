<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class digitador extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            if ($this->tank_auth->get_rol() != 1 && $this->tank_auth->get_rol() != 3 && $this->tank_auth->get_rol() != 2)
                redirect('');
        }
    }

    function documentos() {
        //informacion Usuario
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol == 1 || $session_rol == 2) {
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 6;

            //Configuracion Grocery_CRUD listado de usuarios
            $crud = new Grocery_CRUD();
            //restriccion de acciones
            if ($session_rol == 2) {
                $crud->unset_delete();
            }
            $crud->unset_read();

            $crud->set_table('DOCUMENTO');
            $crud->set_subject("Documentos");
            $crud->columns('EMPID', 'TIPO', 'NUMERO', 'ESTADO');
            $crud->display_as('EMPID', 'Asignado a');            
            $crud->display_as('TIPO', 'Tipo');
            $crud->display_as('NUMERO', 'Número(s)');
            $crud->display_as('ESTADO', 'Estado');

            $crud->set_relation('EMPID', 'PERSONA', '{CODIGOASESOR} {NOMBRES} {APELLIDOS}', array('TIPOPERSONA' => '2'));
            $crud->unset_back_to_list();
            $crud->edit_fields('EMPID', 'TIPO', 'NUMERO', 'ESTADO');
            $crud->required_fields('TIPO','NUMERO','ESTADO', 'EMPID');

            $crud->set_rules('TIPO', 'Tipo', 'callback_tipo_check');
            $crud->set_rules('NUMERO', 'Numero', 'callback_numero_check');

            $crud->add_fields('EMPID', 'TIPO', 'NUMERO', 'ESTADO');
            $crud->field_type('TIPO', 'dropdown', array('1' => 'Contrato', '2' => 'Recibo de Caja'));
            $crud->field_type('ESTADO', 'dropdown', array('1' => 'Asignado', '2' => 'Reportado', '3' => 'Anulado'));

            $crud->callback_insert(array($this, 'callaback_insert_documentos'));
            //$crud->buttons_form('sinGuardar');
            $output = $crud->render();

            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Documentos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Digitador/documentos', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }

    public function tipo_check($str) {
        if (trim($str) == '') {
            $this->form_validation->set_message('tipo_check', 'El campo %s Es Obligatorio');
            return FALSE;
        } else {
            if ($this->is_session_started() === FALSE)
                session_start();
            $_SESSION['_aux_var'] = $str;
            return TRUE;
        }
    }

    public function numero_check($str) {
        if ($this->is_session_started() === FALSE)
            session_start();
     
        if (trim($str) == '') {
            $this->form_validation->set_message('numero_check', 'El campo %s es Obligatorio');
            return FALSE;
        }
        $this->load->model('contratosmodel');
        $numero = $str;
        if(isset($_SESSION['_aux_var'])){
            $tipo = $_SESSION['_aux_var'];
            unset($_SESSION['_aux_var']);
        }else {
            $this->form_validation->set_message('numero_check', 'se debe seleccionar un tipo para comprobar los documentos');
            return FALSE;
        }
        
        $pos = strpos($numero, "-");
        $ban = TRUE;
        $mensaje = 'los siguientes documentos de Tipo ';
        if($tipo==1){
            $mensaje=$mensaje.'Contrato ya se han asignado: ';
        }else{
            $mensaje=$mensaje.'Recibo de Caja Ya se han asignado: ';
        }
        if ($pos != FALSE) {
            list($desde, $hasta) = split('-', $numero);
            for ($i = $desde; $i <= $hasta; $i++) {
                if ($this->contratosmodel->existe_documento($i, $tipo)) {
                    $ban = FALSE;
                    $mensaje = $mensaje . ' ' . $i.' ';
                }
            }
        } else {
            if (is_numeric($numero)) {
                if($this->contratosmodel->existe_documento($numero, $tipo)) {
                    $ban = FALSE;
                    $mensaje = $mensaje . ' ' . $numero.' ';
                }
            } else {
                $this->form_validation->set_message('numero_check', 'El campo %s debe ser de tipo numerico'.$tipo);
                return FALSE;
            }
        }        
        if (!$ban) {
            $this->form_validation->set_message('numero_check', $mensaje);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function callaback_insert_documentos($post_array) {
        $numero = $post_array['NUMERO'];
        $pos = strpos($numero, "-");
        if ($pos != FALSE) {
            list($desde, $hasta) = split('-', $numero);
            for ($i = $desde; $i <= $hasta; $i++) {
                $post_array["NUMERO"] = $i;
                $this->db->insert('DOCUMENTO', $post_array);
            }
        } else {

            if (is_numeric($numero)) {
                $post_array["NUMERO"] = $numero;
                $this->db->insert('DOCUMENTO', $post_array);
            }else
                return false;
        }
        return true;
    }

    function pagos() {
        //informacion 
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol == 1 || $session_rol == 3) {
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 8;
            //Configuracion Grocery_CRUD listado de usuarios
            $crud = new Grocery_CRUD();
            //acciones especificas de usuario
            if ($session_rol == 3) {
                $crud->unset_read();
                $crud->unset_edit();
                $crud->unset_delete();
            }
            $crud->unset_read();

            $crud->set_table('PAGO');
            $crud->set_subject("Pagos");
            $crud->columns('RECID', 'TITID', 'VALOR', 'FECHA', 'TIPOCONCEPTO');
            $crud->display_as('RECID', 'Recibo de caja');
            $crud->display_as('TITID', 'Titular de contrato');
            $crud->display_as('VALOR', 'Valor pagado');
            $crud->display_as('FECHA', 'Fecha de pago');
            $crud->display_as('TIPOCONCEPTO', 'Por Concepto de');

            $crud->set_relation('RECID', 'DOCUMENTO', '{NUMERO}', array('TIPO' => '2', 'ESTADO' => '1'));
            $crud->set_relation('TITID', 'TITULAR', '{NODOCUMENTO} - {NOMBRES} {APELLIDOS}', array('ID !=' => 1));

            $crud->edit_fields('VALOR', 'FECHA', 'TIPOCONCEPTO');
            //definicion de las reglas
            $crud->required_fields('RECID', 'TITID', 'VALOR', 'FECHA', 'TIPOCONCEPTO');
            $crud->set_rules('VALOR', 'Valor Pagado', 'required|trim|xss_clean|max_length[20]|numeric');
            $crud->add_fields('RECID', 'TITID', 'TIPOCONCEPTO', 'VALOR', 'FECHA');

            $crud->field_type('TIPOCONCEPTO', 'dropdown', array('1' => 'Pago mensualidad', '2' => 'Pago afiliación', '3' => 'Otros conceptos'));
            $crud->field_type('FECHAHASTA', 'date');
            $crud->field_type('VALOR', 'integer');

            $crud->callback_after_insert(array($this, '_callback_after_insert_pago'));

            $output = $crud->render();

            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Pagos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Digitador/pagos', $output);
            $this->template->render();
        } else {
            //echo $session_rol;
            redirect('');
        }
    }

    function _callback_after_insert_pago($post_array) {
        $data = array('ESTADO' => '2');
        $this->db->where('ID', $post_array['RECID']);
        $this->db->update('DOCUMENTO', $data);
        return true;
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

}
