<?
require_once('server.conf.php');
require_once('function.php');
//include "Snoopy.class.php";

/*
$url = ARDUINO_ACCESS_PATH.'?cmd=2';
$lines_array = file($url);
print_r($lines_array);
*/

// 建立CURL連線
$ch = curl_init();

// 設定擷取的URL網址
//curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_URL, ARDUINO_ACCESS_PATH."?cmd=2");
//Header
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
//curl_setopt($ch, CURLOPT_REFERER, "http://cc.beingo.net");
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,6);

curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

// 執行
for ($i = 0; $i < 5; $i++)
{
    $temp=curl_exec($ch);
    if(curl_errno($ch))
    {
        echo 'error:' . curl_errno($ch) . " - " . curl_error($ch);
    }
    if ($temp != '')
        break;
    sleep(10);
}
//echo $temp;

$DB_Link = @mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PSWD) or Die(mysql_error());
mysql_query("SET NAMES 'utf8'");
mysql_select_db(MYSQL_DB,$DB_Link);

$sql = "select log_no, log_temp FROM smarthome_temp_log where log_temp > 0 order by log_no desc limit 1;";
$result = mysql_query($sql,$DB_Link) or Die(mysql_error());
$row = mysql_fetch_array($result);
$last_temp = $row[1];
$last_no = $row[0];

if ($temp == '')
{
    $temp = $last_temp;
}else if ($temp == $last_temp){
    $sql = "select log_temp FROM smarthome_temp_log where log_no = '".($last_no - 1)."';";
    $result = mysql_query($sql,$DB_Link) or Die(mysql_error());
    $row = mysql_fetch_array($result);
    $prev_temp = $row[0];
    if ($prev_temp > $last_temp)
    {
        $temp = $temp - 0.01;
    }else{
        $temp = $temp + 0.01;
    }
}else{
    /*
    //$temp = 33;
    $sql = "select log_no, log_temp FROM smarthome_temp_log where log_temp = '".$last_temp."' order by log_no desc;";
    $result = mysql_query($sql,$DB_Link) or Die(mysql_error());
    $check_no = $last_no - 1;
    $modify_cnt = 0;
    $modify_no = $last_no;
    while( $row = mysql_fetch_array($result) )
    {
        if ($row[0] == $last_no)
        {
            continue;
        }else if ($row[0] == $check_no && $row[1] == $last_temp){
            $check_no--;
            $modify_cnt++;
        }else{
            break;
        }
    }
    $avg_diff = abs($temp - $last_temp) / ($modify + 1);
    for ($i = 0; $i < $modify_cnt; $i++)
    {
        $sql = "update `smarthome_temp_log` set `log_temp` = '".$temp."' where log_no = '".$modify_no."';";
        $modify_no--;
        $result = mysql_query($sql,$DB_Link);

    }
    */
}

// 關閉CURL連線
curl_close($ch);

$sql = "insert into `smarthome_temp_log` (`log_temp` ,`log_time`) values('".$temp."',NOW())";
if (0)
    echo "".$sql."<br>\n";
$result = mysql_query($sql,$DB_Link);

?>