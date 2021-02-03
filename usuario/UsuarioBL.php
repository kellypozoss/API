
<?php

require_once '../Conexion.php';
require '../DTO/UsuarioDTO.php';

class UsuarioBL{
    private $conn;
    public function __construct()
    {
        $this-> conn = new conexion();
    } 

    public function create($usuarioDTO)
    {
        $this-> conn->OpenConnection();
        $connsql = $this-> conn->GetConnection();
        $lastInsertId = 0;

        try {
            if($connsql){
                $connsql ->beginTransaction();
                $sqlStatement = $connsql-> prepare(
                    "INSERT INTO usuario VALUES (
                            DEFAULT, 
                            :usuario,
                            :contrasena
                            )"
                        );
                $sqlStatement->bindParam(':usuario', $usuarioDTO->username);
                $sqlStatement->bindParam(':contrasena', $usuarioDTO->password);
                $sqlStatement->execute();

                $lastInsertId = $connsql->lastInsertId();
                $connsql->commit();
            }
        }catch(PDOException $e){
            $connsql -> rollBack();
        }
        return $lastInsertId;
    }



    public function Read($Id)
    {
        $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();
            $arrayUsuario = new ArrayObject();
            $SQLQuery = "SELECT * FROM usuario";
            $usuarioDTO = new UsuarioDTO();
           
            if($Id > 0)
                $SQLQuery = "SELECT * FROM usuario WHERE id ={$Id}";

            try {
                if($connsql)
                {
                    foreach($connsql->query($SQLQuery) as $row )
                    {
                        $usuarioDTO = new UsuarioDTO(); //inicializacion de una nueva instancia 
                        $usuarioDTO->Id = $row['id_usuario'];
                        $usuarioDTO->Usuario = $row['usuario'];
                        $usuarioDTO->Contrasena = $row['contrasena'];
                        $arrayUsuario->append($usuarioDTO); //tomar los datos de la columnas y mapear a propiedades del objeto DTO
                    }
                }
            } catch (PDOException $e) {
                
            }
            return $arrayUsuario;

    }


    public function Update($usuarioDTO)
    {
        $this->conn->OpenConnection();
        $connsql = $this->conn->GetConnection();

        try
        {
            if($connsql)
            {
                $connsql->beginTransaction();
                $sqlStatement = $connsql-> prepare(
                    "UPDATE usuario SET 
                    usuario =  :usuario,
                    contrasena =  :contrasena
                    WHERE id_usuario = :id_usuario"
                );
                $sqlStatement->bindParam(':id_usuario', $usuarioDTO->id);
                $sqlStatement->bindParam(':usuario', $usuarioDTO->username);
                $sqlStatement->bindParam(':contrasena', $usuarioDTO->password );
                $sqlStatement->execute();
                $connsql ->commit();

            }
        }catch(PDOException $e){
            $connsql ->rollBack();
        }
    }


    public function Delete($id)
    {
        $this->conn->OpenConnection();
        $connsql = $this->conn->GetConnection();

        try
        {

        if($connsql)
        {
            $connsql->beginTransaction();
            $sqlStatement = $connsql->prepare(
                "DELETE FROM usuario WHERE id_usuario = :id_usuario"

            );
            $sqlStatement->bindParam(':id_usuario', $id);
            $sqlStatement->execute();
            $connsql ->commit();
        }

        }catch(PDOException $e){
            $connsql->rollBack();
        }
    }

}

?>