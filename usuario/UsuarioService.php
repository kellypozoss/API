<?php
    require 'UsuarioBL.php';
    class UsuarioService {
        private $usuarioDTO;
        private $usuarioBL;

        public function __CONSTRUCT() {
            $this->usuarioDTO = new UsuarioDTO();
            $this->usuarioBL = new UsuarioBL();
        }


        public function Create($Usuario, $Contrasena){
            $this->usuarioDTO->username = $Usuario;
            $this->usuarioDTO->password = $Contrasena;
            if($this->usuarioDTO = $this->usuarioBL && ($this->usuarioBL->Create($this->usuarioDTO)> 0))
              {
                 echo json_encode($this->usuarioDTO, JSON_PRETTY_PRINT);
                //echo json_encode(true, JSON_PRETTY_PRINT);

            }else{
                echo json_encode(array());   
                //echo json_encode(false, JSON_PRETTY_PRINT);

            }
        }

        public function Update( $Id, $Usuario, $Contrasena){
            $this->usuarioDTO->id = $Id;
            $this->usuarioDTO->username = $Usuario;
            $this->usuarioDTO->password = $Contrasena;
            if($this -> usuarioBL -> Update($this -> usuarioDTO) > 0)
                echo json_encode($this -> usuarioDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
                //echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
                
            
        }

        public function Delete($Id){
            $this->usuarioDTO->id = $Id;
            if($this -> usuarioBL -> Delete($this ->usuarioDTO->id) > 0)
                echo json_encode($this -> usuarioDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
            
        }
        public function Read($Id) {
             
            $this->usuarioDTO = $this->usuarioBL->Read($Id);
            echo json_encode($this->usuarioDTO, JSON_PRETTY_PRINT);
            
        }

    }
    
    $Obj = new UsuarioService();
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if( empty($_GET['param']))
                    $Obj->Read(0);
                else
                {
                    if( is_numeric($_GET['param']))
                         $Obj->Read( $_GET['param']); 
                    else
                    {
                        $usuarioDTO = new UsuarioDTO();
                        $usuarioDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"El parametro debe ser numerico"); 
                        echo json_encode($usuarioDTO->Response);  
                    }
                }
            break;
        case 'POST':
            {
                // Para verificar que los $_POST contengan texto se utiliza el siguiente if
                //isset comprueba si una variable esta vacia 
                $data = json_decode(file_get_contents('php://input'), true);
                if((isset($data['username']) && !empty($data['username'])) && (isset($data['password']) && !empty($data['password'])))
                    $Obj->Create( $data['username'], $data['password']);
                else 
                {
                    $usuarioDTO = new UsuarioDTO();
                    $usuarioDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"Faltan datos"); 
                    echo json_encode($usuarioDTO->Response);
                }
                break;
            }
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
                if((isset($data['Id']) && !empty($data['Id'])) && (isset($data['Usuario']) && !empty($data['Usuario'])) && (isset($data['Contrasena']) && !empty($data['Contrasena'])))
                    $Obj->Update( $data['Id'], $data['Usuario'], $data['Contrasena']);
                else 
                {
                    $usuarioDTO = new UsuarioDTO();
                    $usuarioDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"Faltan datos");
                    echo json_encode($usuarioDTO->Response);  
                }
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if((isset($data['Id']) && !empty($data['Id']))) 
                $Obj->Delete( $data['Id']);
            else 
            {
                $usuarioDTO = new UsuarioDTO();
                $usuarioDTO->Response = array('CODE'=>"ERROR", 'TEXT'=>"Faltan datos"); 
                echo json_encode($usuarioDTO->Response);  
            }
            break;
        default:
            Echo"OTRO";
            break;
    }
?>