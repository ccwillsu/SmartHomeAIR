<?
define("ARDUINO_ACCESS_PATH", "http://".ARDUINO_ACCESS_IP.":".ARDUINO_ACCESS_PORT."/".ARDUINO_ACCESS_KEY.".htm");

function CheckLogin()
{
    $LoginUser=$_COOKIE["SmartHomeUser"];
    $LoginPass=$_COOKIE["SmartHomePass"];
    if ($LoginUser == LOGIN_USERNAME && $LoginPass == LOGIN_PASSWORD)
    {
        
    }
    else
    {
        echo "<script><!--\n";
        echo "  window.location.href='login.php?page=".urlencode($_SERVER[PHP_SELF])."';\n";
        echo "--></script>\n";
        exit;
    }
}

function Login($User, $Pass)
{
    if ($User == LOGIN_USERNAME && $Pass == LOGIN_PASSWORD)
    {
        setcookie("SmartHomeUser",$User);
        setcookie("SmartHomePass",$Pass);
        return 1;
    }
    else
    {
        setcookie("SmartHomeUser","");
        setcookie("SmartHomePass","");
        return -1;
    }
    
}

function Logout()
{
    setcookie("SmartHomeUser","");
    setcookie("SmartHomePass","");
}



?>