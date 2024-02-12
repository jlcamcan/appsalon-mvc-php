<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php";
?> 

<form  class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre", placeholder="Tu Nombre" 
        value="<?php echo s($usuario->nombre);?>">
    </div>
    <div class="campo">
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos", placeholder="Tus Apellidos"
        value="<?php echo s($usuario->apellidos);?>">>
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono", placeholder="Tu Teléfono"
        value="<?php echo s($usuario->telefono);?>">>
    </div>
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email", placeholder="Tu Correo Electrónico"
        value="<?php echo s($usuario->email);?>">>
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password", placeholder="Tu Contraseña">
    </div>
    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvido">¿Olvidastes tu contraseña?</a>
</div>


