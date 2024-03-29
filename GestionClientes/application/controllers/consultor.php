<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Consultor extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            if ($this->tank_auth->get_rol() < 1 && $this->tank_auth->get_rol() > 4)
                redirect('');
        }
    }

    function index() {
        
    }

    function consultar() {
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol >= 1 && $session_rol <= 4) {

            $data['validentificacion'] = $this->input->post('identificacion');
            $data['valnocontrato'] = $this->input->post('numeroContrato');
            $data['valtitular'] = $this->input->post('nombreTitular');

            //informacion de Usuario
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 4;

            $crud = new grocery_CRUD();
            $crud->set_model('custom_query_model');
            $crud->set_table('PERSONA'); //Change to your table name

            $strSQL = "select 'EstadoCartera' as EstadoCartera, DOCUMENTO.NUMERO as NumeroContrato, 
                CONCAT(TITULAR.NOMBRES, ' ', ifnull(TITULAR.APELLIDOS, '')) as NombreTitular, 
                TITULAR.NODOCUMENTO as Identificacion, CONTRATO.FECHAINICIO as FechaAfiliacion, 
                CONTRATO.ID as ID, CASE WHEN CONTRATO.ESTADO = 1 then 'Si' ELSE 'No' END as EstadoContrato,
                TITULAR.TELMOVIL
                from TITULAR 
                inner join CONTRATO on (CONTRATO.TITID = TITULAR.ID AND TITULAR.ID > 1)
                inner join DOCUMENTO on DOCUMENTO.ID = CONTRATO.DOCID ";

            if ($data['valtitular'] != '') {
                $strSQL = $strSQL . " AND CONCAT(TITULAR.NOMBRES, ' ', TITULAR.APELLIDOS) like '%" . $data['valtitular'] . "%' ";
            }

            if ($data['validentificacion'] != '') {
                $strSQL = $strSQL . " AND TITULAR.NODOCUMENTO like '%" . $data['validentificacion'] . "%' ";
            }

            if (is_numeric($data['valnocontrato'])) {
                $strSQL = $strSQL . " AND DOCUMENTO.NUMERO = " . $data['valnocontrato'] . " ";
            }

            $crud->basic_model->set_query_str($strSQL); //Query text here
            $crud->columns('EstadoCartera', 'NumeroContrato', 'NombreTitular', 'Identificacion', 'TELMOVIL', 'FechaAfiliacion',  'EstadoContrato');
            $crud->display_as('EstadoCartera', 'Estado Cartera');
            $crud->display_as('EstadoContrato', '¿Contrato Activo?');
            $crud->display_as('NumeroContrato', 'No Contrato');
            $crud->display_as('NombreTitular', 'Titular');
            $crud->display_as('TELMOVIL', 'Tel.Móvil');
            $crud->display_as('FechaAfiliacion', 'Fecha Afiliacion');
            
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_read();
            $crud->add_action('Detalles', base_url() . 'images/magnifier.png', 'Detalles', '', array($this, 'direccion_contratos'));
            $crud->unset_jquery();
           // $crud->callback_column('EstadoCartera', array($this, 'callback_column_estado'));
            
            $output = $crud->render();
            
            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Búsqueda de Contratos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Consultor/consulta', $output);
            $this->template->render();
        } else {
            redirect('');
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
    
    function consultaGeneral() {
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol >= 1 && $session_rol <= 4) {
            $data['lstCiudades'] = $this->ObtenerListadoCiudades();
            $data['lstEps'] = $this->ObtenerListadoEPS();
            // se reciben los datos desde el formulario
            $data['validentificacion'] = $this->input->post('identificacion');
            $data['valnocontrato'] = $this->input->post('numeroContrato');
            $data['valtitular'] = $this->input->post('nombreTitular');
            $data['valtelefono'] = $this->input->post('telefono');
            $data['valcorreo'] = $this->input->post('correo');
            $data['valgenero'] = $this->input->post('genero');
            $data['valdireccion'] = $this->input->post('direccion');
            $data['valciudad'] = $this->input->post('ciudad');
            $data['valestrato'] = $this->input->post('estrato');
            $data['valeps'] = $this->input->post('eps');
           /* $data['valafiliaciondesde'] = $this->input->post('afiliaciondesde');
            $data['valafiliacionhasta'] = $this->input->post('afiliacionhasta');*/
            $data['valplan'] = $this->input->post('plan');
            $data['valconvenio'] = $this->input->post('convenio');
            $data['valasesor'] = $this->input->post('asesor');            
            $data['valestadocartera'] = $this->input->post('estadocartera');            
            $data['valestadocontrato'] = $this->input->post('estadocontrato');
            
            //informacion de Usuario
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 9;

            $crud = new grocery_CRUD();
            $crud->set_model('custom_query_model');
            $crud->set_table('PERSONA'); //Change to your table name
            $crud->basic_model->set_estado($data['valestadocartera']);
            $strSQL = "
                select 'EstadoCartera' as EstadoCartera, DOCUMENTO.NUMERO as NumeroContrato, 
                CONCAT(TITULAR.NOMBRES, ' ', ifnull(TITULAR.APELLIDOS, '')) as NombreTitular, 
                TITULAR.NODOCUMENTO as Identificacion, CONTRATO.FECHAINICIO as FechaAfiliacion, 
                CONTRATO.ID as ID, CASE WHEN CONTRATO.ESTADO = 1 then 'Si' ELSE 'No' END as EstadoContrato,
                TITULAR.TELMOVIL
                from TITULAR 
                inner join CONTRATO on (CONTRATO.TITID = TITULAR.ID AND TITULAR.ID > 1)
                inner join DOCUMENTO on DOCUMENTO.ID = CONTRATO.DOCID 
                inner join PLAN on PLAN.ID = CONTRATO.PLANID
                inner join PERSONA on DOCUMENTO.EMPID = PERSONA.ID";

            // se crean los filtros a la consulta dependiendo de lo recibido por post
            if ($data['valtitular'] != '') {
                $strSQL = $strSQL . " AND CONCAT(TITULAR.NOMBRES, ' ', TITULAR.APELLIDOS) like '%" . $data['valtitular'] . "%' ";
            }
            if ($data['validentificacion'] != '') {
                $strSQL = $strSQL . " AND TITULAR.NODOCUMENTO like '%" . $data['validentificacion'] . "%' ";
            }
            if (is_numeric($data['valnocontrato'])) {
                $strSQL = $strSQL . " AND DOCUMENTO.NUMERO = " . $data['valnocontrato'] . " ";
            }            
            if (is_numeric($data['valtelefono'])) {                
                $strSQL = $strSQL . " AND  (TITULAR.TELDOMICILIO LIKE'%" .$data['valtelefono'] . "%' OR TITULAR.TELMOVIL LIKE'%" .$data['valtelefono'] . "%' OR TITULAR.TELOFICINA LIKE'%" .$data['valtelefono'] . "%')";
            }            
            if ($data['valcorreo'] != '') {
                $strSQL = $strSQL . " AND TITULAR.EMAIL LIKE'%" .$data['valcorreo'] . "%' ";
            }            
            if (is_numeric($data['valgenero'])) {
                if($data['valgenero'] != 0){
                    $strSQL = $strSQL . " AND TITULAR.GENERO = " . $data['valgenero'] . " ";
                }
            }
            
            if ($data['valciudad'] != '') {
                $strSQL = $strSQL . " AND (TITULAR.COBROMUNICIPIO like '%" . $data['valciudad'] . "%'  or TITULAR.DOMIMUNICIPIO like '%" . $data['valciudad'] . "%' )";                   
            }
                    
            if ($data['valdireccion'] != '') {
                $strSQL = $strSQL . " AND (TITULAR.COBROBARRIO like '%" . $data['valdireccion'] . "%' or TITULAR.COBRODIRECCION like '%" . $data['valdireccion'] . "%' or TITULAR.DOMIBARRIO like '%" . $data['valdireccion'] . "%' or TITULAR.DOMIDIRECCION like '%" . $data['valdireccion'] . "%' )";                   
            }
            if (is_numeric($data['valestrato'])) {
                $strSQL = $strSQL . " AND TITULAR.ESTRATO = " . $data['valestrato'] . " ";
            }
            if ($data['valeps'] != '') {
                $strSQL = $strSQL . " AND TITULAR.EPS like '%" . $data['valeps'] . "%' ";
            }/*
            if ($data['valafiliaciondesde'] !='') {
                $strSQL = $strSQL . " AND documento.Numero = " . $data['valnocontrato'] . " ";
            }
            if ($data['valafiliacionhasta'] != '') {
                $strSQL = $strSQL . " AND documento.Numero = " . $data['valnocontrato'] . " ";
            } */
            if ($data['valplan'] != '') {
                $strSQL = $strSQL . " AND PLAN.NOMBRE like '%" . $data['valplan'] . "%' ";
            }
            if ($data['valconvenio'] != '') {
                $strSQL = $strSQL . " AND PLAN.NOMBRECONVENIO like '%" . $data['valconvenio'] . "%' ";
            }
            if ($data['valasesor'] != '') {
                $strSQL = $strSQL . " AND CONCAT(PERSONA.NOMBRES, ' ', PERSONA.APELLIDOS) like '%" . $data['valasesor'] . "%' ";
            }
            if ($data['valestadocontrato'] != '') {
                if($data['valestadocontrato'] == 'ACTIVO')
                {
                    $strSQL = $strSQL . " AND CONTRATO.ESTADO = 1 ";    
                }
                else
                {
                    $strSQL = $strSQL . " AND CONTRATO.ESTADO = 0 ";    
                }
            }
            
            //echo $strSQL;
            // Se define el CRUD
            $crud->basic_model->set_query_str($strSQL); //Query text here
            $crud->columns('EstadoCartera', 'NumeroContrato', 'NombreTitular', 'Identificacion','TELMOVIL', 'FechaAfiliacion',  'EstadoContrato');
            $crud->display_as('EstadoCartera', 'Estado Cartera');
            $crud->display_as('EstadoContrato', '¿Contrato Activo?');
            $crud->display_as('NumeroContrato', 'Numero Contrato');
            $crud->display_as('NombreTitular', 'Titular');
            $crud->display_as('TELMOVIL', 'Tel.Móvil');
            $crud->display_as('FechaAfiliacion', 'Fecha Afiliacion');
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_read();
            $crud->add_action('Detalles', base_url() . 'images/magnifier.png', 'Detalles', '', array($this, 'direccion_general'));
            $crud->unset_jquery();
            //$crud->callback_column('EstadoCartera', array($this, 'callback_column_estado'));
            
            $output = $crud->render();
            
            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Búsqueda de General');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Consultor/consultaGeneral', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }
    
    public function callback_column_estado($value, $row) {
        return $this->ObtenerEstado($row->ID, false);
    }
        
    function direccion_contratos($primary_key, $row) {
        return base_url() . 'index.php/consultor/detallesContratos/' . $primary_key;
    }

    function direccion_general($primary_key, $row) {
        return base_url() . 'index.php/consultor/detallesGeneral/' . $primary_key;
    }
    
    function detallesGeneral($noContrato)
    {
       $this->detalleTitulares($noContrato, 9);
    }
    
    function detallesContratos($noContrato)
    {
       $this->detalleTitulares($noContrato, 4);
    }
    
    function detalleTitulares($noContrato, $opcion) {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = $opcion;

        $contarbeneficiarios = 0;

        //$valTitId = $noContrato;
        $query = $this->db->query("
            SELECT TITULAR.*
            FROM TITULAR INNER JOIN CONTRATO on CONTRATO.TITID = TITULAR.ID  
            INNER JOIN DOCUMENTO on DOCUMENTO.ID = CONTRATO.DOCID   WHERE CONTRATO.ID = " . $noContrato);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['titular'] = $row;
            $valTitId = $row->ID;
            if ($row->BENEFICIARIO == 1) {
                $contarbeneficiarios = $contarbeneficiarios + 1;
            }
        } else {
            $data['titular'] = null;            
        }

        /// Se consultan los BENEFICIARIOS del titular
        $qbeneficiarios = $this->db->query("SELECT * FROM BENEFICIARIO WHERE TITID = " . $valTitId);
        if ($qbeneficiarios->num_rows() > 0) {
            $contarbeneficiarios = $contarbeneficiarios + $qbeneficiarios->num_rows();
            $data['beneficiarios'] = $qbeneficiarios->result();
            $data['tienebeneficiarios'] = true;
        } else {
            $data['tienebeneficiarios'] = false;
        }
        $data['numerobeneficiarios'] = $contarbeneficiarios;

        /// Se consultan los CONTACTOS del titular
        $qcontactos = $this->db->query("SELECT * FROM CONTACTO WHERE TITID = " . $valTitId);
        if ($qcontactos->num_rows() > 0) {
            $data['contactos'] = $qcontactos->result();
            $data['tienecontactos'] = true;
        } else {
            $data['tienecontactos'] = false;
        }

        /// SE GENERA LA INFORMACION DE PLAN, PAGOS Y DEUDAS DEL TITULAR
        /// Se consulta la informacion del contrato y los pagos realizados por el titular desde la vigencia del contrato
        $qcontrato = $this->db->query("
            SELECT subconsulta.*, TIMESTAMPDIFF(MONTH,subconsulta.FECHAINICIAL, subconsulta.FECHAFINAL) AS DIFERENCIA
            FROM (  
                SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, PLAN.NOMBRECONVENIO, 
                DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS AS BeneficPlan, 
                COSTOPLAN.COSTOPAGOMES, COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO, 
                CONTRATO.ESTADO, 
                CASE WHEN CONTRATO.PERIODICIDAD = 1 THEN
                   date_add( DATE_FORMAT(CONTRATO.FECHAINICIO, '%Y-%m-01'), INTERVAL 1 MONTH)
                ELSE CONTRATO.FECHAINICIO END  AS FECHAINICIAL,
                CASE WHEN CONTRATO.ESTADO = 1 THEN  CURDATE()
                ELSE CONTRATO.FECHAFIN END  AS FECHAFINAL, CAfilia.COSTOAFILIACION, CONTRATO.NUMBENEFICIARIOS, CONTRATO.TIPOCONTRATO
                FROM CONTRATO 
                INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
                INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
                INNER JOIN COSTOPLAN AS CAfilia ON (CAfilia.PLANID = PLAN.ID AND CONTRATO.FECHAINICIO BETWEEN CAfilia.FECHADESDE AND CAfilia.FECHAHASTA)
                WHERE CONTRATO.ID = " . $noContrato . "
            ) AS subconsulta; ");
        
        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
            $data['contrato'] = $contrato;
            $data['tienecontrato'] = true;
                
            // Se consultan todos los pagos realizados por el titular para el contrato
            $strQueryPagos = "
                SELECT PAGO.VALOR, PAGO.FECHA, PAGO.TIPOCONCEPTO, DOCUMENTO.NUMERO, 
                PERSONA.NOMBRES, PERSONA.APELLIDOS FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $valTitId . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $strQueryPagos = $strQueryPagos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }
            
            $qpagos = $this->db->query($strQueryPagos);
            
            // Para cada pago que se debería haber realizado se genera un elemento del array
            $lstPagos = array();
            $acumulado_en_pagos = 0;
            for($idx = 0; $idx < $qpagos->num_rows(); $idx++ )
            {
                $pagoind = $qpagos->row($idx);
                $fechapago = $pagoind->FECHA;
                $valorpagado = $pagoind->VALOR;
                $numerodoc = $pagoind->NUMERO;
                $asesor = $pagoind->NOMBRES . ' ' . $pagoind->APELLIDOS;
                $lstPagos[$idx] = array(                    
                    "fechapago" => $fechapago,
                    "valor" => $valorpagado,
                    "numero" => $numerodoc,
                    "asesor" => $asesor
                );
                
                $acumulado_en_pagos = $acumulado_en_pagos + $valorpagado;
            }
            $data['acumuladopagos'] = $acumulado_en_pagos;
            $data['lstpagos'] = $lstPagos;
            
             // Se calcula el numero de pagos que se deberian haber realizado            
            $cantidadpagos = ($contrato->DIFERENCIA + 1);
            //$pagosidx = 0;
            $invervalo = 'P1M';
            if ($contrato->PERIODICIDAD == 2) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 6);
                $invervalo = 'P6M';
            } else if ($contrato->PERIODICIDAD == 3) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 12);
                $invervalo = 'P1Y';
            }
            $inicio = date_parse($contrato->FECHAINICIAL);
            
            $factual = new DateTime($inicio['year'] . '-' . $inicio['month'] . '-' . $inicio['day']);
            $ahora = new DateTime('NOW');
            
            $lstPeriodos  =array();
            $acumulado_en_mora = 0;
            $acumulado_por_pagar = 0;
            $acumulado_total = 0;
            //$acumulado_vencido = 0;
            for ($i = 0; $i < $cantidadpagos; $i++) {
                $finperiodo = clone $factual;
                $limitepago = clone $factual;
                $finperiodo->add(new DateInterval($invervalo));
                $limitepago->add(new DateInterval('P9D'));
                
                                
                $valor_pagar = $this->ObtenerValorPago($noContrato, $factual, $contrato->NUMBENEFICIARIOS);
                $valor_pagar_orig = $valor_pagar;
                $acumulado_total = $acumulado_total + $valor_pagar_orig;
                $estado = 'OK';
                if($valor_pagar > $acumulado_en_pagos)
                {
                    $valor_pagar = $valor_pagar - $acumulado_en_pagos;
                    $acumulado_en_pagos = 0;
                    if($ahora > $limitepago)
                    {
                        $acumulado_en_mora = $acumulado_en_mora + $valor_pagar;
                        $estado = 'EN MORA';
                    }
                    else
                    {
                        $acumulado_por_pagar = $acumulado_por_pagar + $valor_pagar;
                        $estado = 'POR PAGAR';
                    }
                }
                else
                {
                    $acumulado_en_pagos = $acumulado_en_pagos -  $valor_pagar;
                }
                
                $lstPeriodos[$i] = array(
                    "inicioperiodo" => $factual->format('Y-m-d'),
                    "finperiodo" => $finperiodo->format('Y-m-d'),
                    "limitepago" => $limitepago->format('Y-m-d'),
                    "valorapagar" => $valor_pagar_orig,
                    "estado" => $estado
                );
                $factual->add(new DateInterval($invervalo));
            }
            $data['acumulado_total'] = $acumulado_total ;
            $data['lstperiodos'] = $lstPeriodos;
            $data['acumuladodeuda'] = $acumulado_en_mora;
            $data['acumuladoporpagar'] = $acumulado_por_pagar;
            
            $sqlOtrosConceptos = "
                SELECT VALOR, FECHA, DESCRIPCION FROM OTROSCARGOS 
                WHERE TITID = " . $valTitId . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $sqlOtrosConceptos = $sqlOtrosConceptos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }
            $qotrosConceptos = $this->db->query($sqlOtrosConceptos);
            if ($qotrosConceptos->num_rows() > 0) {
                $data['otrosconceptos'] = $qotrosConceptos->result();
            }
            else
            {
                $data['otrosconceptos'] = NULL;
            }
            
            $sqlOtrosPagos = "
                SELECT PAGO.VALOR, PAGO.FECHA, DOCUMENTO.NUMERO FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 3 AND  TITID = " . $valTitId . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $sqlOtrosPagos = $sqlOtrosPagos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }
            
            $qotrospagos = $this->db->query($sqlOtrosPagos);
            if ($qotrospagos->num_rows() > 0) {
                $data['otrospagos'] = $qotrospagos->result();
            }
            else
            {
                $data['otrospagos'] = NULL;
            }
            
            $sqlOtrosConceptos = str_replace("VALOR, FECHA, DESCRIPCION", " SUM(VALOR) as TOTAL ", $sqlOtrosConceptos);
            //echo $sqlOtrosConceptos;
            $qtotalOtros = $this->db->query($sqlOtrosConceptos);
            
            if ($qtotalOtros->num_rows() > 0) {
                $rtotalOtros = $qtotalOtros->row(0);
                $data['totalotros'] = $rtotalOtros->TOTAL;
            }
            else
            {
                $data['totalotros'] = 0;
            }
            
            $sqlOtrosPagos = str_replace("PAGO.VALOR, PAGO.FECHA, DOCUMENTO.NUMERO", " SUM(VALOR) TOTAL ", $sqlOtrosPagos);
            //echo $sqlOtrosPagos;
            $qtotalOtrosPagos = $this->db->query($sqlOtrosPagos);
            if ($qtotalOtrosPagos->num_rows() > 0) {
                $rtotalOtrosPAgos = $qtotalOtrosPagos->row(0);
                $data['totalotrospagos'] = $rtotalOtrosPAgos->TOTAL;
            }
            else
            {
                $data['totalotrospagos'] = 0;
            }
            
            $acumulado_en_mora = $acumulado_en_mora + $data['totalotros'] - $data['totalotrospagos'];
            
        } else {
            $data['tienecontrato'] = false;
        }
        
        if(($acumulado_en_mora) > 0)
        {
            $data['estadocontrato'] = "EN MORA $ " . number_format(($acumulado_en_mora), 2, ',', '.') ;    
        }
        else
        {
            $data['estadocontrato'] = "OK";    
        }
        if($acumulado_por_pagar > 0)
        {
            $data['proximopago'] = " $ " . number_format(($acumulado_por_pagar), 2, ',', '.');
        }
        else
        {
            $data['proximopago'] = "";
        }
        
        $costo_afiliacion = 0;
        if($contrato->TIPOCONTRATO != 2)
        {
            $costo_afiliacion = $contrato->COSTOAFILIACION * $contrato->NUMBENEFICIARIOS;
        }
        else
        {
            $strContarBen = "SELECT NUMBENEFICIARIOS FROM CONTRATO WHERE CONTRATO.ID IN ( 
                             SELECT max(CONTRATO.ID) ultimo FROM CONTRATO WHERE CONTRATO.TITID = " . $valTitId . " and ESTADO = 0)";
            $qcontarbenef = $this->db->query($strContarBen);
        
            if ($qcontarbenef->num_rows() > 0) {
                $contadorbenef = $qcontarbenef->row(0);
                $viejosbeneficiarios = $contadorbenef->NUMBENEFICIARIOS;
                $costo_afiliacion = $contrato->COSTOAFILIACION * ($contrato->NUMBENEFICIARIOS - $viejosbeneficiarios);
            }
            
        }
        $data['costoafiliacion'] = $costo_afiliacion;
        $strQueryPagosAfiliacion = "
                SELECT SUM(PAGO.VALOR) total FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 2 AND  TITID = " . $valTitId . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
        if($contrato->ESTADO == 0)
        {
            $strQueryPagosAfiliacion = $strQueryPagosAfiliacion . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
        }

        $qsumapagos = $this->db->query($strQueryPagosAfiliacion);
        if ($qsumapagos->num_rows() > 0) {                
            $sumapagoafiliacion = $qsumapagos->row(0);
            $data['sumapagoafiliacion'] = $sumapagoafiliacion->total;                
        }
        
        
        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Detalles de Titular');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'Consultor/detalleTitulares');
        $this->template->render();
    }
        
    /// LOGICA DUPLICADA EN MODELO CUSTOM_QUERY_MODEL
    function ObtenerValorPago($noContrato, $factual, $numbeneficiarios) {
        $strQuery = "select PLAN.NOMBRE, COSTOPLAN.COSTOAFILIACION,
            case when CONTRATO.PERIODICIDAD = 1 then COSTOPLAN.COSTOPAGOMES
            when CONTRATO.PERIODICIDAD = 2 then COSTOPLAN.COSTOPAGOSEMESTRE
            else COSTOPLAN.COSTOPAGOANIO end as COSTOBENEFICIARIO, PLAN.NUMBENEFICIARIOS, CONTRATO.PERIODICIDAD
            from CONTRATO inner join PLAN on PLAN.ID = CONTRATO.PLANID
            inner join COSTOPLAN on COSTOPLAN.PLANID = PLAN.ID
            where '" . $factual->format('Y-m-d') . "' between COSTOPLAN.FECHADESDE and COSTOPLAN.FECHAHASTA and CONTRATO.ID = " . $noContrato;
        $qcostonormal = $this->db->query($strQuery);     
        if($qcostonormal->num_rows() <= 0 )
        {
            $valor_total = -1;
        }
        else
        {
            $valnormal = $qcostonormal->row(0);       
            $valor_total = $numbeneficiarios * $valnormal->COSTOBENEFICIARIO;
        }
        return $valor_total;
    }  
    
    
    
    

    function consultaDocumentos() {
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol >= 1 && $session_rol <= 4) {

            $data['asesor'] = $this->input->post('asesor');            
            $data['estado'] = $this->input->post('estado');
            $data['nodocumento'] = $this->input->post('nodocumento');
            $data['rangoinicio'] = $this->input->post('rangoinicio');
            $data['rangofin'] = $this->input->post('rangofin');
            $data['tipo'] = $this->input->post('tipo');

            //informacion de Usuario
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 10;

            $crud = new grocery_CRUD();
            $crud->set_model('custom_document_model');
            $crud->set_table('DOCUMENTO'); //Change to your table name

            $strSQL = " SELECT CONCAT(PERSONA.NOMBRES, ' ' , PERSONA.APELLIDOS) AS ASESOR, 
                        DOCUMENTO.NUMERO, DOCUMENTO.ESTADO, DOCUMENTO.TIPO, DOCUMENTO.ID
                        FROM DOCUMENTO
                        INNER JOIN PERSONA ON DOCUMENTO.EMPID = PERSONA.ID  ";
            if ($data['asesor'] != '') {
                $strSQL = $strSQL . " AND CONCAT(PERSONA.NOMBRES, ' ', PERSONA.APELLIDOS) like '%" . $data['asesor'] . "%' ";
            }
            if (is_numeric($data['estado'])) {
                if($data['estado'] > 0)
                    $strSQL = $strSQL . " AND DOCUMENTO.ESTADO = " . $data['estado'] . " ";
            }
            if (is_numeric($data['nodocumento'])) {
                $strSQL = $strSQL . " AND DOCUMENTO.NUMERO = " . $data['nodocumento'] . " ";
            }
            if (is_numeric($data['rangoinicio'])) {
                $strSQL = $strSQL . " AND DOCUMENTO.NUMERO >= " . $data['rangoinicio'] . " ";
            }
            if (is_numeric($data['rangofin'])) {
                $strSQL = $strSQL . " AND DOCUMENTO.NUMERO <= " . $data['rangofin'] . " ";
            }
            if (is_numeric($data['tipo'])) {
                if($data['tipo'] > 0)
                    $strSQL = $strSQL . " AND DOCUMENTO.TIPO = " . $data['tipo'] . " ";
            }
            $strSQL = $strSQL . " ORDER BY DOCUMENTO.NUMERO";
            $crud->basic_model->set_query_str($strSQL); //Query text here
            $crud->columns("NUMERO", "ASESOR","ESTADO", "TIPO");
            $crud->display_as('ASESOR', 'Asesor');
            $crud->display_as('NUMERO', 'Número');
            $crud->display_as('ESTADO', 'Estado Documento');
            $crud->display_as('TIPO', 'Tipo Documento');
                        
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_read();
            
            $crud->unset_jquery();
            $crud->callback_column('ESTADO', array($this, 'callback_column_estado_documento'));
            $crud->callback_column('TIPO', array($this, 'callback_column_tipo_documento'));
            
            $output = $crud->render();
            
            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Búsqueda de Documentos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'Consultor/consultaDocumentos', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }
    
    public function callback_column_estado_documento($value, $row) {  
        $estados=array('1' => 'Asignado', '2' => 'Reportado', '3' => 'Anulado');
        return $estados[$row->ESTADO];
    }
    
    public function callback_column_tipo_documento($value, $row) {    
        $estados=array('1' => 'Contrato', '2' => 'Recibo de Caja');
        return $estados[$row->TIPO];
    }
    
}

