<?php
    require '../DTO/GatewayDTO.php';
    class GatewayBL{


        public function AUTH($username){
            $this->conn->OpenConnection();
                $connsql = $this->conn->GetConnection();
                $confirmacion = false;
                $usuarioDTO = new UsuarioDTO();
                $sqlQuery = "SELECT * FROM usuario WHERE username = '{$username}'";
    
                try {
                    if($connsql) {
                        foreach ($connsql->query($sqlQuery) as $row) {
                            $usuarioDTO->id= $row['id'];
                        }
    
                        if($usuarioDTO->id > 0) {
                            $confirmacion = true;
                        } 
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
                return $confirmacion;
        }
    }  


?>
