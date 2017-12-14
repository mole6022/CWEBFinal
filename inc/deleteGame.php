<?php
include_once('class.game.php');
include_once('class.user.php');
$user = new USER();
session_start(); 

if(!$user->isLoggedIn()) {
	//If not logged in, dump to login page
	header("Location: ../login.php");
}

if(!array_key_exists('gameId', $_GET)) {
	header("Location: ../login.php");; //Dump to login page
} else {
	$gid = $_GET['gameId'];
}
$game = new GAME($gid)
?>
<div class="modal-dialog">
	<div class="modal-content"><form method="POST" action="deleteGame.php">
		<div class="modal-header">
			<h1>Delete Game?</h1>
		</div>
		<div class="container-fluid modal-body">
			<input type="hidden" name="gameId" value="<?php echo $gid; ?>" />
			<input type="hidden" name="userId" value="<?php echo $game->getUserId(); ?>" />
			<div class="form-group">
				Are you sure you wish to delete the game <?php echo $game->getGameName(); ?>? <strong>This action cannot be undone</strong>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" name="btn-cancel" id="btn-cancel" onclick="$('#editSheet').modal('hide')">Cancel</button>
			<button type="submit" class="btn btn-danger pull-right" name="btn-save" id="btn-submit">Delete Game</button>
		</div>
	</form></div>
</div>
