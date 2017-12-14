//Function to process ability score modifiers
function abilityModifier(abilityScore) {
	var mod = "";
	if (Number.isInteger(Number(abilityScore)) && abilityScore != "") {
		mod = Math.floor((abilityScore-10)/2);
	} else { 
		mod = "";
	}
	return mod;
}

function updateBaseAbilityMod(ability) {
	var source = document.getElementById(ability + 'Base').value;
		sourceMod = abilityModifier(source);
	
	$('#'+ability+'Mod').val(sourceMod);
	if(!$('#'+ability+'TempBase').val()) {
		$("[stat="+ability+"]").val(sourceMod);
	}
	switch(ability) {
		case 'str':
			sumAttack();
			break;
		case 'dex':
			sumInit();
			sumAC();
			sumSave('reflex');
			sumAttack();
			break;
		case 'con':
			sumSave('fort');
			break;
		case 'wis':
			sumSave('will');
			break;
	}
}

function updateTempAbilityMod(ability) {
	var source = document.getElementById(ability + 'TempBase').value;
		sourceTempMod = abilityModifier(source);
		sourceMod = document.getElementById(ability+'Mod').value;
		
	$('#'+ability+'TempMod').val(sourceTempMod);
	if(sourceTempMod=="" && sourceMod != "") {
		$("[stat="+ability+"]").val(sourceMod);
	} else {
		$("[stat="+ability+"]").val(sourceTempMod);
	}
}

//Summation devices for armor class, initiative, saves, and attack modifiers
function sumAC() {
	var sum = Number($('#acBase').val())
		+ Number($('#acArmor').val())
		+ Number($('#acShield').val())
		+ Number($('#acDex').val())
		+ Number($('#acSize').val())
		+ Number($('#acNatural').val())
		+ Number($('#acDeflect').val())
		+ Number($('#acMisc').val());
	document.getElementById('acTotal').value = sum;
}
function sumInit() {
	var sum = Number($('#initDex').val()) + Number($('#initMisc').val());
	document.getElementById('initTotal').value = sum;
}
function sumSave(save) {
	var sum = Number($('#'+save+'SaveBase').val())
		+ Number($('#'+save+'SaveAbility').val())
		+ Number($('#'+save+'SaveMagic').val())
		+ Number($('#'+save+'SaveMisc').val())
		+ Number($('#'+save+'SaveTemp').val());
	document.getElementById(save+'SaveTotal').value = sum;
}

function sumAttack() {
	var sumMelee = Number($('#babMeleeBase').val())
		+ Number($('#babMeleeAbility').val())
		+ Number($('#babMeleeSize').val())
		+ Number($('#babMeleeMisc').val())
		+ Number($('#babMeleeTemp').val());
	var sumRange = Number($('#babRangeBase').val())
		+ Number($('#babRangeAbility').val())
		+ Number($('#babRangeSize').val())
		+ Number($('#babRangeMisc').val())
		+ Number($('#babRangeTemp').val());
	document.getElementById('babMeleeTotal').value = sumMelee;
	document.getElementById('babRangeTotal').value = sumRange;
}

//Update Size parameters
function changeSize() {
	var size = document.getElementById('size').value;
	var sizeMod = "";
	
	switch(size) {
		case "S":
		case "s":
		case "Small":
		case "small":
			sizeMod = 1;
			break;
		
	}
}

$(document).ready(function() {
	$("#portrait").click(function(){
		$("#modalFileUpload").modal();
	});
	
	//Ability Score propagators
	$('#strBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("str");
	});
	
	$('#strTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("str");
	});
	
	$('#dexBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("dex");
	});
	
	$('#dexTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("dex");
	});
	
	$('#conBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("con");
	});
	
	$('#conTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("con");
	});
	
	$('#intBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("int");
	});
	
	$('#intTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("int");
	});

	$('#wisBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("wis");
	});
	
	$('#wisTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("wis");
	});
	
	$('#chaBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateBaseAbilityMod("cha");
	});
	
	$('#chaTempBase').change(function() {
		var mod = abilityModifier($(this).val());
		updateTempAbilityMod("cha");
	});
	
	//Summers for totaled values
	$('#armorClass').find('input').change(function() { sumAC(); });
	$('#initGroup').find('input').change(function() { sumInit(); });
	$('#saves').find('input').change(function() {
		var save = $(this).attr('save');
		
		sumSave(save);
	});
	$('#babMelee').find('input').change(function() {
		if($(this).attr('id') == 'babMeleeBase') {
			$('#babRangeBase').val($(this).val());
		}
		sumAttack(); 
	});
	$('#babRange').find('input').change(function() {
		if($(this).attr('id') == 'babRangeBase') {
			$('#babMeleeBase').val($(this).val());
		}
		sumAttack(); 
	});
	
	//Begin initial calculation for all derived terms
	updateBaseAbilityMod('str');
	updateBaseAbilityMod('dex');
	updateBaseAbilityMod('con');
	updateBaseAbilityMod('int');
	updateBaseAbilityMod('wis');
	updateBaseAbilityMod('cha');
	updateTempAbilityMod('str');
	updateTempAbilityMod('dex');
	updateTempAbilityMod('con');
	updateTempAbilityMod('int');
	updateTempAbilityMod('wis');
	updateTempAbilityMod('cha');
	sumAC();
	sumInit();
	sumAttack();
	sumSave('fort');
	sumSave('reflex');
	sumSave('will');
});