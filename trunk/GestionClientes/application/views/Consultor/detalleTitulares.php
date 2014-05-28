<style>
.delta {font-size: 20px; font-weight: bold; padding-left: 30px;}
</style>
<div>
    <h3>Información detallada</h3>
    <div  style="padding-left: 30px;">
        <table>
            <?php if($titular != NULL) { ?>
            <tr><th>Titular:</th><td class="delta" ><?php echo $titular->NOMBRES . ' ' . $titular->APELLIDOS; ?>    </td></tr>
            <tr><th>Identificación:</th><td  class="delta" ><?php echo $titular->NODOCUMENTO ?></td></tr>
            <tr><th>No Contrato:</th><td  class="delta"  ><?php echo $titular->NUMERO ?></td></tr>
            <?php } 
            else
            {
                ?>                    
                    <tr><td style="color:red; font-size: 20px; font-weight: bold;" >'Titular sin definir o no existe'</td></tr>
                    <?php
            }
            ?>
        </table>
    </div>
    <br/>
   
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
                <td><?php echo array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera', NULL => '')[$titular->TIPODOC] ?></td>
                <td>Número de documento:</td>
                <td><?php echo $titular->NODOCUMENTO ?></td>
            </tr>
            <tr>                
                <td>Género:</td>
                <td><?php echo array(0 => 'Masculino', 1 => 'Femenino', NULL => '')[$titular->GENERO] ?></td>
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
                <td><?php echo array(0 => 'Soltero', 1 => 'Casado', 2 => 'Divorciado', 3 => 'Unión Libre', 4 => 'Viudo', NULL => '')[$titular->ESTADOCIVIL] ?></td>
            </TR>
            <TR>                
                <td>Ocupación: </td>
                <td><?php echo array(0 => 'Empleado', 1 => 'Independiente', 2 => 'Jubilado', 3 => 'Ama de Casa', 4 => 'Estudiante', 5 => 'Desempleado', NULL => '')[$titular->OCUPACION] ?></td>
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
            <tr><th colspan ="4">Beneficiario Número <?php echo $iCounter; $iCounter = $iCounter + 1; ?>: </th></tr>
            <TR>
                <td>Nombre completo:</td>
                <td  colspan="3"><?php echo  $benefic->NOMBRES . ' ' . $benefic->APELLIDOS ?></td>
            </TR>
            <tr>
                <td>Tipo de documento:</td>
                <td><?php echo array(0 => 'Cédula de Ciudadanía', 1 => 'Tarjeta de Identidad', 2 => 'Cedula Extrangera', NULL => '')[$benefic->TIPODOC] ?></td>
                <td>Número de documento:</td>
                <td><?php echo $benefic->NODOCUMENTO ?></td>
            </tr>
            <tr>                
                <td>Género:</td>
                <td><?php echo array(0 => 'Masculino', 1 => 'Femenino', NULL => '')[$benefic->GENERO] ?></td>
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
                <td><?php echo array(0 => 'Soltero', 1 => 'Casado', 2 => 'Divorciado', 3 => 'Unión Libre', 4 => 'Viudo', NULL => '')[$benefic->ESTADOCIVIL] ?></td>
            </TR>
            <TR>                
                <td>Ocupación: </td>
                <td><?php echo array(0 => 'Empleado', 1 => 'Independiente', 2 => 'Jubilado', 3 => 'Ama de Casa', 4 => 'Estudiante', 5 => 'Desempleado', NULL => '')[$benefic->OCUPACION] ?></td>
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
            <tr><th colspan ="4">Contacto Número <?php echo $iCounter; $iCounter = $iCounter + 1; ?>: </th></tr>
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
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
          Contrato y Pagos
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
</div>