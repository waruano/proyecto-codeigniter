<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <h3>Contratos</h3>
    <div  style="padding-left: 30px; display: inline-block">
        <div style="float:left; width: 50%;">
            <table>
                <tr><th>Titular:</th><td style="font-size: 20px; font-weight: bold;" ><?php echo $titularFullName ?>    </td></tr>
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
        if(isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard'] == true) { ?>
             <input type="button" class="btn btn-large"  onclick="window.location='<?php echo base_url()."contratos/titulares"; ?>'" value="Terminar"/>   
             <input type="button" class="btn btn-large"  onclick="window.location='<?php echo base_url()."contratos/beneficiarios"; ?>'" value="Regresar a Beneficiarios"/>  
        <?php
        }else{ ?>
             <input type="button" class="btn btn-large"  onclick="window.location='<?php echo base_url()."contratos/titulares"; ?>'" value="Regresar a Titulares"/>  
        <?php }?>
</div>
