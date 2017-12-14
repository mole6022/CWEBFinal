<?php
require_once ('inc\class.user.php');
session_start();
$user_active = new USER();
$errorMsg = "";
$msgAction = "";
$registrationConfirm = FALSE;
if(!$user_active->isLoggedIn()) {
	if(!empty($_POST)) {
		//print_r($_POST);
		if($_POST['password'] != $_POST['passwordConfirm']) {
			$errorMsg = "Entered passwords do not match.";
		} else {
			try {
				if($user_active->register($_POST['username'], 
						$_POST['password'], 
						array_key_exists('gm', $_POST)?2:1, 
						array_key_exists('email', $_POST)?$_POST['email']:NULL) == 1) {
					$user_active->login($_POST['username'], $_POST['password']);
					$registrationConfirm = TRUE;
				}
			}
			catch(Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
	}
} else {
	//User is already logged in
	header('Location:index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Natural One Games</title>
	<?php include 'inc\bootstrap.php'; ?>
	<?php if($registrationConfirm) { ?>
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
		<div class="container panel panel-default" id="register">
		<?php if($registrationConfirm) { ?>
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body">
				<div class="panel-body text-center">
					<p><?php echo $user_active->getUsername() ?>, thank you for registering.  You will be forwarded to your character sheet index in five seconds.</p>
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
				<h1>Register</h1>
			</div>
			<div class="panel-body">
				<?php if(!empty($errorMsg)) { ?>
				<!--- Echo error messages -->
				<div class="alert alert-danger">
					<strong>Error:</strong> <?php echo $errorMsg; ?>
				</div>
				<?php } ?>
				<form action="registerUser.php" method="post">
					<div class="form-group">
						<p>Please complete the following form and submit to register your account.  All fields with a red star are required.</p>
					</div>
					<div class="form-group">
						<label for="username">Username<span class="text-danger" title="Required">*</span>:</label>
						<input type="text" name="username" id="username" class="form-control" required 
							<?php if(array_key_exists('username', $_POST)) { ?> value=<?php echo $_POST['username'];} ?> />
					</div>
					<div class="form-group">
						<label for="password">Password<span class="text-danger" title="Required">*</span>:</label>
						<input type="password" name="password" id="password" class="form-control" required />
					</div>
					<div class="form-group">
						<label for="passwordConfirm">Confirm Password<span class="text-danger" title="Required">*</span>:</label>
						<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control" required />
					</div>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" name="email" id="email" class="form-control" 
							<?php if(array_key_exists('email', $_POST)) { ?> value=<?php echo $_POST['email'];} ?> />
					</div>
					<div class="checkbox-inline">
						<label>
							<input type="checkbox" name="gm" id="gm" 
								<?php if(array_key_exists('gm', $_POST)) { ?>checked<?php } ?> />
							Check to unlock Game Manager options
						</label>
					</div>
					<button type="submit" name="submit" class="btn btn-default pull-right">Submit</button>
				</form>
			</div>
		<?php } ?>
		</div>
	</div>
</body>
</html>