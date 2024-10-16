
	var monthNames = [ "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря" ]; 
	var dayNames= ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"]

	var serverAddress;
	var timestamp;
	var tmr;
	var errorFlag = 0;
	
	function updateData(){
	
		var tmp;
		
		tmp = new Object();
		
		tmp['getClimateData'] = 1;
				
		$.ajax({
			type: "GET",
			url: "http://"+serverAddress+"/climateStation.php",
			data: tmp,
			timeout: 900,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
					
					if( errorFlag ){
					
						$("#box1").show(200, function(){ 
							$("#box2").show(200, function(){
								$("#box3").show(200, function(){
									$("#box4").show(200, function(){
										$("#box5").show(200, function(){
											$("#box6").show(200, function(){
											});
										});
									});
								});
							});
						});
						
						errorFlag = 0;
					}
				
					 setSensor( msg["voc"], 4, setVOC );
					 setSensor( msg["humiduty"], 1, setHumiduty );
					 setSensor( msg["pressure"], 3, setPressure );
					 setSensor( msg["temperature"], 2, setTemperature );
					 setSensor( msg["carbonDioxide"], 6, setCarbonDioxide );
					 setSensor( msg["externalTemperature"], 5, setExternalTemperature );
					 
					 setTimeout(updateData, 1000);

			},
			error: function(a,b,c){
			
					errorFlag = 1;
					
					$("#box6").hide(200, function(){ 
						$("#box5").hide(200, function(){ 
							$("#box4").hide(200, function(){
								$("#box3").hide(200, function(){
									$("#box2").hide(200, function(){
										$("#box1").hide(200, function(){
										});
									});
								});
							});
						});
					});
					
					setTimeout(updateData,1000);
					
				}
				
		});	
		
	}

	function resizeElements(){
		
		var wMin, hMin;
				
		wMin = parseInt($(window).width()) / parseInt(22);
		hMin = parseInt($(window).height()) / parseInt(12);
		
		if( wMin < hMin ){
		
			$("#Date").css("font-size", wMin);
			
			$("ul li").css("font-size", wMin*1.7);
			
			$(".sensorHeaderText").css("font-size",wMin/2.5);
			$(".sensorFooter").css("font-size",wMin/2.5);

		} else {
		
			$("#Date").css("font-size", hMin);
			
			$("ul li").css("font-size", hMin*1.7);
			
			$(".sensorHeaderText").css("font-size",hMin/2.5);
			$(".sensorFooter").css("font-size",hMin/2.5);
			
		}

		wthe = $("#bg").width()
		hgte = $("#bg").height()		

		if( ( wthe / hgte ) < 1.3 ){
			
			//wthe = 
			//hgte = 
			
			tWidth  = wthe / 1.8
			tHeight = hgte / 1.8
			
			while( ( tWidth / tHeight ) < 1.2 ){
				tHeight -= 1
			}
			
			// Узнал новую высоту которая даст пропорциональный блок
			
			// Добавляем отступ сверху для блока
			
			// 
			$("#blockSensorsData").css( "height", tHeight+"px" );
			$("#blockSensorsData").css( "top", (( (hgte / 1.5) - tHeight) )+"px" );

		} else {
			// Убирает отступ блока
			//  Задает стиль в процентах
			$("#blockSensorsData").css( "height", "65%" );
			$("#blockSensorsData").css( "top","7%" );

			// height: 68%;
		}
		
		
		var wth, hgt;
		
		wth = $("#box1").width()
		hgt = $("#box1").height()
		
		// 402   ---- 396
		// 295   ---- 487
		
		// 1.3627
		
		// 0.813
		

		
		// width / height > 0.9
		
		
		//console.log("width: "+wth);  // 157.883
		//console.log("height: "+hgt); // 164.95
		
		// 153
		// 213
		
		// wth /= 1.2;
		
		if( wth > hgt ){
			
			wth = hgt;
			
		}
		
		$("#circleTemperature").trigger('configure',{
			width: wth
		});
		
		$("#circlePressure").trigger('configure',{
			width: wth
		});
				
		$("#circleHumiduty").trigger('configure',{
			width: wth
		});

		$("#circleVOC").trigger('configure',{
			width: wth
		});

		$("#circleCarbonDioxide").trigger('configure',{
			width: wth
		});
		
		$("#circleExternalTemperature").trigger('configure',{
			width: wth
		});	
		
	}

	function setKnob(){
		
		var min;
		var max;
		
		min = 0;
		max = 100;
		
		$("#box1Min").html(min);
		$("#box1Max").html(max);
		
		$("#circleHumiduty").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			readOnly: true
		});
		
		min = 10;
		max = 40;
		
		$("#box2Min").html(min);
		$("#box2Max").html(max);
		
		$("#circleTemperature").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			fgColor: "#84cfef",
			readOnly: true
		});
		
		min = 704;
		max = 790;
		
		$("#box3Min").html(min);
		$("#box3Max").html(max);
		
		$("#circlePressure").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			fgColor: "#84cfef",
			readOnly: true
		});
		
		min = 0;
		max = 1024;
		
		$("#box4Min").html(min);
		$("#box4Max").html(max);
		
		$("#circleVOC").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			readOnly: true
		});
		
		min = -35;
		max = 35;
		
		$("#box5Min").html(min);
		$("#box5Max").html(max);
		
		$("#circleExternalTemperature").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			readOnly: true
		});
		
		min = 350;
		max = 2500;
		
		$("#box6Min").html(min);
		$("#box6Max").html(max);
		
		$("#circleCarbonDioxide").knob({
			angleArc: 180,
			angleOffset: -90,
			min: min,
			max: max,
			width: 200,
			readOnly: true
		});
		
	}
	
