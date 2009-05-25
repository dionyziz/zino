/*
TODO:
    --Resolve Overlapping Tags
    --Meta to Tag creation na gini fadeIn to tag gia 2-3s
    --Otan iparxi ena Tag stn gonia ( i teleuteo tag? ) k to mouse plisiazi to onoma, emfanizi border
    --Ta onomata dn exoun style.cursor="pointer"
    --An kanis drag to box os tn akri, k drop ektos ikonas, dn emfanizete sosta
    --To box dn ginete panta hide me onmouseout
	
    From IE7 with love:
        --To onoma dn emfanizete terma aristera, alla sto kentro i sta deksia
        --Otan ginete onmouseover pano sto onoma sto tag, anabosbini
        --Otan ginonte onmouseover ta tags kato apo tn ikona, dn emfanizonte ta onomata
        --Otan kano click sto box dn me kani redirect sto profil tou xristi
*/
var Tag = {
	virgin : true, // controls whether friends,genders has been set
    photoid : false, // set by view.php, contains the id of the current photo
    friends : [], // an array of all your mutual friends
	genders : [], // an array of all your mutual friends' genders.  friends[ i ]  gender
    already_tagged : [], // an array of all the people tagged in this photo
    clicked : false, // true when the mouse is pressed on the image, false otherwiser
	resized : false, // true when the tag is resized
    run : false, // when tagging action is enabled
	prestart : function( kollitaria, keyword, aux ) {
		if ( Tag.virgin ) {
			Coala.Cold( 'album/photo/tag/getstuff', { 'callmeback' : Tag.start } );
		}
		else {
			Tag.start( kollitaria, keyword, aux );
		}
	},
    // updates the friendlist and enables tagging
    start : function( kollitaria, keyword, aux ) {
		if ( Tag.virgin ) { // after Coala still Virgin
			return;
		}
		var ul = $( 'div.thephoto div.frienders ul' ).find( 'li' ).remove().end()
		.get( 0 );
        if ( kollitaria === false ) {
            kollitaria = Tag.friends;
        }
        for( var i=0; i < kollitaria.length; ++i ) {
            if ( kollitaria[i] === '' ) { // flagged username. Do not display
                continue;
            }
            
            var li = document.createElement( 'li' );
            li.style.cursor = "pointer";
            if ( $.inArray( kollitaria[ i ], Tag.already_tagged ) != -1 ) { // the person is already recognised on this pic. Do not display
                li.style.display = "none";
            }
            
            var div = document.createElement( 'div' );
            div.onmousedown = ( function( username ) {
                            return function( event ) {
                                Tag.submitTag( event, username, this );
                                return false;
                            };
                } )( kollitaria[ i ] );
                
            var span = document.createElement( 'span' );
            
            span.appendChild( document.createTextNode( kollitaria[ i ].substr( 0, keyword.length ) ) );
            div.appendChild( span );
            div.appendChild( document.createTextNode( kollitaria[ i ].substr( keyword.length ) ) );
            li.appendChild( div );
            ul.appendChild( li );
        }
        $( 'dd.addtag' ).hide(); // Hide tagging button
        $( 'div.thephoto > div:not(.tanga)' ).show(); // Show tagging windows, but not image tags
        if ( aux === true ) { // Smooth Scrolling
            $( 'html, body' ).animate( { scrollTop: ( $( 'div.thephoto' ).offset().top - 20 ) }, 700 );
        }
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.run = true; // Tagging is now fully enabled
    },
    submitTag : function( event, username, node ) {
        // Get the current position of the tagging window
        var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
        var top = parseInt( $( 'div.tagme' ).css( 'top' ), 10 );
		var width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
		var height = parseInt( $( 'div.tagme' ).css( 'height'), 10 );
        var ind = $.inArray( username, Tag.friends );
		if ( ind === -1 ) {
			alert( "Δεν μπορείται να σημάνεται τον συγκεκριμένο χρήστη" );
			return;
		}
		var gender = ( Tag.genders[ ind ] == 'f' )?"η ":"ο ";
		
        $( node ).parent().hide(); // hide username from friends TODO: why not just remove?
        $( 'div.thephoto div.frienders form input' ).val( '' ); // clear the input field
        Tag.already_tagged.push( username ); // add username to the array of the people tagged
        
        // Add username to tagged people below the photo
        var div = document.createElement( 'div' );
		div.appendChild( document.createTextNode( gender ) );
		
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
        
		if ( $( 'div.image_tags:first' ).children().length !== 0 ) {
			var las = $( 'div.image_tags:first div:last' ).get( 0 );
			las.appendChild( document.createTextNode( ' και ' ) );
			if ( $( las ).prevAll().length !== 0 ) {
				las = $( las ).prev().get( 0 );
				las.removeChild( las.lastChild );
				las.appendChild( document.createTextNode( ', ' ) );
			}
		}
		
        $( 'div.image_tags:first' ).get( 0 ).appendChild( div );
        
        // Add a place on the image where the user appears
        var divani = document.createElement( 'div' );
        divani.className = "tag";
        divani.style.left = left + 'px';
        divani.style.top = top + 'px';
		divani.style.width = width + 'px';
		divani.style.height = height + 'px';
        var divani2 = document.createElement( 'div' );
        // updates the friendlist and enables tagging
        divani2.appendChild( document.createTextNode( username ) );
        divani.appendChild( divani2 );
        $( 'div.tanga' ).get( 0 ).appendChild( divani );
        
        // Show all the actual image tags    
        $( 'div.image_tags:first' ).show();
        
        Coala.Warm( 'album/photo/tag/new', { 'photoid' : Tag.photoid,
                                             'username' : username,
                                             'left' : left,
                                             'top' : top,
											 'width' : width,
											 'height' : height,
                                             'callback' : Tag.newCallback
                                            } );
        
        // Disable tagging
        Tag.close();
        return false;
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
        $( 'div.thephoto div.frienders' ).css( { left: ( x + tag_width ) + 'px', top : y + 'px' } ); // Move TagFriend window accordingly
    },
    // Drags the tagging window while tagging, or shows tags otherwise
    drag : function( event ) {
        if ( !Tag.run ) { // not tagging
            var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
            var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
            $( 'div.tanga div' ).each( function( i ) { // Move through all the tags and display appropriate ones. Hide the rest
                var left = parseInt( $( this ).css( 'left' ), 10 );
                var top = parseInt( $( this ).css( 'top' ), 10 );
				var width = parseInt( $( this ).css( 'width' ), 10 );
				var height = parseInt( $( this ).css( 'height' ), 10 );
                if ( x>left && x < left + width && y > top && y < top + height ) { // mouse is over the current tag area
                    $( this ).css( { "borderWidth" : "2px", "cursor" : "pointer" } ).find( 'div' ).show();
                }
                else {
                    $( this ).css( {"borderWidth" : "0px", "cursor" : "default" } ).find( 'div' ).hide();
                }
            } );
            return;
        }
		if ( Tag.resized ) {
			$( 'div.thephoto div.frienders' ).hide();
			Tag.resize_do( event );
		}
        else if ( Tag.clicked ) { // Click is pressed and tagging mode enabled. Drag
            $( 'div.thephoto div.frienders' ).hide();
            Tag.focus( event );
        }
    },
    // Works as event bubble canceling function, so that the rest of the events won't be triggered
    ekso : function( event, stop ) { 
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
		if ( stop !== true ) {
			//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br />Tag.clicked=false apo to ekso";
			Tag.clicked=false; // Drop
		}
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
		//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br /> Tag.clicked=false apo toshowSug";
        Tag.clicked = false;
		Tag.resized = false;
        $( 'div.thephoto div.frienders' ).show();
        $( 'div.thephoto div.frienders form input' ).focus();
    },
    // onmousedown the image
    katoPontike : function( event ) {
        if ( !Tag.run ) {
            return;
        }
		//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br />Tag.clicked=true apo tn katoPontike";
        Tag.clicked=true;
        Tag.focus( event );
    },
    // Displays friends based on what the user typed in the input box
    filterSug : function( event ) {
        var text = $( 'div.thephoto div.frienders form input' ).val();
        if ( event.keyCode === 27 ) {
            Tag.close();
            return;
        }
        if ( event.keyCode === 13 ) {
            var index, found;
            found = false;
            for( index=0; index < Tag.friends.length; ++index ) {
                if ( text.toUpperCase() == Tag.friends[ index ].toUpperCase() ) {
                    found = true;
                    break;
                }
            }
            if ( !found ) {
                return;
            }
            Tag.submitTag( event, Tag.friends[ index ], $( "div.thephoto div.frienders ul li:contains('" + Tag.friends[ index ] + "') a" ).get( 0 ) );
            return;
        }
        var friends = $.grep( Tag.friends, function( item, index ) { // select friends
                        return ( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
        Tag.start( friends, text ); // update friend list
    },
    // Disable tagging
    close : function() {
        $( 'div.thephoto div.frienders form input' ).val( '' );
        $( 'dd.addtag' ).show();
        $( 'div.thephoto div.frienders, div.thephoto div.tagme' ).hide();
        Tag.run = false;
        return false;
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
            $( 'div.image_tags:first' ).hide();
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
				Tag.parseDel( $( this ).parent() );
            } );
        
        a.appendChild( document.createTextNode( " " ) ); // Space needed for CSS Spriting
        $( 'div.image_tags:first div:last' ).get( 0 ).appendChild( a );
        $( 'div.thephoto div.tanga div.tag:last' ).click( function() { document.location.href = "http://" + subdomain + ".zino.gr"; } );
        return false;
    },
    showhideTag : function( node, show, event ) {
        if ( show ) {
            if ( !Tag.run ) {
                //$( node ).css( { "borderWidth" : "2px", "cursor" : "pointer" } );
                $( node ).css( 'borderWidth', '2px' );
                Tag.ekso( event );
            }
            return;
        } //else if ( !show )
        //$( node ).css( { "borderWidth" : "0px", "cursor" : "default" } ); 
        //$( node ).css( 'borderWidth', '0px' );
    },
	// displays conjucates and punctuation correctly
	parseDel : function( par ) {
        var neighbor;
		var deksia = par.nextAll().length;
		if ( deksia === 0 ) { // deleting last tag
			if ( par.prevAll().length !== 0 ) { // there is some tag left to it
				neighbor = $( par ).prev().get( 0 );
				neighbor.removeChild( neighbor.lastChild ); // remove "and" text
				if ( $( neighbor ).prevAll().length !== 0 ) { // if there is something even lefter, append the text there
					neighbor = neighbor.previousSibling;
					neighbor.removeChild( neighbor.lastChild );
					neighbor.appendChild( document.createTextNode( " και " ) );
				}
			}
		}
		else if ( deksia === 1 && par.prevAll().length !== 0 ) {
			neighbor = par.prev().get( 0 );
			neighbor.removeChild( neighbor.lastChild );
			neighbor.appendChild( document.createTextNode( " και " ) );
		}
		$( par ).hide( 400, function() {
			$( this ).remove(); 
		} );
	},
	resize_down : function( event ) {
		if ( !Tag.run ) {
			return;
		}
		Tag.ekso( event );
		Tag.resized = true;
	},
	resize_do : function( event ) {
		if ( !Tag.run ) {
			return;
		}
		// Click position, relative to the image
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
		var pos_x = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
		var pos_y = parseInt( $( 'div.tagme').css( 'top' ), 10 );
		var width = x - pos_x;
		var height = y - pos_y;
		
		if ( width >= 45 ) {
			$( 'div.tagme' ).css( { "width" : width + 'px' } );
		}
		if ( height >= 45 ) {
			$( 'div.tagme' ).css( { "height" : height + 'px' } );
		}
		
		var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
		$( 'div.thephoto div.frienders' ).css( 'left', ( left + width ) + 'px' );
	},
	autocomplete : function( event ) {
		if ( event.keyCode == 9 ) {
			var node = $( "div.thephoto div.frienders ul li div" );
			var text = node.text();
            if ( $.inArray( text, Tag.friends ) !== -1 ) {
				Tag.submitTag( event, text, $( "div.thephoto div.frienders ul li:contains('" + text + "') a" ).get( 0 ) );
				window.setTimeout( "$( 'div.thephoto' ).get( 0 ).scrollIntoView( true )", 5 );
			}
			Tag.ekso( event );
		}
	},
    OnLoad : function() {
        // Already Tagged people
        $( 'div.image_tags:first div' ).each( function( i ) {
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
        $( 'div.image_tags:first div a.tag_del' ).click( function() {
				Tag.parseDel( $( this ).parent() );
            } );
        // Show/Hide tags when not tagging
        $( 'div.thephoto div.tanga div.tag' ).mouseover( function(event) { Tag.showhideTag( this, true, event ); } );
        $( 'div.thephoto div.tanga div.tag' ).mouseout( function() { Tag.showhideTag( this, false ); } );
        
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
        $( 'div.thephoto div.frienders' ).css( { left: ( x + tag_width ) + 'px', top : y + 'px' } );
    }
};
