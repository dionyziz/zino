var contacts = {
	provider: "",
	username: "",
	password: "",
    retrieve: function(){
        contacts.provider = $( "#left_tabs li.selected span" ).attr( 'id' );
        contacts.username = $( "#mail input" ).val();
        contacts.password = $( "#password input" ).val();
        if ( true ){//TODO: check validity of inputs
            Coala.Warm( 'contacts/retrieve', {
                provider: contacts.provider,
                username: contacts.username,
                password: contacts.password
            });
            contacts.loading();
        }
        else{
            //TODO: invalid inputs bold
        }
    },
	loading: function(){
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
    addContactInZino: function( display, Mail ){
        div = document.createElement( "div" );
        var text = "<input type='checkbox' checked='checked' />";
        text += display;
        text += "<div class='contactMail'>" + Mail + "</div>";
        $( div ).addClass( "contact" ).html( text ).appendTo( '.contacts' );
    },
    previwContactsInZino: function(){
		$( "#foot input" ).css( 'backgroundImage', "url('http://static.zino.gr/phoenix/contacts/add.png')");
		$( "#loading" ).css( 'position', 'absolute' ).fadeOut();
		$( "#contactsInZino, #foot" ).fadeIn();
		
		$( "#foot input" ).one( 'click', contacts.previwContactsNotInZino );
	},
	previwContactsNotInZino: function(){
		$( "#foot input" ).css( 'backgroundImage', "url('http://static.zino.gr/phoenix/contacts/invite.png')");
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
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" ).each(function(){
                this.checked=true;
            });
		});
		$( ".step .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" ).each(function(){
                this.checked=false;
            });
		});
	}
};
$( function(){
    contacts.init();
});
