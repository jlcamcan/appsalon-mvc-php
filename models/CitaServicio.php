<?php
namespace Model;
class CitaServicio extends ActiveRecord{
    //Base de Datos
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id','citasId','serviciosId'];
    //Campos
    public $id;
    public $citasId;
    public $serviciosId;
    //Constructor de la clase
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->citasId = $args['citasId'] ?? '';
        $this->serviciosId = $args['serviciosId'] ?? '';
    }
}

