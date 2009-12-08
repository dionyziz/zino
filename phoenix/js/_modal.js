/*
  Developer: Chorvus
  Modal relay function for jqModal
  Requirements:
    jQuery  ( jquery.js )
    jqModal ( jquery.modal.js )
*/

/*
    Change of plans, new modal system
*/


( function($) {
    $.fn.modal = function( config, modalTrigger ) {
        var defconfig = {
            position: 'center',
            overlayClass: 'mdloverlay1',
            noClose: false,
            noFrame: false,
            modal: true
        };
        config = $.extend( config, defconfig );
        if ( !config.noClose ) {
            close = document.createElement( 'div' );
            $( close ).addClass( 'close' ).click(
                function ( modalElement ) {
                    return function () {
                        modalElement.jqmHide();
                    }
                } ( this )
            );
            this.append( close );
        }
        if ( config.position == 'center' ) {
            this.center();
        }
        return this.jqm( config );
        if ( modalTrigger ) {
            this.jqmAddTrigger( modalTrigger );
        }
    }
} ) ( jQuery );

var Modals = {
    Confirm: function ( question, action ) {
        Modals.Question( question, [
            { Text: 'Ναι' , Callback: action },
            { Text: 'Όχι' }
        ] );
    },
    Question: function ( question, answers, callback ) {
        qq = document.createElement( 'div' );
        qq.appendChild( document.createTextNode( question ) );
        optionsdiv = document.createElement( 'div' );
        options = document.createElement( 'ul' );
        if ( typeof( answers ) == 'string' ) {
            answers = [ answers ];
        }
        else if ( typeof( answers ) == 'undefined' ) {
            answers = [ "OK" ];
        }
        for ( i in answers ) {
            answer = answers[ i ];
            li = document.createElement( 'li' );
            btn = document.createElement( 'a' );
            btn.href = '';
            if ( typeof( answer ) == 'object' ) {
                    btn.appendChild( document.createTextNode( answer.Text ) );
            }
            else {
                btn.appendChild( document.createTextNode( answer ) );
            }
            $( btn ).click( function( j, a, w ) {
                return function() {
                    $( w ).jqmHide().remove();
                    if ( a.Callback ) {
                        a.Callback.call( this );
                    }
                    if ( callback ) {
                        callback.call( this, j );
                    }
                    return false;
                };
            } ( i, answer, qq ) );
            
            li.appendChild( btn );
            options.appendChild( li );
        }
        qq.appendChild( options );
        document.body.appendChild( qq );
        qq.className = "modal";
        if ( $( qq ).width() > $( "body" ).width() * 0.6 ) {
            $( qq ).css( "width", $( "body" ).width() * 0.6 );
        }
        $( qq ).center().modal().jqmShow();
        return;
    }
};
