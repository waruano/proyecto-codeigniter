<style>
    .delta {font-size: 20px; font-weight: bold; padding-left: 30px;}
</style>
<div>
    <div class="titlerow">Información detallada</div>


</div>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    Información del titular
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <table class="table table-condensed">
                    <TR>
                        <td>Nombre completo:</td>
                        <td colspan="3"><?php echo $titular->NOMBRES . ' ' . $titular->APELLIDOS ?></td>
                    </TR>
                    <tr>
                        <td>Tipo de documento:</td>
                        <td><?php 
                        $valor = array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera', NULL => '');
                        echo $valor[$titular->TIPODOC] ?></td>
                        <td>Número de documento:</td>
                        <td><?php echo $titular->NODOCUMENTO ?></td>
                    </tr>
                    <tr>                
                        <td>Género:</td>
                        <td><?php 
                        $valor = array(1 => 'Masculino', 2 => 'Femenino', NULL => '');
                        echo $valor[$titular->GENERO] ?></td>
                        <td>Fecha de nacimiento:</td>
                        <td><?php echo $titular->FECHANACIMIENTO ?></td>
                    </tr>
                    <tr>                
                        <td>Dirección domicilio:</td>
                        <td colspan="3"><?php echo $titular->DOMIDIRECCION . ', Barrio ' . $titular->DOMIBARRIO . ', ' . $titular->DOMIMUNICIPIO . ', ' . $titular->DOMIDEPTO ?></td>
                    </tr>
                    <tr>                
                        <td>Dir correspondencia / cobro:</td>
                        <td colspan="3"><?php echo $titular->COBRODIRECCION . ', Barrio ' . $titular->COBROBARRIO . ', ' . $titular->COBROMUNICIPIO . ', ' . $titular->COBRODEPTO ?></td>
                    </tr>
                    <tr>                
                        <td>Tel domicilio:</td>
                        <td><?php echo $titular->TELDOMICILIO ?></td>
                        <td>Tel Oficina:</td>
                        <td><?php echo $titular->TELOFICINA ?></td>
                    </tr>
                    <tr>                
                        <td>Tel móvil:</td>
                        <td><?php echo $titular->TELMOVIL ?></td>                
                        <td>Correo electrónico:</td>
                        <td><?php echo $titular->EMAIL ?></td>
                    </tr>
                    <TR>                
                        <td>Número de hijos: </td>
                        <td><?php echo $titular->NOHIJOS ?></td>
                        <td>Personas a cargo (no hijos): </td>
                        <td><?php echo $titular->NODEPENDIENTES ?></td>
                    </TR>
                    <TR>                
                        <td>Estrato domicilio: </td>
                        <td><?php echo $titular->ESTRATO ?></td>
                        <td>Estado civil: </td>
                        <td><?php 
                        $valor = array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo', NULL => '');
                        echo $valor[$titular->ESTADOCIVIL] ?></td>
                    </TR>
                    <TR>                
                        <td>Ocupación: </td>
                        <td><?php 
                        $valor = array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado', NULL => '');
                        echo $valor[$titular->OCUPACION] ?></td>
                        <td>EPS: </td>
                        <td><?php echo $titular->EPS ?></td>
                    </TR>
                    <TR>                
                        <td>Es beneficiario: </td>
                        <td><?php
                        $valor = array(0 => 'No', 1 => 'Si', NULL => '');
                        echo $valor[$titular->BENEFICIARIO] ?></td>
                    </TR>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    Beneficiarios
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                if ($tienebeneficiarios) {
                    $iCounter = 1;
                    foreach ($beneficiarios as $benefic) {
                        ?>
                        <table class="table table-condensed">
                            <tr><th colspan ="4">Beneficiario Número <?php echo $iCounter;
                $iCounter = $iCounter + 1; ?> </th></tr>
                            <TR>
                                <td>Nombre completo:</td>
                                <td  colspan="3"><?php echo $benefic->NOMBRES . ' ' . $benefic->APELLIDOS ?></td>
                            </TR>
                            <tr>
                                <td>Tipo de documento:</td>
                                <td><?php 
                                $valor = array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera',4 => 'Registro Civil', NULL => '');
                                echo $valor[$benefic->TIPODOC] ?></td>
                                <td>Número de documento:</td>
                                <td><?php echo $benefic->NODOCUMENTO ?></td>
                            </tr>
                            <tr>                
                                <td>Género: </td>
                                <td><?php 
                                    $valor = array(1 => 'Masculino', 2 => 'Femenino', NULL => '');
                                    echo $valor[$benefic->GENERO] ?></td>
                                <td>Fecha de nacimiento:</td>
                                <td><?php echo $benefic->FECHANACIMIENTO ?></td>
                            </tr>
                            <tr>                
                                <td>Dirección domicilio:</td>
                                <td colspan="3"><?php echo $benefic->DIRECCION . ', Barrio ' . $benefic->BARRIO . ', ' . $benefic->MUNICIPIO . ', ' . $benefic->DEPTO ?></td>
                            </tr>           
                            <tr>                
                                <td>Tel domicilio:</td>
                                <td><?php echo $benefic->TELDOMICILIO ?></td>
                                <td>Tel Oficina:</td>
                                <td><?php echo $benefic->TELOFICINA ?></td>
                            </tr>
                            <tr>                
                                <td>Tel móvil:</td>
                                <td><?php echo $benefic->TELMOVIL ?></td>                
                                <td>Correo electrónico:</td>
                                <td><?php echo $benefic->EMAIL ?></td>
                            </tr>
                            <TR>                
                                <td>Número de hijos: </td>
                                <td><?php echo $benefic->NOHIJOS ?></td>
                                <td>Estrato domicilio: </td>
                                <td><?php echo $benefic->ESTRATODOMICILIO ?></td>
                            </TR>
                            <TR>                                
                                <td>Estado civil: </td>
                                <td><?php 
                                $valor = array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo', NULL => '');
                                echo $valor[$benefic->ESTADOCIVIL] ?></td>
                            </TR>
                            <TR>                
                                <td>Ocupación: </td>
                                <td><?php 
                                $valor = array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado', NULL => '');
                                echo $valor[$benefic->OCUPACION] ?></td>
                                <td>EPS: </td>
                                <td><?php echo $benefic->EPS ?></td>
                            </TR>
                        </table>

                    <?php
                    }
                }
                else
                    echo 'No existen beneficiarios registrados para el titular';
                ?>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    Contactos
                </a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
