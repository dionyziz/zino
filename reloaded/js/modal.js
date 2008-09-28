var Modals = {
    ModalBG: false,
    CurrentWindow: false,
    CurrentWidth: 0,
    CurrentHeight: 0,
    Confirm: function ( question, action ) {
        Modals.Question( question, [
            { Text: 'Ναι' , Action: action },
            { Text: 'Όχι' }
        ] );
    },
    Question: function ( question, answers ) {
        qq = document.createElement( 'div' );
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( document.createTextNode( question ) );
        options = document.createElement( 'ul' );
        options.style.display = 'inline';
        options.style.margin = '0px';
        options.style.padding = '0px';
        for ( i in answers ) {
            answer = answers[ i ];
            li = document.createElement( 'li' );
            li.style.display = 'inline';
            li.style.padding = '5px';
            btn = document.createElement( 'input' );
            btn.type = 'button';
            btn.value = answer.Text;
            btn.onclick = function ( act ) {
                return function () {
                    if ( act ) {
                        act();
                    }
                    Modals.Destroy();
                };
            }( answer.Action );
            li.appendChild( btn );
            options.appendChild( li );
        }
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( options );
        Modals.Create( qq , 250 , 100 );
    },
    Create: function ( node, width, height ) {
        if ( !width ) {
            width = 500;
        }
        if ( !height ) {
            height = 300;
        }
        Modals.ModalBG = bg = document.createElement( 'div' );
        bg.className = 'modalbg';
        Modals.CurrentWindow = modal = document.createElement( 'div' );
        modal.className = 'modal';
        modal.appendChild( node );
        modal.style.width  = width + 'px';
        modal.style.height = height + 'px';
        document.body.appendChild( bg );
        document.body.appendChild( modal );
        document.body.onscroll = Modals.Scrolled;
        Modals.CurrentWidth = width;
        Modals.CurrentHeight = height;
        Modals.Scrolled();
    },
    Destroy: function () {
        document.body.removeChild( Modals.CurrentWindow );
        document.body.removeChild( Modals.ModalBG );
    },
    Scrolled: function () {
        Modals.ModalBG.style.top = document.body.scrollTop + 'px';
        Modals.ModalBG.style.left = document.body.scrollLeft + 'px';
        Modals.CurrentWindow.style.marginLeft = document.body.scrollLeft - Modals.CurrentWidth / 2 + 'px'; // document.body.scrollTop + 'px';
        Modals.CurrentWindow.style.marginTop  = document.body.scrollTop - Modals.CurrentHeight / 2 + 'px';
    }
};
