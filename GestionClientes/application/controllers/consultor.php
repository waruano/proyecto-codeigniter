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
            titular.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, titular.id as ID
            from titular left join contrato on contrato.TITID = titular.ID left join documento on documento.ID = contrato.DOCID
            where contrato.ESTADO = 1";

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
    
    public function callback_column_estado($value, $row)
    {
        return $this->ObtenerEstado($row->ID, false);
    }
    
    function busquedageneral() {
        $session_rol = $this->tank_auth->get_rol();
        if ($session_rol >= 1 && $session_rol <= 4) {
            
            // Se obtienen todos los valores de los filtros
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
            titular.NoDocumento as Identificacion, contrato.FechaInicio as FechaAfiliacion, titular.id as ID
            from titular left join contrato on contrato.TITID = titular.ID left join documento on documento.ID = contrato.DOCID
            where contrato.ESTADO = 1";

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
    
        
    
    function direccion_contratos($primary_key, $row) {
        return base_url() . 'consultor/detalleTitulares/' . $primary_key;
    }

    function detalleTitulares($noContrato) {
        //informacion de Usuario
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['username'] = $this->tank_auth->get_username();
        $data['selectedoption'] = 4;

        $contarbeneficiarios = 0;

        $valTitId = $noContrato;
        $query = $this->db->query("SELECT *
                                   FROM titular Left Join CONTRATO on Contrato.TitId = titular.ID   and contrato.estado = 1
                                   left join documento on documento.id = contrato.docId   WHERE titular.id = " . $valTitId);
        if ($query->num_rows() > 0) {
            $row = $query->row(0);
            $data['titular'] = $row;
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
            SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, 
            PLAN.NOMBRECONVENIO, DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS,
            COSTOPLAN.COSTOPAGOMES, COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO,                                    
            TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CURDATE()) AS DIFERENCIA
            FROM CONTRATO INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
            INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
            INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID 
            AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
            WHERE contrato.ESTADO = 1 AND TITID = " . $valTitId);
        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
            $data['contrato'] = $contrato;
            $data['tienecontrato'] = true;

            $qpagos = $this->db->query("
                SELECT PAGO.VALOR, PAGO.FECHA, PAGO.TIPOCONCEPTO, DOCUMENTO.NUMERO, PERSONA.NOMBRES, PERSONA.APELLIDOS 
                FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $valTitId . " AND FECHA >= " . $contrato->FECHAINICIO);
            $data['pagos'] = $qpagos->result();

            $qcostoadicional = $this->db->query("
                SELECT COSTOPLAN.COSTOAFILIACION, COSTOPLAN.COSTOPAGOMES, COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO
                FROM PLAN INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID 
                AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
                WHERE PLAN.NOMBRE = '" . $contrato->NOMBRE . "' 
                AND plan.NUMBENEFICIARIOS in (	SELECT MAX(plan.NUMBENEFICIARIOS) as maximo	FROM PLAN WHERE PLAN.NOMBRE = '" . $contrato->NOMBRE . "' )");
            $data['costoadicional'] = $qcostoadicional->row(0);

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

            // Para cada pago que se debería haber realizado se genera un elemento del array
            $lstPagos = array();
            if ($cantidadpagos < $qpagos->num_rows()) {
                $cantidadpagos = $qpagos->num_rows();
            }

            for ($i = 0; $i < $cantidadpagos; $i++) {
                $finperiodo = clone $factual;
                $limitepago = clone $factual;
                $finperiodo->add(new DateInterval($invervalo));
                $limitepago->add(new DateInterval('P10D'));
                $fechapago = 'No realizado';
                $valorpagado = 0;
                $numerodoc = 0;
                $asesor = "";
                if ($qpagos->num_rows() > $pagosidx) {
                    $pagoind = $qpagos->row($pagosidx);
                    $fechapago = $pagoind->FECHA;
                    $valorpagado = $pagoind->VALOR;
                    $numerodoc = $pagoind->NUMERO;
                    $asesor = $pagoind->NOMBRES . ' ' . $pagoind->APELLIDOS;
                    $pagosidx = $pagosidx + 1;
                }

                $lstPagos[$i] = array(
                    "inicioperiodo" => $factual->format('Y-m-d'),
                    "finperiodo" => $finperiodo->format('Y-m-d'),
                    "limitepago" => $limitepago->format('Y-m-d'),
                    "fechapago" => $fechapago,
                    "valor" => $valorpagado,
                    "numero" => $numerodoc,
                    "asesor" => $asesor
                );

                $factual->add(new DateInterval($invervalo));
            }

            $data['lstpagos'] = $lstPagos;
        } else {
            $data['tienecontrato'] = false;
        }

        $data['estadocontrato'] = $this->ObtenerEstado($valTitId, true);

        //Configuracion de la Plantilla
        $this->template->write_view('login', $this->tank_auth->get_login(), $data);
        $this->template->write('title', 'Búsqueda de Contratos');
        $this->template->write_view('sidebar', $this->tank_auth->get_sidebar());
        $this->template->write_view('content', 'consultor/detalleTitulares');
        $this->template->render();
    }

    function ObtenerEstado($titularId, $condetalle) {
        /// Se consulta la informacion del contrato y los pagos realizados por el titular desde la vigencia del contrato
        $qcontrato = $this->db->query("
            SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, 
            PLAN.NOMBRECONVENIO, DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS,            
            TIMESTAMPDIFF(MONTH,CONTRATO.FECHAINICIO,CURDATE()) AS DIFERENCIA
            FROM CONTRATO INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
            INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
            WHERE contrato.ESTADO = 1 AND TITID = " . $titularId);

        $estado = "OK";

        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
            $qpagos = $this->db->query("
                SELECT PAGO.VALOR, PAGO.FECHA, PAGO.TIPOCONCEPTO, DOCUMENTO.NUMERO, PERSONA.NOMBRES, PERSONA.APELLIDOS 
                FROM PAGO INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $titularId . " AND FECHA >= " . $contrato->FECHAINICIO);


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

            $ahora = getdate();
            $fahora = new DateTime($ahora['year'] . '-' . $ahora['month'] . '-' . $ahora['mday']);

            // Para cada pago que se debería haber realizado se genera un elemento del array
            $lstPagos = array();

            $coutas_pendientes = 0;

            for ($i = 0; $i < $cantidadpagos; $i++) {
                /* $finperiodo = clone $factual; */
                $limitepago = clone $factual;
                /* $finperiodo->add(new DateInterval($invervalo)); */
                $limitepago->add(new DateInterval('P10D'));
                /* $fechapago = 'NO'; */
                /* $valorpagado = 0; */
                if ($qpagos->num_rows() <= $pagosidx && $limitepago < $fahora) {
                    $coutas_pendientes = $coutas_pendientes + 1;
                }

                $pagosidx = $pagosidx + 1;
                $factual->add(new DateInterval($invervalo));
            }
            if ($coutas_pendientes == 1) {
                if($condetalle)
                {                    
                    $estado = "EN MORA (1 pago pendiente)";        
                }
                else
                {
                    $estado = "EN MORA";        
                }
                
            } else if ($coutas_pendientes > 1) {                
                if($condetalle)
                {                    
                    $estado = "EN MORA (" . $coutas_pendientes . " pagos pendientes)";
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

