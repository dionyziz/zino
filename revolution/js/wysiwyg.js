var WYSIWYG = {
    blankPage: 'http://static.zino.gr/revolution/blank.html',
    
    VideoPlay: function ( id, node ) {
        if ( $( node ).parents( '.novideo' ).length ) {
            return;
        }
        node.innerHTML = '<object type="application/x-shockwave-flash" style="width:425px; height:344px;" data="http://www.youtube.com/v/' + id + '&amp;autoplay=1"><param name="movie" value="http://www.youtube.com/v/' + node + '&amp;autoplay=1" /></object>';
        node.style.width = '425px';
        node.style.height = '344px';
        node.style.backgroundColor = 'black';
        node.style.clear = 'both';
    },
    CurrentTarget: 0,
    Create: function ( where, fieldname, buttons, tabindex ) {
        setTimeout( function () {
            WYSIWYG.CreateReal( where, fieldname, buttons, tabindex );
        } , 300 );
    },
    GetDocument: function ( iframe ) {
        if ( typeof iframe.contentWindow != 'undefined' ) {
            return iframe.contentWindow.document;
        }
        if ( typeof iframe.contentDocument != 'undefined' ) {
            return iframe.contentDocument;
        }
        return false;
    },
    ByName: [],
    Focus: function ( which ) {
        setTimeout( function() {
            which.contentWindow.focus();
        }, 100 );
        /*
        var editdoc     = WYSIWYG.GetDocument( which );             // get iframe editor document object
        var editorRange = editdoc.body.createTextRange();           // editor range
        var curRange    = editdoc.selection.createRange();          // selection range
 
        if (curRange.length == null && !editorRange.inRange(curRange)) { // make sure it's not a controlRange
          // is selection in editor range
          editorRange.collapse();                                      // move to start of range
          editorRange.select();                                        // select
          curRange = editorRange;
        }
        */
    },
    InsertVideo: function ( target, userstring ) {
        if ( typeof userstring == 'string' && userstring !== '' ) {
            // youtube
            var match = /v\=([a-zA-Z0-9_-]+)/.exec( userstring );
            if ( match !== null && match.length == 2 ) { // youtube video
                WYSIWYG.ExecCommand( target, 'inserthtml', '<br /><img src="' + ExcaliburSettings.imagesurl + 'video-placeholder.png?v=' + match[ 1 ] + '" alt="Στη θέση αυτή θα εμφανιστεί το video σου" style="border:1px dotted blue;" /><br />' );
            }
            else {
                // veoh
                match = /v([a-zA-Z0-9_-]+)/.exec( userstring );
                if ( match !== null && match.length ==2 ) { // veoh video
                    WYSIWYG.ExecCommand( target, 'inserthtml', '<br /><img src="' + ExcaliburSettings.imagesurl + 'video-placeholder.png?w=' + match[ 1 ] + '" alt="Στη θέση αυτή θα εμφανιστεί το video σου" style="border:1px dotted blue;" /><br />' );
                }
                else {
                    alert( 'Το video δεν είναι έγκυρη διεύθυνση του YouTube' );
                }
            }
        }
    },
    InsertImage: function ( target, userstring  ) {
        if ( typeof userstring == 'string' && userstring !== '' ) {
            match = /^https?\:\/\/[a-z.0-9-]{5,128}\/[a-zA-Z0-9_.,?&=\/-]{1,256}$/.exec( userstring );
            if ( match === null || match.length != 1 ) {
                alert( 'Η εικόνα δεν είχε έγκυρη διεύθυνση' );
                return;
            }
            WYSIWYG.ExecCommand( target, 'inserthtml', '<img src="' + match[ 0 ].replace(/&/, "&amp;") + '" alt="" style="border:1px dotted blue;" /><br />' );
        }
    },
    InsertFromAlbum: function ( target, albumid, where ) {
        var img = document.createElement( 'img' );
        
        img.src = ExcaliburSettings.imagesurl + 'ajax-loader.gif';
        img.alt = 'Φόρτωση...';

        $( where ).parents( 'div.albumlist' ).parents( 'form' ).find( 'div.photolist' ).empty().append( img );

        Coala.Cold( 'album/photo/list', {
            'albumid': albumid,
            'callback': function ( items, where ) {
                var photolist = $( where ).parents( 'div.albumlist' ).parents( 'form' ).find( 'div.photolist' )[ 0 ];

                $( photolist ).empty();

                for ( i = 0; i < items.length; ++i ) {
                    var a = document.createElement( 'a' );
                    var img = document.createElement( 'img' );
                    var url = items[ i ][ 0 ];

                    img.src = url;
                    a.appendChild( img );
                    a.href = '';
                    $( a ).click( function ( url, title ) {
                        return function () {
                            title = title.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
                            WYSIWYG.ExecCommand( target, 'inserthtml', '<img src="' + url + '" alt="' + title + '" />' );
                            Modals.Destroy();
                            return false;
                        };
                    }( items[ i ][ 1 ], items[ i ][ 2 ] ) );
                    photolist.appendChild( a );
                }
                var div = document.createElement( 'div' );
                div.style.clear = 'both';
                photolist.appendChild( div );
            },
            'location': where
        } );
    },
    CommandVideo: function ( target ) {
        return function () {
            var vid = $( '.wysiwyg-control-video' )[ 0 ].cloneNode( true );
            Modals.Create( vid, 500, 150 );
            setTimeout( function () {
                $( vid ).find( 'input' )[ 0 ].focus();
            }, 0 );
        };
    },
    CommandImage: function ( target ) {
        return function () {
            var pic = $( '.wysiwyg-control-image-start' )[ 0 ].cloneNode( true );
            Modals.Create( pic, 500, 250 );
            setTimeout( function () {
                $( pic ).find( 'a' )[ 0 ].focus();
            }, 100 );
        };
    },
    CommandLink: function ( target ) {
        return function () {
            var q = prompt( 'Πληκτρολόγησε την διεύθυνση προς την οποία θέλεις να γινει link:', 'http://www.zino.gr/' );
            
            if ( typeof q == "string" && q !== '' ) {
                WYSIWYG.ExecCommand( target, 'createLink', q );
            }
        };
    },
    ExecCommand: function ( fieldname, command, parameters ) {
        WYSIWYG.ByName[ fieldname ].execCommand( command, parameters );
    },
    CreateReal: function ( where, fieldname, buttons, tabindex ) {
        var toolbox = document.createElement( 'div' );
        var which = document.createElement( 'iframe' );
        
        which.src = WYSIWYG.blankPage;
        
        toolbox.className = 'toolbox';
        for ( i = 0; i < buttons.length; ++i ) {
            var link = document.createElement( 'a' );
            link.href = '';
            link.onclick = function ( command, parameters, textfocus ) {
                return function () {
                    link.blur();
                    WYSIWYG.CurrentTarget = fieldname;
                    if ( typeof command == 'function' ) {
                        command( parameters );
                    }
                    else {
                        WYSIWYG.ExecCommand( fieldname, command, parameters );
                    }
                    if ( textfocus ) {
                        WYSIWYG.Focus( which );
                    }
                    return false;
                };
            }( buttons[ i ].command, buttons[ i ].parameters, buttons[ i ].textfocus );
            var tooltip = document.createElement( 'span' );
            var img = document.createElement( 'img' );
            img.src = buttons[ i ].image;
            img.alt = buttons[ i ].tooltip;
            tooltip.appendChild( document.createTextNode( buttons[ i ].tooltip ) );
            link.appendChild( img );
            link.appendChild( tooltip );
            toolbox.appendChild( link );
        }
        
        var oldcontents = where.cloneNode( true );

        while ( where.firstChild ) {
            where.removeChild( where.firstChild );
        }
        
        which.style.backgroundColor = '#ccc';
        
        where.appendChild( toolbox );
        where.appendChild( which );
        
        which.tabIndex = tabindex;
        
        var doc = WYSIWYG.GetDocument( which );
        
        if ( doc === false ) {
            alert( 'WYSIWYG is not supported by your browser' );
            return;
        }
        
        WYSIWYG.Enable( which, fieldname, oldcontents );
    },
    Enable: function ( which, fieldname, oldcontents ) {
        try {
            WYSIWYG.ByName[ fieldname ] = new XbDesignMode( which );
        }
        catch ( e ) { // not ready yet, retry in another 100ms
            setTimeout( function () {
                WYSIWYG.Enable( which, fieldname, oldcontents );
            }, 100 );
            return;
        }

        setTimeout( function () {
            WYSIWYG.Check( which, fieldname, oldcontents );
        }, 100 ); // can't do check inline -- need the timeout for the browser to realize that the designMode has/hasn't taken effect and return us the ~actual~ value, not the one we set it to
    },
    Check: function ( which, fieldname, oldcontents ) {
        var doc = WYSIWYG.GetDocument( which );

        if ( doc.designMode.toLowerCase() != 'on' && doc.designMode.toLowerCase() != 'inherit' ) {
            setTimeout( function () {
                WYSIWYG.Enable( which, fieldname, oldcontents ); // RECURSE, go back to Enable() to enable WYSIWYG (late enabling) and wait for the next check!
            }, 100 );
            return;
        }

        WYSIWYG.ByName[ fieldname ].setCSSCreation( false );

        var frm = which;
        while ( frm.nodeName.toLowerCase() != 'form' ) {
            frm = frm.parentNode;
            if ( frm === null ) {
                alert( 'WYSIWYG elements should only be called within HTML <form>' );
                return;
            }
        }
        var scfield = document.createElement( 'input' );
        scfield.type = 'hidden';
        scfield.name = fieldname;
        scfield.value = '';
        frm.appendChild( scfield );
        var oldonsubmit = frm.onsubmit? frm.onsubmit: function () {};
        frm.onsubmit = function ( sc, ifdoc ) {
            return function () {
                var ret = oldonsubmit();
                sc.value = ifdoc.body.innerHTML;
                return ret;
            };
        }( scfield, doc );
        which.style.backgroundColor = 'white';

        doc.body.innerHTML = oldcontents.innerHTML;
        
        WYSIWYG.Focus( which );
    }
};

