<?php


include_once "model/CRUD.php";





class Login{


    private static $dataConfig;

    private static function obtenerDataConfig(){
        if(self::$dataConfig== null){
            $urlJson=trim(__FILE__, "function.php")."config.json";
            $json=file_get_contents($urlJson);
            self::$dataConfig=json_decode($json, true);
        }
        return self::$dataConfig;
    }

    public static function returnUrl($url){
        $data=self::obtenerDataConfig();
        if($url ==="login")
            return $data["urlLogin"];
        else if($url ==="register")
            return $data["urlRegister"];
        else 
            return $data["urlHome"];
    }

    public static function authenticationLoginStar(){
        $data=self::obtenerDataConfig();
        if(!isset($_SESSION["user"])){
            header("Location: ".$data["urlHome"]);
        }
    }

    public static function authenticaAccess($username, $password){
        $data=self::obtenerDataConfig();
        if (isset($_SESSION['user'])) {
            header("Location: ".$data["urlStar"]);
            exit;
        }

        $access=new Table("accesos", BD::conecction());     
        $users=$access->readAllData();
        
        foreach ($users as $user) {
            if ($user['user'] === $username &&  password_verify($password, $user['password'])) {

                $_SESSION['user'] = $username;
                header("Location: ".$data["urlStar"]);
                exit;
            }
        }
        return "Error no ahi coincidencia";
    }

    public static function register($username, $password){
        if(empty($username) || empty($password))
            return "Todos los campos son obligatorios";
        else
        {
            $access=new Table("accesos", BD::conecction());
            $users=$access->readAllData();
    
            foreach($users as $user){
                if($user["user"]===$username){
                   return "El usuario ya existe";
                    break;
                }
               
            }
        }
        $data=self::obtenerDataConfig();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $access->insertData(["user" =>$username, "password" => $hashedPassword]);
        $_SESSION["user"]=$username;
        header("Location: ".$data["urlStar"]);
        exit;
  
    }

    public static function   logOut(){
        session_unset();
        session_destroy();
        $data=self::obtenerDataConfig();
        $urlJson=trim(__FILE__, "function.php")."config.json";
        $json=file_get_contents($urlJson);
        $data=json_decode($json, true);
        header("Location: ".$data["urlHome"]);

    }
}




?>

