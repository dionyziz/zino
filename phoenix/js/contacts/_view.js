var contacts = {
	provider: "",
	username: "",
	password: "",
    retrieve: function(){
        contacts.provider = $( "#left_tabs li.selected span" ).attr( 'id' );
        contacts.username = $( "#mail input" ).val().split( '@' )[ 0 ];
        if ( contacts.provider == "hotmail" ){
            contacts.username += "@hotmail.com";
        }
        contacts.password = $( "#password input" ).val();
        Coala.Warm( 'contacts/retrieve', {
            provider: contacts.provider,
            username: contacts.username,
            password: contacts.password
        });
        contacts.loading();
    },
	loading: function(){
        document.title = "Φόρτωση επαφών...";
/*		$( '#foot, #login' ).fadeOut( 2000 ); too heavy...
		$( '#left_tabs li span')
			.fadeTo( 'normal', 0 ).parent()
			.filter( 'li.selected' )
			.css({
				'position': 'absolute',
				'borderTopWidth': 1
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
		}, 2000 );*/
        $( "#foot, #login, #left_tabs li" ).fadeOut( 'normal', function(){
            $( "#body" ).animate({
                'width': 700,
                'height': 466,
                'marginLeft': 0
            }, 'normal', function(){
                $( "#loading" ).fadeIn();
            });
        });
	},
	backToLogin: function(){
        document.title = "Λάθος στοιχεία! | Zino";
		$( '#foot, #login, #left_tabs, #left_tabs li, #left_tabs li span, #body, #loading' ).attr( 'style', '' );
		//$( '#password div label' ).css( 'fontWeight', 'bold' );
		$( "#foot input" ).one( 'click', contacts.retrieve );
	},
    addContactInZino: function( display, mail, location, id ){
        div = document.createElement( "div" );
        var text = "<div class='contactName'>";
        text += "<input type='checkbox' checked='checked' />";
        //text += "<input type='hidden' name='mails[]' value='" + mail + "' />";
        text += display;
        text += "<div class='contactMail'>" + mail + "</div>";
        text += "</div>";
        text += "<div class='location'>";
        text += location;
        text += "</div>";
        
        $( div ).addClass( "contact" ).attr( 'id', id ).html( text ).appendTo( '#contactsInZino .contacts' );
    },
    previwContactsInZino: function(){
        document.title = "Προσθήκη φίλων | Zino";
		$( "#foot input" ).css( 'backgroundImage', "url('http://static.zino.gr/phoenix/contacts/add.png')");
		$( "#loading" ).css( 'position', 'absolute' ).fadeOut();
		$( "#contactsInZino, #foot" ).fadeIn();
		
		$( "#foot input" ).one( 'click', contacts.addFriends );
	},
    addContactNotZino: function( mail, nickname ){
        div = document.createElement( "div" );
        var text = "<input type='checkbox' checked='checked' />";
        if ( mail != nickname ){
            text += "<div class='contactNickname'>" + nickname + "</div>";
            text += "<div class='contactMail'>" + mail + "</div>";
        }
        else{
            text += "<div style='margin-top: 8px' class='contactMail'>" + mail + "</div>";
        }
        $( div ).addClass( "contact" ).html( text ).appendTo( '#contactsNotZino .contacts' );
    },
    previwContactsNotInZino: function(){
        document.title = "Πρόσκληση φίλων | Zino";
		$( "#foot input" ).css( 'backgroundImage', "url('http://static.zino.gr/phoenix/contacts/invite.png')");
		$( "#contactsInZino, #loading" ).fadeOut();
		$( "#body" ).animate({
			"height": 420,
			"marginLeft": 80,
			"width": 570
			}, 1000, function(){
				$( "#contactsNotZino, #foot" ).fadeIn();
		});
        $( "#foot input" ).one( 'click', contacts.invite );
	},
    addFriends: function(){
    var ids = new Array;
        $( "#contactsInZino .contact input:checked" ).parent().each( function( i ){
            ids.push( $( this ).attr( "id" ) );
        });
        idsString = ids.join( " " );
        if ( !confirm( "The following users will be added as friends\n" + idsString ) ){
            return 0;
        }
        Coala.Warm( "contacts/addfriends", {
            "ids": idsString
        });
    },
    invite: function(){
    var mails = new Array;
        $( "#contactsNotZino .contact input:checked" ).siblings( ".contactMail" ).each( function( i ){
            mails.push( $( this ).html() );
        });
        mailsString = mails.join( " " );
        if ( !confirm( "Invitations will be send to:\n" + mailsString ) ){
            return 0;
        }
        Coala.Warm( "contacts/invite", {
            "mails": mailsString
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
