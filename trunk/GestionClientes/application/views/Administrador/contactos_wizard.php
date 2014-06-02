<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <h3>Contactos</h3>
    <div class="row">
        <div class="col-md-6">
            <table>
                <thead>
                <th colspan="2"><h4>Informacion del Plan</h4></th>
                </thead>
                <tr>
                    <th>Nombre:</th><td><?php echo $plan_nombre ?>    </td>
                </tr>
                <tr>
                    <th>Numero de Beneficiarios:</th><td><?php echo $plan_beneficiarios ?></td>
                </tr>
                <tr>            
                    <th>Nombre del Convenio:</th><td><?php echo $plan_convenio ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table>
                <thead>
                <th colspan="2"><h4>Informacion del Contrato</h4><th>
                    </thead>
                <tr>
                    <th>Tipo:</th><td><?php echo $contrato_tipo ?>    </td>
                </tr>
                <tr>
                    <th>Periodicidad:</th><td><?php echo $contrato_periodicidad ?></td>
                </tr>
                <tr>            
                    <th>Fecha de Inicio:</th><td><?php echo $contrato_fechaInicio ?></td>
                </tr>
            </table>
        </div>
    </div>
    </br>
    <div  class="row" style="display: inline-block">
        <div style="float:left; width: 50%; padding-left: 1.5%;">
            <table>
                <thead>
                <th colspan="2"><h4>Informacion del Titular</h4><th>
                </thead>
                <tr><th>Titular:</th><td><?php echo $titularFullName ?>    </td></tr>
                <tr><th>Identificación:</th><td><?php echo $titularIdentificacion ?></td></tr>
                <tr><th>No Contrato:</th><td><?php echo $titularContrato ?></td></tr>
            </table>
        </div>
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
        <input type="button" class="btn btn-large" onclick="window.location = '<?php echo base_url() . "home/remap/administrador/planes"; ?>'" value="Terminar "/>  
          <?php
        }else{ ?>
             <input type="button" class="btn btn-large"  onclick="window.location='<?php echo base_url()."contratos/titulares"; ?>'" value="Regresar a Titulares"/>  
        <?php }?>
</div>
