$(function(){
			
	$( "#buttonWeatherMonth" ).button({
		showLabel: true
	});
	
	$( "#buttonWeatherWeek" ).button({
		showLabel: true
	});
	
	$( "#buttonWeatherYesterday" ).button({
		showLabel: true
	});
	
	$( "#buttonWeatherUserSelect" ).button({
		showLabel: true
	});
	
	$( "#buttonWeatherToday" ).button({
		showLabel: true
	});
	
	$( "#buttonBack" ).button({
		icons: { primary: 'back_icon' },
		showLabel: true,
	});

	pickmeup.defaults.locales['ru'] = {
		days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
		daysShort: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		daysMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		monthsShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек']
	};
	
	pickmeup( '#inputCalendarChoise', {
		mode       : 'range',
		hide_on_select : true,
		calendars : 2,
		format: 'd-m-Y',
		min: '30-11-17',
		locale: 'ru',
		separator: '/'
	});
	
	var calendarElement = document.getElementById("inputCalendarChoise");
	
	var dateString;
	
	calendarElement.addEventListener('pickmeup-hide', function (e) {
		
		if( dateString != $("#inputCalendarChoise").val()  ){
		
			dateIntervalChanged( $("#inputCalendarChoise").val() );
		
		}
		
	});
	
	calendarElement.addEventListener('pickmeup-show', function (e) {
		
		dateString = $("#inputCalendarChoise").val();
		
	});
	
	
	$("#buttonWeatherMonth").click(function(){
		
		var darr = [];
		
		darr[0] = new Date; // начало диапазона времени
		darr[1] = new Date;
		
		darr[0].setDate( darr[1].getDate() - 31 );
		
		pickmeup('#inputCalendarChoise').set_date( darr );
		
		dateIntervalChanged( $("#inputCalendarChoise").val() );
			
	});
	
	$("#buttonWeatherWeek").click(function(){
		
		var darr = [];
		
		darr[0] = new Date; // начало диапазона времени
		darr[1] = new Date;
		
		darr[0].setDate( darr[1].getDate() - 7 );
		
		pickmeup('#inputCalendarChoise').set_date( darr );
		
		dateIntervalChanged( $("#inputCalendarChoise").val() );
		
	});
	
	$("#buttonWeatherYesterday").click(function(){
		
		var darr = [];
		
		darr[0] = new Date; // начало диапазона времени
		darr[1] = new Date;
		
		darr[0].setDate( darr[0].getDate() - 1 );
		darr[1].setDate( darr[1].getDate() - 1 );
		
		pickmeup('#inputCalendarChoise').set_date( darr );
		
		dateIntervalChanged( $("#inputCalendarChoise").val() );
		
	});
	
	$("#buttonWeatherToday").click(function(){
		
		var darr = [];
		
		darr[0] = new Date; // начало диапазона времени
		darr[1] = new Date;
			
		pickmeup('#inputCalendarChoise').set_date( darr );
		
		dateIntervalChanged( $("#inputCalendarChoise").val() );
			
	});
 	
	$("#buttonBack").click(function(){
				
		$(location).attr('href', "index.php");
		
	});
	
	$("#selectGraph1").change(function(){
		
		var dates = $("#inputCalendarChoise").val().split('/');
		
		updateGraph( "weatherGraph1", dates[0], dates[1], $(this).val() );
			
	});
	
	$("#selectGraph2").change(function(){
	
		var dates = $("#inputCalendarChoise").val().split('/');
		
		updateGraph( "weatherGraph2", dates[0], dates[1], $(this).val() );
		
	});
	
});

function dateIntervalChanged( dateInterval ){
	
	var dates = $("#inputCalendarChoise").val().split('/');
		
	updateGraph( "weatherGraph1", dates[0], dates[1], $("#selectGraph1").val() );
	updateGraph( "weatherGraph2", dates[0], dates[1], $("#selectGraph2").val() );
	
}

var flagGraphArray = [];

