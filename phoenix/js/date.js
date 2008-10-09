function dateDiff( dateTimeBegin, dateTimeEnd ) {
    // TODO: Use client's timezone in calculations
    var endval = new Date();
    var beginval = new Date();

    if ( typeof dateTimeEnd != 'undefined' ) {
        var dateAndTime = dateTimeEnd.split( ' ' );
        var dateEnd = dateAndTime[ 0 ];
        var timeEnd = dateAndTime[ 1 ];
        var dateParts = dateEnd.split( '-' );
        var timeParts = timeEnd.split( ':' );

        endval.setFullYear( dateParts[ 0 ] );
        endval.setMonth( dateParts[ 1 ] );
        endval.setDate( dateParts[ 2 ] );
        endval.setHours( timeParts[ 0 ] );
        endval.setMinutes( timeParts[ 1 ] );
        endval.setSeconds( timeParts[ 2 ] );
    }
    var dateAndTime = dateTimeBegin.split( ' ' );
    var dateBegin = dateAndTime[ 0 ];
    var timeBegin = dateAndTime[ 1 ];
    var dateParts = dateBegin.split( '-' );
    var timeParts = timeBegin.split( ':' );

    endval.setFullYear( dateParts[ 0 ] );
    endval.setMonth( dateParts[ 1 ] );
    endval.setDate( dateParts[ 2 ] );
    endval.setHours( timeParts[ 0 ] );
    endval.setMinutes( timeParts[ 1 ] );
    endval.setSeconds( timeParts[ 2 ] );

    var diff = Date.parse( dateTimeEnd.toString() ) - Date.parse( dateTimeBegin.toString() );

    if ( diff < 0 ) {
        // error condition
        return false;
    }

    var weeks = 0;
    var days = 0;
    var hours = 0;
    var minutes = 0;
    var seconds = 0; // initialize vars

    if ( diff % 604800 > 0 ) {
        var rest1 = diff % 604800;
        weeks = ( diff - rest1 ) / 604800; // seconds a week
        if ( rest1 % 86400 > 0 ) {
            var rest2 = ( rest1 % 86400 );
            days = ( rest1 - rest2 ) / 86400; // seconds a day
            if ( rest2 % 3600 > 0 ) {
                var rest3 = ( rest2 % 3600 );
                hours = ( rest2 - rest3 ) / 3600; // seconds an hour
                if ( rest3 % 60 > 0 ) {
                    seconds = ( rest3 % 60 );
                    minutes = ( rest3 - seconds ) / 60; // seconds a minute
                } 
                else {
                    minutes = rest3 / 60;
                }
            }
            else {
                hours = rest2 / 3600;
            }
        }
        else {
            days = rest1 / 86400;
        }
    }
    else {
        weeks = diff / 604800;
    }
    if ( weeks ) {
        hours = 0;
    }
    if ( days || weeks ) {
        minutes = 0;
    }
    months = Math.floor( weeks / 4 );
    if ( months ) {
        weeks -= months * 4;
    }
    years = Math.floor( months / 12 );
    if ( years ) {
        months -= years * 12;
    }

    return {
        'years': years,
        'months': months,
        'weeks': weeks,
        'days': days,
        'hours': hours,
        'minutes': minutes
    };
}
