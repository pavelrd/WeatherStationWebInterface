$(function(){

	serverAddress = $("#server_address").val();
	
	currentYear = (new Date()).getFullYear()
	
	dates = []
	
	$( "button" ).button({
		showLabel: true
	});
	
	$( ".jqueryButton" ).button({
		showLabel: true
	});	
	
	$("#buttonBack").click(function(){
				
		$(location).attr('href', "calendar.php")
		
		return false;
		
	});
	
	$("#selectDaysOfWeek").click( function(){
		
		sday = $("#selectedDayOfWeek").val()
		
		dt = new Date(currentYear,0,1)

		console.log(sday+"  "+currentYear)
		
		// Проход по всем месяцам текущего года
		
		for( i = 0 ; i < 370 ; i++ ){
			
			if( dt.getFullYear() != currentYear ) {
				break;
			}
			
			if( dt.getDay() == sday ){
			
				dat = dt.getDate() + '/' + (dt.getMonth() + 1)
				
				dates.push(dat)
				
			}
			
			dt.setDate( dt.getDate() + 1 )

		}
		
		$(".box").addClass("hidden")
		makeCalendar( "#divMoth", currentYear, hFreeDaysCallback, selectFreeDaysCallback )	
		$(".box").removeClass("hidden")

	});
	
	$("#unselectAllDays").click( function(){
		
		dates = []
		
		$(".box").addClass("hidden")
		makeCalendar( "#divMoth", currentYear, hFreeDaysCallback, selectFreeDaysCallback )	
		$(".box").removeClass("hidden")

	});
	
	$("#buttonPrevYear").click(function(){
		
		currentYear -= 1
		
		makeFreeDaysCalendarForYear(currentYear)
		
		return false;
		
	});
	
	$("#buttonNextYear").click(function(){
	
		currentYear += 1
		
		makeFreeDaysCalendarForYear(currentYear)

		return false;
		
	});
	
	$( "#accordion" ).accordion({
		collapsible: true,
		active: false,
		heightStyle: "content",
		activate: function(event, ui ){
			if ( typeof(ui.newPanel[0]) !== 'undefined' ){
				
				if( ui.newPanel[0].id == "divFreeDaysPanel" ){
					
					makeFreeDaysCalendarForYear(currentYear)
					
				}
				
			}
		}
		
	});
	
	$("#inputBirthday").datepicker({
		dayNames        : dNames,
		dayNamesMin     : dNamesMin,
		dayNamesShort   : dNamesShort,
		monthNames      : mNames,
		monthNamesShort : mNamesShort,
		firstDay: 1,
		changeMonth: true,
		changeYear: false,
		selectOtherMonths: true,
		showButtonPanel: false,
		hideIfNoPrevNext: true,
		dateFormat: "dd-mm"
	});
	
	$("#colorCalendar").datepicker({
		dayNames        : dNames,
		dayNamesMin     : dNamesMin,
		dayNamesShort   : dNamesShort,
		monthNames      : mNames,
		monthNamesShort : mNamesShort,
		firstDay: 1,
		changeYear: false,
		beforeShowDay: highlightDays,
		dateFormat: "dd-mm"
	});
	
	$(".ui-datepicker-year").hide()
	
	$("#divCurrentYear").html(currentYear)
	
	$(".buttonDeleteUser").bind( "click", deleteUser )
	$(".buttonChangeUser").bind( "click", changeUser )
	
	var tmp
	
	tmp = new Object()
	
	tmp['getCalendarColors'] = 1
		
	$.ajax({
		
		type: "GET",
		url: "http://"+serverAddress+"/calendarSettings.php",
		data: tmp,
		timeout: 3000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "json",
		processData: true,
		success: function(msg){
			
			replaceColor( "highlightFreeDays",  "freeDays",  msg["freeDayBackground"], msg["freeDayBorder"] )
			replaceColor( "highlightBirthdays", "birthDays", msg["birthDayBackground"], msg["birthDayBorder"] )
			replaceColor( "highlightGoodDays",  "goodDays",  msg["goodDayBackground"], msg["goodDayBorder"] )
			
			$("#divFreeDayColor").iris({
				color: msg["freeDayBackground"],
				hide: false,
				change: function(event, ui) {
			
				changeDayBackgroundColor( "td.freeDays", ui.color.toString() )
		
				}
			});			

			$("#divFreeDayBorderColor").iris({
				color: msg["freeDayBorder"],
				hide: false,
				change: function(event, ui) {
			
					changeDayBorderColor( "td.freeDays", ui.color.toString() )
			
				}
			});
				
			$("#divBirthDayColor").iris({
				color: msg["birthDayBackground"],
				hide: false,
				change: function(event, ui) {
					changeDayBackgroundColor( "td.birthDays", ui.color.toString() )
				}
			});			

			$("#divBirthDayBorderColor").iris({
				color: msg["birthDayBorder"],
				hide: false,
				change: function(event, ui) {
					changeDayBorderColor( "td.birthDays", ui.color.toString() )
				}
			});

	
			$("#divFreeAndBirthDayColor").iris({
				color: msg["goodDayBackground"],
				hide: false,
				change: function(event, ui) {
					changeDayBackgroundColor( "td.goodDays", ui.color.toString() )
				}
			});			

			$("#divFreeAndBirthDayBorderColor").iris({
				color: msg["goodDayBorder"],
				hide: false,
				change: function(event, ui) {
					changeDayBorderColor( "td.goodDays", ui.color.toString() )
				}
			});
			
		},
		error: function(a,b,c){
			console.log('error');	
		}  
	});
	
	$("#applyColors").click( function(){
		
		$("#divApplyColorsState").html("Применение новых цветов")

		var tmp;
	
		tmp = new Object()
	
		tmp['changeCalendarColors'] = 1
		
		tmp['freeDayBackground']  = rgb2hex($(".freeDays").first().children().css("background-color"))
		tmp['freeDayBorder']      = rgb2hex($(".freeDays").first().children().css("border-left-color"))
		tmp['birthDayBackground'] = rgb2hex($(".birthDays").first().children().css("background-color"))
		tmp['birthDayBorder']     = rgb2hex($(".birthDays").first().children().css("border-left-color"))
		tmp['goodDayBackground']  = rgb2hex($(".goodDays").first().children().css("background-color"))
		tmp['goodDayBorder']      = rgb2hex($(".goodDays").first().children().css("border-left-color"))
		
		$.ajax({
			type: "GET",
			url: "http://"+serverAddress+"/calendarSettings.php",
			data: tmp,
			timeout: 3000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
				
				$("#divApplyColorsState").html("Новые цвета применены")
			
			},
			error: function(a,b,c){
				
				$("#divApplyColorsState").html("Не удалось применить новые цвета!")
				
			}  
		});			
		
		return false;		
	});
	
	$("#buttonApplyFreeDays").click( function(){
		
		$("#divApplyFreeDaysState").html("Применение настроек...")

		var tmp;
	
		tmp = new Object()
	
		tmp['year'] = currentYear
		tmp['dates'] = dates
		
		$.ajax({
			type: "POST",
			url: "http://"+serverAddress+"/setDates.php",
			data: tmp,
			timeout: 5000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
				
				$("#divApplyFreeDaysState").html("Применены успешно.")
			
			},
			error: function(a,b,c){
				
				$("#divApplyFreeDaysState").html("Ошибка!")
				
			}  
		});			
		
		return false;		
	});
	
	$("#buttonGoBack").click(function(){
				
		$(location).attr('href', "calendar.php");
		
		return false;
		
	});
	

	
	$("#addUser").click(function(){
		
		var tmp;
	
		tmp = new Object()
	
		tmp['ajaxAddUser'] = 1
		
		tmp['name']       = $("#inputName").val()
		tmp['lastname']   = $("#inputLastName").val()
		tmp['secondname'] = $("#inputSecondName").val()
		tmp['message']    = $("#inputMessage").val()
		tmp['birthday']   = $("#inputBirthday").val()
		
		if( tmp['name'].length <= 0 ) { alert("Напишите имя!"); return false; }
		if( tmp['birthday'].length <= 0 ) { alert("Выберите день рождения!"); return false; }
		
		$.ajax({
			type: "GET",
			url: "http://"+serverAddress+"/calendarSettings.php",
			data: tmp,
			timeout: 3000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
				
				updateBirthdaysTable()
				
			},
			error: function(a,b,c){
				console.log('error');	
			}  
		});			
		
		return false;
		
	});
	
	
	
});

