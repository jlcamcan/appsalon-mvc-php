<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>
<?php include_once __DIR__ . '/../templates/barra.php' ;?> 
<ul class="servicios">
    <?php foreach ($servicios as $servicio){?> 
        <li>
            <p>Servicio: <span><?php echo $servicio->nombre?> </span></p>
            <p>Precio: <span><?php echo $servicio->precio?> €</span></p>
            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id;?>">Actualizar</a>
                <form method="POST" action="/servicios/eliminar" id="formEliminarServicio-<?php echo  $servicio->id; ?>">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <button type="submit" class="boton-eliminar" onclick="confirmarBorrado(event, 'formEliminarServicio-<?php echo $servicio->id; ?>')">Eliminar</button>
                </form>
            </div>
        </li>
    <?php }?> 
</ul>

<?php 
 $script = "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src='build/js/alertas.js'></script>"
?> 






