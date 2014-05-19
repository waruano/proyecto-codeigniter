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
?>

<?php echo form_open('consultor/consultar') ?>
<h3>Consulta de Contratos</h3>

 <div class="row"  >
     <div class="col-md-4">   
         <?php echo form_label('Identificación', $identificacion['id']); ?> &nbsp; &nbsp;
         <?php echo form_input($identificacion); ?>          
         <div style="color: red;">
            <?php echo form_error($identificacion['name']); ?><?php echo isset($errors[$identificacion['name']])?$errors[$identificacion['name']]:''; ?>
        </div>
     </div>
     <div class="col-md-4">         
         <?php echo form_label('Número Contrato', $numeroContrato['id']); ?> 
        <?php echo form_input($numeroContrato); ?>
        <div style="color: red;"><?php echo form_error($numeroContrato['name']); ?><?php echo isset($errors[$numeroContrato['name']])?$errors[$numeroContrato['name']]:''; ?>    </div>
     </div>
     <div class="col-md-4">         
         <?php echo form_label('Nombre Titular', $nombreTitular['id']); ?> 
        <?php echo form_input($nombreTitular); ?>
        <div style="color: red;"><?php echo form_error($nombreTitular['name']); ?><?php echo isset($errors[$nombreTitular['name']])?$errors[$nombreTitular['name']]:''; ?>        </div>
     </div>     
 </div>
        <center>
            <input type="submit" value="Consultar" />       
         </center>
    
<?php echo form_close(); ?>