/**     available tabs: 
* search in zino.
*   width: 800px, height: 400px
* search in other networks: login
*   width: 600px, height: 250px
* search in other networks: loading
*   width: 700px, height: 466px
* search in other networks: contacts In zino
*   width: 700px, height: 466px
* search in other networks: contacts Not in zino
*   width: 570px, height: 420px
* invite by mail
*   width: 540px, height: 400px;
*/
var contacts = {
    tab: 1,
    step: 0,
	provider: "",
	username: "",
	password: "",
    changeToSearchInZino: function(){
        if ( contacts.tab == 1 && contacts.step == 0 ){
            return;
        }
        contacts.tab = 1;
        contacts.step = 0;
        $( '.tab:visible, #foot' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 950,
                minHeight: 300
                }, function(){
                    $( '#search' ).fadeIn( 'normal' );
            });
        });
    },
    changeToFindInOtherNetworks: function(){
        if ( contacts.tab == 2 && contacts.step == 0 ){
            return;
        }
        document.title = "Αναζήτηση φίλων | Zino";
        contacts.tab = 2;
        contacts.step = 0;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 600,
                minHeight: 250
                }, function(){
                    $( '#login' ).fadeIn( 'normal' );
                    $( "#top_tabs" ).css( 'zIndex', '10' );
                    $( "#foot input" ).removeClass() //.addClass( 'continue' )
                        .unbind().bind( 'click', contacts.retrieve )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
            });
        });
    },
    changeToAddByEmail: function(){
        if ( contacts.tab == 3 ){
            return;
        }
        document.title = "Πρόσκληση φίλων | Zino";
        contacts.tab = 3;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 540,
                minHeight: 320
                }, function(){
                    $( '#inviteByEmail' ).fadeIn( 'normal' );
                    $( "#foot input" ).removeClass().addClass( "invite" )
                        .unbind().bind( 'click', contacts.sendInvitations )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
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
            corMails.push( mail );
        }
        var mailString = corMails.join( ';' );
        Coala.Warm( 'contacts/invitebymail', {
            mails: mailString
        });
        $( '#foot input' ).unbind();
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
        $( "#top_tabs" ).css( 'zIndex', '-10' );
        $( "#foot, .tab:visible" ).fadeOut( 'normal', function(){
            $( "#body" ).animate({
                maxWidth: 700,
                minHeight: 466
            }, 'normal', function(){
                $( "#loading" ).fadeIn();
            });
        });
	},
	backToLogin: function(){
        contacts.changeToFindInOtherNetworks();
        document.title = "Λάθος στοιχεία! | Zino";
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
        
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#contactsInZino' ).fadeIn( 'normal' );
            $( "#foot input" ).removeClass().addClass( 'add' )
                .unbind().bind( 'click', contacts.addFriends )
                .parent().filter( "div:hidden" ).fadeIn( 'normal' );
        });
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
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 570,
                minHeight: 480
                }, function(){
                    $( '#contactsNotZino' ).fadeIn( 'normal' );
                    $( "#foot input" ).removeClass().addClass( 'invite' )
                        .unbind().bind( 'click', contacts.invite )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
            });
        });
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
    calcCheckboxes: function(){
        if ( contacts.step == 2 ){
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
            if ( $( '.tab:animated' ).length != 0 ){
                return false;
            }
            $( '#top_tabs li' ).removeClass();
            $( this ).addClass( 'selected' );
        }).filter( '#otherNetworks' ).click( contacts.changeToFindInOtherNetworks ).end()
            .filter( '#ByEmail' ).click( contacts.changeToAddByEmail ).end()
            .filter( '#searchInZino' ).click( contacts.changeToSearchInZino );
        
        
        //next step with enter
        $( '#password input' ).keydown( function( event ){
            if ( event.keyCode == 13 ){
                $( '#foot input' ).click();
            }
        });
		//checkboxes
		$( ".networks .contact input" ).attr( "checked", "checked" );
		
		$( ".networks .selectAll .all" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" ).each(function(){
                this.checked=true;
            });
            contacts.calcCheckboxes();
		});
		$( ".networks .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" ).each(function(){
                this.checked=false;
            });
            contacts.calcCheckboxes();
		});
	}
};
$( function(){
    contacts.init();
});
