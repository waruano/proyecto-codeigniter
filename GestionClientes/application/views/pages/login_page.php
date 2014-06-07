<br/><br/>
<?php $this->load->view('pages/initpage')  ?>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$submit = array(
	'name'	=> 'submit',
	'id'	=> 'submit',
	'value' => 'Entrar',
        'class'=>'btn btn-default'
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Usuario';
} else if ($login_by_username) {
	$login_label = 'Usuario';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
	<div>
		<div><?php echo form_label($login_label, $login['id']); ?></div>
		<div><?php echo form_input($login); ?></div>
		<div style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
	</div>
	<div>
		<div><?php echo form_label('Contraseña', $password['id']); ?></div>
		<div><?php echo form_password($password); ?></div>
		<div style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
	</div>
	<div>
                <?php echo form_checkbox($remember); ?>
                <?php echo form_label('Recordarme', $remember['id']); ?>
                <?php echo anchor('/auth/forgot_password/', 'Recuperar Contraseña'); ?>
                <?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>
		
	</div>
</div>
<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>