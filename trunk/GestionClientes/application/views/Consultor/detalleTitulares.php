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
                <td colspan="3"><?php echo  $titular->NOMBRES . ' ' . $titular->APELLIDOS ?></td>
            </TR>
            <tr>
                <td>Tipo de documento:</td>
                <td><?php echo array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera', NULL => '')[$titular->TIPODOC] ?></td>
                <td>Número de documento:</td>
                <td><?php echo $titular->NODOCUMENTO ?></td>
            </tr>
            <tr>                
                <td>Género:</td>
                <td><?php echo array(1 => 'Masculino', 2 => 'Femenino', NULL => '')[$titular->GENERO] ?></td>
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
                <td><?php echo array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo', NULL => '')[$titular->ESTADOCIVIL] ?></td>
            </TR>
            <TR>                
                <td>Ocupación: </td>
                <td><?php echo array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado', NULL => '')[$titular->OCUPACION] ?></td>
                <td>EPS: </td>
                <td><?php echo $titular->EPS ?></td>
            </TR>
            <TR>                
                <td>Es beneficiario: </td>
                <td><?php echo array(0 => 'No', 1 => 'Si', NULL => '')[$titular->BENEFICIARIO] ?></td>
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
        
        <?php if($tienebeneficiarios) { 
        $iCounter = 1;
        foreach( $beneficiarios as $benefic)
        { ?>
        <table class="table table-condensed">
            <tr><th colspan ="4">Beneficiario Número <?php echo $iCounter; $iCounter = $iCounter + 1; ?> </th></tr>
            <TR>
                <td>Nombre completo:</td>
                <td  colspan="3"><?php echo  $benefic->NOMBRES . ' ' . $benefic->APELLIDOS ?></td>
            </TR>
            <tr>
                <td>Tipo de documento:</td>
                <td><?php echo array(1 => 'Cédula de Ciudadanía', 2 => 'Tarjeta de Identidad', 3 => 'Cedula Extrangera', NULL => '')[$benefic->TIPODOC] ?></td>
                <td>Número de documento:</td>
                <td><?php echo $benefic->NODOCUMENTO ?></td>
            </tr>
            <tr>                
                <td>Género: </td>
                <td><?php echo array(1 => 'Masculino', 2 => 'Femenino', NULL => '')[$benefic->GENERO] ?></td>
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
                <td><?php echo array(1 => 'Soltero', 2 => 'Casado', 3 => 'Divorciado', 4 => 'Unión Libre', 5 => 'Viudo', NULL => '')[$benefic->ESTADOCIVIL] ?></td>
            </TR>
            <TR>                
                <td>Ocupación: </td>
                <td><?php echo array(1 => 'Empleado', 2 => 'Independiente', 3 => 'Jubilado', 4 => 'Ama de Casa', 5 => 'Estudiante', 6 => 'Desempleado', NULL => '')[$benefic->OCUPACION] ?></td>
                <td>EPS: </td>
                <td><?php echo $benefic->EPS ?></td>
            </TR>
        </table>
        
        <?php }
        }
        else
            echo 'No existen beneficiarios registrados para el titular'; ?>
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
        <?php if($tienecontactos) { 
        $iCounter = 1;
        foreach( $contactos as $contacto)
        { ?>
        <table class="table table-condensed">
            <tr><th colspan ="4">Contacto Número <?php echo $iCounter; $iCounter = $iCounter + 1; ?> </th></tr>
            <TR>
                <td>Nombre completo:</td>
                <td ><?php echo  $contacto->NOMBRECOMPLETO ?></td>
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
        
        <?php } 
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
          <?php if (! $tienecontrato){ ?>
            No se encuentra contrato activo para el titular
          <?php } 
          else
          { ?>
              <table class="table table-condensed">
                  <tr><th colspan="4">Información del contrato actual</th></tr>
                  <tr>
                      <td>Número de contrato: </td><td><?php echo $contrato->NUMERO ?></td>  
                      <td>Plan: </td><td><?php echo $contrato->NOMBRE . ' (' . $contrato->NUMBENEFICIARIOS . ' beneficiarios)' ?></td>  
                  
                  </tr>
                  <tr>
                      <td>Periodicidad: </td><td><?php echo array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual')[$contrato->PERIODICIDAD] ?></td>  
                      <td>Nombre convenio: </td><td><?php echo $contrato->NOMBRECONVENIO ?></td>  
                  
                  </tr>
                  <tr>
                      <td>Tarifa por beneficiario: </td><td> $
                          <?php                           
                            if ($contrato->PERIODICIDAD == 1){
                                echo number_format($contrato->COSTOPAGOMES, 2, ',' , '.');
                            }
                            else if($contrato->PERIODICIDAD == 2)
                            {
                                echo number_format($contrato->COSTOPAGOSEMESTRE, 2, ',' , '.');
                            }
                            else
                                echo number_format($contrato->COSTOPAGOANIO, 2, ',' , '.');
                           ?></td>  
                      <td>Tarifa por beneficiario adicional: </td>
                      <td>$ 
                          <?php                           
                            if ($contrato->PERIODICIDAD == 1){
                                echo number_format($costoadicional->COSTOPAGOMES, 2, ',' , '.');
                            }
                            else if($contrato->PERIODICIDAD == 2)
                            {
                                echo number_format($costoadicional->COSTOPAGOSEMESTRE, 2, ',' , '.');
                            }
                            else
                                echo number_format($costoadicional->COSTOPAGOANIO, 2, ',' , '.');
                           ?>
                      </td>  
                  
                  </tr>
                         
                  <tr>
                      <td>Pago <?php echo array(1 => 'Mensual', 2 => 'Semestral', 3 => 'Anual')[$contrato->PERIODICIDAD] ?>: </td>
                      
                      <td>$ <?php 
                        if( $numerobeneficiarios <= $contrato->NUMBENEFICIARIOS)
                        {
                             if ($contrato->PERIODICIDAD == 1){
                                echo number_format($contrato->COSTOPAGOMES * $numerobeneficiarios, 2, ',' , '.');
                            }
                            else if($contrato->PERIODICIDAD == 2)
                            {
                                echo number_format($contrato->COSTOPAGOSEMESTRE * $numerobeneficiarios, 2, ',' , '.');
                            }
                            else
                                echo number_format($contrato->COSTOPAGOANIO * $numerobeneficiarios, 2, ',' , '.');
                        }
                        else
                        {
                            $numadicionales = $numerobeneficiarios - $contrato->NUMBENEFICIARIOS;
                            
                            if ($contrato->PERIODICIDAD == 1){
                                echo number_format(($contrato->COSTOPAGOMES * $contrato->NUMBENEFICIARIOS) + ($numadicionales * $costoadicional->COSTOPAGOMES), 2, ',' , '.');
                            }
                            else if($contrato->PERIODICIDAD == 2)
                            {
                                echo number_format(($contrato->COSTOPAGOSEMESTRE * $contrato->NUMBENEFICIARIOS) + ($numadicionales * $costoadicional->COSTOPAGOSEMESTRE), 2, ',' , '.');                             
                            }
                            else
                                echo number_format(($contrato->COSTOPAGOANIO * $contrato->NUMBENEFICIARIOS) + ($numadicionales * $costoadicional->COSTOPAGOANIO), 2, ',' , '.');           
                        }
                      ?>
                      <br/><br/>
                      </td>         
                      <td>Estado:</td>
                      <td
                          <?php if($estadocontrato != "OK") echo "style='color: red;'"; else echo "style='color: green;'" ?>
                          ><?php echo $estadocontrato ?></td>
                      
                  </tr>
                  
                  
                  <tr><td colspan="4">
                          
                          <table width="100%"  class="table table-condensed">                         
                  <TR>
                      <tr><th colspan="9">Historial de pagos</th></tr>                  
                  <tr>
                      <th colspan="3" align="center">Periodo</td><td style="width:20px;"></td>
                      <th colspan="5" align="center">Pagos</td>
                  </tr>
                  <tr>
                  <th>Inicio</th><th>Fin</th><th>Límite pago</th><td></td>
                      
                  <th>Valor</th><th>Fecha pago</th><th>Recibo No</th><th>Asesor</th>
                          </TR>
                  <?php 
                        foreach( $lstpagos as $pago)
                        { ?>
                  <tr>
                      <td><?php echo $pago['inicioperiodo'] ?></td>
                      <td><?php echo $pago['finperiodo'] ?> </td>                      
                      <td><?php echo $pago['limitepago'] ?> </td>
                      <td></td>                      
                      
                      <?php if($pago['valor'] == 0){?>
                      <td>- -</td>
                      <td style="color:red;" >No realizado</td>
                      <td>- -</td>
                      <td>- -</td>
                      <?php }
                      else { ?>
                      <td>$ <?php echo number_format($pago['valor'], 2, ',' , '.') ?></td>
                      <td><?php echo $pago['fechapago'] ?> </td>
                      <td><?php echo $pago['numero'] ?> </td>
                      <td><?php echo $pago['asesor'] ?> </td>
                      <?php } ?>
                      
                      
                      
                      
                  </tr>
                        <?php } ?>                      
                       </table>
                      </td></tr>    
             </table>
          <?php }?>
          
        
      </div>
    </div>
  </div>
</div>