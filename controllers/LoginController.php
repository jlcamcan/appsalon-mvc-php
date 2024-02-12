<?php 
namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)){
                //Usuario rellenó el email y password
                //Comprobar que existe el email
               $usuario = Usuario::where('email',$auth->email);
               if ($usuario){
                    //Verificar el password
                    if($usuario->comprobarPasswordYVerificado($auth->password)){
                        if (!isset($_SESSION)){
                            session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellidos;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] =true;

                        //Redireccionamiento
                        if ($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? 0;
                            header ('Location: /admin');
                        }else{
                            header ('Location: /cita');
                        }
                    }
               }else{
                    //Crear alerta
                    Usuario::setAlerta('error', 'Usuario no encontrado');
               }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        //Eliminamos la sesión.
        $_SESSION = [];
        //Redirigimos al login
        header('Location: /');
    }


    public static function olvido(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
           $auth = new Usuario($_POST);
           $alertas = $auth->validarEmail();
           if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1"){
                  //Generar token
                  $usuario->crearToken();
                  $usuario->guardar();
                  //Alerta de exito
                  Usuario::setAlerta('exito', 'Revisa tu correo electrónico');
                  //Enviar el correo con las instrucciones de reseteo
                  $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                  $email->enviarInstrucciones();
                }else{
                    Usuario::setAlerta('error','El Usuario no existe ó no está confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvido-password', [
            'alertas' => $alertas
        ]);
    }


    public static function recuperar(Router $router){
        $alertas =[];
        $error = false;
        $token = s($_GET['token']);
        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token);
       //Comprobar que el token existe
        if(empty($usuario)){
            Usuario::setAlerta('error','Token no válido');
            $error = true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if(empty($alertas)){
                //Actualizar el password
                $usuario->password = "";
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = "";
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas'=> $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        //Crear una instancia de usuario
        $usuario = new Usuario();
        //Alertas vacias
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD']=== 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //Revisar que no hay alertas
            if (empty($alertas['error'])){
                //Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //No está registrado
                    //Hashear el password
                    $usuario->hashPassword();
                    //Generar un Token unico
                    $usuario->crearToken(); 
                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
          
        }
        $router->render('auth/crear-cuenta', [
            'usuario'=> $usuario,
            'alertas'=> $alertas
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Confirmación de cuenta no válida');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado = 1;
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Confirmación de cuenta válida');
        }
        //Obtener alertas
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}

