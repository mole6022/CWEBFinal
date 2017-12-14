<?php 
require_once 'inc\class.user.php';
session_start();
require_once 'inc\class.sheet.php';

//print_r($_POST);
if(empty($_POST)) {
	//If no data posted or if no sheet is specified
	header("Location: index.php");
} else {
	$user = new USER();
	$sheet = new SHEET($_POST['sheetId']);
	$errMsg = "";
	
	if (!$user->isLoggedIn() OR $user->getUserId() != $sheet->getUserId()) {
		//User is not logged in or user does not match sheet parameters
		//header("Location: index.php");
	} else {
		//Save sheet using posted data
		try {
			$sheet->saveSheet($_POST);
			//header("Location: viewSheet.php?id=".$_POST['sheetId']);
			//echo "success!";
		}
		catch (Exception $e) {
			$errMsg = $e->getMessage();
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Save Sheet &mdash; Natural One Games</title>
	<?php include 'inc\bootstrap.php' ?>
	<?php if(empty($errMsg)) { ?><noscript>
		<meta http-equiv="Refresh" content="5; URL=viewSheet.php?id=<?php echo $_POST['sheetId'] ?>" />
	</noscript><?php } ?>
</head>
<body>
	<div class="pagecontainer">
		<header class="container sitelogo" id="logologin">
			<img src="img\SiteLogo.png" height=150px alt="Natural One Games" />
			<h1>Natural One Games</h1>
		</header>
		<div class="container panel panel-default" id="login">
			<?php if(empty($errMsg)) { ?>
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body text-center">
				<p>You have updated your character sheet. You will be returned to it in five seconds.</p>
				<a href="viewSheet.php?id=<?php echo $_POST['sheetId'] ?>">Click here if your browser does not automatically redirect you.</a>
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
					window.location = "viewSheet.php?id=<?php echo $_POST['sheetId'] ?>";
				}
			}

			var myvar = "";
			var timeout = 50;
			exec_refresh();
			</script>
			<?php } else { ?>
			<!--- Echo error messages -->
			<div class="alert alert-danger">
				<strong>Error:</strong> <?php echo $errMsg; ?>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>