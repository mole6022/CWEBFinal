<?php
include_once('inc/class.game.php');
include_once('inc/class.user.php');
session_start();

$user = new USER();

if(empty($_POST) || $user->getUserId() != $_POST['userId']) {
	//Redirect if not accessed by submitting post or if logged-in user ID does not match owner of game
	header("Location: index.php?data=games");
} else {
	if ($_POST['gameId'] == 0) {
		//Write new game
		$game = new GAME();
		if(array_key_exists('gameName', $_POST)) {
			$gameName = $_POST['gameName'];
		}
		
		$game->createGame($user->getUserId(), $gameName);
	} else {
		//Edit existing game
		$game = new GAME($_POST['gameId']);
		$gameName = $game->getGameName();
		//$visible = $game->getVisibility();
		
		if(array_key_exists('gameName', $_POST) && $_POST['gameName'] !== $gameName) {
			$gameName = $_POST['gameName'];
		}
		
		$game->updateOptions($gameName);
	}
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>Natural One Games</title>
	<?php include 'inc\bootstrap.php'; ?>
	<noscript>
		<meta http-equiv="Refresh" content="5; URL=index.php?data=games" />
	</noscript>
</head>
<body>
	<div class="pagecontainer">
		<header class="container sitelogo" id="logologin">
			<img src="img\SiteLogo.png" height=150px alt="Natural One Games" />
			<h1>Natural One Games</h1>
		</header>
		<div class="container panel panel-default" id="register">
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body">
				<div class="panel-body text-center">
					<p><?php echo $user->getUsername() ?>, you have updated your game.  You will be returned to your game index in five seconds.</p>
					<a href="index.php?data=games">Click here if your browser does not automatically redirect you.</a>
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
					window.location = "index.php?data=games";
				}
			}

			var myvar = "";
			var timeout = 50;
			exec_refresh();
			</script>
		</div>
	</div>
</body>
</html>