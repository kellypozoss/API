<?php
    require 'GatewayBL.php';
    class GatewayService{
        private $usuarioDTO;
        private $usuarioBL;
        private $gatewayDTO;
        private $gatewayBL;

        public function __construct(){
            $this->gatewayDTO = new GatewayDTO;
        }

        public function usuario()
    
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


        public function Read($Id) {
            if(($this->usuarioDTO = $this->usuarioBL->AUTH($username)) == true) {
            $this->usuarioDTO = $this->usuarioBL->Read($id);
            echo json_encode($this->usuarioDTO, JSON_PRETTY_PRINT);
            }
        }
    }

?>
