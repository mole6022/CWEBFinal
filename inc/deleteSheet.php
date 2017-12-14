<?php
include_once('class.sheet.php');
include_once('class.user.php');
$user = new USER();
session_start(); 

if(!$user->isLoggedIn()) {
	//If not logged in, dump to login page
	header("Location: ../login.php");
}

if(!array_key_exists('sheetId', $_GET)) {
	header("Location: ../login.php");; //Dump to login page
} else {
	$sid = $_GET['sheetId'];
}
$sheet = new SHEET($sid)
?>
<div class="modal-dialog">
	<div class="modal-content"><form method="POST" action="deleteSheet.php">
		<div class="modal-header">
			<h1>Delete Sheet?</h1>
		</div>
		<div class="container-fluid modal-body">
			<input type="hidden" name="sheetId" value="<?php echo $sid; ?>" />
			<input type="hidden" name="userId" value="<?php echo $sheet->getUserId(); ?>" />
			<div class="form-group">
				Are you sure you wish to delete the sheet <?php echo $sheet->getSheetName(); ?>? <strong>This action cannot be undone</strong>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" name="btn-save" id="btn-cancel" onclick="$('#editSheet').modal('hide')">Cancel</button>
			<button type="submit" class="btn btn-danger pull-right" name="btn-save" id="btn-submit">Delete Sheet</button>
		</div>
	</form></div>
</div>
