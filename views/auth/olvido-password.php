<h1 class="nombre-pagina">Reestablecer Contraseña</h1>
<p class="descripcion-pagina">Reeestablece tu contraseña escribiendo tu email a continuación</p>
<?php include_once __DIR__ . "/../templates/alertas.php";
?> 
<form  class="formulario" method="POST" action="/olvido">
    <div class="campo">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email", placeholder="Tu Correo Electrónico">
    </div>
    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>

