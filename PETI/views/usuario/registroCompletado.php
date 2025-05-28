<?php
if(isset($_SESSION['registro']) && $_SESSION['registro'] == 'completado'): ?>
    <h2 style="text-align:center;">¡Registro completado!</h2>
<?php else: ?>
   <h2 style="text-align:center;">Error en el registro, ¡por favor ingresa los datos correctamente!</h2>
<?php endif; ?>

<?php Utilidades::eliminarSesion('registro'); ?>
