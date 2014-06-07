<?php $this->load->view('pages/initpage')  ?>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30
);
$submit = array(
	'name'	=> 'reset',
	'id'	=> 'reset',
	'value' => 'Obtener Nueva Contraseña',
	'class' => 'btn btn-default'
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email o Usuario';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<tr><td><?php echo form_label($login_label, $login['id']); ?></td></tr>
		<tr><td><?php echo form_input($login); ?></td></tr>
		<tr><td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
</table>
</br>
<?php echo form_submit($submit); ?>
<?php echo form_close();?>