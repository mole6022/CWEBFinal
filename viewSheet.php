<?php
require_once('inc\class.sheet.php');
if(empty($_GET) || !array_key_exists('id', $_GET)) {
	header("Location: index.php");
} else {
	$sheet = new SHEET($_GET['id']);
}
//print_r($sheet);
?>
<html>
<head>
	<title><?php echo $sheet->getSheetName() ?>&mdash; Natural One Games</title>
	<?php include 'inc/bootstrap.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/sheet.css">
	<!--<script src="js/jquery.min.js"></script>-->
	<script>
		function toggleBox(target) {
			var targetBox = '#'+target;
			//$('#'+target+' > .containerBody').slideToggle();
			if($(targetBox+' > .containerHeader > .toggleContainer').text() == '–') {
				//Presently displaying - disable display
				$(targetBox+' > .containerHeader > .toggleContainer').text('+')
				$(targetBox+' > .containerBody').slideUp();
			}
			else {
				//Not displaying - enable display
				$(targetBox+' > .containerHeader > .toggleContainer').text('–');
				$(targetBox+' > .containerBody').slideDown();
			}
		}
	</script>
</head>
<body>
<form action="updateSheet.php" method="POST"><nav class="navbar navbar-inverse navbar-fixed-top" id="topmenu">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php">Natural One Games</a><a class="navbar-brand">&#0187;</a><a class="navbar-brand"><?php echo $sheet->getSheetName() ?></a>
		</div>
		<ul class="nav navbar-nav navbar-right">
			<!--<li><a href="#">Settings</a></li>-->
			<button type="submit" class="btn btn-default navbar-btn">Save</button>
		</ul>
	</div>
