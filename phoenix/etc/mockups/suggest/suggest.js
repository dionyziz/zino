var Suggest = {
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds the suggestions that we have already received from the server
    list : {
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds all the requests we have done to the server
    requested : { 
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
    },
    type2int : function( type ) {
		switch( type ) {
			// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
			case 'hobbies':
				return 1;
			case 'movies':
				return 2;
			case 'books':
				return 3;
			case 'songs':
				return 4;
			case 'artists':
				return 5;
			case 'games':
				return 6;
			case 'shows':
				return 7;
			default:
				return -1;
		}
	},
    inputMove : function( event ) {
        var ul = $( 'div.hobbies ul' );
        if ( ul.css( "display" ) == "none" ) {
			return;
		}
        var lis = ul.find( 'li.selected' );
        if ( event.keyCode == 40 ) { // down
            if ( lis.length == 0 ) { // || ul.find( 'li:last' ).hasClass( 'selected' ) ) {
                //lis.removeClass( 'selected' );
                ul.find( 'li:first' ).addClass( 'selected' );
                return;
            }
            lis.removeClass( 'selected' ).next().addClass( 'selected' );
        }
        else if ( event.keyCode == 38 ) { // up
            if ( lis.length == 0 ) { // || ul.find( 'li:first' ).hasClass( 'selected' ) ) {
                //lis.removeClass( 'selected' );
                ul.find( 'li:last' ).addClass( 'selected' );
                return;
            }
            ul.find( 'li.selected' ).removeClass( 'selected' ).prev().addClass( 'selected' );
        }
        else if ( event.keyCode == 13 ) { // enter
            if ( lis.length == 0 ) {
                ul.css( 'display', 'none' );
                return;
            }
            $( 'div.hobbies input' ).attr( 'value', lis.text() );
            ul.css( 'display', 'none' );
        }
    }
};

$( function() {
    $( 'div.hobbies input' ).unbind(); // prevent onkeydown event from settings.js
    var ul = $( 'div.hobbies ul' );
    ul.find( 'li' ).mouseover(
        function() {
            ul.find( 'li.selected' ).removeClass( 'selected' );
            $( this ).addClass( 'selected' );
        }
    ).mousedown(
        function() {
            $( 'div.hobbies input' ).attr( 'value', $( this ).text() );
            ul.css( 'display', 'none' );
        }
    );
} );