$( document ).ready(function() {

	var fireTimer;
	
	fireTimer = 0;
	
	function endFire(){
		
		$("#bg").fireworks("destroy");
		
		fireTimer = 0;
		
	}
	
	 $("html").keydown(function(eventObject){
		 

		  if( eventObject.keyCode == 32 ){
			  				
				if( fireTimer != 0 ){
					
					$("#bg").fireworks("destroy");
					
					fireTimer = 0;
					
					return;
					
				}
								
				$("#bg").fireworks();
				
				fireTimer = 1;
				
		  }
		
		 
	 });

	serverAddress = $("#server_address").val();
	
	$(window).resize( function(){
		
		resizeElements();
		
	});
	
	setKnob();
	
	$("#Date").html("Установка соединения...");
	
	$("#sec").html("--");
	$("#min").html("--");
	$("#hours").html("--");
	
	updateTime();
	
	setInterval(updateTime, 10000);
	setTimeout(updateData, 1);
	
	resizeElements();

});


function setPressure(pressure){

	$("#circlePressure").val(pressure).trigger('change');
	
}

function setTemperature(temperature){
		
	var colour;
	
	if( temperature < 17 ){
		
		colour = "blue";
		
	} else if( temperature < 23 ){
		
		colour = "#84cfef";
		
	} else if ( temperature < 28 ){
		
		colour = "green";
		
	} else if( temperature < 32 ){
		
		colour = "yellow";
	
	} else {
		
		colour = "red";
		
	}

	$("#circleTemperature").trigger(
		'configure',
		{
			'fgColor' : colour,
			'inputColor' : colour
		}
	);
		
	$("#circleTemperature").val(temperature).trigger('change');

}

function setHumiduty(hum){

	$("#circleHumiduty").val(hum).trigger('change');
	
}

function setExternalTemperature(temperature){
	
	$("#circleExternalTemperature").val(temperature).trigger('change');

	
}

