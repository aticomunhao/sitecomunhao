<?php
$url = $_SERVER["PHP_SELF"];
if(strtolower($url) == "/cesb/modulos/config/dbcclass.php"){
    header("Location: /cesb/");
}else{
    function conecPost(){
//        $con_string = "host= 192.168.1.137 port=5432 dbname=pessoal user=postgres password=scga2298";
        $con_string = "host= localhost port=5432 dbname=pessoal user=postgres password=postgres";
        if(function_exists("pg_pconnect")){ // para o caso de a extension=pgsql não estar habilitada no phpini
            if(@pg_connect($con_string)){
                $Con = @pg_connect($con_string); // or die("Fooo");
            }else{
                $Con = "Foo";
            }
        }else{
            $Con = "Foo";
        }
        return $Con;
    }
}