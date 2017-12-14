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
	$gid = 0; //Create new Game
} else {
	$gid = $_GET['gameId'];
}
$game = new GAME($gid);
//echo "<hr />"; print_r($game);
?>
<div class="modal-dialog">
	<div class="modal-content"><form method="POST" action="updateGameOptions.php">
		<div class="modal-header">
			<h1>
				<?php if($gid != 0) { ?>Edit Game: <?php echo $game->getGameName(); ?>
					<?php } else { ?>Create New Game<?php } ?></h1>
		</div>
		<div class="container-fluid modal-body">
			<input type="hidden" name="gameId" value="<?php echo $gid; ?>" />
			<input type="hidden" name="userId" value="<?php if($gid==0){echo $user->getUserId();} else {echo $game->getUserId();} ?>" />
			<div class="form-group">
				<label class="col-xs-3" for="gameName">Game Name<span class="text-danger" title="Required">*</span></label>
				<input class="col-xs-9" name="gameName" id="gameName" type="text" <?php if($gid!=0){ ?>value="<?php echo $game->getGameName(); ?>"<?php } ?> required />
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-default pull-right" name="btn-save" id="btn-submit">Save Changes</button>
		</div>
	</form></div>
</div>
