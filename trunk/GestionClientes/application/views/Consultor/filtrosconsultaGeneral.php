<?php
$identificacion = array(
	'name'	=> 'identificacion',
	'id'	=> 'identificacion',
	'value' => $validentificacion,
	'size' 	=> 30
); 
$numeroContrato = array(
	'name'	=> 'numeroContrato',
	'id'	=> 'numeroContrato',	
        'value' => $valnocontrato,
	'size'	=> 30,
);
$nombreTitular = array(
	'name'	=> 'nombreTitular',
	'id'	=> 'nombreTitular',
        'value' => $valtitular,
	'size' 	=> 30,
);

$telefono = array(
	'name'	=> 'telefono',
	'id'	=> 'telefono',
	'value' => $valtelefono,
	'size' 	=> 30
); 

$genero = array(
	'name'	=> 'genero',
	'id'	=> 'genero',
        'value' => $valgenero,
	'size' 	=> 30,
        'options' => array(
                  0 => '',
                  1  => 'Masculino',
                  2  => 'Femenino'
                )
);

$direccion = array(
	'name'	=> 'direccion',
	'id'	=> 'direccion',
	'value' => $valdireccion,
	'size' 	=> 30
); 

$correo = array(
	'name'	=> 'correo',
	'id'	=> 'correo',	
        'value' => $valcorreo,
	'size'	=> 30
);

$estrato = array(
	'name'	=> 'estrato',
	'id'	=> 'estrato',	
        'value' => $valestrato,
	'size'	=> 30,
        'options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6)
);
$eps = array(
	'name'	=> 'eps',
	'id'	=> 'eps',
        'value' => $valeps,
	'size' 	=> 30
);
/*
$afiliaciondesde = array(
	'name'	=> 'afiliaciondesde',
	'id'	=> 'afiliaciondesde',
	'value' => $valafiliaciondesde,
	'size' 	=> 30
); 
$afiliacionhasta = array(
	'name'	=> 'afiliacionhasta',
	'id'	=> 'afiliacionhasta',	
        'value' => $valafiliacionhasta,
	'size'	=> 30
); */
$plan = array(
	'name'	=> 'plan',
	'id'	=> 'plan',
        'value' => $valplan,
	'size' 	=> 30
);

$convenio = array(
	'name'	=> 'convenio',
	'id'	=> 'convenio',	
        'value' => $valconvenio,
	'size'	=> 30
);
$asesor = array(
	'name'	=> 'asesor',
	'id'	=> 'asesor',
        'value' => $valasesor,
	'size' 	=> 30
);

?>

<?php echo form_open('consultor/consultageneral') ?>

<div class="titlerow">Consulta General</div>

<div class="row" style="padding-top: 10px; " >
     <div class="col-md-1">   
         <?php echo form_label('Identificación', $identificacion['id']); ?> 
     </div>
         <div class="col-md-3">        
    <?php echo form_input($identificacion); ?>          
         <div style="color: red;">
            <?php echo form_error($identificacion['name']); ?><?php echo isset($errors[$identificacion['name']])?$errors[$identificacion['name']]:''; ?>
        </div>
     </div>
     <div class="col-md-1">         
         <?php echo form_label('No. Contrato', $numeroContrato['id']); ?> 
        </div>
         <div class="col-md-3">
             <?php echo form_input($numeroContrato); ?>
        <div style="color: red;"><?php echo form_error($numeroContrato['name']); ?><?php echo isset($errors[$numeroContrato['name']])?$errors[$numeroContrato['name']]:''; ?>    </div>
     </div>
     <div class="col-md-1">         
         <?php echo form_label('Nombre Titular', $nombreTitular['id']); ?> 
</div>
         <div class="col-md-3">       
 <?php echo form_input($nombreTitular); ?>
        <div style="color: red;"><?php echo form_error($nombreTitular['name']); ?><?php echo isset($errors[$nombreTitular['name']])?$errors[$nombreTitular['name']]:''; ?>        </div>
     </div>     
 </div>

<div class="row"  >
    <div class="col-md-1">         
         <?php echo form_label('Género', $genero['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_dropdown($genero['name'], $genero['options'], $genero['value'], 'style="width:215px; height: 26px; "');         
        ?>        
        <div style="color: red;">
            <?php echo form_error($genero['name']); ?><?php echo isset($errors[$genero['name']])?$errors[$genero['name']]:''; ?>        </div>
    </div>       
    <div class="col-md-1">         
         <?php echo form_label('Dirección', $direccion['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_input($direccion); ?>
        <div style="color: red;"><?php echo form_error($direccion['name']); ?><?php echo isset($errors[$direccion['name']])?$errors[$direccion['name']]:''; ?>        </div>
    </div>   
    <div class="col-md-1">         
         <?php echo form_label('Estrato', $estrato['id']); ?> 
        </div>
         <div class="col-md-3"> 
             
        <?php 
        echo form_dropdown($estrato['name'], $estrato['options'], $estrato['value'], 'style="width:215px; height: 26px; "');                 
        ?>
        <div style="color: red;"><?php echo form_error($estrato['name']); ?><?php echo isset($errors[$estrato['name']])?$errors[$estrato['name']]:''; ?>        </div>
    </div>   

</div>

<div class="row" style="padding-top: 5px;" >
    <div class="col-md-1">         
         <?php echo form_label('Eps', $eps['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_input($eps); ?>
        <div style="color: red;"><?php echo form_error($eps['name']); ?><?php echo isset($errors[$eps['name']])?$errors[$eps['name']]:''; ?>        </div>
    </div>   
    <div class="col-md-1">         
         <?php echo form_label('Plan', $plan['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_input($plan); ?>
        <div style="color: red;"><?php echo form_error($plan['name']); ?><?php echo isset($errors[$plan['name']])?$errors[$plan['name']]:''; ?>        </div>
    </div>   
    <div class="col-md-1">         
         <?php echo form_label('Convenio', $convenio['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_input($convenio); ?>
        <div style="color: red;"><?php echo form_error($convenio['name']); ?><?php echo isset($errors[$convenio['name']])?$errors[$convenio['name']]:''; ?>        </div>
    </div>
</div>

<div class="row" style="padding-top: 5px; padding-bottom: 5px;" >
        <div class="col-md-1">         
         <?php echo form_label('Asesor', $asesor['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_input($asesor); ?>
        <div style="color: red;"><?php echo form_error($asesor['name']); ?><?php echo isset($errors[$asesor['name']])?$errors[$asesor['name']]:''; ?>        </div>
    </div> 
       
</div>
    <center>
            <input type="submit" value="Consultar" />       
         </center>
    
<?php echo form_close(); ?>