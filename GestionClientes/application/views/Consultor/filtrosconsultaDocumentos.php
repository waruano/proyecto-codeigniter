<?php
$lasesor = array(
	'name'	=> 'asesor',
	'id'	=> 'asesor',
	'value' => $asesor,
	'size' 	=> 30
); 
$lestado = array(
	'name'	=> 'estado',
	'id'	=> 'estado',	
        'value' => $estado,
	'size'	=> 30,
        'options' => array('0' => '', '1' => 'Asignado', '2' => 'Reportado', '3' => 'Anulado')
);

$lnodocumento = array(
	'name'	=> 'nodocumento',
	'id'	=> 'nodocumento',
        'value' => $nodocumento,
	'size' 	=> 30
);

$lrangoinicio = array(
	'name'	=> 'rangoinicio',
	'id'	=> 'rangoinicio',
        'value' => $rangoinicio,
	'size' 	=> 30
);

$lrangofin = array(
	'name'	=> 'rangofin',
	'id'	=> 'rangofin',
        'value' => $rangofin,
	'size' 	=> 30
);

$ltipo = array(
	'name'	=> 'tipo',
	'id'	=> 'tipo',
        'value' => $tipo,
	'size' 	=> 30,
        'options' => array('0' => '', '1' => 'Contrato', '2' => 'Recibo de Caja')
); 


?>

<?php echo form_open('consultor/consultaDocumentos') ?>

<div class="titlerow">Consulta de Documentos</div>

 <div class="row" style="padding-top: 10px; " >
     <div class="col-md-1">   
         <?php echo form_label('Asesor', $lasesor['id']); ?> 
     </div>
         <div class="col-md-3">        
    <?php echo form_input($lasesor); ?>          
         <div style="color: red;">
            <?php echo form_error($lasesor['name']); ?><?php echo isset($errors[$lasesor['name']])?$errors[$lasesor['name']]:''; ?>
        </div>
     </div>
     
     <div class="col-md-1" style="padding-left: 0px;">   
         <?php echo form_label('No. Documento', $lnodocumento['id']); ?> 
     </div>
         <div class="col-md-3">        
    <?php echo form_input($lnodocumento); ?>          
         <div style="color: red;">
            <?php echo form_error($lnodocumento['name']); ?><?php echo isset($errors[$lnodocumento['name']])?$errors[$lnodocumento['name']]:''; ?>
        </div>
     </div>
     
     <div class="col-md-1" style="padding-left: 0px;">         
         <?php echo form_label('Tipo', $ltipo['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_dropdown($ltipo['name'], $ltipo['options'], $ltipo['value'], 'style="width:215px; height: 26px; "');         
        ?>        
        <div style="color: red;">
            <?php echo form_error($ltipo['name']); ?><?php echo isset($ltipo[$ltipo['name']])?$errors[$ltipo['name']]:''; ?>        </div>
    </div>
         
 </div>
<div class="row" style="padding-bottom: 5px; padding-top: 5px;" >
     <div class="col-md-1">         
         <?php echo form_label('Estado', $lestado['id']); ?> 
        </div>
         <div class="col-md-3"> 
        <?php echo form_dropdown($lestado['name'], $lestado['options'], $lestado['value'], 'style="width:215px; height: 26px; "');         
        ?>        
        <div style="color: red;">
            <?php echo form_error($lestado['name']); ?><?php echo isset($errors[$lestado['name']])?$errors[$lestado['name']]:''; ?>        </div>
    </div> 

    <div class="col-md-1" style="padding-left: 0px;">   
         <?php echo form_label('Mayores o iguales a', $lrangoinicio['id']); ?> 
     </div>
         <div class="col-md-3">        
    <?php echo form_input($lrangoinicio); ?>          
         <div style="color: red;">
            <?php echo form_error($lrangoinicio['name']); ?><?php echo isset($errors[$lrangoinicio['name']])?$errors[$lrangoinicio['name']]:''; ?>
        </div>
     </div>
    
    <div class="col-md-1" style="padding-left: 0px;">   
         <?php echo form_label('Menores o iguales a', $lrangofin['id']); ?> 
     </div>
         <div class="col-md-3">        
    <?php echo form_input($lrangofin); ?>          
         <div style="color: red;">
            <?php echo form_error($lrangofin['name']); ?><?php echo isset($errors[$lrangofin['name']])?$errors[$lrangofin['name']]:''; ?>
        </div>
     </div>
    
</div> 
        <center>
            <input type="submit" value="Consultar" />       
         </center>
    
<?php echo form_close(); ?>