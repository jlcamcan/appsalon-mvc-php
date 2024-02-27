<?php 
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try{
            //Configurar SMTP (Protocolo para envio emails)
            $mail->isSMTP();
            
            //Credenciales de la cuenta
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username =  $_ENV['EMAIL_USER'];
            $mail->Password =  $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Encriptación TLS
            $mail->Port = $_ENV['EMAIL_PORT'];

            //Destinatario
            $mail->setFrom('appsalonpeluqueria@gmail.com', 'AppSalon Peluquería'); 
            $mail->addAttachment('../public/build/img/logo-2.jpg', 'foto.jpg');
            $mail->addAddress($this->email, $this->nombre);
      
            //Habilitar HTML
            $mail->isHTML(true);
            //Contenido
            $mail->Subject = "Confirma tu Cuenta en APP SALÓN";
            //Construimos el contenido
            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado tu cuenta en <strong>App Salon</strong>, solo debes confirmarla presionando el siguiente enlace</p>";
            $contenido .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= "</html>";
            //Mostramos el contenido
            $mail->Body = $contenido;
            //Método alternativo si no tiene HTML
            $mail->AltBody  = "Contenido sin HTML";

            //Enviar email
            $mail->send();
        }catch(Exception $e){
            "Se ha producido un error en el envío del formulario: {$mail->ErrorInfo}";
        }
    }

    public function enviarInstrucciones(){
        //Crear una instancia de PHPMailer
        $mail = new PHPMailer();
        //Configurar SMTP (Protocolo para envio emails)
        try{
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username =  $_ENV['EMAIL_USER'];
            $mail->Password =  $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->setFrom("appsalonpeluqueria@gmail.com", 'App Salon Peluquería');
            //Adjuntar un archivo
            $mail->addAttachment('../public/build/img/logo-2.jpg', 'foto.jpg');
            $mail->addAddress($this->email, "AppSalon.com");
            $mail->Subject = "Reestablece tu Contraseña";

            //Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            //Construimos el contenido
            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/recuperar?token=" . $this->token . "'>Reestablecer Contraseña</a></p>";
            $contenido .= "<p>Si tú no solicitaste este cambio, puedes ignorar el mensaje</p>";
            $contenido .= "<img src=\"cid:logo\" border='0'>";
            $contenido .= "</html>";
            //Añadimos el contenido
            $mail->Body = $contenido;

            //Enviar email
            $mail->send(); 
        }catch(Exception $e){
            "Se ha producido un error en el envío del formulario: {$mail->ErrorInfo}";
        }

    }
}
