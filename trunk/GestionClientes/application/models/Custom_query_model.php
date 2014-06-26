<?php 

if ( ! defined('BASEPATH')) 
     exit('No direct script access allowed');
 
class Custom_query_model extends grocery_CRUD_model {
 
	private  $query_str = ''; 
        private $estado = '';
        
	function __construct() {
		parent::__construct();
	}
 
        function set_estado($valorEstado){
            $this->estado = $valorEstado;
        }
        
	function get_list() {
		$query=$this->db->query($this->query_str);
                $results_array=$query->result();                
                foreach ($results_array as $result)
                {
                    $result->EstadoCartera = $this->ObtenerEstado($result->ID, false);;
                }
                $rFiltrado = array();
                $indice = 0;
                
                if($this->estado != '')
                {
                    foreach ($results_array as $result)
                    {
                        if($result->EstadoCartera == $this->estado)
                        {
                            $duplicado = clone $result;
                            $rFiltrado[$indice] = $duplicado;
                            $indice = $indice + 1;
                        }
                    }
                }
                else
                {
                    foreach ($results_array as $result)
                    {
                        $duplicado = clone $result;
                        $rFiltrado[$indice] = $duplicado;
                        $indice = $indice + 1;
                    }
                    //$rFiltrado = $result;
                }
		
		return $rFiltrado;		
	}
 
	public function set_query_str($query_str) {
		$this->query_str = $query_str;
        }
        
