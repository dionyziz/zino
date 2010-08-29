var Calendar = {
    Days: [ 'Κυ', 'Δε', 'Τρ', 'Τε', 'Πε', 'Πα', 'Σα' ],
    Months: [
        [ "Ιανουάριος", 31 ],
        [ "Φεβρουάριος", 28 ],
        [ "Μάρτιος", 31 ],
        [ "Απρίλιος", 30 ],
        [ "Μάιος", 31 ],
        [ "Ιούνιος", 30 ],
        [ "Ιούλιος", 31 ],
        [ "Αύγουστος", 31 ],
        [ "Σεπτέμβριος", 30 ],
        [ "Οκτώβριος", 31 ],
        [ "Νοέμβριος", 30 ],
        [ "Δεκέμβριος", 31 ]
    ],
    CurrentDate: new Date(),
    Element: null,
    Init: function( id, callback, title ) {
        Calendar.Element = Calendar.Construct( title );
        Calendar.SetDate( Calendar.CurrentDate.getFullYear(), Calendar.CurrentDate.getMonth() + 1, Calendar.CurrentDate.getDate() );
        Calendar.AssignEvents( id, callback );
        $( Calendar.Element ).css( {
            position: 'absolute',
            display: 'none'
        } );
        $( '#world' ).append( Calendar.Element );
    },
    SetDate: function( year, month ) {
        var i;
        var day;
        Calendar.CurrentDate.setFullYear( year );
        Calendar.CurrentDate.setMonth( month - 1 );
        Calendar.CurrentDate.setDate( 1 );
        Calendar.Element.getElementsByTagName( 'div' )[ 0 ].getElementsByTagName( 'span' )[ 0 ].innerHTML = year;
        Calendar.Element.getElementsByTagName( 'div' )[ 1 ].getElementsByTagName( 'span' )[ 0 ].innerHTML = Calendar.Months[ month - 1][ 0 ];
        Calendar.Element.getElementsByTagName( 'ul' )[ 1 ].getElementsByTagName( 'li' )[ 0 ].style.marginLeft = Calendar.CurrentDate.getDay() * 25 + 'px';
        var days = Calendar.Element.getElementsByTagName( 'ul' )[ 1 ].getElementsByTagName( 'li' );
        if( Calendar.IsLeapYear() && Calendar.CurrentDate.getMonth() == 1 ){
            days[ 28 ].style.display = 'block';
            for( i = 29; i < 31; ++i ){
                days[ i ].style.display = 'none';
            }
            return;
        }
        for( i = 28; i < 31; ++i ){
            days[ i ].style.display = 'block';
        }
        for( i = 30; i >= Calendar.Months[ Calendar.CurrentDate.getMonth() ][ 1 ]; --i ){
            days[ i ].style.display = 'none';
        }
    },
    IsLeapYear: function() {
        var year = Calendar.CurrentDate.getFullYear();
        return ( year % 4 === 0 && year % 100 !== 0 ) || year % 400 === 0;
    },
    Construct: function( title ) {
        var calendar = document.createElement( 'div' );

        var years = document.createElement( 'div' );
        var months = document.createElement( 'div' );

        if ( typeof title == 'string' ) {
            var h3 = document.createElement( 'h3' );
            h3.appendChild( document.createTextNode( title ) );
            calendar.appendChild( h3 );
        }

        years.className = 'years';
        months.className = 'months';
        years.innerHTML = months.innerHTML = '<a href="#" style="float: left">&lt;</a><span></span><a href="#" style="float: right">&gt;</a>';

        calendar.appendChild( years);
        calendar.appendChild( months );

        var month = document.createElement( 'ul' );
        var days = document.createElement( 'ul' );
        var day;
        var i;
        month.className = 'month';
        days.className = 'days';
        
        for ( i = 0; i < 7; ++i ) {
            day = document.createElement( 'li' );
            day.innerHTML = Calendar.Days[ i ];
            days.appendChild( day );
        }

        for ( i = 1; i <= 31; ++i ) {
            day = document.createElement( 'li' );
            day.innerHTML = '<a href="#">' + i + '</a>';
            month.appendChild( day );
        }

        calendar.appendChild( days );
        calendar.appendChild( month );

        calendar.id = 'calendar';
        return calendar;
    },
    AssignEvents: function( id, callback ) {
        $( '#' + id ).click( function( e ) {
            if ( Calendar.Element.style.display == 'none' ) {
                Calendar.Element.style.display = 'block';
                Calendar.Element.style.top = e.pageY + 20 + 'px';
                Calendar.Element.style.left = e.pageX + 'px';
                return;
            }
            Calendar.Element.style.display = 'none';
        } );
        $( Calendar.Element.getElementsByTagName( 'div' )[ 0 ].getElementsByTagName( 'a' )[ 0 ] ).click( function() {
            Calendar.SetDate( Calendar.CurrentDate.getFullYear() - 1, Calendar.CurrentDate.getMonth() + 1 );
            return false;
        } );
        $( Calendar.Element.getElementsByTagName( 'div' )[ 0 ].getElementsByTagName( 'a' )[ 1 ] ).click( function() {
            Calendar.SetDate( Calendar.CurrentDate.getFullYear() + 1, Calendar.CurrentDate.getMonth() + 1 );
            return false;
        } );
        $( Calendar.Element.getElementsByTagName( 'div' )[ 1 ].getElementsByTagName( 'a' )[ 0 ] ).click( function() {
            Calendar.SetDate( Calendar.CurrentDate.getFullYear(), Calendar.CurrentDate.getMonth() === 0 ? 12 : Calendar.CurrentDate.getMonth() );
            return false;
        } );
        $( Calendar.Element.getElementsByTagName( 'div' )[ 1 ].getElementsByTagName( 'a' )[ 1 ] ).click( function() {
            Calendar.SetDate( Calendar.CurrentDate.getFullYear(), Calendar.CurrentDate.getMonth() == 11 ? 1 : Calendar.CurrentDate.getMonth() + 2 );
            return false;
        } );
        if( typeof callback === 'function' ){
            $( '.month li a', Calendar.Element ).click( function() {
                callback( Calendar.CurrentDate.getFullYear(), Calendar.CurrentDate.getMonth() + 1, this.innerHTML );
                Calendar.Element.style.display = 'none';
                return false;
            } );
        }
    }
};

