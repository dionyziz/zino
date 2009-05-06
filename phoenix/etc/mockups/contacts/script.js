var contacts = {
	provider: "",
	mail: "",
	password: "",
	retrieve: function(){
		$( '#foot, #login' ).fadeOut( 2000 );
		$( '#left_tabs li span')
			.fadeTo( 'normal', 0 ).parent()
			.filter( 'li.selected' )
			.css({
				'position': 'absolute',
				'borderBottomWidth': 1
				})
			.animate({
				'height': 144,
				'top': '0'
			}, 1000, function(){
				$( '#left_tabs li:not(.selected)').hide();
				$( '#left_tabs li.selected' ).animate({
					'width': 0,
					'paddingLeft': 0,
					'paddingRight': 0,
					'left': 1
					}, 1000, function(){
						$( this ).hide();
				});
				$( '#body' )
					.animate({
						'width': 698,
						'height': 466,
						'marginLeft': 0
					}, 960 );
		});
		setTimeout( function(){
			$( "#loading" ).fadeIn();
		}, 2000 );
	},
	backToLogin: function(){
		$( '#foot, #login, #left_tabs li, #left_tabs li span, #body, #loading' ).attr( 'style', '' );
		$( '#password div label' ).css( 'fontWeight', 'bold' );
		$( "#foot input" ).one( 'click', contacts.retrieve );
	},
	previwContactsInZino: function(){
		$( "#foot input" ).css( 'backgroundImage', "url('add.png')");
		$( "#loading" ).css( 'position', 'absolute' ).fadeOut();
		$( "#contactsInZino, #foot" ).fadeIn();
		
		$( "#foot input" ).one( 'click', contacts.previwContactsNotInZino );
	},
	previwContactsNotInZino: function(){
		$( "#foot input" ).css( 'backgroundImage', "url('invite.png')");
		$( "#contactsInZino" ).fadeOut();
		$( "#body" ).animate({
			"height": 420,
			"marginLeft": 80,
			"width": 570
			}, 1000, function(){
				$( "#contactsNotZino" ).fadeIn();
		});
	},
	init: function(){
		$( "#foot input" ).one( 'click', contacts.retrieve );
		//left tabs clickable
		$('#left_tabs li').click( function(){
			$('#left_tabs li').removeClass();
			$( this ).addClass( 'selected' );
		});
		//checkboxes
		$( ".step .contact input" ).attr( "checked", "checked" );
		
		$( ".step .selectAll .all" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" );
		});
		$( ".step .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" );
		});
	}
};
contacts.init();
