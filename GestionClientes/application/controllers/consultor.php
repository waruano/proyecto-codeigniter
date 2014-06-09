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
            $crud->set_model('Custom_query_model');
            $crud->set_table('persona'); //Change to your table name

            $strSQL = "select 'Estado' as Estado, documento.Numero as NumeroContrato, CONCAT(titular.Nombres, ' ', ifnull(titular.Apellidos, '')) as NombreTitular, 
            titular.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, contrato.id as ID
            from titular inner join contrato on contrato.TITID = titular.ID inner join documento on documento.ID = contrato.DOCID ";

            if ($data['valtitular'] != '') {
                $strSQL = $strSQL . " AND CONCAT(persona.Nombres, ' ', persona.Apellidos) like '%" . $data['valtitular'] . "%' ";
            }

            if ($data['validentificacion'] != '') {
                $strSQL = $strSQL . " AND persona.NoDocumento like '%" . $data['validentificacion'] . "%' ";
            }

            if (is_numeric($data['valnocontrato'])) {
                $strSQL = $strSQL . " AND documento.Numero = " . $data['valnocontrato'] . " ";
            }

            $crud->basic_model->set_query_str($strSQL); //Query text here
            $crud->columns('Estado', 'NumeroContrato', 'NombreTitular', 'Identificacion', 'FechaAfiliacion');
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_read();
            $crud->add_action('Detalles', base_url() . 'images/magnifier.png', 'Detalles', '', array($this, 'direccion_contratos'));
            $crud->unset_jquery();
            $crud->callback_column('Estado', array($this, 'callback_column_estado'));
            
            $output = $crud->render();
            
            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Búsqueda de Contratos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'consultor/consulta', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }
    
    function consultaGeneral() {
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol >= 1 && $session_rol <= 4) {
            // se reciben los datos desde el formulario
            $data['validentificacion'] = $this->input->post('identificacion');
            $data['valnocontrato'] = $this->input->post('numeroContrato');
            $data['valtitular'] = $this->input->post('nombreTitular');
            $data['valtelefono'] = $this->input->post('telefono');
            $data['valcorreo'] = $this->input->post('correo');
            $data['valgenero'] = $this->input->post('genero');
            $data['valdireccion'] = $this->input->post('direccion');
            $data['valestrato'] = $this->input->post('estrato');
            $data['valeps'] = $this->input->post('eps');
            $data['valafiliaciondesde'] = $this->input->post('afiliaciondesde');
            $data['valafiliacionhasta'] = $this->input->post('afiliacionhasta');
            $data['plan'] = $this->input->post('plan');
            $data['convenio'] = $this->input->post('convenio');
            $data['asesor'] = $this->input->post('asesor');            
            
            //informacion de Usuario
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['selectedoption'] = 4;

            $crud = new grocery_CRUD();
            $crud->set_model('Custom_query_model');
            $crud->set_table('persona'); //Change to your table name

            $strSQL = "select 'Estado' as Estado, documento.Numero as NumeroContrato, CONCAT(titular.Nombres, ' ', ifnull(titular.Apellidos, '')) as NombreTitular, 
                    titular.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, contrato.id as ID
                    from titular inner join contrato on contrato.TITID = titular.ID inner join documento on documento.ID = contrato.DOCID ";

            // se crean los filtros a la consulta dependiendo de lo recibido por post
            if ($data['valtitular'] != '') {
                $strSQL = $strSQL . " AND CONCAT(persona.Nombres, ' ', persona.Apellidos) like '%" . $data['valtitular'] . "%' ";
            }

            if ($data['validentificacion'] != '') {
                $strSQL = $strSQL . " AND persona.NoDocumento like '%" . $data['validentificacion'] . "%' ";
            }

            if (is_numeric($data['valnocontrato'])) {
                $strSQL = $strSQL . " AND documento.Numero = " . $data['valnocontrato'] . " ";
            }

            // Se define el CRUD
            $crud->basic_model->set_query_str($strSQL); //Query text here
            $crud->columns('Estado', 'NumeroContrato', 'NombreTitular', 'Identificacion', 'FechaAfiliacion');
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_read();
            $crud->add_action('Detalles', base_url() . 'images/magnifier.png', 'Detalles', '', array($this, 'direccion_contratos'));
            $crud->unset_jquery();
            $crud->callback_column('Estado', array($this, 'callback_column_estado'));
            
            $output = $crud->render();
            
            //Configuracion de la Plantilla
            $this->template->write_view('login', $this->tank_auth->get_login(), $data);
            $this->template->write('title', 'Búsqueda de Contratos');
            $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
            $this->template->write_view('content', 'consultor/consulta', $output);
            $this->template->render();
        } else {
            redirect('');
        }
    }
    
    public function callback_column_estado($value, $row) {
        return $this->ObtenerEstado($row->ID, false);
    }
        
    function direccion_contratos($primary_key, $row) {
        return base_url() . 'consultor/detalleTitulares/' . $primary_key;
    }

    function detalleTitulares($noContrato) {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 4;

        $contarbeneficiarios = 0;

        //$valTitId = $noContrato;
        $query = $this->db->query("SELECT titular.*
                                   FROM titular INNER JOIN CONTRATO on Contrato.TitId = titular.ID  AND contrato.estado = 1
                                   INNER JOIN documento on documento.id = contrato.docId   WHERE contrato.id = " . $noContrato);
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

        /// Se consultan los beneficiarios del titular
        $qbeneficiarios = $this->db->query("SELECT * FROM BENEFICIARIO WHERE titId = " . $valTitId);
        if ($qbeneficiarios->num_rows() > 0) {
            $contarbeneficiarios = $contarbeneficiarios + $qbeneficiarios->num_rows();
            $data['beneficiarios'] = $qbeneficiarios->result();
            $data['tienebeneficiarios'] = true;
        } else {
            $data['tienebeneficiarios'] = false;
        }
        $data['numerobeneficiarios'] = $contarbeneficiarios;

        /// Se consultan los contactos del titular
        $qcontactos = $this->db->query("SELECT * FROM CONTACTO WHERE titId = " . $valTitId);
        if ($qcontactos->num_rows() > 0) {
            $data['contactos'] = $qcontactos->result();
            $data['tienecontactos'] = true;
        } else {
            $data['tienecontactos'] = false;
        }

        /// Se consulta la informacion del contrato y los pagos realizados por el titular desde la vigencia del contrato
        $qcontrato = $this->db->query("
            SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, PLAN.NOMBRECONVENIO, DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS,
            COSTOPLAN.COSTOPAGOMES, COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO,
            CASE WHEN CONTRATO.ESTADO = 1 THEN
                TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CURDATE()) 
            ELSE
                TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CONTRATO.FECHAFIN) END
            AS DIFERENCIA,
            CONTRATO.ESTADO
            FROM CONTRATO 
            INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
            INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
            INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
            WHERE contrato.id = " . $noContrato);
        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
            $data['contrato'] = $contrato;
            $data['tienecontrato'] = true;
                
            // Se consultan los costos adicionales para el plan actual
            $qcostoadicional = $this->db->query("
                SELECT COSTOPLAN.COSTOAFILIACION, COSTOPLAN.COSTOPAGOMES, COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO
                FROM PLAN INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID 
                AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
                WHERE PLAN.NOMBRE = '" . $contrato->NOMBRE . "' 
                AND plan.NUMBENEFICIARIOS in (	SELECT MAX(plan.NUMBENEFICIARIOS) as maximo FROM PLAN WHERE PLAN.NOMBRE = '" . $contrato->NOMBRE . "' )");
            $data['costoadicional'] = $qcostoadicional->row(0);
            
            // Se consultan todos los pagos realizados por el titular para el contrato
            $strQueryPagos = "
                SELECT PAGO.VALOR, PAGO.FECHA, PAGO.TIPOCONCEPTO, DOCUMENTO.NUMERO, PERSONA.NOMBRES, PERSONA.APELLIDOS 
                FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $valTitId . " AND FECHA >= " . $contrato->FECHAINICIO; 
            if($contrato->ESTADO == 0)
            {
                $strQueryPagos = $strQueryPagos . " AND FECHA <= " . $contrato->FECHAFIN;                
            }
            $qpagos = $this->db->query($strQueryPagos);
            $data['pagos'] = $qpagos->result();

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
            $pagosidx = 0;
            $invervalo = 'P1M';
            if ($contrato->PERIODICIDAD == 2) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 6);
                $invervalo = 'P6M';
            } else if ($contrato->PERIODICIDAD == 3) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 12);
                $invervalo = 'P1Y';
            }
            $inicio = date_parse($contrato->FECHAINICIO);
            $factual = new DateTime($inicio['year'] . '-' . $inicio['month'] . '-' . $inicio['day']);
            
            $lstPeriodos  =array();
            $acumulado_deuda = 0;
            for ($i = 0; $i < $cantidadpagos; $i++) {
                $finperiodo = clone $factual;
                $limitepago = clone $factual;
                $finperiodo->add(new DateInterval($invervalo));
                $limitepago->add(new DateInterval('P10D'));
                $valor_pagar = $this->ObtenerValorPago($noContrato, $factual, $contarbeneficiarios);
                $acumulado_deuda = $acumulado_deuda + $valor_pagar;
                $estado = 'OK';
                if($valor_pagar > $acumulado_en_pagos)
                {
                    $estado = 'EN MORA';
                }
                $acumulado_en_pagos = $acumulado_en_pagos -  $valor_pagar;
                $lstPeriodos[$i] = array(
                    "inicioperiodo" => $factual->format('Y-m-d'),
                    "finperiodo" => $finperiodo->format('Y-m-d'),
                    "limitepago" => $limitepago->format('Y-m-d'),
                    "valorapagar" => $valor_pagar,
                    "estado" => $estado
                );

                $factual->add(new DateInterval($invervalo));
            }
            $data['lstperiodos'] = $lstPeriodos;
            $data['acumuladodeuda'] = $acumulado_deuda;
        } else {
            $data['tienecontrato'] = false;
        }
        
        if($data['acumuladodeuda'] > $data['acumuladopagos'])
        {
            $data['estadocontrato'] = "EN MORA ($ " . number_format(($data['acumuladodeuda'] - $data['acumuladopagos']), 2, ',', '.') .")";    
        }
        else
        {
            $data['estadocontrato'] = "OK";    
        }
        
        //$data['estadocontrato'] = $this->ObtenerEstado($valTitId, true);

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Búsqueda de Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'consultor/detalleTitulares');
        $this->template->render();
    }
    
    function ObtenerValorPago($noContrato, $factual, $numbeneficiarios) {
        $strQuery = "select plan.nombre, costoplan.COSTOAFILIACION,
            case when contrato.PERIODICIDAD = 1 then costoplan.COSTOPAGOMES
            when contrato.PERIODICIDAD = 2 then costoplan.COSTOPAGOSEMESTRE
            else costoplan.COSTOPAGOANIO end as COSTOBENEFICIARIO, plan.NUMBENEFICIARIOS, contrato.PERIODICIDAD
            from contrato inner join plan on plan.id = contrato.PLANID
            inner join costoplan on costoplan.planid = plan.id
            where '" . $factual->format('Y-m-d') . "' between costoplan.fechadesde and costoplan.fechahasta and contrato.id = " . $noContrato;
        $qcostonormal = $this->db->query($strQuery);     
        if($qcostonormal->num_rows() <= 0 )
        {
            $valor_total = -1;
        }
        else
        {
            $valnormal = $qcostonormal->row(0);                    
            if($numbeneficiarios > $valnormal->NUMBENEFICIARIOS)
            {
                $qadicional = $this->db->query("SELECT COSTOPLAN.COSTOAFILIACION, 
                case when " . $valnormal->PERIODICIDAD ." = 1 then costoplan.COSTOPAGOMES
                when " . $valnormal->PERIODICIDAD ."  = 2 then costoplan.COSTOPAGOSEMESTRE
                else costoplan.COSTOPAGOANIO end as COSTOBENEFICIARIO
                FROM PLAN INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID 
                AND '" . $factual->format('Y-m-d') . "' BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
                WHERE PLAN.NOMBRE = '" . $valnormal->nombre . "' 
                AND plan.NUMBENEFICIARIOS in (	SELECT MAX(plan.NUMBENEFICIARIOS) as maximo	FROM PLAN WHERE PLAN.NOMBRE = '" . $valnormal->nombre . "')");
                $valadicional = $qadicional->row(0);
                //echo $valnormal->COSTOBENEFICIARIO . ' ' . $valadicional->COSTOBENEFICIARIO . ' -- ';
                $valor_total = ($valnormal->NUMBENEFICIARIOS * $valnormal->COSTOBENEFICIARIO) + 
                               ($valadicional->COSTOBENEFICIARIO * ($numbeneficiarios - $valnormal->NUMBENEFICIARIOS) );
            }
            else
            {
                $valor_total = $numbeneficiarios * $valnormal->COSTOBENEFICIARIO;
            } 
        }
        return $valor_total;
    }

    function ObtenerEstado($contratoId, $condetalle) {
        /// Se consulta la informacion del contrato y los pagos realizados por el titular desde la vigencia del contrato
        $qcontrato = $this->db->query("
            SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, 
            PLAN.NOMBRECONVENIO, DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS,            
            CASE WHEN CONTRATO.ESTADO = 1 THEN
                TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CURDATE()) 
            ELSE
                TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CONTRATO.FECHAFIN) END
            AS DIFERENCIA, 
            CONTRATO.TITID, CONTRATO.FECHAFIN, CONTRATO.ESTADO
            FROM CONTRATO INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
            INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
            WHERE contrato.id = " . $contratoId);

        $estado = "OK";

        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
            $strQueryPagos = "
                SELECT IFNULL(SUM(PAGO.VALOR),0) AS VALOR
                FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $contrato->TITID . " AND FECHA >= " . $contrato->FECHAINICIO; 
            if($contrato->ESTADO == 0)
            {
                $strQueryPagos = $strQueryPagos . " AND FECHA <= " . $contrato->FECHAFIN;                
            }
            $qpagos = $this->db->query($strQueryPagos);
            $rtotal = $qpagos->row(0);
            $totalpagado = $rtotal->VALOR;
            //echo $totalpagado . ' ' ;

            // Se calcula el numero de pagos que se deberian haber realizado            
            $cantidadpagos = ($contrato->DIFERENCIA + 1);
            $pagosidx = 0;
            $invervalo = 'P1M';
            if ($contrato->PERIODICIDAD == 2) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 6);
                $invervalo = 'P6M';
            } else if ($contrato->PERIODICIDAD == 3) {
                $cantidadpagos = ceil($contrato->DIFERENCIA / 12);
                $invervalo = 'P1Y';
            }
            $inicio = date_parse($contrato->FECHAINICIO);
            $factual = new DateTime($inicio['year'] . '-' . $inicio['month'] . '-' . $inicio['day']);

            $coutas_pendientes = 0;
            $total_deuda = 0;
            
            for ($i = 0; $i < $cantidadpagos; $i++) {
                /* $finperiodo = clone $factual; */
                $limitepago = clone $factual;
                /* $finperiodo->add(new DateInterval($invervalo)); */
                $limitepago->add(new DateInterval('P10D'));
                $valorPago = $this->ObtenerValorPago($contratoId, $factual, $contrato->NUMBENEFICIARIOS );
                if($valorPago >= 0 )
                {
                    $total_deuda = $total_deuda + $valorPago;       
                }
                else {
                    $estado = 'Error en definicion de tarifas! (Plan: ' . $contrato->NOMBRE . ' Fecha: ' . $factual->format('Y-m-d') . ' ) ';
                    return $estado;
                }
                $pagosidx = $pagosidx + 1;
                $factual->add(new DateInterval($invervalo));
            }
            
            if ($totalpagado < $total_deuda) {
                if($condetalle)
                {                    
                    $estado = "EN MORA ($ " . number_format(($total_deuda - $totalpagado), 2, ',', '.') .")";    
                }
                else
                {
                    $estado = "EN MORA";        
                }                
            } 
        } else {
            $estado = "CONTRATO INACTIVO Ó INEXISTENTE";
        }
        return $estado;
    }

}

