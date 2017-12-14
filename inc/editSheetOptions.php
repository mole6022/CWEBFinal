<?php
include_once('class.sheet.php');
include_once('class.game.php');
include_once('class.user.php');
$user = new USER();
session_start(); 

if(!$user->isLoggedIn()) {
	//If not logged in, dump to login page
	header("Location: ../login.php");
}

if(!array_key_exists('sheetId', $_GET)) {
	$sid = 0; //Create new sheet
} else {
	$sid = $_GET['sheetId'];
}
$sheet = new SHEET($sid);
$gameSet = new GAME();
$games = $gameSet->getOpenGames();
//echo "<hr />"; print_r($sheet);
?>
<div class="modal-dialog">
	<div class="modal-content"><form method="POST" action="updateSheetOptions.php">
		<div class="modal-header">
			<h1>
				<?php if($sid != 0) { ?>Edit Sheet: <?php echo $sheet->getSheetName(); ?>
					<?php } else { ?>Create New Sheet<?php } ?></h1>
		</div>
		<div class="container-fluid modal-body">
			<input type="hidden" name="sheetId" value="<?php echo $sid; ?>" />
			<input type="hidden" name="userId" value="<?php if($sid==0){echo $user->getUserId();} else {echo $sheet->getUserId();} ?>" />
			<div class="form-group">
				<label class="col-xs-3" for="sheetName">Sheet Name<span class="text-danger" title="Required">*</span></label>
				<input class="col-xs-9" name="sheetName" id="sheetName" type="text" <?php if($sid!=0){ ?>value="<?php echo $sheet->getSheetName(); ?>"<?php } ?> required />
			</div>
			<div class="form-group">
				<p class="col-xs-12">
				<?php if($sid != 0) { ?>
					<?php if(!$sheet->getGameName()) { ?>
					Not currently a member of any game. 
					<?php } else { ?>
					Currently a member of the game <strong><?php echo $sheet->getGameName(); ?></strong>. 
					<?php } ?>
				<?php } ?>
					Select an open game: 
				</p>
				<label class="col-xs-3" for="gameId">Open Games</label>
				<select class="col-xs-9" name="gameId" id="gameId">
					<option value="0">No Game</option>
				<?php foreach($games as $game) { ?>
					<option value="<?php echo $game['game_id'];?>" <?php if($sid != 0 && $game['game_id']==$sheet->getGameId()) { ?>selected<?php } ?>>
						<?php echo $game['game_name'];?>
					</option>
				<?php } ?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-default pull-right" name="btn-save" id="btn-submit">Save Changes</button>
		</div>
	</form></div>
</div>
