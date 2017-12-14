<?php
session_start();
include_once 'inc\class.user.php';

$user = new USER();

/*if($user->isLoggedIn()) {
	//User already logged in to system
	header('Location: index.php');
}*/

if(!$user->isLoggedIn() && isset($_POST['submit'])) {
	//Authenticate user to system on POST
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	try {
		if($user->login($username, $password)) {
			//header('Location: index.php');
		} else {
			$errorMsg = "Error";
		}
	}
	catch(Exception $e) {
		$errorMsg = $e->getMessage();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log In &mdash; Natural One Games</title>
	<?php include 'inc\bootstrap.php' ?>
	<?php if($user->isLoggedIn()) { ?>
	<noscript>
		<meta http-equiv="Refresh" content="5; URL=index.php" />
	</noscript>
	<?php } ?>
</head>
<body>
	<div class="pagecontainer">
		<header class="container sitelogo" id="logologin">
			<img src="img\SiteLogo.png" height=150px alt="Natural One Games" />
			<h1>Natural One Games</h1>
		</header>
		<div class="container panel panel-default" id="login">
		<?php if($user->isLoggedIn()) { ?>
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body">
				<div class="panel-body text-center">
					<p><?php echo $user->getUsername() ?>, thank you for logging in.  You will be forwarded to your character sheet index in five seconds.</p>
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
		<?php } else { ?>
			<div class="panel-heading row">
				<h1>Log In</h1>
			</div>
			<div class="panel-body">
				<?php if(!empty($errorMsg)) { ?>
				<!--- Echo error messages -->
				<div class="alert alert-danger">
					<strong>Error:</strong> <?php echo $errorMsg; ?>
				</div>
				<?php } ?>
				<form action="login.php" method="post">
					<div class="form-group">
						<p>You must log in to access the character sheet creator. Don't have an account? <strong>Click <a href="registerUser.php">here</a> to register.</strong></p>
					</div>
					<div class="form-group">
						<label for="username">Username:</label>
						<input type="text" name="username" id="username" class="form-control" required 
							<?php if(array_key_exists('username', $_POST)) { ?> value=<?php echo $_POST['username'];} ?> />
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" name="password" id="password" class="form-control" required />
					</div>
					<button type="submit" name="submit" class="btn btn-default pull-right">Submit</button>
				</form>
			</div>
		<?php } ?>
		</div>
	</div>
</body>
</html>