<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <div>
        <h4> Titular: <strong><?php echo $titularFullName ?>    </strong> </h4>
    </div>
    
    <?php
    echo $output;
    //zona pendiente por confirmar
    if(isset($_SESSION['to_contratos']) && $_SESSION['to_contratos']){
        unset($_SESSION['to_contratos']); ?>
    ?>
    <input type="button" onclick="window.location='<?php echo base_url()."contratos/index/add"; ?>'" value="Terminar"/>        
    <?php }else {?>
    <input type="button" onclick="window.location='<?php echo base_url()."contratos/titulares"; ?>'" value="Terminar"/>        
    <?php } ?>
</div>
