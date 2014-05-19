<?php
$this->set_css($this->default_theme_path . '/flexigrid/css/flexigrid.css');
$this->set_js_lib($this->default_theme_path . '/flexigrid/js/jquery.form.js');
$this->set_js_config($this->default_theme_path . '/flexigrid/js/flexigrid-add.js');

$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.noty.js');
$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
    
    <div class="row mDiv" style="border: 1px solid; text-align: center; color: #444;
         font-size: 18px; padding-top: 5px; padding-bottom: 5px; border-radius: 6px 6px 0px 0px; ">
        <p><?php echo $this->l('form_add'); ?> <?php echo $subject ?></p>
    </div>
    <div id='main-table-box'>
        <?php echo form_open($insert_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
        <div class='form-div' style="padding: 0;">
            <?php
            $counter = 0;
             $counter_odd = 0;
            foreach ($fields as $field) {
                $even_odd = $counter_odd % 2 == 0 ? 'odd' : 'even';                
                ?>
                <?php 
                if($counter % 2 == 0){ 
                    echo '<div class="row" style="border-left: 1px solid;  border-right: 1px solid;">';                     
                }  
                ?>
            
                <div class='form-field-box col-md-6 <?php echo $even_odd ?>' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
                        <?php echo $input_fields[$field->field_name]->display_as; ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required'>*</span> " : ""; ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
                        <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
                <?php if($counter % 2 != 0){ 
                        echo '</div>'; 
                        $counter_odd++;
                }  
                $counter++;
                ?>
            <?php } ?>
            <?php if($counter % 2 != 0){ 
                        echo '</div>'; 
                }                      
            ?>
            <!-- Start of hidden inputs -->
            <?php
            foreach ($hidden_fields as $hidden_field) {
                echo $hidden_field->input;
            }
            ?>
            <!-- End of hidden inputs -->
            <?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php } ?>
            
            <div class="row" style="border-left: 1px solid; border-right: 1px solid;">
                <div class="col-md-6">
                    <div id='report-error' class='report-div error'></div>
                    <div id='report-success' class='report-div success'></div>
                </div>
            </div>
        </div>
        <div class="row" style="border: 1px solid; border-top: 0px;  text-align: center; font-size: 18px; 
             padding-bottom: 10px; border-radius: 0px 0px 6px 6px; ">
            <?php
            switch ($buttons_form) {
                case 'default':
                    ?>
                    <div class='form-button-box'>
                        <input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large"/>
                    </div>
                    <?php if (!$this->unset_back_to_list) { ?>
                        <div class='form-button-box'>
                            <input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
                        </div>
                    <?php } ?>
                    <div class='form-button-box'>
                        <div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
                    </div>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                    </div>
                    <div class='clear'></div>
                </div>
                <?php
                break;
            case 'sinGuardar':
                    ?>                   
                    <?php if (!$this->unset_back_to_list) { ?>
                        <div class='form-button-box'>
                            <input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
                        </div>
                    <?php } ?>
                    <div class='form-button-box'>
                        <div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
                    </div>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                    </div>
                    <div class='clear'></div>
                </div>
                <?php
                break;    
            
            case 'siguienteTitular':
                ?>
                <div class='form-button-box'>
                    <input type='button' value='Siguiente' id="save-and-go-back-button"  class="btn btn-large"/>
                </div>
                <div class='form-button-box'>
                    <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                </div>
                <div class='clear'></div>
                <?php
                break;
            case'back_to_list':
                ?>
                <div class='form-button-box'>
                    <input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large"/>
                </div>
                <?php if (!$this->unset_back_to_list) { ?>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
                    </div>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                    </div>
                <?php } ?>
                <div class='form-button-box'>
                    <div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
                </div>
                <div class='clear'></div>
            </div>
            <?php break;
    }
    ?>
    <?php echo form_close(); ?>
</div>
</div>
<script>
    var validation_url = '<?php echo $validation_url ?>';
    var list_url = '<?php echo $list_url ?>';

    var message_alert_add_form = "<?php echo $this->l('alert_add_form') ?>";
    var message_insert_error = "<?php echo $this->l('insert_error') ?>";
</script>