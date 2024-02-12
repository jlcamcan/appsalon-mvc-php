<?php
namespace Model;
class Cita extends ActiveRecord{
    //Base de Datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id','fecha','hora','usuarioId'];
    //Campos
    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;
    //Constructor de la clase
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }
}