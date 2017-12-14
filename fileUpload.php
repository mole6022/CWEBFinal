<?php
require_once('inc\class.user.php');
session_start();
require_once('inc\class.sheet.php');

$user = new USER();
//print_r($_POST);
//print_r($_FILES);
$errorMsg = "";
$successMsg = "";
if(!$user->isLoggedIn() || empty($_POST)) {
	header('Location: index.php');
} else {
	if(array_key_exists('uploadSheetId', $_POST)) {
		$sheet = new SHEET($_POST['uploadSheetId']);
		
		if(array_key_exists('portraitClear', $_POST)) {
			//Delete existing portrait
			unlink("portraits/".$sheet->image);
			$sheet->setPortrait(NULL);
			$successMsg = "You have successsfully deleted your portrait.";
		} else {
			//Upload new file
			if($_FILES["uploadPortrait"]['error'] > 0) {
				$errorMsg .= "Error on file upload: ".$_FILES['uploadPortrait']['error']."<br />";
			} else { //File uploaded with no errors
				$extension = end(explode(".", $_FILES["uploadPortrait"]["name"]));
				
				//Arrays for error checking: Valid image file types
				$validType = array("image/", "image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png");
				$allowedExts = array("gif", "jpg", "jpeg", "png");
				
				//Begin error checking
				if($_FILES["uploadPortrait"]["size"] >= 500000) {
					$errorMsg .= "Image size greater than 500kB<br />";
				} 
				if(!in_array($_FILES['uploadPortrait']['type'], $validType) || !in_array($extension, $allowedExts)) {
					$errorMsg .= "Invalid file type uploaded<br />";
				}
				if(empty($errorMsg)) {
					$filename = "portrait_sid".$_POST['uploadSheetId'].".".$extension;
					move_uploaded_file($_FILES["uploadPortrait"]["tmp_name"], "portraits/".$filename);
					
					$sheet->setPortrait($filename);
					$successMsg = "You have succesfully uploaded your portrait.";
				}
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>File Upload &mdash; Natural One Games</title>
	<?php include 'inc\bootstrap.php' ?>
	<?php if(empty($errorMsg)) { ?><noscript>
		<meta http-equiv="Refresh" content="5; URL=viewSheet.php?id=<?php echo $sheet->getSheetId() ?>" />
	</noscript><?php } ?>
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
				<?php if(empty($errorMsg)) { ?>
				<p><?php echo $successMsg; ?>  You will be returned to your character sheet in five seconds.</p>
				<a href="viewSheet.php?id=<?php echo $sheet->getSheetId() ?>">Click here if your browser does not automatically redirect you.</a>
				<?php } else { ?>
				<!--- Echo error messages -->
				<div class="alert alert-danger">
					<strong>Error:</strong> <?php echo $errMsg; ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if(empty($errorMsg)) { ?><script type="text/javascript">
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
			window.location = "viewSheet.php?id=<?php echo $sheet->getSheetId() ?>";
		}
	}

	var myvar = "";
	var timeout = 50;
	exec_refresh();
	</script><?php } ?>
</body>
</html>