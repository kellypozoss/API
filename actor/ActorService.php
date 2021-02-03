<?php
    require '../actor/PHPMailer/Exception.php';        
    require '../actor/PHPMailer/PHPMailer.php';
    require '../actor/PHPMailer/SMTP.php';
    require 'ActorBL.php';
    require '../usuario/UsuarioBL.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    class ActorService {
        private $actorDTO;
        private $actorBL;
        private $usuarioDTO;
        private $usuarioBL;

        public function __CONSTRUCT() {
            $this->actorDTO = new ActorDTO();
            $this->actorBL = new ActorBL();
            $this->usuarioDTO = new UsuarioDTO();
            $this->usuarioBL = new UsuarioBL();
        }

        public function Create($TOKEN, $Nombre, $Apellidos){
            $this->actorDTO->Nombre = $Nombre;
            $this->actorDTO->Apellidos = $Apellidos;
            if((($this->usuarioDTO = $this->usuarioBL->AUTH($TOKEN)) == true) && (($this->actorBL->Create($this->actorDTO)) > 0)){
                echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
                //echo json_encode(true, JSON_PRETTY_PRINT);

            }else{
                echo json_encode(array());   
                //echo json_encode(false, JSON_PRETTY_PRINT);

            }
        }

        public function Update($TOKEN, $Id, $Nombre, $Apellidos){
            $this->actorDTO->Id = $Id;
            $this->actorDTO->Nombre = $Nombre;
            $this->actorDTO->Apellidos = $Apellidos;
            if((($this->usuarioDTO = $this->usuarioBL->AUTH($TOKEN)) == true) && $this->actorBL->Update($this->actorDTO)){
                //echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
                echo json_encode(false, JSON_PRETTY_PRINT);
            }else{
                echo json_encode(true, JSON_PRETTY_PRINT);
            }
        }

        public function Delete($TOKEN, $Id){
            if((($this->usuarioDTO = $this->usuarioBL->AUTH($TOKEN)) == true) &&$this->actorBL->Delete($Id))
                 echo json_encode(true, JSON_PRETTY_PRINT);
            else
                echo json_encode(false, JSON_PRETTY_PRINT);
        }

        public function Read($TOKEN, $Id) {
            if(($this->usuarioDTO = $this->usuarioBL->AUTH($TOKEN)) == true) {
            $this->actorDTO = $this->actorBL->Read($Id);
            echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
            }
        }
    }
    
    $Obj = new ActorService();
    switch($_SERVER['REQUEST_METHOD']) {
       case 'GET':
            {
                if(!empty($_GET['TOKEN']) && empty($_GET['param']))
                    $Obj->Read($_GET['TOKEN'], 0);
                else
                {
                    if(!empty($_GET['TOKEN']) && is_numeric($_GET['param']))
                         $Obj->Read($_GET['TOKEN'], $_GET['param']); 
                    else
                        $actorDTO = new ActorDTO();
                        $actorDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"El parametro debe ser numerico"); 
                        echo json_encode($actorDTO->Response);   
                }
            }
        break;
        case 'POST':{
            if(!empty($_GET['TOKEN'])){
                $data = json_decode(file_get_contents('php://input'), true);
                $Obj->Create($_GET['TOKEN'], $data['Nombre'], $data['Apellidos']);

            }
            break;
        }
        case 'PUT':{
            if(!empty($_GET['TOKEN'])){
                $data = json_decode(file_get_contents('php://input'), true);
                $Obj->Update($_GET['TOKEN'], $data['Id'], $data['Nombre'], $data['Apellidos']);
            }
            break;
        }
        case 'DELETE':{
            if(!empty($_GET['param'])){
                if(!empty($_GET['TOKEN']) && is_numeric($_GET['param']))
                {
                    $Obj->delete($_GET['TOKEN'], $_GET['param']); 
                }
            }   
           else{
               $actorDTO = new ActorDTO();
               $actorDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"El parametro debe ser numerico"); 
               echo json_encode($actorDTO->Response);    
            }
            
        break;
            }
        case 'MAIL':{
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = "20193l001088@utcv.edu.mx";
                $mail->Password = "minigato1";
                $mail->setFrom('20193l001088@utcv.edu.mx', 'Kelly');
                $mail->addAddress('20193l001003@gmail.com', 'Danibb');
                $archivo ='Horario.pdf';
                $mail->AddAttachment($archivo, $archivo);
                $mail->Subject = 'Probando';
                $mail->msgHTML("<html>
                <body>
                <h1>Revisando conexión</h1>
                Aquí va el contenido
                <p>Esto es un parrafo de prueba</p>
                </body>
                </html");
                $mail->CharSet = 'UTF-8';
                $mail->IsHTML(true);
                if (!$mail->send())
                {
                    echo "Error al enviar el E-Mail: ".$mail->ErrorInfo;
                }
                else
                {
                    echo "E-Mail enviado";
                }
        }
        default:
        
            break;
    }
?>