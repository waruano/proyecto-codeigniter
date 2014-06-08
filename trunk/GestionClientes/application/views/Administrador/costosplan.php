<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>        
    <div class="titlerow">Tarifas para el plan <strong>'<?php echo $planFullName ?>'</div>
    <?php
    echo $output;
    ?>
</div>
