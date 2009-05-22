var contacts = {
	provider: "",
	username: "",
	password: "",
    step: 0,
    changeToAddByEmail: function(){
        contacts.step = 0;
        $( '#left_tabs, #left_tabs li, #left_tabs li span, #body, #body > *' ).attr( 'style', '' );
        
        $( '#login, #left_tabs' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({ 
                marginLeft: 90,
                width: 540,
                height: 400
                }, function(){
                    $( '#inviteByEmail' ).fadeIn( 'normal' );
                    $( "#foot input" ).removeClass().addClass( "invite" ).unbind().bind( 'click', contacts.sendInvitations );
            });
        });
    },
    changeToFindInOtherNetworks: function(){
        $( '#inviteByEmail' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({ 
                marginLeft: 100,
                width: 600,
                height: 245
                }, function(){
                    $( '#login, #left_tabs' ).fadeIn( 'normal' );
                    $( "#foot input" ).removeClass().unbind().bind( 'click', contacts.retrieve );
            });
        });
    },
    sendInvitations: function(){
        var text = $( '#contactMail textarea' ).val();
        var mails = text.split( /[\s,;]+/ );
        var mail;
        var corMails = new Array();
        for ( var i in mails ){
            var mail = mails[ i ];
            corMails.push = mail;
        }
        var mailString = corMails.join( ';' );
        alert( mailString );
    },
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
        contacts.step = 1;
        $( "#top_tabs").css( 'zIndex', '-10' );
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
        contacts.step = 0;
		$( '#top_tabs, #left_tabs, #left_tabs li, #left_tabs li span, #body, #body > *' ).attr( 'style', '' );
		$( "#foot input" ).unbind().bind( 'click', contacts.retrieve );
	},
    addContactInZino: function( display, mail, location, id ){
        div = document.createElement( "div" );
        var text = "<div class='contactName'>";
        text += "<input type='checkbox' checked='checked' />";
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
        contacts.step = 2;
		$( "#foot input" ).removeClass().addClass( "add" );
		$( "#loading" ).css( 'position', 'absolute' ).fadeOut();
		$( "#contactsInZino, #foot" ).fadeIn();
		
		$( "#foot input" ).unbind().bind( 'click', contacts.addFriends );
	},
    addContactNotZino: function( mail, nickname, contact_id ){
        div = document.createElement( "div" );
        var text = "<input type='checkbox' checked='checked' />";
        if ( mail != nickname && mail.split( "@" )[ 0 ] != nickname ){
            text += "<div class='contactNickname'>" + nickname + "</div>";
            text += "<div class='contactMail'>" + mail + "</div>";
        }
        else{
            text += "<div style='margin-top: 8px' class='contactMail'>" + mail + "</div>";
        }
        $( div ).attr( 'id', 'contact_' + contact_id ).addClass( "contact" ).html( text ).appendTo( '#contactsNotZino .contacts' );
    },
    previwContactsNotInZino: function(){
        document.title = "Πρόσκληση φίλων | Zino";
        contacts.step = 3;
		$( "#foot input" ).removeClass().addClass( "invite" );
		$( "#contactsInZino, #loading" ).fadeOut();
		$( "#body" ).animate({
			"height": 420,
			"marginLeft": 80,
			"width": 570
			}, 1000, function(){
				$( "#contactsNotZino, #foot" ).fadeIn();
		});
        $( "#foot input" ).unbind().bind( 'click', contacts.invite );
	},
    addFriends: function(){
    var ids = new Array;
        $( "#contactsInZino .contact input:checked" ).parent().parent().each( function( i ){
            ids.push( $( this ).attr( "id" ) );
        });
        idsString = ids.join( " " );
        Coala.Warm( "contacts/addfriends", {
            "ids": idsString
        });
    },
    invite: function(){
    var ids = new Array;
        $( "#contactsNotZino .contact input:checked" ).parent().each( function( i ){
            var id = $( this ).attr( 'id' ).split( "_" )[ 1 ];
            ids.push( id );
        });
        idsString = ids.join( "," );
        Coala.Warm( "contacts/invite", {
            "ids": idsString
        });
    },
    calcCheckboxes: function( step ){
        if ( step == 2 ){
            if ( $( "#contactsInZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "add" );
            }
            else{
                $( "#foot input" ).removeClass();
            }
        }
        else{ //if step == 3
            if ( $( "#contactsNotZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "invite" );
            }
            else{
                $( "#foot input" ).removeClass().addClass( "finish" );
            }
        }
    },
	init: function(){
		$( "#foot input" ).bind( 'click', contacts.retrieve );
		//left tabs clickable
		$('#left_tabs li').click( function(){
			$('#left_tabs li').removeClass();
			$( this ).addClass( 'selected' );
		});
        //top tabs clickable
        $( '#top_tabs li' ).click( function(){
            $( '#top_tabs li' ).removeClass();
            $( this ).addClass( 'selected' );
        }).filter( '#otherNetworks' ).click( contacts.changeToFindInOtherNetworks ).end()
        .filter( '#ByEmail' ).click( contacts.changeToAddByEmail );
        
        //next step with enter
        $( '#password input' ).keydown( function( event ){
            if ( event.keyCode == 13 ){
                $( '#foot input' ).click();
            }
        });
		//checkboxes
		$( ".step .contact input" ).attr( "checked", "checked" );
		
		$( ".step .selectAll .all" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" ).each(function(){
                this.checked=true;
            });
            contacts.calcCheckboxes( contacts.step );
		});
		$( ".step .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" ).each(function(){
                this.checked=false;
            });
            contacts.calcCheckboxes( contacts.step );
		});
	}
};
$( function(){
    contacts.init();
});
