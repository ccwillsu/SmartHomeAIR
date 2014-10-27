<?
require_once('server.conf.php');
require_once('function.php');
$User = $_POST['user'];
$Pass = $_POST['pass'];
$Cmd = $_GET['cmd'];
$Page = $_GET['page'];
if ($Cmd == 'logout')
{
    Logout();
}
if ($User != "" && $Pass != "")
{
    $res = Login($User, $Pass);
}
else
{
    $res = 0;
}
if ($res == 1)
{
    echo "<script><!--\n";
    echo "  window.location.href='".($Page == '' ? 'index.php' : $Page)."';\n";
    echo "--></script>\n";  
}
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
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	
	<title>Admin</title>
</head>

<body onorientationchange="Orientation();">

<div id="wrapper">

	<!-- HEADER -->
	<div id="header">
	
		<h1 id="logo">Log In</h1>
		
		<!--<p class="header-button"><a href="#" onclick="return link(this)">Sign Up</a></p>-->
		
	</div> <!-- /header -->
	
	<!-- CONTENT -->
	<div id="content">

<?
if ($res == -1)
{
?>
		<div class="content-box err">
			<p>Login Failed.</p>
		</div>
<?
}
?>
	
		<!-- LOGIN -->
		<div class="content-box nomb">		
		
			<form action="#" method="post">
                <input type="hidden" name="page" value="<?=$Page?>" />
				<dl>
					<dt>Username:</dt>
					<dd><input type="text" size="30" name="user" class="input-text" /></dd>
					
					<dt>Password:</dt>
					<dd><input type="password" size="30" name="pass" class="input-text" /></dd>
				</dl>				
				
				<p class="nomb t-center"><input type="submit" value="Login" class="input-submit" /> or <a href="#" onclick="return link(this)">Send forgotten password</a></p>

			</form>
		
		</div> <!-- /content-box -->		
		
	</div> <!-- /content -->
		
	<!-- FOOTER -->
	<div id="footer">
	
		<p id="footer-button"><a onclick="jQuery('html,body').animate({scrollTop:0},'slow');" href="javascript:void(0);">Back on top</a></p>
	
		<p>&copy; 2014 <a href="http://cc.beingo.net/">Smart Home AIR</a></p>
		
	</div> <!-- /footer -->

</div> <!-- /wrapper -->

</body>
</html>