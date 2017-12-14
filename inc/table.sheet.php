<?php
session_start();
require_once('class.user.php');
$user_active = new USER();

$sheets = array();
$displayPerPage = 5;
if(array_key_exists('perPage', $_POST)) {
	//$displayPerPage = $_POST['perPage'];
}
$pageNumber = 0;
if(array_key_exists('pageNumber', $_POST)) {
	$pageNumber = $_POST['pageNumber'];
}
$sortField='none';
//Valid fields are 'sheet', 'game', and 'date'
if(array_key_exists('sortField', $_POST)) {
	$sortField = $_POST['sortField'];
}
$sortOrder='asc';
//Valid sort orders are 'asc' and 'desc'
if(array_key_exists('sortOrder', $_POST)) {
	$sortOrder = $_POST['sortOrder'];
}
//Sorting should switch between UNSORTED->SORT ASC->SORT DESC

try {
	$sheets = $user_active->getSheets($pageNumber, $displayPerPage);
	$maxPage = ceil($user_active->getTotalSheets() / $displayPerPage);
}
catch (Exception $e) {
	$errorMsg = $e->getMessage();
}
?>
<?php if(isset($errorMsg)) { ?>
<!--- Echo error messages -->
<div class="alert alert-danger">
	<strong>Error:</strong> <?php echo $errorMsg; ?>
</div>
<?php } ?>

<!--Create new sheet-->
<div><a class="btn btn-default pull-right" onclick="editSheetSettings(0)">Create Sheet</a></div>
<!--Primary data table-->
<table class="table table-striped table-hover">
	<thead><tr>
		<th class="col-xs-1">
			<!--Direct visibility controls and sheet options-->
		</th>
		<th class="col-xs-5">
			Sheet Name
			<!--<a onclick=""><span class="glyphicon glyphicon-sort"></span></a>-->
		</th>
		<th class="col-xs-4">
			Game Name
			<!--<a onclick=""><span class="glyphicon glyphicon-sort"></span></a>-->
		</th>
		<th class="col-xs-2">
			Date Modified
			<!--<a onclick=""><span class="glyphicon glyphicon-sort"></span></a>-->
		</th>
	</tr></thead>
	<tbody>
	<?php foreach($sheets as $sheet) { ?>
		<tr>
			<td>
				<span class="glyphicon glyphicon-eye-open"></span>
				<span onclick="editSheetSettings(<?php echo $sheet['sheet_id']?>)" class="glyphicon glyphicon-cog iconMenu"></span>
				<span onclick="deleteSheet(<?php echo $sheet['sheet_id']?>)" class="glyphicon glyphicon-trash iconMenu"></span>
			</td>
			<td><a href="viewSheet.php?id=<?php echo $sheet['sheet_id'] ?>"><?php echo $sheet['sheet_name'];?></a></td>
			<td><?php echo $sheet['game_name'];?></td>
			<td><?php echo $sheet['date_modified'];?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<!--Page Control-->
<nav class="text-center"><ul class="pagination pagination-lg">
	<li <?php if($pageNumber==0){ ?>
		class="disabled"
	<?php } else { ?>
		onclick="getresult(<?php echo $displayPerPage ?>,<?php echo $pageNumber-1 ?>)"
	<?php } ?>><a>Previous</a></li>
	
	<?php for($i = 0; $i < $maxPage; $i++) { ?>
	<li  <?php if($pageNumber==$i){ ?>class="active"<?php } ?>><a onclick="getresult(<?php echo $displayPerPage ?>,<?php echo $i ?>)"><?php echo $i+1; ?></a></li>
	<?php } ?>
	
	<li <?php if($pageNumber>=$maxPage-1){ ?>
		class="disabled"<?php } else { ?>
		onclick="getresult(<?php echo $displayPerPage ?>,<?php echo $pageNumber+1 ?>)"
	<?php } ?>><a>Next</a></li>
</ul></nav>
<script type="text/javascript">
function editSheetSettings(sheetId) {
	$('#editSheet').load('inc/editSheetOptions.php?sheetId='+sheetId);
	$('#editSheet').modal();
}
function deleteSheet(sheetId) {
	$('#editSheet').load('inc/deleteSheet.php?sheetId='+sheetId);
	$('#editSheet').modal();
}
function getresult(perPage = 2, pageNo = 0, sortField = 'none', sortOrder = 'asc'){
	$.ajax({
		url: 'inc/table.sheet.php',
		type: "POST", 
		data: {
			perPage: perPage,
			pageNumber: pageNo,
			sortField: sortField,
			sortOrder: sortOrder
		},
		success: function(data) {
			//Insert returned HTML table into data
			$("#dataTable").html(data);
		},
		error: function() {}
	});
}
</script>