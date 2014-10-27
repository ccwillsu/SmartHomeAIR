<?
require_once('server.conf.php');
require_once('function.php');

$Cmd = $_GET['cmd'];
$Temp = $_GET['temp'];
CheckLogin();
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
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" language="javascript">
    <!--
	    
	function ExecCmd(cmd)
	{
		if (cmd == '1')
		{
		    document.getElementById('res').innerHTML = "<img src=images/loading_open.gif>";
            $.ajax({url:"<?=ARDUINO_ACCESS_PATH?>?cmd=1",timeout:<?=ARDUINO_ACCESS_TIMEOUT?>,
            success:function(result){
                GetResult('<font color=#cc0>Opened</font>');
            },
            error:function(){
                GetResult('<font color=#cc0><b>Something wrong!!</b></font>');
            }
            });
		}else{
		    document.getElementById('res').innerHTML = "<img src=images/loading_close.gif>";
            $.ajax({url:"<?=ARDUINO_ACCESS_PATH?>?cmd=0",timeout:<?=ARDUINO_ACCESS_TIMEOUT?>,
            success:function(result){
                GetResult('<font color=red>Closed</font>');
            },
            error:function(){
                GetResult('<font color=red><b>Something wrong!!</b></font>');
            }
            });
		}
	}
	function GetResult(res)
	{
	    //alert(res);
	    //if (res.localeCompare('Opened') || res.localeCompare('Closed'))
        document.getElementById('res').innerHTML = res;
	}
    -->
    </script>
	<title>Smart Home System</title>

</head>

<body onorientationchange="Orientation();">

<div id="wrapper">

	<!-- HEADER -->
	<div id="header">
	
		<h1 id="logo">Dashboard</h1>

		<p class="header-button left"><a href="javascript:history.go(-1)" onclick="return link(this)">Back</a></p>
		
		<p class="header-button"><a href="login.php?cmd=logout" onclick="return link(this)">Logout</a></p>
		
	</div> <!-- /header -->
	
	<!-- CONTENT -->
	<div id="content">

<?

if ($Cmd != '')
{
    $DB_Link = @mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PSWD) or Die(mysql_error());
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db(MYSQL_DB,$DB_Link);

    if ($Cmd == '1')
    {
        $sql = "insert into `smarthome_operation_log` (`log_op` ,`log_time`) values('1',NOW())";
        $result = mysql_query($sql,$DB_Link);
?>
		<div class="content-box ok">
			<p>Changes have been saved.<iframe width=1 height=1 src="<?=ARDUINO_ACCESS_PATH?>?cmd=1"></iframe></p>
		</div>
<?
    }else{
        $sql = "insert into `smarthome_operation_log` (`log_op` ,`log_time`) values('0',NOW())";
        $result = mysql_query($sql,$DB_Link);
?>
		<div class="content-box ok">
			<p>Changes have been saved.<iframe width=1 height=1 src="<?=ARDUINO_ACCESS_PATH?>?cmd=0"></iframe></p>
		</div>
<?
    }


}
else
{
?>

<?
}
?>

