<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <div class="titlerow">Registro de Contactos</div>
    <div class="row" style="margin-bottom: 5px">
        <div class="col-md-4" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Plan</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 10px;">Nombre: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $plan_nombre ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Numero de Beneficiarios: </td><td style="padding-left: 20px;"><?php echo $plan_beneficiarios ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 10px;">Nombre del Convenio: </td><td style="padding-left: 20px; padding-bottom: 10px;"><?php echo $plan_convenio ?></td>
                </tr>                
                </tbody>
            </table>
        </div>
        <div class="col-md-4" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Contrato</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 10px;">Tipo: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $contrato_tipo ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Periodicidad: </td><td style="padding-left: 20px;"><?php echo $contrato_periodicidad ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 10px;">Fecha de Inicio: </td><td style="padding-left: 20px; padding-bottom: 10px;"><?php echo $contrato_fechaInicio ?></td>
                </tr>                
                </tbody>
            </table>
        </div>
        <div class="col-md-4" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Titular</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 10px;">Titular: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $titularFullName ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Identificación: </td><td style="padding-left: 20px;"><?php echo $titularIdentificacion ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 10px;">No Contrato: </td><td style="padding-left: 20px; padding-bottom: 10px;"><?php echo $titularContrato ?></td>
                </tr>                
                </tbody>
            </table>
            
            
        </div>
    </div>
    </br>
    <div  class="row" style="display: inline-block">        
        <div style="float: right; width: 50%;">
            <?php $this->load->view('Administrador/sidebar_add_titular');?>
        </div>
    </div>
    <br/>
    <br/>

    <?php
    echo $output;
    ?>

    <?php if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard'] == true) { ?>
        <input type="button" class="btn btn-large" onclick="window.location = '<?php echo base_url() . "index.php/home/remap/administrador/planes"; ?>'" value="Terminar "/>  
          <?php
        }else{ ?>
             <input type="button" class="btn btn-large"  onclick="window.location='<?php echo base_url()."index.php/contratos/titulares"; ?>'" value="Regresar a Titulares"/>  
        <?php }?>
</div>