function changeUser(){
	
	var id
	
	id = parseInt($(this).attr("id").replace(/\D+/g,""))
		
	var tmp
		
	tmp = new Object()
		
	tmp['name']       = $("#inputFirstName"+id).val()
	tmp['lastname']   = $("#inputLastName"+id).val()
	tmp['secondname'] = $("#inputSecondName"+id).val()
	tmp['message']    = $("#inputMessage"+id).val()	
		
	tmp['ajaxChangeUser'] = id;
				
	$.ajax({
		type: "GET",
		url: "http://"+serverAddress+"/calendarSettings.php",
		data: tmp,
		timeout: 3000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "json",
		processData: true,
		success: function(msg){
			
			updateBirthdaysTable()
				
		},
		error: function(a,b,c){
			
			console.log('error');
				
		}  
	});	

}

function deleteUser(){
		
	var id;
		
	id = parseInt($(this).attr("id").replace(/\D+/g,""));
		
	var tmp;
		
	tmp = new Object();
		
	tmp['ajaxDeleteUser'] = id;
		
	$.ajax({
		type: "GET",
		url: "http://"+serverAddress+"/calendarSettings.php",
		data: tmp,
		timeout: 3000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "json",
		processData: true,
		success: function(msg){
			
			updateBirthdaysTable()
				
		},
		error: function(a,b,c){
			
			console.log('error')
				
		}  
	});			
		
}

