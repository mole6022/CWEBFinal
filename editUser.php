<?php
require_once ('inc\class.user.php');
session_start();
$user_active = new USER();
$errorMsg = "";
$msgAction = "";
$updateConfirm = FALSE;
if(!$user_active->isLoggedIn()) {
	header("Location: registerUser.php");
} else {
	$userId = $user_active->getUserId();
	$username = $user_active->getUsername();
	$email = $user_active->getEmail();
	$gm = $user_active->isGM()?2:1;
	if (!empty($_POST)) {
		//Posted new request for change
		$passNew = NULL;
		$passNewConfirm = NULL;
		if(isset($_POST['passwordNew']) && trim($_POST['passwordNew'])) {
			$passNew = trim($_POST['passwordNew']);
		} 
		if(isset($_POST['passwordNewConfirm']) && trim($_POST['passwordNewConfirm'])) {
			$passNewConfirm = trim($_POST['passwordNewConfirm']);
		} 
		if(isset($_POST['email'])) {
			$email = trim($_POST['email']);
		}
		if(isset($_POST['gm'])) {
			$gm = 2;
		} else {
			$gm = 1;
		}
		
		if ($passNew != $passNewConfirm) {
			$errorMsg = "Entered passwords do not match.";
		} else {
			//Commit changes to db
			try {
				$user_active->updateUser($username, $_POST['passwordOld'], $email, $passNew, $gm);
				//Reacquire new session data
				if(!empty($passNew)) 
					$user_active->login($username, $passNew); 
				else
					$user_active->login($username, $_POST['passwordOld']); 
				$updateConfirm = TRUE;
			}
			catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Natural One Games</title>
	<?php include 'inc\bootstrap.php'; ?>
	<?php if($updateConfirm) { ?>
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
		<?php if($updateConfirm) { ?>
			<div class="panel-heading row">
				<h1>Redirecting<span id="progress">...</span></h1>
			</div>
			<div class="panel-body">
				<div class="panel-body text-center">
					<p><?php echo $user_active->getUsername() ?>, thank you for updating your account.  You will be forwarded to your character sheet index in five seconds.</p>
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
				<h1>Edit User Account: <?php echo $username; ?></h1>
			</div>
			<div class="panel-body">
				<?php if(!empty($errorMsg)) { ?>
				<!--- Echo error messages -->
				<div class="alert alert-danger">
					<strong>Error:</strong> <?php echo $errorMsg; ?>
				</div>
				<?php } ?>
				<form action="editUser.php" method="post">
					<div class="form-group">
						<p>Enter your new e-mail address if you wish to update, or leave blank to remove</p>
					</div>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" name="email" id="email" class="form-control"
							<?php if(!empty($email)) { ?> value="<?php echo $email; ?>"<?php } ?> />
					</div>
					<hr />
					<div class="form-group">
						<p>Enter a new password if you wish to update, or leave blank to remain default.</p>
					</div>
					<div class="form-group">
						<label for="passwordNew">New Password:</label>
						<input type="password" name="passwordNew" id="passwordNew" class="form-control" />
					</div>
					<div class="form-group">
						<label for="passwordNewConfirm">Confirm New Password:</label>
						<input type="password" name="passwordNewConfirm" id="passwordNewConfirm" class="form-control" />
					</div>
					<hr />
					<div class="form-group">
						<p>Check if you wish to enable GM functions.</p>
					</div>
					<div class="checkbox-inline">
						<label>
							<input type="checkbox" name="gm" id="gm" 
								<?php if($gm == 2) { ?> checked <?php } ?>/>
							Check to unlock Game Manager options
						</label>
					</div>
					<hr />
					<div class="form-group">
						<label for="passwordOld">Enter Old Password To Make Revisions<span class="text-danger" title="Required">*</span>:</label>
						<input type="password" name="passwordOld" id="passwordOld" class="form-control" required />
					</div>
					<a href="index.php" class="btn btn-default pull-left">Return to Index</a>
					<button type="submit" name="submit" class="btn btn-default pull-right">Submit</button>
					<button type="reset" name="reset" class="btn btn-warning pull-right">Reset</button>
				</form>
			</div>
		<?php } ?>
		</div>
	</div>
</body>
</html>