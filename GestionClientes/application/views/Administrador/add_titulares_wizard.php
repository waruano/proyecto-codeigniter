<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div>    
    <div class="titlerow">Registro de Titular</div>
    <div class="row" style="margin-bottom: 5px">
        <div class="col-md-4" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Plan</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 10px;">Nombre: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $plan_nombre ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Numero de Beneficiarios: </td><td style="padding-left: 20px;"><?php echo $plan_beneficiarios ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 10px;">Nombre del Convenio: </td><td style="padding-left: 20px; padding-bottom: 10px;"><?php echo $plan_convenio ?></td>
                </tr>                
                </tbody>
            </table>
        </div>
        <div class="col-md-4" style="border: 1px solid #ccc; text-align: center; font-size: 18px; 
             padding: 0px; border-radius: 6px 6px 6px 6px;  " >
            
            <h4 >Informacion del Contrato</h4>            
                <table style="width: 100%; background-color: rgb(239, 239, 239);" >
                <tbody >
                <tr>
                    <td style="padding-left: 20px; padding-top: 10px;">Tipo: </td><td style="padding-left: 20px; padding-top: 10px;"> <?php echo $contrato_tipo ?>    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Periodicidad: </td><td style="padding-left: 20px;"><?php echo $contrato_periodicidad ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;  padding-bottom: 10px;">Fecha de Inicio: </td><td style="padding-left: 20px; padding-bottom: 10px;"><?php echo $contrato_fechaInicio ?></td>
                </tr>                
                </tbody>
            </table>
            
            
        </div>
        
    </div>
    <?php
    echo $output;
    ?>
    <script language="javascript" type="text/javascript">
        
    jQuery(document).ready(function()
    {   
        
    
        jQuery("div[id$='COBROMUNICIPIO_field_box']").css('height','50px');            
        jQuery("div[id$='COBRODIRECCION_field_box']").css('height','50px');
        
        jQuery("div[id$='COBROMUNICIPIO_field_box']").append('<input type="checkbox" id="disbledir" onchange="javascript: return copiardireccion();" >Utilizar igual dirección como domicilio');
        //jQuery("div[id$='COBROBARRIO_field_box']").append('<a href="#" onclick="javascript: return copiardireccion();">Copiar</a>');        
        jQuery("input[id$='form-button-save']").click(function(){
         copiardireccion();   
        });
        jQuery("input[id$='save-and-go-back-button']").click(function(){
         copiardireccion();   
        });
        
        apariencia_direccion();
                
        jQuery("select[id$='field-DOMIMUNICIPIO']").change(function(){            
            loadBarrios('field-DOMIMUNICIPIO', 'field-DOMIBARRIO','');
        });
        
        jQuery("select[id$='field-COBROMUNICIPIO']").change(function(){            
            loadBarrios('field-COBROMUNICIPIO', 'field-COBROBARRIO', '');
        }); 
        
        loadBarrios('field-DOMIMUNICIPIO', 'field-DOMIBARRIO',"<?php echo $DOMIBARRIO ?>");
        loadBarrios('field-COBROMUNICIPIO', 'field-COBROBARRIO',"<?php echo $COBROBARRIO ?>");        
    });
    
    function loadBarrios(selCiudades, selBarrios, sSeleccionado)
    {        
        if(sSeleccionado == '') sSeleccionado = -1;
        jQuery.ajax({
            'url': '<?php echo base_url()."index.php/administrador/listadobarrios/"; ?>' + jQuery("select[id$='" + selCiudades + "']").val() + "/" + sSeleccionado ,
            'success':function(data){                        
                jQuery("select[id$='" + selBarrios + "']").empty();
                jQuery("select[id$='" + selBarrios + "']").append(data);
            }
            });
    }
    
    function apariencia_direccion()
    {
        jQuery("select[id$='field-COBROBARRIO']").css('display', 'block');
        jQuery("div[id$='field_COBROBARRIO_chzn']").css('display', 'none');
        
        jQuery("select[id$='field-DOMIBARRIO']").css('display', 'block');
        jQuery("div[id$='field_DOMIBARRIO_chzn']").css('display', 'none');
        
        jQuery("select[id$='field-DOMIMUNICIPIO']").css('display', 'block');
        jQuery("div[id$='field_DOMIMUNICIPIO_chzn']").css('display', 'none');
        
        jQuery("select[id$='field-COBROMUNICIPIO']").css('display', 'block');
        jQuery("div[id$='field_COBROMUNICIPIO_chzn']").css('display', 'none');
        
        
    }
    
    function copiardireccion()
    {
        if(jQuery("input[id$='disbledir']").is(':checked'))
        {
            jQuery("div[id$='DOMIDIRECCION_field_box']").css('visibility','hidden');            
            jQuery("div[id$='DOMIBARRIO_field_box']").css('visibility','hidden');
            jQuery("div[id$='DOMIMUNICIPIO_field_box']").css('visibility','hidden');
            
            var iCobro = jQuery("input[id$='field-COBRODIRECCION']");
            var iDomicilio = jQuery("input[id$='field-DOMIDIRECCION']");        

            var dropCobro = jQuery("select[id$='field-COBROBARRIO']");
            //var dropDomicilio = jQuery("select[id$='field-DOMIBARRIO']");  

            var ciudadCobro = jQuery("select[id$='field-COBROMUNICIPIO']");
            var ciudadDomi = jQuery("select[id$='field-DOMIMUNICIPIO']");        

            ciudadDomi.val(ciudadCobro.val());
            //dropDomicilio.val(dropCobro.val());
            iDomicilio.val(iCobro.val());
            
            loadBarrios('field-DOMIMUNICIPIO', 'field-DOMIBARRIO', replaceAll(' ','_', dropCobro.val()) );             
                        
        }
        else
        {
            jQuery("div[id$='DOMIDIRECCION_field_box']").css('visibility','visible');            
            jQuery("div[id$='DOMIBARRIO_field_box']").css('visibility','visible');
            jQuery("div[id$='DOMIMUNICIPIO_field_box']").css('visibility','visible');
        }
            
        return false;
    }
    
    function replaceAll(find, replace, str) {
        return str.replace(new RegExp(find, 'g'), replace);
      }
    </script>
    
</div>