<?
if ($Cmd == '')
{
?>
		<h1 id="logo"><span id=temp_icon><img src=images/Temperature-5-32.png></span><span id=temp><img src=images/loading1.gif></span></h1>
<?
}else{
?>
		<!--<h1 id="logo"><?=$Temp?>℃</h1>-->
<?
}
?>
		<center><h1 id="logo">Air Conditioner</h1><span id=res></span>
		<table class="air-box"><tr>
		<td align=center class="air-td"><img src=images/on.png onclick="ExecCmd('1');" width=48 style="cursor: pointer;"><font color=#aa0>On</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src=images/off.png onclick="ExecCmd('0');" width=48 style="cursor: pointer;"><font color=red>Off</font></td>
		</tr></table>
		</center>

		<!-- SETTINGS -->
		<!--<form action="#" method="post">

			<div class="content-box">		

					<dl>
						<dt>Title:</dt>
						<dd><input type="text" size="30" name="" class="input-text" value="My Website" /></dd>
						
						<dt>URL:</dt>
						<dd><input type="email" size="30" name="" class="input-text" value="http://www.example.com/" /></dd>
					</dl>
			
			</div>					

			<div class="content-box">		

					<dl>
						<dt>Password:</dt>
						<dd><input type="password" size="30" name="" class="input-text" value="password" /></dd>
						
						<dt>Password again:</dt>
						<dd><input type="password" size="30" name="" class="input-text" value="password" /></dd>
					</dl>
			
			</div>			
			
			<div class="content-box alt">	
			
				<p class="t-center"><input type="submit" value="Save changes" class="input-submit" /></p>
				
			</div>
			
		</form>-->

		<!-- NAVIGATION -->
		<h2 class="t-center">Navigation</h2>
		
		<ul id="nav" class="box">
			<li class="ico-dashboard active"><a href="index.php" onclick="return link(this)">Dashboard</a></li>
			<li class="ico-stats"><a href="stats.php" onclick="return link(this)">Stats</a></li>
			<li class="ico-settings"><a href="settings.php" onclick="return link(this)">Settings</a></li> <!-- Active page (.active) -->
			<!--
			<li class="ico-pages"><a href="pages.html" onclick="return link(this)">Pages</a></li>
			<li class="ico-categories"><a href="categories.html" onclick="return link(this)">Categories</a></li>
			<li class="ico-images"><a href="images.html" onclick="return link(this)">Images</a></li>			
			<li class="ico-contacts"><a href="contacts.html" onclick="return link(this)">Contacts</a></li>
			<li class="ico-users"><a href="users.html" onclick="return link(this)">Users</a></li>
			<li class="ico-comments"><a href="comments.html" onclick="return link(this)">Comments</a></li>
			<li class="ico-search"><a href="search.html" onclick="return link(this)">Search</a></li>
			-->
			<br><br><br><br><br><br>
		</ul>
		
		<!--<p class="nomb t-center smaller grey">Icons by <a href="http://www.woothemes.com/2009/09/woofunction-178-amazing-web-design-icons/">WeFunction</a></p>-->
		
	</div> <!-- /content -->
	
	<!-- FOOTER -->
	<div id="footer">
	
		<p id="footer-button"><a onclick="jQuery('html,body').animate({scrollTop:0},'slow');" href="javascript:void(0);">Back on top</a></p>
	
		<p>&copy; 2014 <a href="http://cc.beingo.net/">Smart Home AIR</a></p>
		
	</div> <!-- /footer -->

</div> <!-- /wrapper -->

<?
if ($Cmd == '')
{
?>
<script>
//TempGet();

  $.ajax({url:"<?=ARDUINO_ACCESS_PATH?>?cmd=2",timeout:<?=ARDUINO_ACCESS_TIMEOUT?>,success:function(result){
    if (!isNaN(result)){
      var temp = parseInt(result);
      if (temp < 0)
      {
        document.getElementById('temp_icon').innerHTML = '<img src=images/Temperature-1-32.png>';
      }else if (temp < 10){
        document.getElementById('temp_icon').innerHTML = '<img src=images/Temperature-2-32.png>';
      }else if (temp < 20){
        document.getElementById('temp_icon').innerHTML = '<img src=images/Temperature-3-32.png>';
      }else if (temp < 30){
        document.getElementById('temp_icon').innerHTML = '<img src=images/Temperature-4-32.png>';
      }else{
        document.getElementById('temp_icon').innerHTML = '<img src=images/Temperature-5-32.png>';
      }
      document.getElementById('temp').innerHTML = result + '℃';
    }else{
      document.getElementById('temp').innerHTML = '#1 Something wrong!!';
  }},
  error:function(){
    document.getElementById('temp').innerHTML = '#2 Something wrong!!';
  }
  });
</script>
<?
}
?>
</body>
</html>