<?php
if ($tienecontactos) {
    $iCounter = 1;
    foreach ($contactos as $contacto) {
        ?>
                        <table class="table table-condensed">
                            <tr><th colspan ="4">Contacto Número <?php echo $iCounter;
        $iCounter = $iCounter + 1; ?> </th></tr>
                            <TR>
                                <td>Nombre completo:</td>
                                <td ><?php echo $contacto->NOMBRECOMPLETO ?></td>
                                <td>Parentesco:</td>
                                <td><?php echo $contacto->PARENTESCO ?></td>
                            </TR>                    
                            <tr>                
                                <td>Tel domicilio:</td>
                                <td><?php echo $contacto->TELDOMICILIO ?></td>
                                <td>Tel móvil:</td>
                                <td><?php echo $contacto->TELMOVIL ?></td>
                            </tr>           
                        </table>

    <?php
    }
}
else
    echo 'No existen contactos registrados para el titular';
?>

            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                    Contrato y Pagos
                </a>
            </h4>
        </div>
        <div id="collapseFour" class="panel-collapse collapse">
            <div class="panel-body">
<?php if (!$tienecontrato) { ?>
                    No se encuentra contrato activo para el titular
<?php
} else {
    ?>
                    <table class="table table-condensed">
                        <tr><th colspan="4">Información del contrato actual</th></tr>
                        <tr>
                            <td>Número de contrato: </td><td><?php echo $contrato->NUMERO ?></td>  
                            <td>Plan: </td><td><?php echo $contrato->NOMBRE . ' (' . $contrato->BeneficPlan . ' beneficiarios)' ?></td>  
   
                        </tr>
                        <tr>
                            <td>Periodicidad: </td><td><?php 
                                $valor = array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual');
                            echo $valor[$contrato->PERIODICIDAD] ?></td>  
                            <td>Nombre convenio: </td><td><?php echo $contrato->NOMBRECONVENIO ?></td>  

                        </tr>
                        <tr>
                            <td>Fecha de inicio de contrato</td>
                            <td>
                            <?php echo $contrato->FECHAINICIO ?>
                            </td>
                            
                            <td>Tarifa actual por beneficiario: </td><td> $
                                <?php
                                if ($contrato->PERIODICIDAD == 1) {
                                    echo number_format($contrato->COSTOPAGOMES, 2, ',', '.');
                                } else if ($contrato->PERIODICIDAD == 2) {
                                    echo number_format($contrato->COSTOPAGOSEMESTRE, 2, ',', '.');
                                }
                                else
                                    echo number_format($contrato->COSTOPAGOANIO, 2, ',', '.');
                                ?></td>  
                        </tr>

                        <tr>
                            <td>Costo de afiliación: </td>
                            <td>$ <?php echo  number_format($costoafiliacion, 2, ',', '.') ?></td>
                            <td>Total pagado por afiliacion: </td>
                            <td <?php if($sumapagoafiliacion < $costoafiliacion) { echo "style='color:red;'"; }?> >$ <?php echo number_format($sumapagoafiliacion, 2, ',', '.') ?></td>
                        </tr>
                        
                        <tr>
                            <td>Pago <?php 
                            $valor = array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual');
                            echo $valor[$contrato->PERIODICIDAD] ?> (tarifa actual): </td>

                            <td>$ <?php
                                    if ($contrato->PERIODICIDAD == 1) {
                                        echo number_format($contrato->COSTOPAGOMES * $contrato->NUMBENEFICIARIOS, 2, ',', '.');
                                    } else if ($contrato->PERIODICIDAD == 2) {
                                        echo number_format($contrato->COSTOPAGOSEMESTRE * $contrato->NUMBENEFICIARIOS, 2, ',', '.');
                                    }
                                    else
                                        echo number_format($contrato->COSTOPAGOANIO * $contrato->NUMBENEFICIARIOS, 2, ',', '.');
                               
                                ?>
                                
                            </td>   
                            <td>Beneficiarios registrados: </td>
                            <td><?php echo $contrato->NUMBENEFICIARIOS ?></td>
                        </tr><tr>  
                         
                            <td>Estado:</td>
                            <td <?php if ($estadocontrato != "OK") echo "style='color: red;'";    else echo "style='color: green;'" ?> >
                                   <?php echo $estadocontrato ?>
                            <br/><br/>
                            </td>
                            
                             <?php if ($proximopago <> "") { ?>   
                                 <td>Próximo pago:</td>
                                 <td><?php echo $proximopago ?>:</td>
                                 <?php }  ?> 
                            

                        </tr>
                        <tr><td colspan="4">                          
                                <table width="100%"  class="table table-condensed">                         
                                    <TR>
                                    <tr><th colspan="9">Historial de pagos <?php 
                                            $valor = array(1 => 'Mensuales', 2 => 'Semestrales', 3 => 'Anuales');
                                            echo $valor[$contrato->PERIODICIDAD] ?> </th></tr>                  
                                    <tr>
                                        <th align="center">Pagos</th>
                                        <td style="width:20px;"></td>
                                        <th align="center">Periodo</th>                                
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%">
                                                <tr><th>Fecha pago</th><th>Recibo No</th><th>Asesor</th><th>Valor</th></tr>
    <?php foreach ($lstpagos as $pago) { ?>
                                                    <tr>
                                                        <td><?php echo $pago['fechapago'] ?> </td>
                                                        <td><?php echo $pago['numero'] ?> </td>
                                                        <td><?php echo $pago['asesor'] ?> </td>
                                                        <td>$ <?php echo number_format($pago['valor'], 2, ',', '.') ?></td>
                                                    </tr>
    <?php } ?>
                                                <tr><td></td><td></td><th>Total pagado:</th><td>$ <?php echo number_format($acumuladopagos, 2, ',', '.'); ?></td></tr>
                                            </table>

                                        </td>
                                        <td></td>
                                        <td>
                                            <table  width="100%">
                                                <tr><th>Inicio</th><th>Fin</th><th>Límite pago</th><th>Valor pago</th><th>Estado</th></TR>
    <?php foreach ($lstperiodos as $periodo) { ?>
                                                    <tr>
                                                        <td><?php echo $periodo['inicioperiodo'] ?></td>
                                                        <td><?php echo $periodo['finperiodo'] ?> </td>
                                                        <td><?php echo $periodo['limitepago'] ?> </td>   
                                                        <td>$ <?php echo number_format($periodo['valorapagar'], 2, ',', '.') ?></td>
                                                        <td><?php echo $periodo["estado"] ?></td>
                                                    </tr>
    <?php } ?>
                                                    
                                                    <tr><td></td><td></td><th>Costos totales: </th>
                                                        <td>$ <?php echo number_format($acumulado_total, 2, ',', '.') ?></td></tr>
                                            </table>
                                        </td>
                                    </tr>                                               
                                </table>
                            </td></tr>    
                        
                        <tr>
                            <td colspan="4" stule="padding-top: 20px;">
                                <table width="100%"  class="table table-condensed">                         
                                    <TR>
                                    <tr><th colspan="9">Historial de pagos y deudas por otros conceptos</th></tr>                  
                                    <tr>
                                        <th align="center">Cargos adicionales</th>                                        
                                        <td style="width:20px;"></td>
                                        <th align="center">Pagos adicionales</th>                                
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Descripción</th>
                                                    <th>Valor</th>
                                                </tr>
    <?php 
    if($otrosconceptos != NULL){
        foreach ($otrosconceptos as $itemconcepto) { ?>
                                                    <tr>
                                                        <td><?php echo $itemconcepto->FECHA; ?></td>
                                                        <td><?php echo $itemconcepto->DESCRIPCION; ?></td>
                                                        <td>
                                                            $ <?php echo number_format($itemconcepto->VALOR, 2, ',', '.') ?>
                                                            </td>
                                                    </tr>
    <?php } 
    }?>
                                                    <tr><td></td><th>Total adicionales: </th>
                                                        <td>$ <?php echo number_format($totalotros, 2, ',', '.') ?></td></tr>
                                                    
                                            </table>                                            
                                        </td>
                                        <td></td>
                                        <td>
                                            <table width="100%">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Recibo de Caja</th>
                                                    <th>Valor</th>
                                                </tr>
<?php 
    if($otrospagos != NULL){
        foreach ($otrospagos as $itempagoconcepto) { ?>
                                                    <tr>
                                                        <td><?php echo $itempagoconcepto->FECHA; ?></td>
                                                        <td><?php echo $itempagoconcepto->NUMERO; ?></td>
                                                        <td>
                                                            $ <?php echo number_format($itempagoconcepto->VALOR, 2, ',', '.') ?>
                                                            </td>
                                                    </tr>
    <?php } 
    }?>
                                                    <tr><td></td><th>Total pagos adicionales: </th>
                                                        <td>$ <?php echo number_format($totalotrospagos, 2, ',', '.') ?></td></tr>
                                            </table>
                                            
                                        </td>                               
                                    </tr>
                                    <tr>
                                </table>
                            </td>
                        </tr>
                    </table>
<?php } ?>


            </div>
        </div>
    </div>
</div>