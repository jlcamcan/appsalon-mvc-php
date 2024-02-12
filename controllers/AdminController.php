<?php 
namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController {
public static function index(Router $router){
    if (!isset($_SESSION)){
        session_start();
    }
    esAdmin();
    $fecha = $_GET['fecha'] ?? date('Y-m-d');;
    $fechas = explode('-', $fecha);
    if(!checkdate($fechas[1], $fechas[2],$fechas[0])){
        header('Location: /404');
    }
    
    //Consultar la base de datos
    $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellidos) as cliente, ";
    $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
    $consulta .= " FROM citas ";  
    $consulta .= " LEFT OUTER JOIN usuarios ";
    $consulta .= " ON citas.usuarioId=usuarios.id ";
    $consulta .= " LEFT OUTER JOIN citasServicios "; 
    $consulta .= " ON citasServicios.citasId=citas.id ";
    $consulta .= " LEFT OUTER JOIN servicios ";
    $consulta .= " ON servicios.id=citasServicios.serviciosId ";
    $consulta .= " WHERE fecha = '${fecha}'";
    $consulta .= " ORDER BY citas.hora";
    
    $citas = AdminCita::SQL($consulta);
  
    $router->render('admin/index',[
        'nombre'=> $_SESSION['nombre'],
        'citas' => $citas,
        'fecha' => $fecha
    ]);
}

public static function pdf(Router $router){
    $fecha = $_POST['fecha'];
    //Consultar la base de datos
    $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellidos) as cliente, ";
    $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
    $consulta .= " FROM citas ";  
    $consulta .= " LEFT OUTER JOIN usuarios ";
    $consulta .= " ON citas.usuarioId=usuarios.id ";
    $consulta .= " LEFT OUTER JOIN citasServicios "; 
    $consulta .= " ON citasServicios.citasId=citas.id ";
    $consulta .= " LEFT OUTER JOIN servicios ";
    $consulta .= " ON servicios.id=citasServicios.serviciosId ";
    $consulta .= " WHERE fecha = '${fecha}'";
    $consulta .= " ORDER BY citas.hora";
    
    $citas = AdminCita::SQL($consulta);
      
    $router->render('admin/pdf',[
        'nombre'=> $_SESSION['nombre'],
        'citas' => $citas,
        'fecha' => $fecha
    ]);
}
}

