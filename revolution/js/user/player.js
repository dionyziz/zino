Profile.Player = {
    Init: function() {
        if ( $( 'a#addnewsong' ).length ) {
            $( 'a#addnewsong' ).click( Profile.Player.OpenSearchModal );
        }
        else {
            var $toolbox = $( '<div class="toolbox" />' );
            $( '<span class="delete">' ).attr( 'title', 'Διαγραφή τραγουδιού.' ).text( '\xA0' )
                .click( Profile.Player.DeleteSong )
                .appendTo( $toolbox );
            $( '<span class="search">' ).attr( 'title', 'Αλλαγή τραγουδιού.' ).text( '\xA0' )
                .click( Profile.Player.OpenSearchModal )
                .appendTo( $toolbox );
            
            $( '.mplayer .player' ).append( $toolbox ).hover( function(){
                $( this ).children( '.toolbox' ).stop( 1, 1 ).fadeIn( 'fast' );
            }, function(){
                $( this ).children( '.toolbox' ).stop( 1, 1 ).fadeOut( 'fast' );
            } );
        }
    },
    OpenSearchModal: function() {
        axslt( $.get(), 'call:user.modal.song', function() { //TODO remove pointless ajax request
            Profile.Player.PrepareModal( $( this ).filter( '#mplayersearchmodal' ), function() {} );
        } );
        return false;
    },
    DeleteSong: function() {
        $.post( 'user/update', { song: -1 } );
        $( '.mplayer' ).empty().append( $( '<a href="" id="addnewsong" class="notshown editable" />' ).text( 'Πρόσθεσε ένα τραγούδι στο προφίλ σου' ) );
    },
    SubmitSearch: function( $modal, query ) {
        $modal.animate( {
            marginTop: '-170'
        }, 'normal' ).css({
            MozBorderRadiusBottomright: 4,
            MozBorderRadiusBottomleft: 4,
        }).find( '.list' ).slideDown( 'normal' );
        $( '#mplayersearchmodal' ).find( 'tbody' ).empty();
        axslt( $.get( 'song/list', { query: query } ), 'call:user.modal.songlist', function( elems ) {
            $modal.find( 'table thead tr' ).removeClass( 'hidden' );
            var $elems = $( elems ).filter( 'tr' );
            $elems.click( function () { 
                var id = $( this ).attr( 'id' ).split( '_' )[ 1 ];
                var albumid = $( this ).find( '.albumid' ).text().split( '_' )[ 1 ];
                var artistid = $( this ).find( '.artistid' ).text().split( '_' )[ 1 ];
                if ( !isNaN( id ) ) {
                    axslt( '<song id="' + id + '" />', '/song', function( elems ) {
                        $.post( 'user/update', { 
                            song: { songid: id, albumid: albumid, artistid: artistid }
                        } );
                        $modal.jqmHide().remove();
                        $( '.mplayer' ).empty().append( $( elems ).filter( 'div.player' ) );
                        Profile.Player.Init();
                    } )
                }
                return false;
            } );
            $modal.find( 'table' ).append( $elems );
        } );
        //Coala.Cold( 'user/profile/searchsongs', { query: $( '#mplayersearchmodal .input input:first' ).val() } );
    },
    PrepareModal: function( $modal ) {
        $modal.appendTo( '#world' ).modal();

        $modal.find( '.input input:first' ).attr( 'default', $modal.find( '.input input:first' ).val() )
            .focus( function(){
                if( $( this ).val() == $( this ).attr( 'default' ) ){
                    $( this ).val( '' );
                }
            }).blur( function(){
                if( $( this ).val() == '' ){
                    $( this ).val( $( this ).attr( 'default' ) );
                }
            }).keypress( function( e ){
                if( e.keyCode == 13 ){
                    Profile.Player.SubmitSearch( $modal, $modal.find( '.input input:first' ).val() );
                    return false;
                }
            }).siblings( '.search' ).click( function(){
                if( !$( this ).hasClass( 'loading' ) ){
                    Profile.Player.SubmitSearch( $modal, $modal.find( '.input input:first' ).val() );
                }
                return false;
            }).mousedown( function(){
                $( this ).addClass( 'active' );
            }).mouseup( function(){
                $( this ).removeClass( 'active' );
            }).mouseout( function(){
                $( this ).removeClass( 'active' );
            });
    }
}

