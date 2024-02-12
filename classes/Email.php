<?php 
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

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
        $mail = new PHPMailer();
        //Configurar SMTP (Protocolo para envio emails)
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Confirma tu Cuenta";
         //Habilitar HTML
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
        //Construimos el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send();
    }

    public function enviarInstrucciones(){
        //Crear una instancia de PHPMailer
        $mail = new PHPMailer();
        //Configurar SMTP (Protocolo para envio emails)
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Reestablece tu Contraseña";
         //Habilitar HTML
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
        //Construimos el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/recuperar?token=" . $this->token . "'>Reestablecer Contraseña</a></p>";
        $contenido .= "<p>Si tú no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send(); 
    }
}