/**
	
	\brief Обновляет выбранный график
	\param graphDiv div блок(объект блока) с графиков
	\param startDate начальная дата
	\param endDate конечная дата
	\param sensorName название датчика
	
*/

function updateGraph( graphDivId, startDate, endDate, sensorName ){
	
	for( i = 0; i < flagGraphArray.length; i++ ){
		
		if( flagGraphArray[i] == graphDivId ){
			
			$("#"+graphDivId).html("График уже загружается!");
			
			return;
		}
	}
	
	$("#"+graphDivId).unbind(); //Remove a previously-attached event handler from the elements.
	$("#"+graphDivId).empty();
		
	$("#"+graphDivId).html("Загрузка графика...");
		
	flagGraphArray.push( graphDivId );
	
	var tmp = new Object();
	
	tmp['getWeatherData'] = true;
	tmp['sensorName']     = sensorName;
	tmp['startDate']      = startDate;
	tmp['endDate']        = endDate;
	
	$.ajax({
		type: "GET",
		url: "http://"+$("#inputServerAddress").val()+"/climateGraphs.php",
		data: tmp,
		timeout: 10000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "json",
		processData: true,
		success: function(msg){

			var options = {
				selection: { mode: "x" },
				// pan: {
				//        interactive: true
				//    },
				xaxis: {
					mode: "time",
					show: true,
					position: "bottom",
					monthNames: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
					dayNames: ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"]
					
					//transform: function (v) { if ($.inArray(v, timestamps) > -1){return v} else {return null} }, // function (v) { return Math.log(v); },
					// inverseTransform: function (v) { return Math.exp(v); }

				//position: "bottom",
				// color: "#323232",
				//font: {
				//    size: 10,
				//     lineHeight: 15,
				// },
				// labelHeight: 15,
				
				},
				selection: {
					mode: "x"
				},
				yaxis: {
				show: true,
				position: "left",
				// position: "left",
				// color: "#323232",
				// labelWidth: 20,
				// font: {
				//     size: 10,
				//  },
				//  max: 150,
				//  min: 0,
				//  panRange: false
				},   
				series: {
					lines:  { 
						show: true,
						steps: true
					},
					points: { show: false }
				},
				grid: {
					clickable: false,
					hoverable: false
				}
			};			
			
			var data = [[]]; 
			
			var sensorDescription; // Температура, влажность и др.
			var sensorColour;
			
			switch( sensorName ){
				case 'humiduty'                 : sensorDescription = "Относительная влажность, %"; break;
				case 'indoorTemperature'        : sensorDescription = "Температура в комнате, C"; break;
				case 'atmospherePressure'       : sensorDescription = "Атмосферное давление, мм.рт.ст"; break;
				case 'volatileOrganicCompounds' : sensorDescription = "Органические вещества, ед."; break;
				case 'outdoorTemperature'       : sensorDescription = "Температура на улице, С"; break;
				case 'carbonDioxide'            : sensorDescription = "Углекислый газ, ppm(частей на миллион)"; break;
				default : sensorDescription = "ошибка";
			};
			
			
			$("#"+graphDivId).unbind(); //Remove a previously-attached event handler from the elements.
			$("#"+graphDivId).empty();
			
			if( msg['sensorTimestamps'].length > 0  ){
			
				for( i=0; i < msg['sensorTimestamps'].length; i++ ){
				
					data.push([ msg['sensorTimestamps'][i] * 1000, msg['sensorValues'][i] ]);
			
				}
						
				var graphLines = [
					{ label: sensorDescription, data: data }
				];
				
				$.plot( $("#"+graphDivId), graphLines, options );				

			} else {
				
				$("#"+graphDivId).html("Нет данных за указанный период!");
				
			}
			
			flagGraphArray.splice( flagGraphArray.indexOf(graphDivId), 1 );
			
		},
		error: function(a,b,c){
			
			$("#"+graphDivId).html("Ошибка при загрузке данных!");
			
			flagGraphArray.splice( flagGraphArray.indexOf(graphDivId), 1 );
				
		}  
	});

}