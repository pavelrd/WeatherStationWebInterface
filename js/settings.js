$(function(){

	serverAddress = $("#server_address").val();
	
	$( "button" ).button({
		showLabel: true
	});	
	
	$( ".jqueryButton" ).button({
		showLabel: true
	});	
	
	$( "#buttonBack" ).button({
		icons: { primary: 'back_icon' },
		showLabel: true,
	});
	
	$("#buttonBack").click(function(){
				
		$(location).attr('href', "index.php");
		
		return false;
		
	});
	
	$("#selectWifiType").change(function(){
		
		var wifiType = $("#selectWifiType option:selected").val();
		
		if ( "wifiDisabled" == wifiType ){
				
			$("#inputAPSSID").prop('disabled', true);
			$("#inputAPPassword").prop('disabled', true);
			$("#inputStationSSID").prop('disabled', true);
			$("#inputStationPassword").prop('disabled', true);

		} else if( "wifiAP" == wifiType ){
			
			$("#inputAPSSID").prop('disabled', false);
			$("#inputAPPassword").prop('disabled', false);
			$("#inputStationSSID").prop('disabled', true);
			$("#inputStationPassword").prop('disabled', true);
			
		} else if( "wifiStation" == wifiType ){
			
			$("#inputAPSSID").prop('disabled', true);
			$("#inputAPPassword").prop('disabled', true);
			$("#inputStationSSID").prop('disabled', false);
			$("#inputStationPassword").prop('disabled', false);	
			
		}
		
	});
	
	lockChange = false
	
	$("#changeWifiSettings").click(function(){
				
		if( lockChange ){
			
			$("#divChangeWifiSettingsState").html("Уже идет сохранение настроек!")
			
			return
			
		} else {
			
			$("#changeWifiSettings").prop( "disabled", true )
			
			$("#divChangeWifiSettingsState").html("Применение настроек...")

			lockChange = true
			
		}		
		
		var tmp;
	
		tmp = new Object();
	
		tmp['ajaxChangeWifiSettings'] = 1;
		
		tmp['mode']            = $('#selectWifiType option:selected').val()
		tmp['APSSID']          = $("#inputAPSSID").val()
		tmp['APPassword']      = $("#inputAPPassword").val()
		tmp['StationSSID']     = $("#inputStationSSID").val()
		tmp['StationPassword'] = $("#inputStationPassword").val()
		
		$.ajax({
			type: "GET",
			url: "http://"+serverAddress+"/settings.php",
			data: tmp,
			timeout: 10000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){

				$("#divChangeWifiSettingsState").html("Настройки сохранены.")

				lockChange = false
				
				$("#changeWifiSettings").prop( "disabled", false )

			},
			
			error: function(a,b,c){
				
				$("#divChangeWifiSettingsState").html("Ошибка при сохранении настроек!")

				lockChange = false
				
				$("#changeWifiSettings").prop( "disabled", false )

			} 
			
		});			
		
		return false;
		
	});
	
	
	
});