<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <h3>Contratos</h3>
    <!--Aqui Empieza el Acordeon-->
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
    </div>
    </br>
    <!-- Aqui termina el Acordeon-->
    <?php
    echo $output;
    if (!isset($_SESSION['_aux_wizard']) || !$_SESSION['_aux_wizard'] == true) {
        ?>
        <input type="button" class="btn btn-large"  onclick="window.location = '<?php echo base_url() . "contratos/titulares"; ?>'" value="Regresar a Titulares"/>  
    <?php } ?>
</div>
