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
                $crud->unset_edit();
                $crud->unset_delete();
            }
            $crud->unset_read();
            
            $crud->set_table('documento');
            $crud->set_subject("Documentos");
            $crud->columns('EMPID', 'NUMERO', 'TIPO', 'ESTADO');
            $crud->display_as('EMPID', 'Asignado a');
            $crud->display_as('NUMERO', 'Número(s)');
            $crud->display_as('TIPO', 'Tipo');
             $crud->display_as('ESTADO', 'Estado');

            $crud->set_relation('EMPID', 'Persona', '{Nombres} {Apellidos}', array('TIPOPERSONA' => '2'));
            $crud->unset_back_to_list();
            $crud->edit_fields('EMPID', 'NUMERO', 'TIPO', 'ESTADO');
            $crud->required_fields('NUMERO', 'TIPO', 'ESTADO','EMPID');
            $crud->add_fields('EMPID', 'NUMERO', 'TIPO', 'ESTADO');
            $crud->field_type('TIPO', 'dropdown', array('1' => 'Contrato', '2' => 'Recibo de Caja'));
            $crud->field_type('ESTADO', 'dropdown', array('1' => 'Asignado', '2' => 'Reportado', '3' => 'Anulado'));

            $crud->callback_insert(array($this, 'callaback_insert_documentos'));
            //$crud->buttons_form('sinGuardar');
            $output = $crud->render();

            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Documentos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'digitador/documentos', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }

    function callaback_insert_documentos($post_array) {
        $numero = $post_array['NUMERO'];
        $pos = strpos($numero, "-");
        if ($pos != FALSE) {
            list($desde, $hasta) = split('-', $numero);
            for ($i = $desde; $i <= $hasta; $i++) {
                $post_array["NUMERO"] = $i;
                $this->db->insert('documento', $post_array);
            }
        } else {

            if (is_numeric($numero)) {
                $post_array["NUMERO"] = $numero;
                $this->db->insert('documento', $post_array);
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
            
            $crud->set_table('pago');
            $crud->set_subject("Pagos");
            $crud->columns('RECID', 'TITID', 'VALOR', 'FECHA', 'TIPOCONCEPTO', 'OTROCONCEPTO');
            $crud->display_as('RECID', 'Recibo de caja');
            $crud->display_as('TITID', 'Titular de contrato');
            $crud->display_as('VALOR', 'Valor pagado');
            $crud->display_as('FECHA', 'Fecha de pago');
            $crud->display_as('TIPOCONCEPTO', 'Por Concepto de');
            $crud->display_as('OTROCONCEPTO', 'Otro? Cual');

            $crud->set_relation('RECID', 'Documento', '{Numero}', array('TIPO' => '2', 'ESTADO' => '1'));            
            $crud->set_relation('TITID', 'Titular', '{Nombres} {Apellidos}');

            $crud->edit_fields('VALOR', 'FECHA', 'TIPOCONCEPTO', 'OTROCONCEPTO');
            //definicion de las reglas
            $crud->required_fields('RECID', 'TITID', 'VALOR', 'FECHA', 'TIPOCONCEPTO');
            $crud->set_rules('VALOR', 'Valor Pagado', 'required|trim|xss_clean|max_length[20]|numeric');
            $crud->add_fields('RECID', 'TITID', 'VALOR', 'FECHA', 'TIPOCONCEPTO', 'OTROCONCEPTO');

            $crud->field_type('TIPOCONCEPTO', 'dropdown', array('1' => 'Pago mensualidad', '2' => 'Pago afiliación', '3' => 'Otro'));
            $crud->field_type('FECHAHASTA', 'date');
            $crud->field_type('VALOR', 'integer');

            $crud->callback_after_insert(array($this, '_callback_after_insert_pago'));

            $output = $crud->render();

            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Pagos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'digitador/pagos', $output);
            $this->template->render();
        } else {
            echo $session_rol;
            //redirect('');
        }
    }

    function _callback_after_insert_pago($post_array) {
        $data = array('ESTADO' => '2');
        $this->db->where('ID', $post_array['RECID']);
        $this->db->update('documento', $data);
        return true;
    }

}
