<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav nav-justified nav-pills">
        <li <?php
        if (isset($step_wizard) && $step_wizard == 0) {
            echo 'class="active"';
        }
        ?> ><a href="<?php echo base_url() . 'administrador/contactos' ?>">Contactos</a></li>
        <li <?php
        if (isset($step_wizard) && $step_wizard == 1) {
            echo 'class="active"';
        }
        ?> ><a href="<?php echo base_url() . 'contratos/beneficiarios' ?>">Beneficiarios</a></li>
        <li <?php
        if (isset($step_wizard) && $step_wizard == 2) {
            echo 'class="active"';
        }
        ?> ><a href="<?php echo base_url() . 'contratos' ?>">Contratos</a></li>

    </ul>
</div>