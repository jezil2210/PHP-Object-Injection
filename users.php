<?php

class ReadFile{
 
      public function __tostring(){
             return file_get_contents($this->filename);      
      }

}


class Users{

      public $username;
      public $isAdmin;

      public function PrintData(){
             if($this->isAdmin){
               echo $this->username . " is an admin\n"; 
             }else{
               echo $this->username . " is not an admin\n";
             }
      }
}

$obj = unserialize($_POST['jeje']);
$obj->PrintData();


?>