function updateBirthdaysTable(){
	
	var tmp

	tmp = new Object()
				
	$.ajax({
		type: "GET",
		url: "http://"+serverAddress+"/birthdaysTable.php",
		data: tmp,
		timeout: 5000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "html",
		processData: true,
		success: function(payload) {
					$("#usersTable").html(payload)
	
					$(".buttonDeleteUser").bind( "click", deleteUser )
					$(".buttonChangeUser").bind( "click",   changeUser   )
	
					$( "button" ).button({
						showLabel: true
					});
				},
		error:  function(){
		
		}
	});
				
}


function highlightDays(date){
		
	currentDate = new Date();
	
	if( date.getDay() == 0 || (date.getDay() == 6) ){
		
		// Выходной день
		
		if( date.getDate() < 7 ){
			// Выходной и день рождения
						
			return [true, 'highlightGoodDays', "День рождения и выходной день" ]
			
		}
		
		return [true, 'highlightFreeDays', "Выходной день" ]
		
	} else {
		
		if( (date.getDate() > 10) && ( date.getDate() < 14 ) ){
			return [true, 'highlightBirthdays', "День рождения" ];
		}
		
		return [true]
		
	}
	
}

function selectFreeDaysCallback( dateText, inst ){
	
	// Возможность и добавить выделение и убрать его
	
	// Убираем ведущие нули у даты
		
	var tdx = dateText.split('/')
	
	var tx = parseInt(tdx[0]) + "/" + parseInt(tdx[1])
	
	var isRemove = false
	
	for( index = 0 ; index < dates.length; index++ ){
	
		if( dates[index] == tx ){
			
			dates.splice(index, 1)
			
			isRemove = true
			
			break
			
		}
	
	}
		
	if( !isRemove ) {
		dates.push(tx)
	}
	
}

function hFreeDaysCallback(date){
	
	var search = ""
	
	search = (date.getDate()) + '/' + (date.getMonth()+1)
		
	for( i = 0 ; i < dates.length ; i++ ){
		
		if( dates[i] == search ){	
			
			return [true, 'highlightFreeDays', "Выходной" ]
			
		}
			
	}
	
	return [true]
	
}

function changeDayBackgroundColor( selct, color ) {
				
	$(selct).each( function() {
		
		$(this).children().css("background-color",color)
				
	});

	
}

function changeDayBorderColor( selct, color ) {
				
	$(selct).each( function() {
		
		$(this).children().css("border-color",color)
				
	});

}

function replaceColor( oldClass, newClass, backgroundColor, borderColor ){
		
	$("td."+oldClass).each( function() {
				
		if( $(this).hasClass(oldClass) ){
			$(this).removeClass(oldClass)
		}

		$(this).children().css( "background-color", backgroundColor )
		$(this).children().css( "border-color", borderColor )
		
		$(this).addClass(newClass);
			
	});
	
}

function makeFreeDaysCalendarForYear(year){
	
	
	dates = []
	
	var tmp;
	
	tmp = new Object()
	
	tmp['freeDays'] = 1
	
	tmp['year'] = year
	
	$.ajax({
		
		type: "GET",
		url: "http://"+serverAddress+"/getDates.php",
		data: tmp,
		timeout: 3000,
		contentType: "application/x-www-form-urlencoded",
		dataType: "json",
		processData: true,
		success: function(msg){
			
			$("#divCurrentYear").html(year)
		
			freedays = msg['freedays']
			
			for( index = 0 ; index < freedays.length; index++ ){
				
				dates.push( freedays[index] );
				
			}
						
			$(".box").addClass("hidden")
			makeCalendar( "#divMoth", currentYear, hFreeDaysCallback, selectFreeDaysCallback )	
			$(".box").removeClass("hidden")
			
		},
		error: function(a,b,c){
		
		}
		
	});
	
}

var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 
		
function rgb2hex(rgb) {
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
 }