<?php
    namespace DAOBD;

    use \Exception as Exception;
    use Models\Room as Room;    
    use DAOBD\Connection as Connection;

    class RoomDAOBD 
    {
        private $connection;
        private $tableName = "Rooms";

        public function Add($room)
        {
            try
            {
               // var_dump($room);
                $query = "INSERT INTO ".$this->tableName." (IdCinema, RoomName, RoomCapacity, RoomIs3D, RoomPrice, RoomAvailability) 
                VALUES (:IdCinema, :RoomName, :RoomCapacity, :RoomIs3D, :RoomPrice, :RoomAvailability );";
                
                $parameters["IdCinema"] = $room->getRoomCinema()->getCinemaId();
                $parameters["RoomName"] = $room->getRoomName();
                $parameters["RoomCapacity"] = $room->getRoomCapacity();
                $parameters["RoomIs3D"] = $room->getIs3d();
                $parameters["RoomPrice"] = $room->getRoomPrice();
                $parameters["RoomAvailability"] = $room->getRoomAvailability();
               

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);

    
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function GetAll()
        {
            try
            {
                $roomList = array();

                $query = "SELECT * FROM ".$this->tableName;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                   
                    $room = new Room();
                    $room->setRoomId($row["IdRoom"]);
                    $room->setRoomName($row["RoomName"]);
                    $room->setRoomCapacity($row["RoomCapacity"]);
                    $room->setIs3d($row["RoomIs3D"]);
                    $room->setRoomPrice($row["RoomPrice"]);
                    $room->setRoomAvailability($row["RoomAvailability"]);
                    ///falta agregar el cine


                    array_push($roomList, $room);
                }

                return $roomList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
    
        public function GetRoomByCinemas($cinemaID)
        {
           
            try
            {
                $roomList = array();

                
                $query = 'SELECT * FROM '.$this->tableName . ' WHERE IdCinema = "' . $cinemaID . '";';
                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                   
                    $room = new Room();
                    $room->setRoomId($row["IdRoom"]);
                    $room->setRoomName($row["RoomName"]);
                    $room->setRoomCapacity($row["RoomCapacity"]);
                    $room->setIs3D($row["RoomIs3D"]);
                    $room->setroomPrice($row["RoomPrice"]);
                    $room->setRoomAvailability($row["RoomAvailability"]);

                    array_push($roomList, $room);
                }

                return $roomList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

       public function getAvailable($cinemaID)
        {
            try
            {
                $roomList = array();

                
                $query = 'SELECT * FROM '.$this->tableName . ' WHERE RoomAvailability = "1" AND IdRoom = "' . $cinemaID .'";';
                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $room = new Room();
                    $room->setRoomId($row["IdRoom"]);
                    $room->setRoomName($row["RoomName"]);
                    $room->setRoomCapacity($row["RoomCapacity"]);
                    $room->setIs3D($row["RoomIs3D"]);
                    $room->setroomPrice($row["RoomPrice"]);
                    $room->setRoomAvailability($row["RoomAvailability"]);

                    array_push($roomList, $room);
                    
                }

                return $roomList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
        }
    
    
    