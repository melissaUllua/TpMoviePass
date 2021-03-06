<?php
    namespace DAOBD;

    use \Exception as Exception;
    use \PDOException as PDOException;
    use Models\Show as Show;    
    use Models\Movie as Movie;    
    use DAOBD\Connection as Connection;
    use DAOBD\MovieDAOBD as MovieDAOBD;
    use DAOBD\RoomDAOBD as RoomDAOBD;
    use DAOBD\CinemaDAOBD as CinemaDAOBD;

   /*create table if not exists  Shows(
        IdShow int auto_increment,
        IdMovie int not null,
	    IdRoom int not null,
        ShowDate date not null,
        ShowTime time not null,
        constraint pk_show PRIMARY KEY(IdShow),
        constraint pfk_show_idMovie FOREIGN KEY(IdMovie) REFERENCES Movies(IdMovie) ON DELETE CASCADE ON UPDATE CASCADE,
        constraint pfk_show_idRoom FOREIGN KEY(IdRoom) REFERENCES Rooms(IdRoom) ON DELETE CASCADE ON UPDATE CASCADE

    );
     //siendo ShowDate "d.m.y" y ShowTime "00:00"
*/
    class ShowDAOBD implements IDAOBD
    {
        private $connection;
        private $tableName = "Shows";

        public function Add($show)
        {
            $message = "";
            try
            {
                $query = "INSERT INTO ".$this->tableName." (IdMovie, ShowDate, ShowTime, IdRoom) 
                VALUES (:IdMovie, :ShowDate, :ShowTime, :IdRoom);";
                
                $parameters["ShowDate"] = $show->getShowDate();
                $parameters["ShowTime"] = $show->getShowTime();
                $parameters["IdRoom"] = $show->getShowRoom()->getroomId();
                $parameters["IdMovie"] = $show->getShowMovie()->getId();
                

                $this->connection = Connection::GetInstance();
                $this->connection->ExecuteNonQuery($query, $parameters);
                $message = "Show added successfully";
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $message;
            }
        }

        public function GetAll()
        {
            $showList = array();
            try
            {
               

                $query = "SELECT * FROM ".$this->tableName;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                $movie_aux = new MovieDAOBD();
                $room_aux = new RoomDAOBD();
                $cinema_aux = new CinemaDAOBD();

                foreach ($resultSet as $row)
                {
                    $show = new Show();
                    $show->setShowId($row["IdShow"]);
                    $show->setShowMovie($movie_aux->searchById($row["IdMovie"]));
                    $room = $room_aux->getOneRoom($row["IdRoom"]);
                    //$room->setRoomCinema($cinema_aux->getOneCinema($room->getRoomCinema()->getCinemaId()));
                    $room->setRoomCinema($cinema_aux->getOneCinema($room->getRoomCinema()->getCinemaId()));
                    $show->setShowRoom($room);
                    $show->setShowDate($row["ShowDate"]);
                    $show->setShowTime($row["ShowTime"]);

                    array_push($showList, $show);
                }
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $showList;
            }
        }
       
        /*
            con la intención de mantener la nomenclatura, trae los shows futuros
         */
        public function GetAvailable()
        {
            $showList = array();
            try
            {
                $today = date("Y-m-d");
               // $now = date("H:i:s"); //necesito una subquery
                

                $query = "SELECT * FROM ".$this->tableName. " WHERE ShowDate >=  '". $today."' ;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                $movie_aux = new MovieDAOBD();
                $room_aux = new RoomDAOBD();
                $cinema_aux = new CinemaDAOBD();

                foreach ($resultSet as $row)
                {
                    $show = new Show();
                    $show->setShowId($row["IdShow"]);
                    $show->setShowMovie($movie_aux->searchById($row["IdMovie"]));
                    $room = $room_aux->getOneRoom($row["IdRoom"]);
                    $room->setRoomCinema($cinema_aux->getOneCinema($room->getRoomCinema()->getCinemaId()));
                    $show->setShowRoom($room);
                    $show->setShowDate($row["ShowDate"]);
                    $show->setShowTime($row["ShowTime"]);

                    array_push($showList, $show);
                }
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $showList;
            }
        }
        public function GetOneById($showID)    //devuelve una función a partir de su ID
        {
            $show = new Show();
            try
            {

                $query = 'SELECT * FROM '.$this->tableName . ' WHERE Idshow =' . "$showID";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                $movie_aux = new MovieDAOBD();
                $room_aux = new RoomDAOBD();
                

                if ($resultSet)
                {                
                    $row = $resultSet['0'];
                    $show->setShowId($row["IdShow"]);
                    $movie = $movie_aux->searchById($row["IdMovie"]);
                    $show->setShowMovie($movie);
                    $show->setShowDate($row["ShowDate"]);
                    $show->setShowTime($row["ShowTime"]);
                    $room = $room_aux->getOneRoom($row["IdRoom"]);
                    $show->setShowRoom($room);
                }
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $show;
            }

        }

        /*
        Recibe una fecha, un horario y una sala. Comprueba que no existan funciones para esa sala en el mismo momento
    * Retorna true si encuentra una coincidencia, o false de no ser así.
    */
    public function ExistsShowByDateTime($showDate, $showTime, $IdRoom)
    {
        $flag = null;
        try
            {
               // $room = new Room();
                $query = "SELECT * FROM " . $this->tableName . " WHERE ShowDate = '" . $showDate ."' AND ShowTime = '" .$showTime ."' AND IdRoom = " .$IdRoom .";";
                                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                if(!empty($resultSet))
                {         
                    $flag = true;
                   
                } else {
                    $flag = false;
                }
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $flag;
            }
        }

        /* Busca si la película ya está en exhibición en otra sala para ese día
        Retorna true si encuentra una coincidencia, o false de no ser así.
        */
        public function ExistsMovieInRoom($showDate, $IdMovie, $IdRoom)
        {
            $flag = null;
            try
                {
                   // $room = new Room();
                    $query = "SELECT * FROM " . $this->tableName . " WHERE ShowDate = '" . $showDate ."' AND IdMovie = " .$IdMovie ." AND IdRoom != " .$IdRoom .";";
  
                    $this->connection = Connection::GetInstance();
    
                    $resultSet = $this->connection->Execute($query);
                    
                    if(!empty($resultSet))
                    {  
                        $flag = true;
                       
                    } else {
                        $flag = false;
                    }
                    
                }
                catch(PDOException $pdoE){
                    throw $pdoE;
                }
                catch(Exception $ex)
                {
                    throw $ex;
                }
                finally {
                    return $flag;
                }
            }
    /* 
    Recibe una función y compara los horarios con funciones ya programadas para habilitar qque se agregue una nueva función
    retorna true si la función puede agregarse, o false si los datos coinciden con alguna función
    */
        public function checkTime(Show $show){
         
            try {
                $query = "SELECT * FROM " . $this->tableName . " WHERE ShowDate = '". $show->getShowDate() ."' AND IdRoom = " .$show->getShowRoom()->getRoomId() .";";
                //me traigo todas las funciones de la sala para ese día
                $this->connection = Connection::GetInstance();
    
                $resultSet = $this->connection->Execute($query);
      
                
                if(!empty($resultSet)) { //si la sala tiene funciones asignadas, establezco los datos para comparar
                
                    $movieDao = new MovieDAOBD();
                    $movieShow = $movieDao->searchById($show->getShowMovie()->getId()); //en base al Id de la función por parámetro, traigo una película
                    $duration = $movieShow->getDuration(); //esto retorna un int equivalente a los minutos
                    $duration = $duration + 15; //le sumo 15 minutos

                    $startShow = $show->getShowTime(); //horario de inicio de la función por parámetro
                    $endShow = date("H:i:s", strtotime('+' . $duration . 'minutes', strtotime($startShow))); //horario final, incluyendo los 15 minutos de la función por parámentro  

                    foreach ($resultSet as $show_aux){ //recorro y comparo horarios de inicio y finalización

                        $movieShow_aux = $movieDao->searchById($show_aux["IdMovie"]); //en base al Id de la función por parámetro, traigo una película
                        $duration_aux = $movieShow_aux->getDuration(); //esto retorna un int equivalente a los minutos
                        $duration_aux = $duration_aux + 15; //le sumo 15 minutos

                        $startShow_aux = $show_aux["ShowTime"];
                        $endShow_aux = date("H:i:s", strtotime('+' . $duration_aux . 'minutes', strtotime($startShow_aux)));
                        if (($startShow > $endShow_aux) || ($startShow_aux > $endShow)){
                            $flag = true; //si el horario en que finaliza 

                        } else {
                            $flag = false;
                        }

                    }
                } else { //si no había funciones
                    $flag = true; //no hay inconvenientes para agregar la función nueva
                }

            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            finally {
                return $flag;
            }
            
        }


        public function GetBillboard()     //devuelve un array de objetos movie con al menos un show programado
        {
            $moviesList = array();
            $today = date("Y-m-d");
            $query = 'SELECT DISTINCT IdMovie FROM '.$this->tableName . ' WHERE ShowDate >=  "'. $today.'" ;';

            try{
                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);

                if($resultSet != null){

                    foreach($resultSet as $row){
                        $movie = new Movie;
                        $movieDao = new MovieDAOBD;

                        $movie = $movieDao->searchById($row['IdMovie']);

                        array_push($moviesList, $movie);
                    }

                }
            }
            catch(PDOException $pdoE){
                throw $pdoE;
            }
            catch(Exception $ex){
                throw $ex;
            }
            finally {
                return $moviesList;
            }

        }


        public function getShowsByMovie($idMovie)     ///devuelve todos los shows correspondientes a una movie. FALTA AGREGAR LA COMPARACION DE FECHA DE HOY CONTRA FECHA DE INICIO
        {

            $showList = array();
           $query = "SELECT * FROM " . $this->tableName . " WHERE IdMovie = " . $idMovie . ";";

            try{
                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);


                if($resultSet != null){

                    $movie_aux = new MovieDAOBD();
                    $room_aux = new RoomDAOBD();
                    $cinema_aux = new CinemaDAOBD();
                    $todayDay = date("Y-m-d");
                    $todayTime = date("H:i:s");
    
                    foreach ($resultSet as $row)
                    {
                        $show = new Show();
                        $show->setShowId($row["IdShow"]);
                        $show->setShowMovie($movie_aux->searchById($row["IdMovie"]));
                        $room = $room_aux->getOneRoom($row["IdRoom"]);
                        $show->setShowRoom($room);
                        $show->setShowDate($row["ShowDate"]);
                        $show->setShowTime($row["ShowTime"]);

                        if($show->getShowDate() > $todayDay){
                                array_push($showList, $show);                     

                        } else if ($show->getShowDate() == $todayDay){
                            if($show->getShowTime() > $todayTime){
                                array_push($showList, $show);                     
                            }
                        }
                    }
                }
            }
                catch(PDOException $pdoE){
                    throw $pdoE;
                }
        catch(Exception $ex){
                throw $ex;
            }
            finally {
                return $showList;
            }

        }
        /* Busca si una sala tiene alguna funcion futura
        Retorna true si encuentra una coincidencia, o false de no ser así.
        */
        public function IsAnyFutureShowInRoom($IdRoom)
        {
            $flag = null;

            try
                {
                    $query = "SELECT * FROM " . $this->tableName . " WHERE ShowDate >= '" . date("Y-m-d") ."' AND IdRoom = " .$IdRoom .";";   //busca en BDD de show si hay algun registro con fecha mayor o igual que hoy y que coincida el IDROOM
  
                    $this->connection = Connection::GetInstance();
    
                    $resultSet = $this->connection->Execute($query);
                    
                    if(!empty($resultSet))
                    {  
                        $flag = true;     //si no esta vacio significa que encontró algo
                       
                    } else {
                        $flag = false;
                    }
                }
                catch(PDOException $pdoE){
                    throw $pdoE;
                }
                catch(Exception $ex)
                {
                    throw $ex;
                }
                finally {       
                    return $flag;
                }
            }
            /*
                    Recibe un idShow y borra la función correspondiente 
             */

            public function DeleteShow($idShow)   //retorna 0 si pudo, 1 si hubo un error, (2 si el address ya existe- deprecated)
            {
            
                        try{
                            
                            $query =  ' DELETE from '.$this->tableName.'  WHERE IdShow= '.$idShow.';';
                            
                            $this->connection = Connection::GetInstance();
                            $this->connection->ExecuteNonQuery($query);
                        }
                        catch(PDOException $pdoE){
                            throw $pdoE;
                        }
                
                        catch(Exception $ex){
                            throw $ex;
                        }                 

            }

    }
    

?>