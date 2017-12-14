<?php
session_start();
require_once('inc\class.user.php');
$user_active = new USER();
if(!$user_active->isLoggedIn()) {
	//User not logged in to system - forward to login screen
	header('Location: login.php');
} else {
	$content = "";
	$data = 1;
	$displayPerPage = 2;
	$sort='none'; //Sort: none, sheet-asc, sheet-desc, game-asc, game-desc, date-asc, date-desc
	
	if(!isset($pageNumber)) {
		$pageNumber = 0;
	}
	
	//Configure display settings for character sheets or game data
	if(!array_key_exists('data', $_GET) || $_GET['data'] === 'sheets') {
		try {
			$sheets = $user_active->getSheets();
			$content = $sheets;
			$maxPage = ceil($user_active->getTotalSheets($pageNumber, $displayPerPage) / $displayPerPage);
		}
		catch (Exception $e) {
			$errorMsg = $e->getMessage();
		}
	} else if ($_GET['data'] === 'games') {
		$data = 2;
		try {
			$games = $user_active->getGames();
			$content = $games;
			$maxPage = ceil($user_active->getTotalGames($pageNumber, $displayPerPage) / $displayPerPage);
		}
		catch (Exception $e) {
			$errorMsg = $e->getMessage();
		}
	} else {
		//Invalid display option configured
		$errorMsg = "Please use the top menu to select a valid option.";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Character Viewer &mdash; Natural One Games</title>
	<?php include 'inc\bootstrap.php' ?>
	<script type="text/javascript">
		
	</script>
</head>
<body>
	<div class="pagecontainer">
		<header class="container sitelogo">
			<img src="img\SiteLogo.png" height=150px alt="Natural One Games" />
			<h1>Natural One Games</h1>
		</header>
		<div class="container panel panel-default" id="index">
			<div class="panel-heading row">
				<nav class="navbar navbar-default">
					<ul class="nav navbar-nav navbar-left">
						<li <?php if($data == 1) { ?> class="active" <?php } ?>><a href="index.php">Characters</a></li>
						<?php if ($user_active->isGM()) { ?>
						<li <?php if($data == 2) { ?> class="active" <?php } ?>><a href="?data=games">Games</a></li>
						<?php } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown">
								<span class="glyphicon glyphicon-user"></span> <?php echo $user_active->getUsername(); ?>
							</a>
							<ul class="dropdown-menu" id="userDropdown">
								<li><a href="editUser.php">Edit Details</a></li>
								<li><a href="logout.php">Log out</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
			<div class="panel-body">
				<?php if(isset($errorMsg)) { ?>
				<!--- Echo error messages -->
				<div class="alert alert-danger">
					<strong>Error:</strong> <?php echo $errorMsg; ?>
				</div>
				<?php } ?>
				
				<div id="dataTable">
					<span class="glyphicon glyphicon-refresh spinning"></span>
				</div> <!--dataTable-->
				
				<div id="editSheet" class="modal fade" role="dialog">
					<!-- Sheet edit options will be inserted here -->
				</div>
			</div>
		</div>
	</div>
</body>
<script>
<?php if(!array_key_exists('data', $_GET) || $_GET['data'] === 'sheets') { ?>
$(document).ready(function() {
	$('#dataTable').load('inc/table.sheet.php');
	//console.log( "ready!" );
});
<?php } else if ($_GET['data'] === 'games') { ?>
$(document).ready(function() {
	$('#dataTable').load('inc/table.game.php');
	//console.log( "ready!" );
});
<?php } else { ?>
	console.log( "oopsie!" );
<?php } ?>
</script>
</html>