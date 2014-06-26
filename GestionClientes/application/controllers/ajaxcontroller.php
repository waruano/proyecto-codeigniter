<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jqueryexample extends CI_Controller {

	// --------------------------------------------------------------------
	
	/**
	 *	The constructor
	 */
	function __construct()
	{
		parent::__construct();	
	}
	
	// --------------------------------------------------------------------
	
	/**
	 *	The default method for this controller
	 */
	function index()
	{		
		$this->tpl_data += array(
			"current_dts" => date("Y-m-d: h:i:s"),
		);

		// Call the display template (/apps/main/views/jqueryexample.php)
		$this->_tpl('jqueryexample');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 *	This method will be called using AJAX
	 */
	function postoutput()
	{	
		// TIP #1 -- Since this is a method that will be called via AJAX and the
		// output will be inserted into a page that is already rendered, we need
		// to use the $this->_widget which is define in the parent MY_Controller 
		// class.
		//
		// The _widget method simply does not include the header & footer but
		// otherwise functions just like the _tpl method.
		//
		// TIP #2 -- If the profile is enabled in a parent controller, then I
		// recommend that you disable it for AJAX methods to prevent profiler
		// data from being displayed in your widgets.
		
		$this->output->enable_profiler(FALSE);


		$this->tpl_data += array(
			"current_dts" => date("Y-m-d: h:i:s"),
		);
		
		// Call the display template (/apps/main/views/widgets/postoutput.php)
		//$this->_widget('Administrador/postoutput');	
	}
	
	// --------------------------------------------------------------------
	
}