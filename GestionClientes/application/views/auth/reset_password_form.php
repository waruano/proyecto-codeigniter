<?php $this->load->view('pages/initpage')  ?>
<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
	<div>
		<div><?php echo form_label('Nueva Contraseña', $new_password['id']); ?></div>
		<div><?php echo form_password($new_password); ?></div>
		<div style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></div>
	</div>
	<div>
		<div><?php echo form_label('Confirmar Nueva Contraseña', $confirm_new_password['id']); ?></div>
		<div><?php echo form_password($confirm_new_password); ?></div>
		<div style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></div>
	</div>
</div>
<br/>
<?php 
$submit = array(
	'name'	=> 'change',
	'id'	=> 'change',
	'value' => 'Cambiar Contraseña',
        'class'=>'btn btn-default'
);
echo form_submit($submit); ?>
<?php echo form_close(); ?>