var PollList = {
	numoptions : 0,
	CreateOption : function() {
		if ( $( 'div#polllist ul div.creationmockup input' )[ 0 ].value !== '' ) {
			var heading = document.createElement( 'h4' );
			var headinglink = document.createElement( 'a' );
			$( headinglink ).attr( { 'href' : '' } ).append( document.createTextNode( $( 'div#polllist ul div.creationmockup input' )[ 0 ].value ) );
			$( heading ).append( headinglink ).css( 'margin-top' , '0' );
			$( 'div#polllist ul div.creationmockup' ).empty().append( heading );
			$( 'div#polllist ul div.creationmockup' ).append( $( 'div#polllist div.tip2' ).clone().css( 'display' , 'block' ) );
			PollList.NewOption();
		}
	},
	Create : function() {
		var newpoll = document.createElement( 'li' );
		$( newpoll ).append( $( 'div.creationmockup' ).clone() );
		$( 'div#polllist ul' )[ 0 ].insertBefore( newpoll , $( 'ul li.create' )[ 0 ] );
		$( 'div#polllist ul div.creationmockup' ).css( 'height' , '0' ).animate( { height: '40px' } , 400 , function() {
			$( this ).css( 'height' , '' );
		} );
		$( 'div#polllist ul div.creationmockup input' )[ 0 ].focus();
		$( 'div#polllist ul div.creationmockup input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				PollList.CreateOption();
			}		
		} );
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( 'div#polllist ul div.creationmockup' ).animate( { height: "0" } , 400 , function() {
				$( newpoll ).remove();
				$( this ).css( 'display' , 'none')
			} );
			PollList.Cancel();
			return false;
		} );
		$( 'div#polllist ul li.create' ).empty().append( link );
	},
	Cancel : function() {
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: "http://static.zino.gr/phoenix/add3.png",
			alt: "Δημιουργία δημοσκόπησης",
			title: "Δημιουργία δημοσκόπησης"
		} );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( createimg ).append( document.createTextNode( "Δημιουργία δημοσκόπησης" ) ).click( function() {
			PollList.Create();
			return false;
		} );
		$( 'div#polllist ul li.create' ).empty().append( link );
	},
	NewOption : function() {
		var container = document.createElement( 'div' );
		var newoption = document.createElement( 'input' );
		var acceptlink = document.createElement( 'a' );
		var acceptimage = document.createElement( 'img' );
		$( acceptimage ). attr( { 
			'src' : 'http://static.zino.gr/phoenix/accept.png',
			'alt' : 'Δημιουργία',
			'title' : 'Δημιουργία'
		} );
		$( acceptlink ).attr( { 'href' : '' } ).append( acceptimage );
		$( newoption ).attr( { 'type' : 'text' } ).css( 'width' , '300px' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( $( newoption )[ 0 ].value !== '' ) {
					var option = document.createElement( 'div' );
					$( option ).append( document.createTextNode( $( newoption )[ 0 ].value ) ).addClass( 'newoption' );
					$( $( newoption )[ 0 ].parentNode ).remove();
					$( 'div#polllist ul li div.creationmockup')[ 0 ].insertBefore( option , $( 'div#polllist ul li div.creationmockup div.tip2' )[ 0 ] );
					if ( PollList.numoptions == 0 ) {
						var donelink = document.createElement( 'a' );
						$( donelink ).attr( { 'href' : '' } ).addClass( 'button' ).css( 'font-weight' , 'bold' ).append( document.createTextNode( 'Δημιουργία' ) );
						$( 'div#polllist ul li div.creationmockup' ).append( donelink );
					}
					++PollList.numoptions;
					PollList.NewOption();
				}
			}
		} );
		$( container ).append( newoption ).append( acceptlink );
		$( 'div#polllist ul li div.creationmockup')[ 0 ].insertBefore( container , $( 'div#polllist ul li div.creationmockup div.tip2' )[ 0 ] );
		$( 'div#polllist ul li div.creationmockup div input' )[ 0 ].focus();
	}
};
$( document ).ready( function() {
	$( 'div#polllist li.create a' ). click( function() {
		PollList.Create();
		return false;
	} );

} );