/*Player: {
		Setsong: function( content ){
			$( '#mplayersearchmodal div.search' ).removeClass( 'loading' );
			
			$( '.sidebar .mplayer .player, .sidebar .mplayer .addsong' ).remove();
			var div = document.createElement( 'div' );
			$( div ).html( content ).children().prependTo( '.sidebar .mplayer' );
			$( '#mplayersearchmodal' ).jqmHide().find( '.input' );
			Profile.Player.Initialize();
		},
		SelectSong: function( songid ){
			$( '#mplayersearchmodal div.search' ).addClass( 'loading' );
			$( '#mplayersearchmodal' ).jqmHide();
			$( '#profile .mplayer div:first' ).text( 'Αποθήκευση προφίλ...' );
			Coala.Warm( 'user/profile/selectsong', { songid: songid } );
		},
		RemoveWidget: function(){
			$( '#profile .mplayer div:first' ).text( 'Αποθήκευση προφίλ...' );
			Coala.Warm( 'user/profile/removewidget', {} );
		},
		SubmitSearch: function(){
			$( '#mplayersearchmodal div.search' ).addClass( 'loading' );
			$( '#mplayersearchmodal' ).animate( {
				top: "15%"
			}, 'normal' ).css({
				MozBorderRadiusBottomright: 4,
				MozBorderRadiusBottomleft: 4,
			}).find( '.list' ).slideDown( 'normal' );
			
			Coala.Cold( 'user/profile/searchsongs', { query: $( '#mplayersearchmodal .input input:first' ).val() } );
		},
		Addsongs: function( songs ){
			$( '#mplayersearchmodal div.search' ).removeClass( 'loading' );
			$( '#mplayersearchmodal table tbody tr' ).remove();
			if( !songs.length ){
				if( $( '#mplayersearchmodal .list div' ).length ){
					return false;
				}
				$( '#mplayersearchmodal .list' ).prepend( 
					$( document.createElement( 'div' ) )
						.text( 'Δε βρέθηκαν αποτελέσματα στην αναζήτησή σου. Δοκίμασε ξανά.' )
						.css({
							position: 'absolute',
							top: 25,
							left: 10
						})
					);
					$( '#mplayersearchmodal table thead tr' ).addClass( 'hidden' );
				return false;
			}
			$( '#mplayersearchmodal .list div' ).remove();
			$( '#mplayersearchmodal table thead tr' ).removeClass( 'hidden' );

			for( var i in songs ){
				var song = songs[ i ];
				
				var tr = document.createElement( 'tr' );
				var td = $( document.createElement( 'td' ) )
					.append( document.createElement( 'div' ) ).children()
						.append( $( document.createElement( 'div' ) ).addClass( 'text' ) )
						.append( $( document.createElement( 'div' ) ).addClass( 'fade' ) ).end();
					
				$( td ).clone().find( 'div.text' ).addClass( 'name' ).text( song.songName ).attr( 'title', song.songName ).end().appendTo( tr );
				$( td ).clone().find( 'div.text' ).addClass( 'artist' ).text( song.artistName ).attr( 'title', song.artistName ).end().appendTo( tr );
				$( td ).clone().find( 'div.text' ).addClass( 'album' ).text( song.albumName ).attr( 'title', song.albumName ).end().appendTo( tr );
				
				$( tr ).attr( 'id', 'song_' + song.songID ).appendTo( '#mplayersearchmodal table tbody' );
			}
		},
		Initialize: ,
		MyProfileOnLoad: function(){
			//to avoid code running without reason
			if( !$( '#mplayersearchmodal' ).length ){
				return false;
			}
			
			Profile.Player.Initialize();
			$( '#mplayersearchmodal .input input:first' ).attr( 'default', $( '#mplayersearchmodal .input input:first' ).val() )
				.focus( function(){
					if( $( this ).val() == $( this ).attr( 'default' ) ){
						$( this ).val( '' );
					}
				}).blur( function(){
					if( $( this ).val() == '' ){
						$( this ).val( $( this ).attr( 'default' ) );
					}
				}).keypress( function( e ){
					if( e.keyCode == 13 ){
						Profile.Player.SubmitSearch();
						return false;
					}
				}).siblings( '.search' ).click( function(){
					if( !$( this ).hasClass( 'loading' ) ){
						Profile.Player.SubmitSearch();
					}
					return false;
				}).mousedown( function(){
					$( this ).addClass( 'active' );
				}).mouseup( function(){
					$( this ).removeClass( 'active' );
				}).mouseout( function(){
					$( this ).removeClass( 'active' );
				});
			
			$( '#mplayersearchmodal table tbody tr' ).live( 'click', function(){
				Profile.Player.SelectSong( $( this ).attr( 'id' ).split( '_' )[ 1 ] );
			});
			$( '#mplayersearchmodal .list' ).mousedown( function(){ return false; });
			
			$( '#mplayersearchmodal' ).keypress( function( e ){
				if( e.keyCode == 27 ){
					$( this ).jqmHide();
				}
			}).find( '.toolbar .exit' ).click( function(){
				$( '#mplayersearchmodal' ).jqmHide();
			});
			//preloading images
			var loader = new Image( 15, 15 );
			loader.src = "http://static.zino.gr/phoenix/ajax-loader.gif";
			var loader2 = new Image( 15, 15 );
			loader2.src = "http://static.zino.gr/phoenix/search-button.png";
			$( window ).resize( Profile.Player.CorrectMidPosition );
		}
    }
*/
