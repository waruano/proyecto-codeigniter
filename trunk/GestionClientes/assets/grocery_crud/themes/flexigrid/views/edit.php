<?php
$this->set_css($this->default_theme_path . '/flexigrid/css/flexigrid.css');
$this->set_js_lib($this->default_theme_path . '/flexigrid/js/jquery.form.js');
$this->set_js_config($this->default_theme_path . '/flexigrid/js/flexigrid-edit.js');

$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.noty.js');
$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
    <div class="row " style="border: 1px solid #69bd43; padding-left: 10px; color: #fff; background-color: #69bd43;
         font-size: 16px; padding-top: 8px; padding-bottom: 8px; border-radius: 6px 6px 0px 0px; ">
        <strong><?php echo $this->l('form_edit'); ?> <?php echo $subject ?></strong>
    </div>
    <div id='main-table-box'>
        <?php echo form_open($update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
        <div class='form-div' style="padding: 0;">
            <?php
            $counter = 0;
            $counter_odd = 0;
            foreach ($fields as $field) {
                $even_odd = $counter_odd % 2 == 0 ? 'odd' : 'even';
                ?>
                <?php
                if ($counter % 2 == 0) {
                    echo '<div class="row" style="border-left: 1px solid #ccc;  border-right: 1px solid #ccc;">';
                }
                ?>
                <div class='form-field-box  col-md-6 <?php echo $even_odd ?>' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
                        <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required'>*</span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
                        <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
                <?php
                if ($counter % 2 != 0) {
                    echo '</div>';
                    $counter_odd++;
                }
                $counter++;
                ?>
            <?php } ?>
            <?php
            if ($counter % 2 != 0) {
                echo '</div>';
            }
            ?>
            <?php if (!empty($hidden_fields)) { ?>
                <!-- Start of hidden inputs -->
                <?php
                foreach ($hidden_fields as $hidden_field) {
                    echo $hidden_field->input;
                }
                ?>
                <!-- End of hidden inputs -->
            <?php } ?>
            <?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php } ?>
            <div class="row" style="border-left: 1px solid #ccc; border-right: 1px solid #ccc;">
                <div class="col-md-6">
                    <div id='report-error' class='report-div error'></div>
                    <div id='report-success' class='report-div success'></div>
                </div>
            </div>
        </div>
        <div class="row" style="border: 1px solid #ccc; border-top: 0px;  text-align: center; font-size: 18px; 
             padding-bottom: 10px; border-radius: 0px 0px 6px 6px; ">
             <?php
             switch ($buttons_form) {

                 case 'siguienteTitular':
                     ?>
                    <div class='form-button-box'>
                        <input type='button' value='Actualizar y Continuar' id="save-and-go-back-button" class="btn btn-large"/>
                    </div>
                    <div class='form-button-box'>
                        <div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
                    </div>
                    <div class='clear'></div>
                    <?php
                    break;
                case 'sinGuardar':
                     ?>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                    </div>
                    <div class='form-button-box'>
                        <div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
                    </div>

                    <div class='clear'></div>
                    <?php
                    break;
                default :
                    ?>
                    <div class='form-button-box'>
                        <input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class="btn btn-large"/>
                    </div>
        <?php if (!$this->unset_back_to_list) { ?>     
                        <div class='form-button-box'>
                            <input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' id="save-and-go-back-button" class="btn btn-large"/>
                        </div>
        <?php } ?>
                    <div class='form-button-box'>
                        <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
                    </div>
                    <div class='form-button-box'>
                        <div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
                    </div>

                    <div class='clear'></div>
                    <?php
                    break;
            }
            ?>
        </div>

<?php echo form_close(); ?>
    </div>
</div>
<script>
    var validation_url = '<?php echo $validation_url ?>';
    var list_url = '<?php echo $list_url ?>';

    var message_alert_edit_form = "<?php echo $this->l('alert_edit_form') ?>";
    var message_update_error = "<?php echo $this->l('update_error') ?>";
</script>