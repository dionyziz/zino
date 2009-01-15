//petros masked for #486
var PollList = {
	numoptions : 0,
	QuestionText: '',
	OptionsText: '',
	CreateQuestion : function() {
		if ( $( 'div#polist ul div.creationmockup input' )[ 0 ].value !== '' ) {
			var heading = document.createElement( 'h4' );
            //var headinglink = document.createElement( 'a' );
			$( heading ).append( document.createTextNode( $( 'div#polist ul div.creationmockup input' )[ 0 ].value ) );
			$( heading ).append( heading ).css( 'margin-top', '0' );
			PollList.QuestionText = $( 'div#polist ul div.creationmockup input' )[ 0 ].value;
			$( 'div#polist ul div.creationmockup' ).empty().append( heading );
			$( 'div#polist ul div.creationmockup' ).append( $( 'div#polist div.tip2' ).clone().css( 'display', 'block' ) );
			PollList.NewOption();
		}
	},
	CreateOption : function( newoption ) {
		if ( newoption.value !== '' ) {
			var option = document.createElement( 'div' );
			$( option ).append( document.createTextNode( $( newoption )[ 0 ].value ) ).addClass( 'newoption' );
			$( $( newoption )[ 0 ].parentNode ).remove();
			$( 'div#polist ul li div.creationmockup')[ 0 ].insertBefore( option, $( 'div#polist ul li div.creationmockup div.tip2' )[ 0 ] );
			if ( PollList.numoptions === 0 ) {
				var donelink = document.createElement( 'a' );
				$( donelink ).attr( { 'href' : '' } ).addClass( 'button' ).css( 'font-weight', 'bold' ).append( document.createTextNode( 'Δημιουργία' ) ).click( function() {
					PollList.OptionsText = PollList.OptionsText.substr( 0, PollList.OptionsText.length - 1 );
					var newpoll = document.createElement( 'li' );
					$( newpoll ).html( $( 'div#polist div.creatingpoll' ).html() );
					$( 'div#polist ul' )[ 0 ].insertBefore( newpoll, $( 'div#polist ul li div.creationmockup' )[ 0 ].parentNode.nextSibling.nextSibling );
					$( $( 'div#polist ul li div.creationmockup' )[ 0 ].parentNode ).remove();
					Coala.Warm( 'poll/new', { question : PollList.QuestionText, options : PollList.OptionsText, node : newpoll } );
					PollList.numoptions = 0;
					PollList.OptionsText = '';
					return false;
				} );
				$( 'div#polist ul li div.creationmockup' ).append( donelink );
			}
			PollList.OptionsText += newoption.value + '|';
			++PollList.numoptions;
			PollList.NewOption();
		}
	},
	Create : function() {
		var newpoll = document.createElement( 'li' );
		$( newpoll ).append( $( 'div.creationmockup' ).clone() );
		$( 'div#polist ul' )[ 0 ].insertBefore( newpoll, $( 'ul li.create' )[ 0 ] );
		$( 'div#polist ul div.creationmockup' ).css( 'height', '0' ).animate( { height: '40px' }, 400, function() {
			$( this ).css( 'height', '' );
		} );
		$( 'div#polist ul div.creationmockup input' )[ 0 ].focus();
		$( 'div#polist ul div.creationmockup input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				PollList.CreateQuestion();
			}		
		} );
		$( 'div#polist ul div.creationmockup div a' ).click( function() {
			PollList.CreateQuestion();
			return false;
		} );
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( 'div#polist ul div.creationmockup' ).animate( { height: "0" }, 400, function() {
				$( newpoll ).remove();
				$( this ).css( 'display', 'none');
			} );
			PollList.Cancel();
			return false;
		} );
		$( 'div#polist ul li.create' ).empty().append( link );
		return false;
	},
	Cancel : function() {
		PollList.numoptions = 0;
		PollList.OptionsText = '';
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: ExcaliburSettings.imagesurl + "add3.png",
			alt: "Δημιουργία δημοσκόπησης",
			title: "Δημιουργία δημοσκόπησης"
		} );
		$( link ).attr( { href: "" } ).append( createimg ).append( document.createTextNode( "Δημιουργία δημοσκόπησης" ) ).click( function() {
			PollList.Create();
			return false;
		} );
		$( 'div#polist ul li.create' ).empty().append( link );
	},
	NewOption : function() {
		var container = document.createElement( 'div' );
		var newoption = document.createElement( 'input' );
		var acceptlink = document.createElement( 'a' );
		var acceptimage = document.createElement( 'img' );
		$( acceptimage ). attr( { 
			'src' : ExcaliburSettings.imagesurl + "accept.png",
			'alt' : "Δημιουργία",
			'title' : "Δημιουργία"
		} );
		$( newoption ).attr( { 'type' : 'text' } ).css( 'width', '300px' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				PollList.CreateOption( newoption );
				return false;
			}
		} );

		$( container ).append( newoption ).append( acceptlink );
		$( 'div#polist ul li div.creationmockup')[ 0 ].insertBefore( container, $( 'div#polist ul li div.creationmockup div.tip2' )[ 0 ] );
		$( acceptlink ).attr( { 'href' : '' } ).append( acceptimage ).click( function( node ) {
			PollList.CreateOption( newoption );
			return false;
		} );
		$( 'div#polist ul li div.creationmockup div input' )[ 0 ].focus();
	}
};
$( function() {
	$( 'div#polist li.create a' ). click( function() {
		PollList.Create();
		return false;
	} );
} );
