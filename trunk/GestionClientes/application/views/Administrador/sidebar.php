<ul class="nav nav-justified nav-pills">
    <li <?php if(isset($selectedoption) && $selectedoption == 0){ echo 'class="active"'; } ?> ><a href="<?php echo base_url()?>">Inicio</a></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 1){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/usuarios','Usuarios'); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 2){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/planes','Planes    '); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 3){ echo 'class="active"'; } ?> ><?php echo anchor('contratos/titulares','Titulares');?>
    <li <?php if(isset($selectedoption) && $selectedoption == 4){ echo 'class="active"'; } ?> ><?php echo anchor('consultor/consultar','Consulta Contratos');?>
    <li <?php if(isset($selectedoption) && $selectedoption == 5){ echo 'class="active"'; } ?> ><?php echo anchor('contratos','Contratos');?>
    <li <?php if(isset($selectedoption) && $selectedoption == 6){ echo 'class="active"'; } ?> ><?php echo anchor('digitador/documentos','Documentos');?>
    <li <?php if(isset($selectedoption) && $selectedoption == 7){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/empleados','Empleados');?>

</ul>