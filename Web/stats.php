<?
require_once('server.conf.php');
require_once('function.php');

$Cmd = $_GET['cmd'];
CheckLogin();

$DB_Link = @mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PSWD) or Die(mysql_error());
mysql_query("SET NAMES 'utf8'");
mysql_select_db(MYSQL_DB,$DB_Link);

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-language" content="en" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="apple-touch-icon" href="images/icon.png" />
	<link rel="apple-touch-startup-image" href="images/splash.png">
	
	<link rel="stylesheet" media="screen" type="text/css" href="css/reset.css" />	
	<link rel="stylesheet" media="screen" type="text/css" href="css/main.css" />
	<link rel="shortcut icon" href="home.ico"/>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.flot.js"></script>	
	<script type="text/javascript" src="js/main.js"></script>
	
	<title>Smart Home System</title>
</head>

<body onorientationchange="Orientation();">

<div id="wrapper">

	<!-- HEADER -->
	<div id="header">
	
		<h1 id="logo">Stats</h1>

		<p class="header-button left"><a href="javascript:history.go(-1)" onclick="return link(this)">Back</a></p>
		
		<p class="header-button"><a href="login.php?cmd=logout" onclick="return link(this)">Logout</a></p>
		
	</div> <!-- /header -->
	
	<!-- CONTENT -->
	<div id="content">

		<!-- STATS -->
		<div class="content-box">		

			<table class="stats">
				<tr>
					<th>Location</th>
					<th>Temperature</th>
					<th>Time</th>
				</tr>
<?
$sql = "select log_temp, log_time FROM smarthome_temp_log where log_temp > 0 order by log_no desc limit 1;";
$result = mysql_query($sql,$DB_Link) or Die(mysql_error());
$row = mysql_fetch_array($result);
echo "<tr>\n";
echo "<td>Bedroom</td><td>".$row[0]." ℃</td><td>".substr($row[1], 11, 5)." </td>\n";
echo "</tr>\n";
?>
			</table>
		
		</div> <!-- /content-box -->
	
		<!-- GRAPH -->
		<div class="content-box">		

			<div id="graph"></div>

			<script id="source" type="text/javascript">
				$(document).ready(function() {
					//var visits = [[1,435], [2,366], [3,75], [4,165], [5,275], [6,335], [7,789], [8,924], [9,955], [10,1070], [11,841], [12,979]];
					//var temps = [[8,32.1], [8.5,32.2], [9,32.3], [9.5,32.4], [10,32.5], [10.5,32.6], [11,32.7], [11.5,32.8], [12,32.9], [12.5,32.7], [13,32.5], [13.5,32.3]];
<?
$temp_string = "var temps = [";
$temp_str = "";
$sql = "select log_temp, log_time FROM smarthome_temp_log where log_temp > 0 order by log_no desc limit 25;";
$result = mysql_query($sql,$DB_Link) or Die(mysql_error());
$i = 0;
while( $row = mysql_fetch_array($result) )
{
    //echo $row[1]."<br>\n";
    $t_ary = explode(':', substr($row[1], 11, 4));
    if ($i == 0)
        $temp_str = "['".(24-$i)."',".$row[0]."]" . $temp_str;
    else
        $temp_str = "['".(24-$i)."',".$row[0]."], " . $temp_str;
    $i++;
    $h = substr($row[1], 11, 2);
}
$t_shift = ($t_ary[1] == '3') ? 1 : 0;
if ($t_shift) $h++;
$temp_string = "var temps = [" . $temp_str . "];";
echo $temp_string."\n";
?>

					$('#graph').css({height:'225px', width:'100%'});
					$.plot($('#graph'),[
						//{label:'', data:visits, color:'#00CCFF', shadowSize:'3'},
						{label:'', data:temps, color:'#FF9000', shadowSize:'5'},
					],
					{   lines:{show:true},
					    points:{show:true},
					    bars:{show:false},
					    grid:{backgroundColor:{colors:["#FFF", "#F5F5F5"]}, color:'#777', tickColor:'#CCC', borderWidth:1, labelMargin:10},
					    xaxis: {
<?
echo "ticks: [";
for ($i = 0; $i <= 25; $i++)
{
    if ($i % 2 == $t_shift)
    {
        if ($i <= 24)
        {
            printf("[%d, \"%02d\"],", $i, $h);
        }else{
            printf("[%d, \"%02d\"]", $i, $h);
        }
        $h++;
        if ($h >= 24)
            $h = 0;
    }
}
echo "]";
?>

                            //ticks: [[0, "20h"], [2, "21h"], [4, "22h"], [6, "23h"], [8, "00h"], [10, "01h"], [12, "02h"], [14, "03h"], [16, "04h"], [18, "05h"], [20, "06h"], [22, "07h"], [24, "08h"]]
                        }
					});
				});
			</script>		
		
		</div>
		
		<!-- PAGINATION -->
		<!--<p class="pagination box">
			<a href="#" onclick="return link(this)" class="pagination-step">&laquo;</a>
			<a href="#" onclick="return link(this)">Mon</a>
			<strong>Today</strong>
			<a href="#" onclick="return link(this)">Wed</a>
			<a href="#" onclick="return link(this)" class="pagination-step">&raquo;</a>
		</p>-->

		<!-- NAVIGATION -->
		<h2 class="t-center">Navigation</h2>
		
		<ul id="nav" class="box">
			<li class="ico-dashboard"><a href="index.php" onclick="return link(this)">Dashboard</a></li>
			<li class="ico-stats active"><a href="stats.php" onclick="return link(this)">Stats</a></li> <!-- Active page (.active) -->
			<li class="ico-settings"><a href="settings.php" onclick="return link(this)">Settings</a></li>
			<!--
			<li class="ico-pages"><a href="pages.html" onclick="return link(this)">Pages</a></li>
			<li class="ico-categories"><a href="categories.html" onclick="return link(this)">Categories</a></li>
			<li class="ico-images"><a href="images.html" onclick="return link(this)">Images</a></li>			
			<li class="ico-contacts"><a href="contacts.html" onclick="return link(this)">Contacts</a></li>
			<li class="ico-users"><a href="users.html" onclick="return link(this)">Users</a></li>
			<li class="ico-comments"><a href="comments.html" onclick="return link(this)">Comments</a></li>
			<li class="ico-search"><a href="search.html" onclick="return link(this)">Search</a></li>
			-->
			<br><br><br><br>
		</ul>
		
		<!--<p class="nomb t-center smaller grey">Icons by <a href="http://www.woothemes.com/2009/09/woofunction-178-amazing-web-design-icons/">WeFunction</a></p>-->
		
	</div> <!-- /content -->
	
	<!-- FOOTER -->
	<div id="footer">
	
		<p id="footer-button"><a onclick="jQuery('html,body').animate({scrollTop:0},'slow');" href="javascript:void(0);">Back on top</a></p>
	
		<p>&copy; 2014 <a href="http://cc.beingo.net/">Smart Home AIR</a></p>
		
	</div> <!-- /footer -->

</div> <!-- /wrapper -->

</body>
</html>