/*
TODO:
    --Tagging Box Resizing
    --Resolve Overlapping Tags
    --Auto Scrolling
    --Na kani bold to tmima tou onomatos p pliktrologise kapios (~)
    --Na figi to X k na mpi Akirosi button
    --To Iparxi se auti tn ikona o kostis90gr na gini: Se auti tn fotografia ine o kostis90gr, o finlandos kai i arianti
    --Tag Notifications
    --Meta to Tag creation na gini fadeIn to tag gia 2-3s
    --Otan iparxi ena Tag stn gonia ( i teleuteo tag? ) k to mouse plisiazi to onoma, emfanizi border
    --Ta onomata dn exoun style.cursor="pointer"
    --An kanis drag to box os tn akri, k drop ektos ikonas, dn emfanizete sosta
    --An kanis click kapou, dn mporis xoris na ksanakanis click, na kanis d&d
*/
var Tag = {
    photoid : false, // set by view.php, contains the id of the current photo
    friends : [], // an array of all your mutual friends
    already_tagged : [], // an array of all the people tagged in this photo
    clicked : false, // true when the mouse is pressed on the image, false otherwiser
    run : false, // when tagging action is enabled
    // updates the friendlist and enables tagging
    start : function( kollitaria, keyword ) {
        if ( kollitaria === false ) {
            kollitaria = Tag.friends;
        }
        // Display only the first 15 entries
        var len = ( kollitaria.length < 15 ) ? kollitaria.length : 15;
        var ul = $( 'div.thephoto div.frienders ul' ).find( 'li' ).remove().end()
        .get( 0 );
        for( var i=0; i < len; ++i ) {
            if ( kollitaria[i] === '' ) { // flagged username. Do not display
                continue;
            }
            var li = document.createElement( 'li' );
            li.style.cursor = "pointer";
            if ( $.inArray( kollitaria[ i ], Tag.already_tagged ) != -1 ) { // the person is already recognised on this pic. Do not display
                li.style.display = "none";
            }
            var a = document.createElement( 'a' );
            a.onmousedown = ( function( username ) {
                            return function( event ) {
                                Tag.submitTag( event, username, this );
                            };
                } )( kollitaria[ i ] );
            var b = document.createElement( 'b' );
            b.appendChild( document.createTextNode( keyword ) );
            a.appendChild( document.createTextNode( keyword.substr( keyword.length ) ) );
            li.appendChild( a );
            ul.appendChild( li );
        }
        $( 'dd.addtag' ).hide(); // Hide tagging button
        $( 'div.thephoto > div:not(.tanga)' ).show(); // Show tagging windows, but not image tags
        
        if ( !Tag.run ) { // If the tagging is to appear (not to be refreshed by typing something in the input), and focus is needed
            var locate = document.location.href;
            if ( locate.substr( locate.length-13 ) != "#tagging_area" ) {
                document.location.href += "#tagging_area";
            }
            else {
                document.location.href = document.location.href;
            }
        }
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.run = true; // Tagging is now fully enabled
    },
    submitTag : function( event, username, node ) {
        // Get the current position of the tagging window
        var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
        var top = parseInt( $( 'div.tagme' ).css( 'top' ), 10 );
        
        $( node ).parent().hide(); // hide username from friends TODO: why not just remove?
        $( 'div.thephoto div.frienders form input' ).val( '' ); // clear the input field
        Tag.already_tagged.push( username ); // add username to the array of the people tagged
        
        // Add username to tagged people below the photo
        var div = document.createElement( 'div' );
        var a = document.createElement( 'a' );
        a.title = username;
        a.style.cursor = "pointer";
        a.onmouseover = ( function( username ) { 
                   return function( event ) {
                        var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                        Tag.showhideTag( nod, true, event );
                        if ( !Tag.run ) {
                            nod.find( 'div' ).css( 'borderWidth', '0px' ).show().end();
                        }
                    };
                } )( username );
        a.onmouseout = ( function( username ) { 
                return function () {
                    var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                    Tag.showhideTag( nod, false );
                    if ( !Tag.run ) {
                        nod.find( 'div' ).hide().end();
                    }
                };
            } )( username );
        a.appendChild( document.createTextNode( username ) );
        
        div.appendChild( a );
        
        $( 'div.image_tags' ).get( 0 ).appendChild( div );
        
        // Add a place on the image where the user appears
        var divani = document.createElement( 'div' );
        divani.className = "tag";
        divani.style.left = left + 'px';
        divani.style.top = top + 'px';
        var divani2 = document.createElement( 'div' );
        // updates the friendlist and enables tagging
        divani2.appendChild( document.createTextNode( username ) );
        divani.appendChild( divani2 );
        $( 'div.tanga' ).get( 0 ).appendChild( divani );
        
        // Display correct text based on the number of people already tagged in the picture
        if ( Tag.already_tagged <= 2 ) {
            // TODO: Maybe the only user is a girl, not a boy
            $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχει σε αυτήν την εικόνα ο";
        }
        else {
            $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχουν σε αυτήν την εικόνα οι: ";
        }
        
        // Show all the actual image tags    
        $( 'div.image_tags' ).show();
        
        Coala.Warm( 'album/photo/tag/new', { 'photoid' : Tag.photoid,
                                             'username' : username,
                                             'left' : left,
                                             'top' : top,
                                             'callback' : Tag.newCallback
                                            } );
        
        // Disable tagging
        Tag.close( event );
    },
    // Moves the tagging windows to a new position
    focus : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        // Click position, relative to the image
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
        // Size of the tagging frame. At the moment it is fixed to 170x170px
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        // Change border_width accordingly
        // 2 borders of 3 pixels each. Should be used in the calculations as well
        var border_width = 3*2;
        // Size of the image
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        // We want to place the center of the tagging frame to the position it was clicked, not the top left corner. Change click position accordingly
        x -= tag_width / 2;
        y -= tag_height / 2;
        // Do not allow tagging frame to "escape" from the image
        if ( x < 0 ) { // The new position was really close to the left border of the image.
            x = 0;
        }
        if ( x + tag_width + border_width > image_width ) { // The new position was really close to the right border of the image
            x = image_width - tag_width - border_width;
        }
        if ( y < 0 ) { // The new position was really close to the top border of the image
            y = 0;
        }
        if ( y + tag_height + border_width > image_height ) { // The new position was really close to the bottom border of the image
            y = image_height - tag_height - border_width;
        }
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } ); // Move to new position
        $( 'div.thephoto div.frienders' ).css( { left: ( x + 170 ) + 'px', top : y + 'px' } ); // Move TagFriend window accordingly
    },
    // Drags the tagging window while tagging, or shows tags otherwise
    drag : function( event ) {
        if ( !Tag.run ) { // not tagging
            var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
            var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
            $( 'div.tanga div' ).each( function( i ) { // Move through all the tags and display appropriate ones. Hide the rest
                var left = parseInt( $( this ).css( 'left' ), 10 );
                var top = parseInt( $( this ).css( 'top' ), 10 );
                if ( x>left && x < left+170 && y > top && y < top+170 ) { // mouse is over the current tag area
                    $( this ).css( { "borderWidth" : "2px", "cursor" : "pointer" } ).find( 'div' ).show();
                }
                else {
                    $( this ).css( {"borderWidth" : "0px", "cursor" : "default" } ).find( 'div' ).hide();
                }
            } );
            return;
        }
        if ( Tag.clicked ) { // Click is pressed and tagging mode enabled. Drag
            $( 'div.thephoto div.frienders' ).hide();
            Tag.focus( event );
        }
    },
    // Works as event bubble canceling function, so that the rest of the events won't be triggered
    ekso : function( event ) { 
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
        Tag.clicked=false; // Drop
    },
    // Runs only when the input is focused
    focusInput : function( event ) {
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.ekso( event ); // Do not move tagging window
    },
    // Shows friend list
    showSug : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=false;
        $( 'div.thephoto div.frienders' ).show();
        $( 'div.thephoto div.frienders form input' ).focus();
    },
    // onmousedown the image
    katoPontike : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=true;
        Tag.focus( event );
    },
    // Displays friends based on what the user typed in the input box
    filterSug : function( event ) {
        var text = $( 'div.thephoto div.frienders form input' ).val();
        if ( event.keyCode === 13 && $.inArray( text, Tag.friends ) !== -1 ) {
            Tag.submitTag( event, text, $( "div.thephoto div.frienders ul li:contains('" + text + "') a" ).get( 0 ) );
            return;
        }
        var friends = $.grep( Tag.friends, function( item, index ) { // select friends
                        return ( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
        Tag.start( friends, text ); // update friend list
    },
    // Disable tagging
    close : function( event ) {
        $( 'div.thephoto div.frienders form input' ).val( '' );
        $( 'dd.addtag' ).show();
        $( 'div.thephoto > div:not(.tanga)' ).hide();
        Tag.run = false;
    },
    // Delete an image tag
    del : function( id, username ) {
        var index = $.inArray( username, Tag.already_tagged );
        if ( index === -1 ) { // This will never run under normal cirmustances
            return;
        }
        
        $( "div.thephoto div.tanga div:contains('" + username + "')" ).remove();
        
        // Determine the number of the actual tagged people. TODO: Why not use DOM?
        var count = Tag.already_tagged.length;
        for( var i=0; i<Tag.already_tagged.length; ++i ) {
            if ( Tag.already_tagged[ i ] === '' ) {
                --count;
            }
        }
        Tag.already_tagged[ index ] = '';
        --count;
        if ( count === 0 ) {
            $( 'div.image_tags' ).hide();
        }
        else if ( count == 1 ) {
            $( 'div.image_tags' ).get( 0 ).firstChild.nodeValue = "Υπάρχει σε αυτήν την εικόνα ο";
        }
        Coala.Warm( 'album/photo/tag/delete', { 'id' : id } );
    },
    // Appends Tag Id to tags below the image and links on the actual tags
    newCallback : function( id, username, subdomain ) {
        var a = document.createElement( 'a' );
        a.title = "Διαγραφή";
        a.onclick = function() { 
            Tag.del( id, username );
            return false;
          };
        a.style.cursor = "pointer";
        a.className = "tag_del";
        $( a ).click( function() {
                $( this ).parent().hide( 400, function() {
                    $( this ).remove(); 
                } ); 
            } );
        
        $( 'div.image_tags div:last' ).get( 0 ).appendChild( a );
        $( 'div.thephoto div.tanga div.tag:last' ).click( function() { document.location.href = "http://" + subdomain + ".zino.gr"; } );
    },
    showhideTag : function( node, show, event ) {
        if ( show ) {
            if ( !Tag.run ) {
                $( node ).css( { "borderWidth" : "2px", "cursor" : "pointer" } );
                Tag.ekso( event );
            }
            return;
        }
        $( node ).css( { "borderWidth" : "0px", "cursor" : "default" } ); 
    }
};
$( document ).ready( function() {
        // Already Tagged people
        $( 'div.image_tags div' ).each( function( i ) {
                var username = $( this ).find( 'a:first' ).text();
                Tag.already_tagged.push( username );
                var a = $( this ).find( 'a:first' ).get( 0 );
                a.onmouseover = ( function( username ) { 
                           return function( event ) {
                                var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                                Tag.showhideTag( nod, true, event );
                                if ( !Tag.run ) {
                                    nod.find( 'div' ).css( 'borderWidth', '0px' ).show().end();
                                }
                            };
                        } )( username );
                a.onmouseout = ( function( username ) { 
                        return function () {
                            var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                            Tag.showhideTag( nod, false );
                            if ( !Tag.run ) {
                                nod.find( 'div' ).hide().end();
                            }
                        };
                    } )( username );
            } );
        $( 'div.image_tags div a.tag_del' ).click( function() {
                $( this ).parent().hide( 400, function() {
                    $( this ).remove(); 
                } ); 
            } );
        // Show/Hide tags when not tagging
        $( 'div.thephoto div.tanga div' ).mouseover( function(event) { Tag.showhideTag( this, true, event ); } );
        $( 'div.thephoto div.tanga div' ).mouseout( function() { Tag.showhideTag( this, false ); } );
        
        // Dump Face Detection Heuristic. Most faces are located on the first quarter of the image vertically, and in the middle horizontally. Place tag frame there
        // Change border_width accordingly
        var border_width = 3*2;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        var x = ( image_width - tag_width - border_width )/2;
        var y = ( image_height - tag_height - border_width )*0.25; // 1/4
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
        $( 'div.thephoto div.frienders' ).css( { left: ( x + 170 ) + 'px', top : y + 'px' } );
    } );
