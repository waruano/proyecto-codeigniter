<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Cree una nueva contraseña en <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Cree una nueva contraseña</h2>
Olvidaste tu contraseña? No hay problema!!<br />
Para crear una nueva contraseña sigue el siguiente enlace:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">Crear nueva contraseña</a></b></big><br />
<br />
No funciona? Copie la siguiente URL en la barra de direcciones de su navegador:<br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
<br />
Ud recibió este correo porque fué solicitado por un usuario en <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> como parte del proceso para crear una nueva contraseña en el sistema.
Si Ud no solicitó una nueva contraseña por favor ignore este correo, su contraseña no cambiará.<br />
<br />
<br />
Gracias,<br />
Equipo <?php echo $site_name; ?>
</td>
</tr>
</table>
</div>
</body>
</html>