        function ObtenerEstado($contratoId, $condetalle) {
        $qcontrato = $this->db->query("
            SELECT subconsulta.*, TIMESTAMPDIFF(MONTH,subconsulta.FECHAINICIAL, subconsulta.FECHAFINAL) AS DIFERENCIA
            FROM (  
                SELECT CONTRATO.FECHAINICIO, PLAN.NOMBRE, CONTRATO.PERIODICIDAD, PLAN.NOMBRECONVENIO, 
                DOCUMENTO.NUMERO, DOCUMENTO.TIPO, PLAN.NUMBENEFICIARIOS AS BeneficPlan, COSTOPLAN.COSTOPAGOMES, 
                COSTOPLAN.COSTOPAGOSEMESTRE, COSTOPLAN.COSTOPAGOANIO, CONTRATO.ESTADO, 
                CASE WHEN CONTRATO.PERIODICIDAD = 1 THEN
                   date_add( DATE_FORMAT(CONTRATO.FECHAINICIO, '%Y-%m-01'), INTERVAL 1 MONTH)
                ELSE CONTRATO.FECHAINICIO END  AS FECHAINICIAL,
                CASE WHEN CONTRATO.ESTADO = 1 THEN  CURDATE()
                ELSE CONTRATO.FECHAFIN END  AS FECHAFINAL, CAfilia.COSTOAFILIACION, CONTRATO.NUMBENEFICIARIOS, CONTRATO.TITID
                FROM CONTRATO 
                INNER JOIN PLAN ON PLAN.ID = CONTRATO.PLANID 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = CONTRATO.DOCID 
                INNER JOIN COSTOPLAN ON (COSTOPLAN.PLANID = PLAN.ID AND CURDATE() BETWEEN COSTOPLAN.FECHADESDE AND COSTOPLAN.FECHAHASTA)
                INNER JOIN COSTOPLAN AS CAfilia ON (CAfilia.PLANID = PLAN.ID AND CONTRATO.FECHAINICIO BETWEEN CAfilia.FECHADESDE AND CAfilia.FECHAHASTA)
                WHERE contrato.id = " . $contratoId . "
            ) AS subconsulta; ");
        
        if ($qcontrato->num_rows() > 0) {
            $contrato = $qcontrato->row(0);
                
            // Se consultan todos los pagos realizados por el titular para el contrato
            $strQueryPagos = "
                SELECT PAGO.VALOR, PAGO.FECHA, PAGO.TIPOCONCEPTO, DOCUMENTO.NUMERO, 
                PERSONA.NOMBRES, PERSONA.APELLIDOS 
                FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 1 AND  TITID = " . $contrato->TITID . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $strQueryPagos = $strQueryPagos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }
            
            $qpagos = $this->db->query($strQueryPagos);
            
            // Para cada pago que se debería haber realizado se genera un elemento del array
            
            $acumulado_en_pagos = 0;
            for($idx = 0; $idx < $qpagos->num_rows(); $idx++ )
            {
                $pagoind = $qpagos->row($idx);
                $valorpagado = $pagoind->VALOR;
                $acumulado_en_pagos = $acumulado_en_pagos + $valorpagado;
            }
            
             // Se calcula el numero de pagos que se deberian haber realizado            
            $cantidadpagos = ($contrato->DIFERENCIA + 1);
            
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
                        
            $acumulado_en_mora = 0;
            $acumulado_por_pagar = 0;
            $acumulado_total = 0;
            //$acumulado_vencido = 0;
            for ($i = 0; $i < $cantidadpagos; $i++) {
                $finperiodo = clone $factual;
                $limitepago = clone $factual;
                $finperiodo->add(new DateInterval($invervalo));
                $limitepago->add(new DateInterval('P9D'));
                $valor_pagar = $this->ObtenerValorPago($contratoId, $factual, $contrato->NUMBENEFICIARIOS);
                $valor_pagar_orig = $valor_pagar;
                $acumulado_total = $acumulado_total + $valor_pagar_orig;
                if($valor_pagar > $acumulado_en_pagos)
                {
                    $valor_pagar = $valor_pagar - $acumulado_en_pagos;
                    $acumulado_en_pagos = 0;
                    if($ahora > $limitepago)
                    {
                        $acumulado_en_mora = $acumulado_en_mora + $valor_pagar;
                    }
                    else
                    {
                        $acumulado_por_pagar = $acumulado_por_pagar + $valor_pagar;
                    }
                }
                else
                {
                    $acumulado_en_pagos = $acumulado_en_pagos -  $valor_pagar;
                }
                
                $factual->add(new DateInterval($invervalo));
            }
            
            $sqlOtrosConceptos = "
                SELECT  SUM(VALOR) as TOTAL  FROM otroscargos 
                WHERE TITID = " . $contrato->TITID . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $sqlOtrosConceptos = $sqlOtrosConceptos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }            
            $qtotalOtros = $this->db->query($sqlOtrosConceptos);
            
            if ($qtotalOtros->num_rows() > 0) {
                $rtotalOtros = $qtotalOtros->row(0);
                $data['totalotros'] = $rtotalOtros->TOTAL;
            }
            else
            {
                $data['totalotros'] = 0;
            }
            
            $sqlOtrosPagos = "
                SELECT  SUM(VALOR) TOTAL  FROM PAGO 
                INNER JOIN DOCUMENTO ON DOCUMENTO.ID = PAGO.RECID
                INNER JOIN PERSONA ON PERSONA.ID = DOCUMENTO.EMPID
                WHERE PAGO.TIPOCONCEPTO = 3 AND  TITID = " . $contrato->TITID . " AND FECHA >= '" . $contrato->FECHAINICIO . "' "; 
            if($contrato->ESTADO == 0)
            {
                $sqlOtrosPagos = $sqlOtrosPagos . " AND FECHA <= '" . $contrato->FECHAFINAL ."' ";                
            }
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
        }
        
        if(($acumulado_en_mora) > 0)
        {            
            if($condetalle)
            {                    
                $estado = "EN MORA $ " . number_format(($acumulado_en_mora), 2, ',', '.') ;   
            }
            else
            {
                $estado = "EN MORA";        
            }    
        }
        else
        {
            $estado = "OK";    
        }
       
        return $estado;
    }
    
    /// LOGICA DUPLICADA EN CONTROLADOR DE CONSULTOR
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
            $valor_total = $numbeneficiarios * $valnormal->COSTOBENEFICIARIO;
        }
        return $valor_total;
    }
        
}