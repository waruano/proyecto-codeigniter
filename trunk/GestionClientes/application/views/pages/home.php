<?php if ($this->tank_auth->is_logged_in() != true) {	?><br/><br/><?php  } ?>
<div>
    <div>
    <h3>Bienvenido al sistema de administración de "PREVIMED"</h3>
    <p>
        Mediante el presente sistema podrá administrar información de Titulares, Beneficiarios, Contratos, Papelería y Cartera de <strong>Previmed</strong>. 
        Es necesario que cuente con un usuario y contraseña suministrado por el administrador de la aplicación.
    </p>
    <p>
        <?php if ($this->tank_auth->is_logged_in() != true) {	
                 echo anchor('auth', 'Ingresar');        } ?>
    </p>
    </div>    
</div>
