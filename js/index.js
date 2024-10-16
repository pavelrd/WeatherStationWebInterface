
$(function(){
		
	$( "#buttonClimateStation" ).button({
		icons: { primary: 'climate_icon' },
		showLabel: true,
		iconPosition: "end"
	});
	
	
	$( "#powerOffButton" ).button({
		showLabel: true,
	});
	
	$( "#buttonBirthdays" ).button({
		showLabel: true,
	});
	
	$( "#buttonSettings" ).button({
		showLabel: true,
	});
	
	$( "#slider" ).slider({
		range: false,
		values: [ 0, 4096 ]
	});

	$("#buttonBirthdays").click(function(){
		
		$(location).attr('href', "calendar.php");
		
	});
	
	$("#buttonClimateStation").click(function(){
		
		$(location).attr('href', "climateStation.php");
		
	});

	$("#buttonClimateGraphs").click(function(){
		
		$(location).attr('href', "climateGraphs.php");
		
	});
	
	$("#buttonSettings").click(function(){
		
		$(location).attr('href', "settings.php");
		
	});
	
	$("#powerOffButton").click(function(){
		
		$(location).attr('href', "powerOff.php");
		
	});
	
});