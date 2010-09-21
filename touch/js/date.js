function stringToDate( str ){ //str: dd-mm-yyyy hh:mm:ss
    if( typeof str == 'undefined' ){
        return false;
    }
    var dt = str.split( ' ' );
    var d = dt[ 0 ];
    var t = dt[ 1 ];

    return new Date( d.split( '-' )[ 0 ],
                     d.split( '-' )[ 1 ] - 1,
                     d.split( '-' )[ 2 ],
                     t.split( ':' )[ 0 ],
                     t.split( ':' )[ 1 ],
                     t.split( ':' )[ 2 ] );
                            
}
function dateToString( d ){
    return d.getFullYear() + '-'
         + ( ( d.getMonth() + 1 + '' ).length == 1 ? '0' : '' ) + ( d.getMonth() + 1 ) + '-'
         + ( ( d.getDate() + '' ).length == 1 ? '0' : '' ) + d.getDate() + ' '
         + d.getHours() + ':'
         + ( ( d.getMinutes() + '' ).length == 1 ? '0' : '' ) + d.getMinutes() + ':'
         + ( ( d.getSeconds() + '' ).length == 1 ? '0' : '' ) + d.getSeconds();
}
function dateDiff( dateTimeBegin, dateTimeEnd ) {
    var endval = stringToDate( dateTimeEnd );
    var beginval = stringToDate( dateTimeBegin );

    var diff = Date.parse( endval.toString() ) - Date.parse( beginval.toString() );
    diff /= 1000;

    if ( diff < 0 ) {
        // error condition
        return false;
    }

    var years = 0;
    var months = 0;
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

function greekDateDiff( diff ) {
    years = diff.years;
    months = diff.months;
    weeks = diff.weeks;
    days = diff.days;
    hours = diff.hours;
    minutes = diff.minutes;

    if ( years ) {
        if ( years == 1 ) {
            return 'πέρσι';
        }
        if ( years == 2 ) {
            return 'πρόπερσι';
        }
        return 'πριν ' + years + ' χρόνια';
    }
    if ( months ) {
        if ( months == 1 ) {
            return 'τον προηγούμενο μήνα';
        }
        return 'πριν ' + months + ' μήνες';
    }
    if ( weeks ) {
        if ( weeks == 1 ) {
            return 'την προηγούμενη εβδομάδα';
        }
        return 'πριν ' + weeks + ' εβδομάδες';
    }
    if ( days ) {
        if ( days == 1 ) {
            return 'χθες';
        }
        if ( days == 2 ) {
            return 'προχθές';
        }
        return 'πριν ' + days + ' μέρες';
    }
    if ( hours ) {
        if ( hours == 1 ) {
            return 'πριν 1 ώρα';
        }
        return 'πριν ' + hours + ' ώρες';
    }
    if ( minutes ) {
        if ( minutes == 1 ) {
            return 'πριν 1 λεπτό';
        }
        if ( minutes == 15 ) {
            return 'πριν ένα τέταρτο';
        }
        if ( minutes == 30 ) {
            return 'πριν μισή ώρα';
        }
        if ( minutes == 45 ) {
            return 'πριν τρία τέταρτα';
        }
        return 'πριν ' + minutes + ' λεπτά';
    }
    return 'πριν λίγο';
}

