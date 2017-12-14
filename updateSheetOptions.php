<?php
include_once('inc/class.sheet.php');
include_once('inc/class.user.php');
session_start();

$user = new USER();

if(empty($_POST) || $user->getUserId() != $_POST['userId']) {
	//Redirect if not accessed by submitting post or if logged-in user ID does not match owner of sheet
	header("Location: index.php");
} else {
	if ($_POST['sheetId'] == 0) {
		//Write new sheet
		$sheet = new SHEET();
		if(array_key_exists('sheetName', $_POST)) {
			$sheetName = $_POST['sheetName'];
		}
		if(array_key_exists('gameId', $_POST)) {
			$gameName = $_POST['gameId'];
		}
		else {
			$gameName = 0;
		}
		
		$sheet->createSheet($user->getUserId(), $sheetName, $gameName);
	} else {
		//Edit existing sheet
		$sheet = new SHEET($_POST['sheetId']);
		$sheetName = $sheet->getSheetName();
		$gameName = $sheet->getGameId();
		//$visible = $sheet->getVisibility();
		
		if(array_key_exists('sheetName', $_POST) && $_POST['sheetName'] !== $sheetName) {
			$sheetName = $_POST['sheetName'];
		}
		if(array_key_exists('gameId', $_POST) && $_POST['gameId'] !== $gameName) {
			$gameName = $_POST['gameId'];
		}
		else {
			$gameName = 0;
		}
		/*if(array_key_exists('sheetName', $_POST) && $_POST['sheetName'] !== $visible) {
			$visible = $_POST['visibility'];
		}*/
		
		$sheet->updateOptions($sheetName, $gameName);
	}
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>Natural One Games</title>
	<?php include 'inc\bootstrap.php'; ?>
	<noscript>
		<meta http-equiv="Refresh" content="5; URL=index.php" />
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
					<p><?php echo $user->getUsername() ?>, you have updated your sheet.  You will be returned to your character sheet index in five seconds.</p>
					<a href="index.php">Click here if your browser does not automatically redirect you.</a>
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
					window.location = "index.php";
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