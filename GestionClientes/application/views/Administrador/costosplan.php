<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>    
    <div>
        <h3> Tarifas para el Plan <strong>"<?php echo $planFullName ?>"</strong> </h3>
    </div>   
    <?php
    echo $output;
    ?>
</div>
