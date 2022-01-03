<?php 
    class Database {
        private $DB_USER = 'b2df0fbbf1e8f2';
        private $DB_PASSWORD = '00891f1d';

        private $connection;
        private $users = [];

        protected function connection_start(){
             
            $this->connection = new PDO("mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_28b7288f93e7ae1", $this->DB_USER, $this->DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }

        protected function insert($data = [], $query){
            $stmt = $this->connection->prepare($query);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                throw new Exception("Error Processing Request", 1);
            }
        }

        protected function get($query){
        
            try {
                $result = $this->connection->query($query);
                return $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                throw new Exception("Error Processing Request", 1);
                
            }
        }

        protected function loadAllUsers(){
            $dbUsers = $this->get("SELECT * FROM users");

            foreach ($dbUsers as $user) {
                array_push($this->users, $user);
            }
        }

    }
?>  