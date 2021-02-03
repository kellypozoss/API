<?php
    require 'GatewayBL.php';
    require '../actor/ActorBL.php';
    class GatewayService{
        private $actorDTO;
        private $actorBL;
        private $usuarioDTO;
        private $usuarioBL;
        private $gatewayDTO;
        private $gatewayBL;

        public function __construct(){
            $this->gatewayDTO = new GatewayDTO;
        }

        public function actor()
    
        $datos_post = http_build_query(
            array(
                'Usuario' => 'username',
                'Contrasena' => 'password'
            )
        );
        
        $opciones = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $datos_post
            )
        );
        
        $contexto = stream_context_create($opciones);
        
        $resultado = file_get_contents('http://localhost/UsuarioService.php', false, $contexto);


        public function Read($TOKEN, $Id) {
            if(($this->usuarioDTO = $this->usuarioBL->AUTH($TOKEN)) == true) {
            $this->actorDTO = $this->actorBL->Read($Id);
            echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
            }
        }
    }

?>