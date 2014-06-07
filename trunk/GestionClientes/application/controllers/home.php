<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
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

    public function remap($method,$function) {
        if(!$this->is_session_started())
            session_start ();
        if(isset($_SESSION['success_titular'])){
            unset($_SESSION['success_titular']);
        }
        if(isset($_SESSION['_aux_var'])){
            unset($_SESSION['_aux_var']);
        }
        if(isset($_SESSION['_aux_wizard'])){
            unset($_SESSION['_aux_wizard']);
        }
        if(isset($_SESSION['_aux_primary_key'])){
            unset($_SESSION['_aux_primary_key']);
        }
        redirect($method.'/'.$function);
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