var Dates = {
	LeapYear : function( year ) {
		if ( year % 100 ) {
			if ( year % 400 ) {
				return true;
			}
		}
		else if ( year % 4 ) {
			return true;
		}
		return false;
	},
	DaysInMonth : function( month , year ) {
		alert( 'month ' + month );
		switch ( month ) {
			case '01':
			case '03':
			case '05':
			case '07':
			case '08':
			case '10':
			case '12':
				return 31;
			case '02':
				alert( 'case 2' );
				if ( Dates.LeapYear( year ) ) {
					return 29;
				}
				return 28;
		}
		return 30;
	},
	ValidDate : function( day , month , year ) {
		var daysinmonth = Dates.DaysInMonth( month , year );
		alert( 'days in month: ' + daysinmonth );
		if ( day < 0 || day > daysinmonth ) {
			return false;
		}
		if ( month < 0 || month >12 ) {
			return false;
		}
		return true;
	}
};