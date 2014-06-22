<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>
    
    <div class="titlerow">Cargos Adicionales</div>
    <div class="row" style="margin-bottom: 5px">
        <div class="col-md-6" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >

            <h4 >Información del Titular</h4>            
            <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                    <tr>
                        <td style="padding-left: 20px; padding-top: 10px;">Nombre: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $titular->NOMBRES . ' ' . $titular->APELLIDOS ?>    </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;  padding-bottom: 15px;">Numero de Beneficiarios: </td><td style="padding-left: 20px;  padding-bottom: 15px;"><?php echo $titular->NODOCUMENTO ?></td>
                    </tr>            
                </tbody>
            </table>
        </div>
        
        
    </div>
<?php
echo $output;
?>

</div>