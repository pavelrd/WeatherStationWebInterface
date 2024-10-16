$(function(){
	
	currentYear = (new Date()).getFullYear()
	
	dates = [];
	freeDates = [];
	
	// ---------------- ONxxx functions
	
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
	
	$("#buttonSettings").click(function(){
				
		$(location).attr('href', "calendarSettings.php")
		
		return false;
		
	});	
	
	// ---------------- Set jquery buttons
	
	// set beforeShowDay: highlightDays,
	
	$( "button" ).button({
		showLabel: true
	});
	
	$( ".jqueryButton" ).button({
		showLabel: true
	});
	
	$("#inputShowFreeDays").change(function(a){
			
		updateCalendar(currentYear)	
				
	});

	$("#inputShowBirthdays").change(function(a){
	
		updateCalendar(currentYear)
		
	});
	
	$("#inputShowNow").change(function(a){
		
		updateCalendar(currentYear)
		
	});
	
	getBirthdayDates()
	
	function highlightDays(date) {
				
		// freeDates
		
		var isShowBirthdays = $("#inputShowBirthdays").is(":checked")
		var isShowFreeDays  = $("#inputShowFreeDays").is(":checked")
		var isShowNow       = $("#inputShowNow").is(":checked")
		
		var currentDate = new Date()
		
		scurrentDate = currentDate.getDate() + '/' + (currentDate.getMonth() + 1)
		
		var search = date.getDate() + '/' + (date.getMonth() + 1)
		
		var searchedUsers = ""
		
		if( isShowBirthdays ){
		
			for( i = 0 ; i < dates.length ; i++ ){
					
				if( dates[i][0] == search ){
				
					searchedUsers += dates[i][1] + " \n"	
			
				}
			
			}
			
		}
		
		isFreeDay = false
		
		if( isShowFreeDays ){
		
			for( i = 0 ; i < freeDates.length ; i++ ){
						
				if( freeDates[i] == search ){
				
					isFreeDay = true
				
					break;
				
				}
			
			}
			
		}
		
		var nowStr = "";
		
		if( scurrentDate == search ){
			nowStr = "Сегодня ";
		}
		
		if( (searchedUsers != "") && isFreeDay ){
			
			if( nowStr == "" ){
			
				return [true, 'highlightGoodDays', "Выходной день и день рождения у: \n"+searchedUsers ]

			} else {

				return [true, 'highlightGoodDays', nowStr+"выходной день и день рождения у: \n"+searchedUsers ]
				
			}
			
		} else if ( ( searchedUsers != "" )  ){
			
			if( nowStr == "" ){
			
				return [true, 'highlightBirthdays', "День рождения у: \n"+searchedUsers ]

			} else {

				return [true, 'highlightBirthdays', nowStr+"день рождения у: \n"+searchedUsers ]

			}
			
			
		} else if( isFreeDay ){
			
			if( nowStr == "" ){
			
				return [true, 'highlightFreeDays', "Выходной день"+searchedUsers ]

			} else {

				return [true, 'highlightFreeDays', nowStr+"выходной день" ]

			}
			
		} else if( ( scurrentDate == search ) && ( currentDate.getFullYear() == currentYear) ){
			
			if( isShowNow ){
			
				return [true, 'highlightToday', "Сегодня"]
				
			} else {
			
				return [true]
				
			}
			
		} else {
			
			//return [true]
		
			 if( ( freeDates.length != 0 ) && ( ( date.getDay() == 0 ) || ( date.getDay() == 6 ) ) ) {
				
				return [true, 'highlightUnstandartDay', "Рабочий день" ]				 

			 } else {
				
				return [true];
				
			 }
			

			
		}
		
	}
	
	function getBirthdayDates(){
		
		var tmp = new Object()
	
		tmp['goodDays'] = true
		tmp['year']     = currentYear
		
		$.ajax({
			type: "GET",
			url: "http://"+$("#inputServerAddress").val()+"/getDates.php",
			data: tmp,
			timeout: 10000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
								
				birthdaysPeople = msg['birthdays']
				
				for( index = 0; index < birthdaysPeople.length; index++ ){
					
					dates.push([ birthdaysPeople[index]['date'], birthdaysPeople[index]['name']+" "+birthdaysPeople[index]['lastname'] ])
				
				}
				
				freeDays = msg['freedays']
				
				for( index = 0; index < freeDays.length; index++ ){
					
					freeDates.push(freeDays[index])
				
				}
				
				updateCalendar(currentYear)
				
						
			},
			error: function(a,b,c){
			
				console.log('error')
				
			}  
		});
	
	}
	
	function selectDays( dateText, inst ){
		
	}

	function makeFreeDaysCalendarForYear(year){
	
		var tmp;
	
		tmp = new Object();
		
		tmp['freeDays'] = 1
		tmp['year']     = year
	
		$.ajax({
		
			type: "GET",
			url: "http://"+$("#inputServerAddress").val()+"/getDates.php",
			data: tmp,
			timeout: 3000,
			contentType: "application/x-www-form-urlencoded",
			dataType: "json",
			processData: true,
			success: function(msg){
			
				freeDates = []
			
				freeDays  = msg['freedays']
												
				for( index = 0; index < freeDays.length; index++ ){
					
					freeDates.push(freeDays[index])
				
				}
				
				updateCalendar(currentYear)
			
			},
			error: function(a,b,c){
		
			}
		
		});
		
	}
	
	function updateCalendar(year){
		
		$("#divCurrentYear").html(year)
		
		$(".box").addClass("hidden")
		
		$("#labelFreeDays").html(parseInt(freeDays.length))

		makeCalendar( "#divMoth", currentYear, highlightDays, selectDays )		
		
		$(".box").removeClass("hidden")
		
		$(".ui-datepicker-year").hide()	
			
	}	
	
});