function setCarbonDioxide(carbon){
	
	/*
	$("#circleCarbonDioxide").knob({
		angleArc: 180,
		angleOffset: -90,
		min: 350,
		max: 2000,
		fgColor: "green",
		width: 240,
		readOnly: true
	});
	*/
	
	if( carbon < 1000 ){
		colour = "green";
	} else if( carbon < 1450 ){
		colour = "yellow";
	} else if( carbon < 1600 ){
		colour = "orange";
	} else {
		colour = "red";
	}
	
	$("#circleCarbonDioxide").trigger(
		'configure',
		{
			'fgColor' : colour,
			'inputColor' : colour
		}
	);	

	$("#circleCarbonDioxide").val(carbon).trigger('change');

}

function setVOC(voc){

	$("#circleVOC").val(voc).trigger('change');
	
}

function setDust(dust){
	
	$("#circleDust").val(dust).trigger('change');
	
}

function updateTime() {
	
	var tmp = new Object();
	
	tmp['getTime'] = 1;
	
		$.ajax({
			type: "GET",
			url: "http://"+serverAddress+"/climateStation.php",
			data: tmp,
			timeout: 1000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
			
					var dt = new Date(msg["time"]*1000);
					
					// Output the day, date, month and year    
					$('#Date').html(dayNames[dt.getDay()] + " " + dt.getDate() + ' ' + monthNames[dt.getMonth()] + ' ' + dt.getFullYear());
					
					// Create a newDate() object and extract the seconds of the current time on the visitor's
					//var seconds = dt.getSeconds();
					// Add a leading zero to seconds value
					//$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
					var minutes = dt.getMinutes();
					// Add a leading zero to the minutes value
					$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
					var hours = dt.getHours();
					// Add a leading zero to the hours value
					$("#hours").html(( hours < 10 ? "0" : "" ) + hours);

			},
			error: function(a,b,c){
					
					$("#hours").html("--");
					$("#min").html("--");
					$("#sec").html("--");	
					$("#Date").html("Нет соединения!");
					
				}
		});
		
}

	function setSensorReadyOn( id ){
		
		$( "#box"+id+" .sensorCircle" ).show();
		$( "#box"+id+" .sensorError" ).html("");
		$( "#box"+id+" .sensorError" ).hide();
		
	}
	
	function setSensorReadyOff( id, state, exinfo ){
		
		if( state == 0 ){
		
			$("#box"+id+" .sensorCircle").hide();
			$("#box"+id+" .sensorError").html("<p class=\"errorFontSize\">Ошибка: не работает программа получения данных! "+exinfo+"</p>");
			$("#box"+id+" .sensorError").show();
			
		} else if( state == 1){
		
			$("#box"+id+" .sensorCircle").hide();
			$("#box"+id+" .sensorError").html("<p class=\"errorFontSize\">Ошибка: потеряно соединение с датчиком! "+exinfo+"</p>");
			$("#box"+id+" .sensorError").show();
			
		} else if( state == 2 ){
		
			$("#box"+id+" .sensorCircle").hide();
			$("#box"+id+" .sensorError").html("<p class=\"errorFontSize\">Датчик прогревается"+exinfo+"</p>");
			$("#box"+id+" .sensorError").show();
			
	    }		
		

							
	}

	
	function setSensor(sensor, sensorBlockId, setfunction ){
					 
					 if( sensor["ready"] ){
						
						if( sensor["state"] == "connected" ){
							
							setSensorReadyOn(sensorBlockId);
							
							setfunction(sensor["value"]);

						} else if( sensor["state"] == "preheat"){
							
							if( sensor["preheatSeconds"] > 0 ){
							
								setSensorReadyOff( sensorBlockId, 2, ", осталось "+sensor["preheatSeconds"]+" секунд..." );
								
							} else {
							
								setSensorReadyOff( sensorBlockId, 2, "..." );

							}
							
						} else if( sensor["state"] == "disconnected" ){
							
							setSensorReadyOff( sensorBlockId, 1, "" );

						}
						
					 } else {
						
						setSensorReadyOff( sensorBlockId, 0, "" );
						
					 }
					 
	}