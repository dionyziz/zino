var Tag = {
    photoid : false,
    friends : [],
    already_tagged : [],
    clicked : false,
    run : false,
    start : function( kollitaria ) {
        if ( kollitaria === false ) {
            kollitaria = Tag.friends;
        }
        var len = ( kollitaria.length < 15 ) ? kollitaria.length : 15;
        var ul = $( 'div.thephoto div.frienders ul' ).find( 'li' ).remove().end()
        .get( 0 );
        for( var i=0; i < len; ++i ) {
            if ( kollitaria[i] === '' ) {
                continue;
            }
            var li = document.createElement( 'li' );
            li.style.cursor = "pointer";
            if ( $.inArray( kollitaria[ i ], Tag.already_tagged ) != -1 ) {
                li.style.display = "none";
            }
            var a = document.createElement( 'a' );
            a.onmousedown = ( function( username ) {
                            return function( event ) {
                                var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
                                var top = parseInt( $( 'div.tagme' ).css( 'top' ), 10 );
                                
                                $( this ).parent().hide();
                                $( 'div.thephoto div.frienders form input' ).val( '' );
                                Tag.already_tagged.push( username );
                                
                                var div = document.createElement( 'div' );
                                var a = document.createElement( 'a' );
                                a.title = username;
                                a.appendChild( document.createTextNode( username ) );
                                div.appendChild( a );
                                
                                $( 'div.image_tags' ).get( 0 ).appendChild( div );
                                
                                if ( Tag.already_tagged <= 2 ) {
                                    $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχει σε αυτήν την εικόνα ο";
                                }
                                else {
                                    $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχουν σε αυτήν την εικόνα οι: ";
                                }
                                    
                                $( 'div.image_tags' ).show();
                                
                                Coala.Warm( 'album/photo/tag/new', { 'photoid' : Tag.photoid,
                                                                     'username' : username,
                                                                     'left' : left,
                                                                     'top' : top,
                                                                     'callback' : Tag.newCallback
                                                                    } );
                                
                                Tag.close( event );
                                // add tag
                            };
                } )( kollitaria[ i ] );
            a.appendChild( document.createTextNode( kollitaria[ i ] ) );
            li.appendChild( a );
            ul.appendChild( li );
        }
        $( 'div.thephoto > div' ).not( 'div.tanga' ).show();
        if ( !Tag.run ) {
            var locate = document.location.href;
            if ( locate.substr( locate.length-13 ) != "#tagging_area" ) {
                document.location.href += "#tagging_area";
            }
            else {
                document.location.href = document.location.href;
            }
        }
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.run = true;
    },
    focus : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        // Change border_width accordingly
        var border_width = 3*2;
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        x -= tag_width / 2;
        y -= tag_height / 2;
        if ( x < 0 ) {
            x = 0;
        }
        if ( x + tag_width + border_width > image_width ) {
            x = image_width - tag_width - border_width;
        }
        if ( y < 0 ) {
            y = 0;
        }
        if ( y + tag_height + border_width > image_height ) {
            y = image_height - tag_height - border_width;
        }
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
        $( 'div.thephoto div.frienders' ).css( { left: ( x + 170 ) + 'px', top : y + 'px' } );
    },
    drag : function( event ) {
        if ( !Tag.run ) {
            var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
            var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
            $( 'div.tanga div' ).each( function( i ) {
                var left = parseInt( $( this ).css( 'left' ), 10 );
                var top = parseInt( $( this ).css( 'top' ), 10 );
                if ( x>left && x < left+170 && y > top && y < top+170 ) {
                    $( this ).css( { "borderWidth" : "2px", "cursor" : "pointer" } ).find( 'div' ).show();
                }
                else {
                    $( this ).css( {"borderWidth" : "0px", "cursor" : "default" } ).find( 'div' ).hide();
                }
            } );
            return;
        }
        if ( Tag.clicked ) {
            $( 'div.thephoto div.frienders' ).hide();
            Tag.focus( event );
        }
    },
    ekso : function( event ) { // Works as bubble canceling function
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
        Tag.clicked=false;
    },
    focusInput : function( event ) {
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.ekso( event );
    },
    showSug : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=false;
        $( 'div.thephoto div.frienders' ).show();
    },
    katoPontike : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=true;
        Tag.focus( event );
    },
    filterSug : function( event ) {
        var text = $( 'div.thephoto div.frienders form input' ).val();
        var friends = $.grep( Tag.friends, function( item, index ) {
                        return ( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
        Tag.start( friends );
    },
    close : function( event ) {
        $( 'div.thephoto > div' ).not( 'div.tanga' ).hide();
        Tag.run = false;
    },
    del : function( id, username ) {
        var index = $.inArray( username, Tag.already_tagged );
        if ( index === -1 ) { // This will never run under normal cirmustances
            return;
        }
        var count = Tag.already_tagged.length;
        for( var i=0; i<Tag.already_tagged.length; ++i ) {
            if ( Tag.already_tagged[ i ] == '' ) {
                --count;
            }
        }
        Tag.already_tagged[ index ] = '';
        --count;
        if ( count == 0 ) {
            $( 'div.image_tags' ).hide();
        }
        else if ( count == 1 ) {
            $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχει σε αυτήν την εικόνα ο";
        }
        Coala.Warm( 'album/photo/tag/delete', { 'id' : id } );
    },
    newCallback : function( id, username ) {
        var a = document.createElement( 'a' );
        a.title = "Διαγραφή";
        a.onclick = function() { 
            Tag.del( id, username );
            return false;
          };
        a.className = "tag_del";
        $( a ).click( function() {
                $( this ).parent().hide( 400, function() {
                    $( this ).remove(); 
                } ); 
            } );
        
        $( 'div.image_tags div:last' ).get( 0 ).appendChild( a );
    }
};
$( document ).ready( function() {
        $( 'dd.addtag a' ).click( function( event ) {
                if ( Tag.run ) {
                    Tag.close( event );
                    return false;
                }
                Tag.start( false );
                return false;
            } );
        $( 'div.image_tags div' ).each( function( i ) {
                Tag.already_tagged.push( $( this ).find( 'a:first' ).text() );
            } );
        $( 'div.image_tags div a.tag_del' ).click( function() {
                $( this ).parent().hide( 400, function() {
                    $( this ).remove(); 
                } ); 
            } );
        $( 'div.thephoto div.tanga div' ).mouseover( function(event) {
                            if ( !Tag.run ) {
                                $( this ).css( { "borderWidth" : "2px", "cursor" : "pointer" } );
                                Tag.ekso( event );
                            }
                        } );
        $( 'div.thephoto div.tanga div' ).mouseout( function() { 
                            $( this ).css( { "borderWidth" : "0px", "cursor" : "default" } ); 
                        } );
            
        var border_width = 3*2;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        var x = ( image_width - tag_width - border_width )/2;
        var y = ( image_height - tag_height - border_width )*0.25; // 3/4
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
        $( 'div.thephoto div.frienders' ).css( { left: ( x + 170 ) + 'px', top : y + 'px' } );
    } );
