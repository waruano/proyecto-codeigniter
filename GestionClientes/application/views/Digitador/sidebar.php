<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav nav-justified nav-pills">
        <li <?php if(isset($selectedoption) && $selectedoption == 0){ echo 'class="active"'; } ?> ><a href="<?php echo base_url()?>">Inicio</a></li>
        <li <?php if(isset($selectedoption) && $selectedoption == 2){ echo 'class="active"'; } ?> ><?php echo anchor('home/remap/administrador/planes','Planes    '); ?></li> 
        <li <?php if(isset($selectedoption) && $selectedoption == 3){ echo 'class="active"'; } ?> ><?php echo anchor('home/remap/contratos/titulares','Titulares');?></li>    
        <li <?php if(isset($selectedoption) && $selectedoption == 6){ echo 'class="active"'; } ?> ><?php echo anchor('home/remap/digitador/documentos','Documentos');?></li>
        
        <li <?php if(isset($selectedoption) && $selectedoption == 4){ echo 'class="active"'; } ?> ><?php echo anchor('home/remap/consultor/consultar','Consulta Contratos');?></li>
    </ul>
</div>