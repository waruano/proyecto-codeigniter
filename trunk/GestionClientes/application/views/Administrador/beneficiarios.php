<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <h3>Beneficiarios</h3>
    <div  style="padding-left: 30px;">
        <table>
            <tr><th>Titular:</th><td style="font-size: 20px; font-weight: bold;" ><?php echo $titularFullName ?>    </td></tr>
            <tr><th>Identificación:</th><td><?php echo $titularIdentificacion ?></td></tr>
            <tr><th>No Contrato:</th><td><?php echo $titularContrato ?></td></tr>
        </table>
    </div>
    <br/>
    
    <?php
    echo $output;
    //zona pendiente por confirmar
     
        if(isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard'] == true) { 
    
        if(isset($_SESSION['to_contratos']) && $_SESSION['to_contratos']){
                unset($_SESSION['to_contratos']); ?>
            ?>
                <input type="button" onclick="window.location='<?php echo base_url()."contratos/index/add"; ?>'" value="Terminar"/>        
            <?php }else {?>
            <input type="button" onclick="window.location='<?php echo base_url()."contratos/titulares"; ?>'" value="Terminar"/>        
        <?php }
        } ?>
</div>
