<h1 class="nombre-pagina">Panel de Administración</h1>
<p class="descripcion-pagina">Control de Citas y Servicios</p>
<?php 
    include_once __DIR__ . '/../templates/barra.php';
?> 
<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value = "<?php echo $fecha; ?>"/>
        </div>
    </form>
</div>
<?php if(count($citas) === 0){
    echo "<h2>No hay citas en esta fecha</h2>";
}
?> 
<div id="citas-admin">
    <ul class="citas">
        <?php 
        $idCita = 0;
        foreach ($citas as $key => $cita){ 
                if($idCita !== $cita->id){ 
                $total = 0;
                ?>
                <li>
                    <p>ID: <span><?php echo $cita->id;?></span></p>
                    <p>Hora: <span><?php echo $cita->hora;?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente;?></span></p>
                    <p>Email: <span><?php echo $cita->email;?></span></p>
                    <p>Teléfono: <span><?php echo $cita->telefono;?></span></p>
                    <h3>Servicios</h3>
            <?php  $idCita = $cita->id;
            } //Fin del if ?>  
                 <p class="servicio"><?php echo $cita->servicio . " - " . number_format($cita->precio,2,',','.') . " €"; 
                 $total += $cita->precio;
                 ?> </p>
                 <?php 
                    $actual = $cita->id;
                    $proximo = $citas[$key+1]->id ?? 0;
                    if(esUltimo($actual,$proximo)){ 
                ?>
                        <p class="total">Total: <span><?php echo number_format($total,2,',','.') . " €"; ?></span></p>
                        <form method="POST" action="/api/eliminar" id="formEliminarCita-<?php echo  $cita->id; ?>">
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                            <button type="submit" class="boton-eliminar" onclick="confirmarEliminarCita(event, 'formEliminarCita-<?php echo $cita->id; ?>')">Eliminar</button>
                        </form>
                        <?php 
                    }
        };//fin del foreach ?> 
    </ul>
</div>
<form action="admin/pdf" target='_blank' method="POST">
   <input type="hidden" name="fecha" value="<?php echo $fecha;?>">
   <input type="submit" class="boton" value="Imprimir PDF">
</form>

<?php 
 $script = "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src='build/js/buscador.js'></script>"
?> 
