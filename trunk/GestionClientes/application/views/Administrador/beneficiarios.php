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
    ?>
    <input type="button" onclick="window.location='<?php echo base_url()."/contratos"; ?>'" value="Terminar"/>        
</div>