</nav>
<div class="pageContainer" id="vitae">
	<div class="containerDefault" id="basicData">
		<input type='hidden' name='sheetId' value='<?php echo $_GET['id'] ?>' />
		<div class="flexRow">
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='charName' name='charName' value="<?php echo $sheet->charName; ?>"/>
				<label for='charName' class="labelSubscript">Character Name</label>
			</div>
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='playerName' name='playerName' value="<?php echo $sheet->playerName; ?>"/>
				<label for='playerName' class="labelSubscript">Player Name</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='alignment' name='alignment' value="<?php echo $sheet->alignment ?>" />
				<label for='alignment' class="labelSubscript">Alignment</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='currentXp' name='currentXp' value="<?php echo $sheet->xpCurrent ?>" />
				<label for='alignment' class="labelSubscript">Current XP</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='nextXp' />
				<label for='alignment' class="labelSubscript">Next Level XP</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='level' name="level" value="<?php echo $sheet->level ?>" />
				<label for='level' class="labelSubscript">Level</label>
			</div>
		</div>
		<div class="flexRow">
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='class' name="class" value="<?php echo $sheet->class ?>" />
				<label for='class' class="labelSubscript">Class</label>
			</div>
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='race' name='race' value="<?php echo $sheet->race ?>" />
				<label for='race' class="labelSubscript">Race</label>
			</div>
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='campaign' name='campaign' value="<?php echo $sheet->campaign ?>" />
				<label for='campaign' class="labelSubscript">Campaign</label>
			</div>
			<div class="inputGroup quarterSpan">
				<input class="underline" type='text' id='deity' name='deity' value="<?php echo $sheet->deity ?>" />
				<label for='deity' class="labelSubscript">Deity</label>
			</div>
		</div>
		<div class="flexRow">
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='size' name='size' value="<?php echo $sheet->size ?>" />
				<label for='size' class="labelSubscript">Size</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='age' name='age' value="<?php echo $sheet->age ?>" />
				<label for='age' class="labelSubscript">Age</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='gender' name='gender' value="<?php echo $sheet->gender ?>" />
				<label for='gender' class="labelSubscript">Gender</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='height' name='height' value="<?php echo $sheet->height ?>" />
				<label for='height' class="labelSubscript">Height</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='weight' name='weight' value="<?php echo $sheet->weight ?>" />
				<label for='weight' class="labelSubscript">Weight</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='eyes' name='eyes' value="<?php echo $sheet->eyes ?>" />
				<label for='eyes' class="labelSubscript">Eyes</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='hair' name='hair' value="<?php echo $sheet->hair ?>" />
				<label for='hair' class="labelSubscript">Hair</label>
			</div>
			<div class="inputGroup eighthSpan">
				<input class="underline" type='text' id='skin' name='skin' value="<?php echo $sheet->skin ?>" />
				<label for='skin' class="labelSubscript">Skin</label>
			</div>
		</div>
	</div>
	<div class="containerDefault" id="attributes">
		<div class="containerDefault interior" id="statBlock">
			<div class="inputTableGroup tableHeader">
				<div class="labelSubscript">Ability Name</div>
				<div class="labelSubscript">Ability Score</div>
				<div class="labelSubscript">Ability Modifier</div>
				<div class="labelSubscript tableTemp">Temporary Score</div>
				<div class="labelSubscript tableTemp">Temporary Modifier</div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='strBase' class="labelBox">STR</label></div>
				<div><input type='text' id='strBase' name='strbase' value="<?php echo $sheet->attributes['str']['base'] ?>" /></div>
				<div><input type='text' id='strMod' /></div>
				<div class="tableTemp"><input type='text' id='strTempBase' name='strtemp' value="<?php echo $sheet->attributes['str']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='strTempMod' /></div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='dexBase' class="labelBox">DEX</label></div>
				<div><input type='text' id='dexBase' name='dexbase' value="<?php echo $sheet->attributes['dex']['base'] ?>" /></div>
				<div><input type='text' id='dexMod' /></div>
				<div class="tableTemp"><input type='text' id='dexTempBase' name='dextemp' value="<?php echo $sheet->attributes['dex']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='dexTempMod' /></div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='conBase' class="labelBox">CON</label></div>
				<div><input type='text' id='conBase' name='conbase'  value="<?php echo $sheet->attributes['con']['base'] ?>" /></div>
				<div><input type='text' id='conMod' /></div>
				<div class="tableTemp"><input type='text' id='conTempBase' name='contemp' value="<?php echo $sheet->attributes['con']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='conTempMod' /></div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='intBase' class="labelBox">INT</label></div>
				<div><input type='text' id='intBase' name='intbase'  value="<?php echo $sheet->attributes['int']['base'] ?>" /></div>
				<div><input type='text' id='intMod' /></div>
				<div class="tableTemp"><input type='text' id='intTempBase' name='inttemp' value="<?php echo $sheet->attributes['int']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='intTempMod' /></div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='wisBase' class="labelBox">WIS</label></div>
				<div><input type='text' id='wisBase' name='wisbase'  value="<?php echo $sheet->attributes['wis']['base'] ?>" /></div>
				<div><input type='text' id='wisMod' /></div>
				<div class="tableTemp"><input type='text' id='wisTempBase' name='wistemp' value="<?php echo $sheet->attributes['wis']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='wisTempMod' /></div>
			</div>
			<div class="inputTableGroup">
				<div class="tableLabel"><label for='chaBase' class="labelBox">CHA</label></div>
				<div><input type='text' id='chaBase' name='chabase'  value="<?php echo $sheet->attributes['cha']['base'] ?>" /></div>
				<div><input type='text' id='chaMod' /></div>
				<div class="tableTemp"><input type='text' id='chaTempBase' name='chatemp' value="<?php echo $sheet->attributes['cha']['temp'] ?>" /></div>
				<div class="tableTemp"><input type='text' id='chaTempMod' /></div>
			</div>
		</div>
		<div class="containerDefault interior" id="hitPoints">
			<div class="labelBox" style="grid-column-start: start; grid-row-start: 2">HP</div>
			<label for='hpmax' class="labelSubscript" style="grid-column: 3; grid-row: 1;">Max HP</label>
			<input type='text' id='hpmax' name='hpmax' style="grid-column: 3; grid-row: 2;" value="<?php echo $sheet->hp['max'] ?>" />
			
			<label for='hpcurrent' class="labelSubscript" style="grid-column: 5; grid-row: 1;">Current HP</label>
			<input type='text' id='hpcurrent' name='hpcurrent' style="grid-column: 5; grid-row: 2;" value="<?php echo $sheet->hp['current'] ?>"/>
			
			<label for='hpnonlethal' class="labelSubscript" style="grid-column: 7; grid-row: 1;">Nonlethal Damage</label>
			<input type='text' id='hpnonlethal' name='hpnonlethal' style="grid-column: 7; grid-row: 2;" value="<?php echo $sheet->hp['nonlethal'] ?>"/>
			
			<label for='hitdice' class="labelSubscript" style="grid-column: 9; grid-row: 1;">Hit Dice</label>
			<input type='text' id='hitdice' name='hitdice' style="grid-column: 9; grid-row: 2;" value="<?php echo $sheet->hp['hitdice'] ?>"/>
			
			<label for='damreduce' class="labelSubscript" style="grid-column: 11; grid-row: 1;">Damage Reduction</label>
			<input type='text' id='damreduce' name='damagereduce' style="grid-column: 11; grid-row: 2;" value="<?php echo $sheet->damageReduction ?>"/>
		</div>
		<div class="containerDefault interior" id="armorClass">
			<div class="labelBox" style="grid-column-start: start; grid-row-start: 1">AC</div>
			<label for='acTotal' class="labelSubscript" style="grid-column: 3; grid-row: 2;">Total</label>
			<input type='text' id='acTotal' name='actotal' style="grid-column: 3; grid-row: 1;" value="<?php echo $sheet->armorClass['total']?>"/>
			<span style="grid-column: 4; grid-row: 1">=</span>
			<label for='acBase' class="labelSubscript" style="grid-column: 5; grid-row: 2;">Base</label>
			<input type='text' id='acBase' style="grid-column: 5; grid-row: 1;" value="10"/>
			<span style="grid-column: 6; grid-row: 1">+</span>
			<label for='acArmor' class="labelSubscript" style="grid-column: 7; grid-row: 2;">Armor</label>
			<input type='text' id='acArmor' name='acarmor' style="grid-column: 7; grid-row: 1;" value="<?php echo $sheet->armorClass['armor']?>"/>
			<span style="grid-column: 8; grid-row: 1">+</span>
			<label for='acShield' class="labelSubscript" style="grid-column: 9; grid-row: 2;">Shield</label>
			<input type='text' id='acShield' name='acshield' style="grid-column: 9; grid-row: 1;" value="<?php echo $sheet->armorClass['shield']?>"/>
			<span style="grid-column: 10; grid-row: 1">+</span>
			<label for='acDex' class="labelSubscript" style="grid-column: 11; grid-row: 2;">Dex</label>
			<input type='text' id='acDex' stat='dex' style="grid-column: 11; grid-row: 1;"/>
			<span style="grid-column: 12; grid-row: 1">+</span>
			<label for='acSize' class="labelSubscript" style="grid-column: 13; grid-row: 2;">Size</label>
			<input type='text' id='acSize' style="grid-column: 13; grid-row: 1;"/>
			<span style="grid-column: 14; grid-row: 1">+</span>
			<label for='acNatural' class="labelSubscript" style="grid-column: 15; grid-row: 2;">Natural</label>
			<input type='text' id='acNatural' name='acnatural' style="grid-column: 15; grid-row: 1;" value="<?php echo $sheet->armorClass['natural']?>"/>
			<span style="grid-column: 16; grid-row: 1">+</span>
			<label for='acDeflect' class="labelSubscript" style="grid-column: 17; grid-row: 2;">Deflect</label>
			<input type='text' id='acDeflect' name='acdeflect' style="grid-column: 17; grid-row: 1;" value="<?php echo $sheet->armorClass['deflect']?>"/>
			<span style="grid-column: 18; grid-row: 1">+</span>
			<label for='acMisc' class="labelSubscript" style="grid-column: 19; grid-row: 2;">Misc</label>
			<input type='text' id='acMisc' name='acmisc' style="grid-column: 19; grid-row: 1;" value="<?php echo $sheet->armorClass['misc']?>"/>
		</div>
		<div class="containerDefault interior" id="touchInitSpeed">
			<div class="inputGroup" id="acTouchGroup">
				<label for='acTouch' class="labelBox">Touch AC</label>
				<input type='text' id='acTouch' name='actouch'  value="<?php echo $sheet->armorClass['touch']?>"/>
			</div>
			<div class="inputGroup" id="acFlatGroup">
				<label for='acFlat' class="labelBox">Flat-footed AC</label>
				<input type='text' id='acFlat' name='acflat'  value="<?php echo $sheet->armorClass['flatfoot']?>"/>
			</div>
			<div class="inputGroup" id="initGroup">
				<div class="labelBox" style="grid-row: 2;">Init</div>
				<div class="inputGroup topLabel" style="grid-column: 2; grid-row: span 2">
					<label for='initTotal' class="labelSubscript">Total</label>
					<input type='text' id='initTotal' name='inittotal' value="<?php echo $sheet->initiative['total']?>"/>
				</div>
				<span style="grid-column: 3; grid-row: 2">=</span>
				<div class="inputGroup topLabel" style="grid-column: 4; grid-row: span 2">
					<label for='initDex' class="labelSubscript">Dex</label>
					<input type='text' id='initDex' stat='dex' />
				</div>
				<span style="grid-column: 5; grid-row: 2">+</span>
				<div class="inputGroup topLabel" style="grid-column: 6; grid-row: span 2">
					<label for='initMisc' class="labelSubscript">Misc</label>
					<input type='text' id='initMisc' name='initmisc' value="<?php echo $sheet->initiative['misc']?>"/>
				</div>
			</div>
			<div class="inputGroup topLabel" id="speedGroup">
				<input type='text' id='speed' name='speed' value="<?php echo $sheet->speed?>" />
				<label for='speed' class="labelSubscript">Speed</label>
			
			</div>
			<div class="inputGroup topLabel" id="armorTypeGroup">
				<input type='text' id='armorTypeWorn' name='armorclass' value="<?php echo $sheet->armorType?>"/>
				<label for='armorTypeWorn' class="labelSubscript">Armor Class</label>
			</div>
		</div>
		<div class="containerDefault interior" id="saves">
			<label class="labelSubscript">Saving Throws</label>
			<div class="labelBox">Fortitude</div>
			<div class="labelBox">Reflex</div>
			<div class="labelBox">Will</div>
			<label class="labelSubscript">Total</label>
			<input type="text" id="fortSaveTotal" save='fort' />
			<input type="text" id="reflexSaveTotal" save='reflex' />
			<input type="text" id="willSaveTotal" save='will' />
			<div>&nbsp;</div><div>=</div><div>=</div><div>=</div>
			<label class="labelSubscript">Base</label>
			<input type="text" name="fortSaveBase" id="fortSaveBase" name='fortsavebase' value="<?php echo $sheet->saves['fort']['base']?>" save='fort' />
			<input type="text" name="reflexSaveBase" id="reflexSaveBase" name='refsavebase' value="<?php echo $sheet->saves['ref']['base']?>" save='reflex' />
			<input type="text" name="willSaveBase" id="willSaveBase" name='willsavebase' value="<?php echo $sheet->saves['will']['base']?>" save='will' />
			<div>&nbsp;</div><div>+</div><div>+</div><div>+</div>
			<label class="labelSubscript">Ability Mod</label>
			<input type="text" id="fortSaveAbility" stat='con' save='fort' />
			<input type="text" id="reflexSaveAbility" stat='dex' save='reflex' />
			<input type="text" id="willSaveAbility" stat='wis' save='will' />
			<div>&nbsp;</div><div>+</div><div>+</div><div>+</div>
			<label class="labelSubscript">Magic Mod</label>
			<input type="text" name="fortSaveMagic" id="fortSaveMagic" name='fortsavemag' value="<?php echo $sheet->saves['fort']['mag']?>" save='fort' />
			<input type="text" name="reflexSaveMagic" id="reflexSaveMagic" name='refsavemag' value="<?php echo $sheet->saves['ref']['mag']?>" save='reflex' />
			<input type="text" name="willSaveMagic" id="willSaveMagic" name='willsavemag' value="<?php echo $sheet->saves['will']['mag']?>" save='will' />
			<div>&nbsp;</div><div>+</div><div>+</div><div>+</div>
			<label class="labelSubscript">Misc Mod</label>
			<input type="text" name="fortSaveMisc" id="fortSaveMisc" name='fortsavemisc' value="<?php echo $sheet->saves['fort']['misc']?>" save='fort' />
			<input type="text" name="reflexSaveMisc" id="reflexSaveMisc" name='refsavemisc' value="<?php echo $sheet->saves['ref']['misc']?>" save='reflex' />
			<input type="text" name="willSaveMisc" id="willSaveMisc" name='willsavemisc' value="<?php echo $sheet->saves['will']['misc']?>" save='will' />
			<div>&nbsp;</div><div>+</div><div>+</div><div>+</div>
			<label class="labelSubscript">Temp Mod</label>
			<input type="text" name="fortSaveTemp" id="fortSaveTemp" name='fortsavetemp' value="<?php echo $sheet->saves['fort']['temp']?>" save='fort' />
			<input type="text" name="reflexSaveTemp" id="reflexSaveTemp" name='refsavetemp' value="<?php echo $sheet->saves['ref']['temp']?>" save='reflex' />
			<input type="text" name="willSaveTemp" id="willSaveTemp" name='willsavetemp' value="<?php echo $sheet->saves['will']['temp']?>" save='will' />
		</div>
		<div class="containerDefault interior" id="babMelee">
			<div class="labelBox" style="grid-row: 2">Melee</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeTotal">Total</label>
				<input type="text" id="babMeleeTotal" />
			</div>
			<div>&nbsp;</div><div>=</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeBase">Base Attack Bonus</label>
				<input type="text" name="babBase" id="babMeleeBase" value="<?php echo $sheet->baseAttackBonus ?>"/>
			</div>
			<div>&nbsp;</div><div>+</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeAbility">Ability Mod</label>
				<input type="text" id="babMeleeAbility" stat='str' />
			</div>
			<div>&nbsp;</div><div>+</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeSize">Size Mod</label>
				<input type="text" id="babMeleeSize" />
			</div>
			<div>&nbsp;</div><div>+</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeMisc">Misc Mod</label>
				<input type="text" name="babMeleeMisc" id="babMeleeMisc" value="<?php echo $sheet->attackBonuses['melee']['misc'] ?>" />
			</div>
			<div>&nbsp;</div><div>+</div>
			<div class="inputgroup topLabel">
				<label class="labelSubscript" for="babMeleeTemp">Temp Mod</label>
				<input type="text" name="babMeleeTemp" id="babMeleeTemp" value="<?php echo $sheet->attackBonuses['melee']['temp'] ?>" />
			</div>
		</div>
		<div class="containerDefault interior" id="babRange">
			<div class="labelBox" style="grid-row: 1">Range</div>
			<div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeTotal">Total</label>
				<input type="text" id="babRangeTotal" />
			</div>
			<div>=</div><div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeBase">Base Attack Bonus</label>
				<input type="text" id="babRangeBase" value="<?php echo $sheet->baseAttackBonus ?>" />
			</div>
			<div>+</div><div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeAbility">Ability Mod</label>
				<input type="text" id="babRangeAbility" stat='dex' />
			</div>
			<div>+</div><div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeSize">Size Mod</label>
				<input type="text" id="babRangeSize" />
			</div>
			<div>+</div><div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeMisc">Misc Mod</label>
				<input type="text" name="babRangeMisc" id="babRangeMisc" value="<?php echo $sheet->attackBonuses['range']['temp'] ?>" />
			</div>
			<div>+</div><div>&nbsp;</div>
			<div class="inputgroup bottomLabel">
				<label class="labelSubscript" for="babRangeTemp">Temp Mod</label>
				<input type="text" name="babRangeTemp" id="babRangeTemp" value="<?php echo $sheet->attackBonuses['range']['temp'] ?>" />
			</div>
		</div>
		<div class="containerDefault interior" id="portrait">
			<?php if (empty($sheet->image)) { ?>
			<p style="">Please click to upload a picture</p>
			<?php } else { ?>
			<img src="<?php echo "portraits/".$sheet->image; ?>" title="Please click to upload a picture" />
			<?php } ?>
		</div>
	</div>
	
	<div class="containerDefault" id="attacks">
		<div class="containerHeader" onclick="toggleBox('attacks')">Attacks <span class="toggleContainer">&ndash;</span></div>
		<div class="containerBody">
		
		</div>
	</div>
	<div class="containerDefault" id="armor">
		<div class="containerHeader" onclick="toggleBox('armor')">Armor / Shields <span class="toggleContainer" >&ndash;</span></div>
		<div class="containerBody">
		
		</div>
	</div>
	<div class="containerDefault" id="skills">
		<div class="containerHeader" onclick="toggleBox('skills')">Skills <span class="toggleContainer">&ndash;</span></div>
		<div class="containerBody">
			<div class="inputgroup interior">
				<label class="labelSubscript">Skill Name</label>
				<label class="labelSubscript">Key Ability</label>
				<label class="labelSubscript">CC</label>
				<label class="labelSubscript">Skill Mod</label>
				<span>=</span>
				<label class="labelSubscript">Ability Mod</label>
				<span>+</span>
				<label class="labelSubscript">Rank</label>
				<span>+</span>
				<label class="labelSubscript">Misc Mod</label>
			</div>
			<div class="inputgroup">
				<div><input class="underline" type="text" id="skill1name" /></div>
				<div><input class="underline" type="text" id="skill1ability" /></div>
				<input type="checkbox" id="skill1cc" />
				<div><input class="underline" type="text" id="skill1total" /></div>
				<span>=</span>
				<div><input class="underline" type="text" id="skill1ability" /></div>
				<span>+</span>
				<div><input class="underline" type="text" id="skill1rank" /></div>
				<span>+</span>
				<div><input class="underline" type="text" id="skill1misc" /></div>
			</div>
		</div>
	</div>
	<div class="containerDefault" id="feats">
		<div class="containerHeader"onclick="toggleBox('feats')">Feats &amp; Special Abilities<span class="toggleContainer" >&ndash;</span></div>
		<div class="containerBody">
			<div class="inputgroup"><input class="underline" type="text" id="feat1" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat2" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat3" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat4" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat5" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat6" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat7" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat8" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat9" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat10" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat11" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat12" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat13" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat14" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat14" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat15" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat16" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat17" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat18" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat19" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat21" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat22" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat23" /></div>
			<div class="inputgroup"><input class="underline" type="text" id="feat24" /></div>
		</div>
	</div>
	<div class="containerDefault" id="inventory">
		<div class="containerHeader" onclick="toggleBox('inventory')">Other Inventory <span class="toggleContainer">&ndash;</span></div>
		<div class="containerBody">
			<div class="inputgroup interior">
				<label class="labelSubscript">Item</label>
				<label class="labelSubscript">Weight</label>
				<label class="labelSubscript">Loc</label>
			</div>
			<div class="inputgroup" id="item1Group">
				<div><input class="underline" type="text" id="item1Name" /></div>
				<div><input class="underline" type="text" id="item1Weight" /></div>
				<div><input class="underline" type="text" id="item1Loc" /></div>
			</div>
		</div>
	</div>
	<div class="containerDefault" id="spells">
		<div class="containerHeader" onclick="toggleBox('spells')">Spells <span class="toggleContainer">&ndash;</span></div>
		<div class="containerBody">
		
		</div>
	</div>
</div> <!--Close .pageContainer -->
</form>
<div id="modalFileUpload" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<div class="modal-content"><form action="fileUpload.php" method="POST" name="modalFileUploadForm" id="modalFileUploadForm" enctype="multipart/form-data">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Portrait Upload</h4>
		</div>
		<div class="modal-body">
			<input type="hidden" name="uploadSheetId" id = "uploadSheetId" value="<?php echo $_GET['id']; ?>" />
			<div class="input-group">
				<label for="uploadPortrait">Please select an image for upload. Your image file size must be no larger than 500kb, and it is recommended that its dimensions be no larger than 250x362px</label>
				<input type="file" id="uploadPortrait" name="uploadPortrait" />
			</div>
			<br />
			<button type="submit" class="btn btn-danger btn-large center-block" name="portraitClear">Clear Portrait</button>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-success" name="portraitSubmit" id="portraitSubmit">Submit</button>
		</div>
	</form></div>
	</div>
</div>
<script src="js/sheet.js"></script>
</body>
</html>