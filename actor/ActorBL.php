<?php
    require_once '../Conexion.php';
    require '../DTO/ActorDTO.php';

    class ActorBL
    {
        private $conn;
        public function __Construct()
        {
            $this-> conn = new Conexion();
        }

        public function create($actorDTO)
        {
            $eror1 = false;
            $this-> conn -> OpenConnection();
            $connsql = $this -> conn -> GetConnection();  
            $lastInsertId = 0;

            try {
                if($connsql)
                {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "INSERT INTO actor VALUES(
                            default,
                            :first_name,
                            :last_name,
                            current_date
                        )"
                    );

                    $sqlStatment->bindParam(':first_name', $actorDTO->Nombre);
                    $sqlStatment->bindParam(':last_name', $actorDTO->Apellidos);
                    $sqlStatment->execute();

                    $lastInsertId = $connsql->lastInsertId();
                    $connsql->commit();
                    $eror1 = true;
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
                $eror1 = false;
            }
            return $lastInsertId;
            return $eror1;
        }

        public function Read($Id)
        {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();
            $arrayActor = new ArrayObject();
            $SQLQuery = "SELECT * FROM actor";
            $actorDTO = new ActorDTO();
            if($Id > 0)
                $SQLQuery = "SELECT * FROM actor WHERE actor_id ={$Id}";

            try {
                if($connsql)
                {
                    foreach($connsql->query($SQLQuery) as $row )
                    {
                        $actorDTO = new ActorDTO(); //inicializacion de una nueva instancia 
                        $actorDTO->Id = $row['actor_id'];
                        $actorDTO->Nombre = $row['first_name'];
                        $actorDTO->Apellidos = $row['last_name'];
                        $arrayActor->append($actorDTO); //tomar los datos de la columnas y mapear a propiedades del objeto DTO
                    }
                }
            } catch (PDOException $e) {
                
            }
            return $arrayActor;
        }

        public function Update($actorDTO)
        {
            $Error2 = false;
            $this-> conn -> OpenConnection();
            $connsql = $this -> conn -> GetConnection(); 

            try {
                if($connsql)
                {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "UPDATE actor SET
                            first_name = :first_name, 
                            last_name = :last_name,
                            last_update = current_date
                        WHERE actor_id = :id"
                    );

                    $sqlStatment->bindParam(':first_name', $actorDTO->Nombre);
                    $sqlStatment->bindParam(':last_name', $actorDTO->Apellidos);
                    $sqlStatment->bindParam(':id', $actorDTO->Id);
                    $sqlStatment->execute();

                    $connsql->commit();
                    $Error2 = true;
                    
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
                echo $E;
                $Error2 = false;
            }
        }

        public function Delete($Id)
        {
            $Error = false;
            $this-> conn -> OpenConnection();
            $connsql = $this -> conn -> GetConnection();  

            try {
                if($connsql)
                {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "DELETE FROM  actor 
                         WHERE actor_id = :id
                        "
                    );

                    $sqlStatment->bindParam(':id', $Id);
                    //$sqlStatment->bindParam(':first_name', $actorDTO->Nombre);
                    //$sqlStatment->bindParam(':last_name', $actorDTO->Apellidos);
                    $sqlStatment->execute();

                    $connsql->commit();
                    $Error = true;
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
                $Error = false;
            }

            return $Error;
        }
    }
    
    
    $actorDTO =new ActorDTO;
    $actorBL = new ActorBL();
    
    

    
    //$actorDTO->Id = 202;
    //$actorDTO->Nombre = "Armando";
    //$actorDTO->Apellidos = "Pozos";
    

    //Agergar actor
    //$actorBL->create($actorDTO);
    
    //Consultar actor
    //print_r($actorBL->Read(201));

    //Modificar actor
    //$actorDTO->Id = 205;
    //$actorDTO->Nombre = "Martin";
    //$actorDTO->Apellidos = "Juarez";
    //$actorBL->Update($actorDTO);

    //Eliminar
    /*$actorDTO->Id = 205;
    $actorDTO->Nombre = "Martin";
    $actorDTO->Apellidos = "Juarez";
    $actorBL->Delete($actorDTO->Id);*/
    

?>