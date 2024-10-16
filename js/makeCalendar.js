dNames      = [ "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота" ]
dNamesMin   = [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ]
dNamesShort = [ "Вск", "Пон", "Вто", "Сре", "Чет", "Пят", "Суб" ]
mNames      = [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентярь", "Октябрь", "Ноябрь", "Декабрь" ]
mNamesShort = [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ]

options = {	
	dayNames          : dNames,
	dayNamesMin       : dNamesMin,
	dayNamesShort     : dNamesShort,
	monthNames        : mNames,
	monthNamesShort   : mNamesShort,
	firstDay          : 1,
	changeMonth       : false,
	changeYear        : false,
	selectOtherMonths : false,
	showButtonPanel   : false,
	hideIfNoPrevNext  : true,
	buttonImage       : "",
	dateFormat        : "dd/mm/yy"
}

function makeCalendar( mothSelector, year, highlightCallback, selectCallback ){

	date = new Date()
		
	month = 0 // +1
		
	for( m = 1 ; m <= 12 ; m++ ){
			
		$(mothSelector+m).css('height', '')
			
		month += 1
		
		dateCalendar = "1/"+month+"/"+year
				
		$(mothSelector+m).datepicker(options, $.extend( options, { beforeShowDay : highlightCallback, onSelect: selectCallback } ))

		//$(mothSelector+m).datepicker( $.extend( options, { beforeShowDay : highlightCallback } ))
		$(mothSelector+m).datepicker("setDate",dateCalendar)	
			
	}

	maxHeight = 0;
		
	for( m = 1; m <= 12; m++ ){
			
		if( parseInt($(mothSelector+m).height()) > parseInt(maxHeight) ){
			maxHeight = $(mothSelector+m).height()
		}
			
	}
		
	for( m = 1; m <= 12; m++ ){
			
		$(mothSelector+m).css('height', maxHeight )	
			
	}
		
}