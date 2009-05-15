var Comments = {
    typing : false,
	numchildren : {},
	Create : function( parentid ) {
		var texter;
		if ( parentid === 0 ) { // Clear new comment message
			texter = $( "div.newcomment > div.text > textarea" ).get( 0 ).value;
			$( "div.newcomment > div.text > textarea" ).get( 0 ).value = '';
		}
		else {
			texter = $( "#comment_reply_" + parentid + " > div.text > textarea" ).get( 0 ).value;
		}
		texter = $.trim( texter );
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var a = document.createElement( 'a' );
        $( a ).append( document.createTextNode( "Απάντησε" ) )
        .attr( 'href' , '' )
        .click( function() {
            return false;
        } );
		var indent = ( parentid === 0 )? -1: parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 ) / 20;
        var marginright = ( parentid === 0 ) ? 0 : ( indent + 1 ) * 20 + 'px';
		// Dimiourgisa ena teras :-S
		var daddy = ( parentid === 0 )? $( "div.newcomment:first" ).clone( true ):$( "#comment_reply_" + parentid );
        var temp = daddy.css( "opacity", 0 ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end()
        .find( "div.toolbox" ).show().end()
        .css( "border-top" , "3px solid #b3d589" )
		.find( "div.text" ).empty()./*html( texter.replace( /\n/gi, "<br />" ) )*/text( texter ).end()
		.find( "div.bottom" ).css( "visibility" , "hidden" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end();
		
		var valu = temp.find( "div.text" ).html();
        valu = valu + 'test';
		temp.find( "div.text" ).html( valu.replace( /\n/gi, "<br />" ) );
		
        var link = document.createElement( 'a' );
        var username = GetUsername();
        if ( ExcaliburSettings.Production ) {
            var hrefs = "http://" + username + ".zino.gr/";
        }
        else {
            var hrefs = "http://" + username + ".beta.zino.gr/phoenix/";
        }
        var avatarview = $( daddy ).find( "div.who span.imageview" );
        var avatar = $( avatarview ).clone( true );
        $( link ).attr( "href" , hrefs )
        .append( avatar ).append( document.createTextNode( username ) );
	    $( daddy ).find( "div.who" ).empty().append( link );	
		if ( parentid === 0 ) {
			temp.insertAfter( "div.newcomment:first" ).fadeTo( 400, 1 );
		}
		else {
			temp.insertAfter( "#comment_" + parentid ).fadeTo( 400, 1 );
		}
		var type = temp.find( "#type:first" ).text();
		Comments.FixCommentsNumber( type, true );
		Coala.Warm( 'comments/new', { 	text : texter, 
            parent : parentid,
            compage : temp.find( "#item:first" ).text(),
            type : type,
            node : temp, 
            callback : Comments.NewCommentCallback
        } );
        Comments.ToggledReplies[ parentid ] = 0;
	},
    NewCommentCallback : function( node , id , parentid , newtext ) {
		if ( parentid !== 0 ) {
			++Comments.numchildren[ parentid ];
		}
		Comments.numchildren[ id ] = 0;	
		var indent = ( parentid===0 )? -1 : parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 )/20;
        node.attr( 'id', 'comment_' + id )
		.find( "div.text" ).html( newtext ).end()
        .find( 'div.bottom' ).css( "visibility" , "visible" ).find( 'a' ).click( function() {
                Comments.ToggleReply( id , indent + 1 );
                return false;
            }
        );
	},
	Reply : function( nodeid, indent ) {
		// Atm prefer marginLeft. When the comment is created it will be converted to paddingLeft. Looks better
		var temp = $( "div.newcomment:first" ).clone( true ).css( { marginLeft : (indent+1)*20 + 'px', opacity : 0 } ).attr( 'id', 'comment_reply_' + nodeid );
		$( temp ).find( "div.toolbox" ).show().end()
        .css( "border-top" , "3px solid #b3d589" )
        .find( "div.bottom form input:first" ).get( 0 ).onclick = function() { // Only with DOM JS the onclick event is overwritten
					$( "#comment_reply_" + nodeid ).css( {marginLeft : (indent+1)*20 + 'px' } );
					Comments.Create( nodeid );
					return false;
				} ;

		temp.insertAfter( '#comment_' + nodeid ).fadeTo( 300, 1 );
        Comments[ "Changed" + nodeid ] = false;
        $( temp ).find( "div.text textarea" ).focus( function() {
            if ( !Comments[ "Changed" + nodeid ] ) {
                this.value = "";
                $( this ).css( "color" , "#000" );
                Comments.typing = true;
            }
        
        } ) 
        .blur( function() {
            $( "#comment_" + nodeid + " div.text" ).css( "font-weight" , "400" );
            if ( this.value  === '' ) {
                this.value = "Πρόσθεσε ένα σχόλιο..."; 
                $( this ).css( "color" , "#666" );
                Comments[ "Changed" + nodeid ] = false;
            }
            else {
                Comments[ "Changed" + nodeid ] = true;
            }
            setTimeout( function() {
                Comments.typing = false;
                Comments.Page.NextComment();
            } , 2000 );
        } ).get( 0 ).focus();
	},
	FixCommentsNumber : function( type, inc ) {
		if ( type != 2 && type != 4 ) { // If !Image or Journal
			return;
		}
		var node = $( "dl dd.commentsnum" );
        var icon = document.createElement( "span" );
        $( icon ).addClass( 's_commnum' ).css( 'padding-left' , '19px' ).append( document.createTextNode( ' ' ) );
		if ( node.length !== 0 ) {
			var commentsnum = parseInt( node.text(), 10 );
			commentsnum = (inc)?commentsnum+1:commentsnum-1;
			$( node ).empty().append( icon ).append( document.createTextNode( commentsnum + " σχόλια" ) );
		}
		else {
			var dd = document.createElement( 'dd' );
			$( dd ).addClass( "commentsnum" )
            .append( icon )
			.append( document.createTextNode( "1 σχόλιο" ) );
			$( "div dl" ).prepend( dd );
		}
	},
    FindLeftPadding : function( node ) {
        var leftpadd = $( node ).css( 'margin-left' );
        if ( leftpadd ) {
            return leftpadd.substr( 0 , leftpadd.length - 2 ) - 0;
        }
        else {
            return 0;
        }
    },
    ToggledReplies: {},
    ToggleReply: function ( id, indent ) {
        if ( typeof Comments.ToggledReplies[ id ] != 'undefined' && Comments.ToggledReplies[ id ] === 1 ) {
            $( '#comment_reply_' + id ).remove(); 
            Comments.ToggledReplies[ id ] = 0;

            return;
        }
        Comments.ToggledReplies[ id ] = 1;
        Comments.Reply( id, indent );
    },
    Focus: function ( id, indent, loggedin ) {
        var cmd = $( '#comment_' + id )[ 0 ];
        $( cmd ).find( "div.text" ).css( "font-weight" , "700" );
        cmd.scrollIntoView( false );
        window.scrollBy( 0 , 200 );
        if ( loggedin ) {
            Comments.ToggleReply( id, indent - 1 );
        }
    },
    parents : [],
    indents : [],
    ids     : [],
    lpadd   : [],
    OnLoad : function() {
        if ( $.browser.msie ) {
            $( "[id^='comment_']" ).each( function( i ) {
                var parent =  this;
                Comments.parents[ i ] = parent;
                
                var id = $( parent ).attr( "id" ).substr( 8 );
                Comments.ids[ i ] = id;
                
                Comments.lpadd[ i ] = Comments.FindLeftPadding( parent );

                var indent = parseInt( Comments.lpadd[ i ], 10 )/20;
                Comments.indents[ i ] = indent;
            } );
        }
        else {
            $( "[id^='comment_']" ).each( function( i ) {
                var parent = this;
                Comments.parents[ i ] = parent;
                
                var id = $( parent ).attr( "id" ).substr( 8 );
                Comments.ids[ i ] = id;
                
                Comments.lpadd[ i ] = Comments.FindLeftPadding( parent );

                var indent = parseInt( Comments.lpadd[ i ], 10 )/20;
                Comments.indents[ i ] = indent;
            } );
        }

        $( "[id^='comment_'] > div.bottom > a" ).each( function( i ) {
            $( this ).click( function() {
                Comments.ToggleReply( Comments.ids[ i ] , Comments.indents[ i ] );
                
                return false;
            } );
        } );
        
        if ( $( "div.comments div[id^='comment_']" )[ 0 ] ) {
            var username = GetUsername();
            $( "[id^='comment_'] > div.toolbox > span.time" ).each( function( i ) {
                var commdate = $( this ).text();
                $( this ).empty()
                .text( greekDateDiff( dateDiff( commdate , Comments.nowdate ) ) )
                .show();
            } );

            if ( !username || ( typeof ExcaliburSettings.CommentsDisabled != 'undefined' && ExcaliburSettings.CommentsDisabled ) ) {
                $( "[id^='comment_'] > div.bottom" ).empty();
            }
            else {
                $( "[id^='comment_'] > div.bottom" ).each( function( i ) {
                    var leftpadd = Comments.lpadd[ i ];
                    if ( leftpadd > 500 ) {
                        $( this ).empty();
                    }
                } );
                $( "[id^='comment_'] > div.who > a > span.imageview > img.avatar[alt='" + username + "']" ).each( function( i ) {
                    $( this ).parent().parent().parent().parent().css( "border-top" , "3px solid #b3d589" );
                } );
            }
        }
    },
    NewCommentOnLoad : function() {
        Comments[ "Changed0" ] = false;
        $( "#newcom div.toolbox" ).hide();
        $( "#newcom div.text textarea" ).css( "color" , "#666" ).focus( function() {
            if ( !Comments[ "Changed0" ] ) {
                this.value = "";
                $( this ).css( "color" , "#000" );
            }
            Comments.typing = true; 
        } )
        .blur( function() {
            if ( this.value  === '' ) {
                this.value = "Πρόσθεσε ένα σχόλιο..."; 
                $( this ).css( "color" , "#666" );
                Comments[ "Changed0"] = false;
            }
            else {
                Comments[ "Changed0"] = true;
            }
            setTimeout( function() {
                Comments.typing = false;
                Comments.Page.NextComment();
            } , 2000 );
        } );
    },
    Page : {
        Queue : [],
        NextComment : function() {
            if ( Comments.Page.Queue.length == 0 ) {
                return;
            }
            if ( !Comments.typing ) {
                Comments.Page.ShowComment( Comments.Page.Queue.pop() , 2000 );
            }
        },
        ShowComment : function( qnode , timervalue ) {
            if ( qnode.name == GetUsername() ) {
                return;
            }
            setTimeout( "Comments.Page.NextComment();" , timervalue );
            $( qnode.node ).css( "opacity" , "0" ).find( "div.toolbox span.time" ).empty().text( "πριν λίγο" ).show();
            if ( qnode.parentid == 0 ) {
                $( qnode.node ).insertBefore( "[id^='comment_']:first" ); 
                $( qnode.node ).find( "div.bottom > a" ).click( function() {
                    var id = $( this ).parent().parent().attr( "id" ).substr( 8 );
                    Comments.ToggleReply( id , 0 );
                    return false;
                } );
            }
            else {
                var parent = $( "#comment_" + qnode.parentid );
                var parentleftmargin = Comments.FindLeftPadding( parent );
                var parentident = Math.floor( parseInt( parentleftmargin , 10 ) / 20 );
                var ident = parentident + 1;
                var leftmargin = ident * 20;
                
                $( qnode.node ).insertAfter( parent )
                .css( 'margin-left' , leftmargin + "px" );
                if ( leftmargin > 500 ) {
                    $( qnode.node ).find( "div.bottom" ).empty();
                }
                else {
                    $( qnode.node ).find( "div.bottom > a" ).click( function() {
                        var id = $( this ).parent().parent().attr( "id" ).substr( 8 );
                        Comments.ToggleReply( id , ident );
                        return false;
                    } );
                }
            }
            Comments.FixCommentsNumber( qnode.type , true );
            $( qnode.node ).fadeTo( 400 , 1 );
        }
    }
};
