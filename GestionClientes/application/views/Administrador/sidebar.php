<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav nav-justified nav-pills">
    <li <?php if(isset($selectedoption) && $selectedoption == 0){ echo 'class="active"'; } ?> ><a href="<?php echo base_url()?>">Inicio</a></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 1){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/usuarios','Usuarios'); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 2){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/planes','Planes    '); ?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 3){ echo 'class="active"'; } ?> ><?php echo anchor('contratos/titulares','Titulares');?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 4){ echo 'class="active"'; } ?> ><?php echo anchor('consultor/consultar','Consulta Contratos');?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 6){ echo 'class="active"'; } ?> ><?php echo anchor('digitador/documentos','Documentos');?></li>
    <li <?php if(isset($selectedoption) && $selectedoption == 7){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/empleados','Empleados');?></li>
        <li <?php if(isset($selectedoption) && $selectedoption == 8){ echo 'class="active"'; } ?> ><?php echo anchor('digitador/pagos','Pagos');?></li>
    <!--<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
    <li <?php if(isset($selectedoption) && $selectedoption == 7){ echo 'class="active"'; } ?> ><?php echo anchor('administrador/empleados','Empleados');?></li>
        <li <?php if(isset($selectedoption) && $selectedoption == 8){ echo 'class="active"'; } ?> ><?php echo anchor('digitador/pagos','Pagos');?>   </li>              
          </ul>
        </li>-->
</ul>
    </div>