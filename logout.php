<?php
require_once 'inc\class.user.php';

$user_active = new USER();
$user_active->logout();
//header('Location: login.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log Out &mdash; Natural One Games</title>
	<?php include 'inc\bootstrap.php' ?>
	<noscript>
		<meta http-equiv="Refresh" content="5; URL=login.php" />
	</noscript>
</head>
<body>
	<div class="pagecontainer">
		<header class="container sitelogo" id="logologin">
			<img src="img\SiteLogo.png" height=150px alt="Natural One Games" />
			<h1>Natural One Games</h1>
		</header>
		<div class="container panel panel-default" id="login">
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body text-center">
				<p>You are now logged out.  You will be returned to the log-in page in five seconds.</p>
				<a href="login.php">Click here if your browser does not automatically redirect you.</a>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	function exec_refresh()
	{
		window.status = "Redirecting..." + myvar;
		myvar = myvar + " .";
		var timerID = setTimeout("exec_refresh();", 100);
		if (timeout > 0)
		{
			timeout -= 1;
		}
		else
		{
			clearTimeout(timerID);
			window.status = "";
			window.location = "login.php";
		}
	}

	var myvar = "";
	var timeout = 50;
	exec_refresh();
	</script>
</body>
</html>