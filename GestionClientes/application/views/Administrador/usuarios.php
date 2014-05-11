<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    <div class="flexigrid" style='width: 100%;'>
        <div class="tDiv">
                <a href='http://localhost/GestionClientes/auth/register' title='Añadir Usuarios' class='add-anchor add_button'>
                    <div class="fbutton">
                        <div>
                            <span class="add">Añadir Usuarios</span>
                        </div>
                    </div>
                </a>
        </div>
    </div>
    <?php
    echo $output;
    ?>
</div>
