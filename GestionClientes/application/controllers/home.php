<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
                        //Configuracion de la Plantilla
                        $this->template->write_view('login',$this->tank_auth->get_login(),$data);
                        $this->template->write('title','Previmed');
                        $this->template->write_view('sidebar',$this->tank_auth->get_sidebar());
                        $this->template->write_view('content','pages/home');
                        $this->template->render();
		}
	}
}