<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    
    <div class="titlerow">Beneficiarios</div>
    <div  style="padding-left: 0px; margin-bottom: 5px;   display: inline-block; ">
        <div  style=" float:left; width: 50%; border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Titular</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 5px;">Titular: </td><td style="padding-left: 20px; padding-top: 5px;"> <?php echo $titularFullName ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Identificación: </td><td style="padding-left: 20px;"><?php echo $titularIdentificacion ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 5px;">No Contrato: </td><td style="padding-left: 20px; padding-bottom: 5px;"><?php echo $titularContrato ?></td>
                </tr>                
                </tbody>
            </table>
        </div>
        
        <div style="float: right; width: 50%; ">
            <?php $this->load->view('Administrador/sidebar_add_titular');?>
        </div>
        
        <div style="float: left; width: 50%; padding-left: 10px;">
             <?php if ($plan_beneficiarios!=FALSE):?>
                    <?php if (intval($total_beneficiarios) < intval($plan_beneficiarios)) { ?>
                        <h4>Total beneficiarios: <?php echo $total_beneficiarios."/".$plan_beneficiarios; ?></h4>
             
                    <?php } else { ?>
                        <h4 style="color: red">Total de Beneficiarios: <?php echo $total_beneficiarios."/".$plan_beneficiarios; ?></h4>
                            <h5 style="color: red">Ha alcanzado su limite de beneficiarios.</h5>
                    <?php } ?>
             <?php else: ?>
                        <h5 style="color: red">Sin contrato Activo.</h5>    
             <?php endif;?>
            </div>
    </div>
<?php
echo $output;
?>

<?php if (isset($_SESSION['_aux_wizard']) && $_SESSION['_aux_wizard'] == true) { ?>
        <input type="button" class="btn btn-large" onclick="window.location = '<?php echo base_url() . "contratos/index"; ?>'" value="Continuar "/>   
        <input type="button" class="btn btn-large" onclick="window.location = '<?php echo base_url() . "administrador/contactos"; ?>'" value="Regresar a Contactos "/>   
<?php } else {
    ?>
        <input type="button" class="btn btn-large"  onclick="window.location = '<?php echo base_url() . "contratos/titulares"; ?>'" value="Regresar a Titulares"/>  
<?php } ?>
</div>

