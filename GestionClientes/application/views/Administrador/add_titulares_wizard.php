<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <h3>Titulares</h3>
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
    <?php
    echo $output;
    ?>
</div>