// -------------------------------

/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Netscape Cross Browser Design Mode code.
 *
 * The Initial Developer of the Original Code is
 * Netscape Communications Corporation.
 * Portions created by the Initial Developer are Copyright (C) 2003
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s): Doron Rosenberg <doron@netscape.com> (original author)
 *
 *                 
 *
 * ***** END LICENSE BLOCK ***** */

 
/*
    xbDesignMode
    
    a JavaScript wrapper for browsers that support designMode

*/ 
 
function XbDesignMode( aIFrame ) {
    this.mEditorDocument = null;
    this.mIFrameElement = null;

    // argument is a string, therefore an ID
    if ( typeof aIFrame == "string" && document.getElementById( aIFrame ).tagName.toLowerCase() == "iframe" ) {
        this.mIFrameElement = document.getElementById( aIFrame );
    }
    else if ( typeof aIFrame =="object" && aIFrame.tagName.toLowerCase() == "iframe" ) {
        this.mIFrameElement = aIFrame;
    }
    else {
        throw "Argument isn't an id of an iframe or an iframe reference";
    }
    if ( this.mIFrameElement.contentDocument ) {
        // Gecko
        console.log( 'in' );
        this.mEditorDocument = this.mIFrameElement.contentDocument;
        this.mEditorDocument.designMode = "On";
    }
    else {
        // IE
        this.mEditorDocument = this.mIFrameElement.contentWindow.document;
        this.mEditorDocument.designMode = "On";
        // IE needs to reget the document element after designMode was set 
        this.mEditorDocument = this.mIFrameElement.contentWindow.document;
    }
}


XbDesignMode.prototype.execCommand = function ( aCommandName, aParam ) {
    if ( this.mEditorDocument ) {
        if ( aCommandName == 'inserthtml' && typeof document.selection !== 'undefined' ) {
            // IE7 inserthtml
            this.mEditorDocument.body.innerHTML += aParam;
            return;
        }
        this.mEditorDocument.execCommand( aCommandName, false, aParam );
    }
    else {
        throw "no mEditorDocument found";    
    }
};

XbDesignMode.prototype.setCSSCreation = function ( aUseCss ) {
    if ( this.mEditorDocument ) {
        try { // IE doesn't support this
            this.mEditorDocument.execCommand( "styleWithCSS", false, aUseCss );
        }
        catch ( e ) {
        }
    }
    else {
        throw "no mEditorDocument found";
    }
};

XbDesignMode.prototype.getContents = function () {
    return this.mEditorDocument.body.innerHTML;
};
