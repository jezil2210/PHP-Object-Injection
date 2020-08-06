# PHP-Object-Injection

   PHP Object Injection enables the manipulation of an object content that shall be unserialized using the PHP unserialize() function. Thus, to fisrt understand the injection we may first understand the functions serialialize() and unserialize().
   
# Serealization and Deserealization.
   
   Developing web applications sometimes require to transfer data for storing, communication, logging, etc. This way the concepts of serealization and deserealization are born.<br>
     — Serialization is a process that converts an object into a specific structure data format such as convert Java Entity class to JSON format for sending via communication with other services or clients or maybe to storage.<br>
     — Deserialization is a process that converts data format into an object such as client sends requests as JSON data format and back end service convert it to Java Entity Class.<br>
   When receiving untrusted data without sufficient data verification a common vulnerability can be exploited, "Insecure Deserialization" that is in OWASP top 10 Web. 
   
# Exploiting Insecure Deserealization.   
   Now that we know the concepts let's dive into the practice. Firsts things first, let's see how a serialized object looks like, we have a simple class "Users" that have two attributes(username, and isAdmin) and one simple method(PrintData), that verify if the user is or not an admin, then i just create an object and set the values of the attributes, the user is "JeJe" and isn't and admin<br>
   
   ```php
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

$obj = new Users();
$obj->username = 'JeJe';
$obj->isAdmin = False;

echo serialize($obj);

```

After the echo, using the php interpreter the output is:<br> 

![Alt text](/<images/1.png)





   
   
   
