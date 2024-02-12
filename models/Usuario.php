<?php 
namespace Model;
class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellidos','email','password','telefono','admin','confirmado','token'];
    //Atributos
    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    //Constructor
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    //Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta(){
       if(!$this->nombre){
        self::$alertas['error'][] = 'El nombre de Usuario es obligatorio';
       } 
       if(!$this->apellidos){
        self::$alertas['error'][] = 'Los apellidos de Usuario son obligatorios';
       } 
       if(!$this->telefono){
        self::$alertas['error'][] = 'El teléfono de Usuario es obligatorio';
       } 
       if(!$this->email){
        self::$alertas['error'][] = 'El correo electrónico de Usuario es obligatorio';
       } 
       if(!$this->password){
        self::$alertas['error'][] = 'El password de Usuario es obligatorio';
       } 
       if(strlen($this->password)<6){
        self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
       }
       return self::$alertas;
    }

    //Validar el login
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El correo electrónico de Usuario es obligatorio';
           } 
           if(!$this->password){
            self::$alertas['error'][] = 'El contraseña de Usuario es obligatoria';
           } 
        return self::$alertas;
    }
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El correo electrónico de Usuario es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña de Usuario es obligatoria';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
           }
        return self::$alertas;
    }

    //Comprobar si el usuario existe
    public function existeUsuario(){
        $consulta = "SELECT * FROM " . self::$tabla . " WHERE email = '". $this->email . "' LIMIT 1";
        $resultado = self::$db->query($consulta);
        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya está registrado";
        }
        return $resultado;
    }

    //Hashear el password
    public function hashPassword(){
       $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Crear un Token unico
    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordYVerificado($password){
      $resultado = password_verify($password, $this->password);
      if(!$resultado || !$this->confirmado){
        self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
      }else{
        return true;
      }
    }
}

