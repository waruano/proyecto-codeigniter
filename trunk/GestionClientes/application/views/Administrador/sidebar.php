<ul class="nav nav-justified nav-pills">
    <li <?php if(isset($selectedoption) && $selectedoption == 0){ echo 'class="active"'; } ?> ><a href="<?php echo base_url()?>">Inicio</a></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 1){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/Usuarios','Usuarios'); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 2){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/planes','Planes    '); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 3){ echo 'class="active"'; } ?> ><?php echo anchor('contratos','Titulares');?>
</ul>