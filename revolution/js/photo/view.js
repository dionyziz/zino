var PhotoView = {
    Title: {
        Empty: 'Γράψε τίτλο για τη φωτογραφία',
        Rename: function( id, title ){
            $.post( 'index.php?resource=photo&method=update', {
                id: id,
                title: title
            });     
        },
        CorrectWidth: function(){
            $( '.title span' ).text( $( '.title input' ).val() );
            var width = $( '.title span' ).width();
            if( width < 300 ){
                $( '.title input' ).width( 300 );
            }
            else{
                $( '.title input' ).width( width + 30 );
            }
        },
        Init: function() {
            if( typeof( User ) != 'string' || $( '.contentitem .details a.username' ).text() != User ){
                return;
            }
            PhotoView.Title.Title = $( '.title input' ).val();
            if ( PhotoView.Title.Title === '' ){
                $( '.title input' ).addClass( 'empty' ).val( PhotoView.Title.Empty );
            }
            PhotoView.Id = $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ];
            PhotoView.Title.CorrectWidth();
            $( '.title input' ).mouseover( function(){
                if( !$( this ).hasClass( 'focus' ) ){
                    $( this ).addClass( 'hover' );
                }
            }).mouseout( function(){
                $( this ).removeClass( 'hover' );
            }).focus( function(){
                if( $( this ).hasClass( 'empty' ) ){
                    PhotoView.Title.Title = '';
                    $( this ).val( '' );
                }
                else{
                    PhotoView.Title.Title = $( this ).val();
                }
                $( this ).removeClass( 'hover' ).removeClass( 'empty' ).addClass( 'focus' )[ 0 ].select();
                PhotoView.Title.Selected = false;
            }).blur( function(){
                $( this ).removeClass( 'focus' );
                PhotoView.Title.Title = $( this ).val();
                if( PhotoView.Title.Title === '' ) {
                    $( this ).addClass( 'empty' ).val( PhotoView.Title.Empty );
                }
                else{
                    $( this ).removeClass( 'empty' );
                }
                PhotoView.Title.Rename( PhotoView.Id, PhotoView.Title.Title );
            }).mouseup( function(){
                if( PhotoView.Title.Selected ){
                    return true;
                }
                PhotoView.Title.Selected = true;
                return false;
            }).keyup( function( event ){
                event.stopImmediatePropagation();
                if( event.which == 13 ){ // esc
                    $( this ).blur();
                }
                if( event.which == 27 ){ // enter
                    $( this ).val( PhotoView.Title.Title );
                    $( this ).blur();
                }
                PhotoView.Title.CorrectWidth();
            });
        }
    },
    Remove: { 
        Remove: function( id ) {
            $.post( 'index.php?resource=photo&method=delete', {
                id: id
            }, function(){
                Async.Go( 'photos/' + User );
            });     
        },
        Init: function(){
            if( typeof( User ) != 'string' || $( '.contentitem .details a.username' ).text() != User ){
                return;
            }
            $( '#deletebutton' ).click( function(){
                if ( confirm( 'Θέλεις να διαγράψεις την εικόνα;' ) ) {
                    PhotoView.Remove.Remove( $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ] );
                }
            });
        }
    },
    Unload: function(){
        Comment.Unload();
    },
    Init: function(){
        PhotoView.Title.Init();
        PhotoView.Remove.Init();
		ItemView.Init( 2 );
        PhotoView.Tag.Init();
        $( document ).bind( 'keydown', { combi: 'left', disableInInput: true }, PhotoView.LoadPrevious );
        $( document ).bind( 'keydown', { combi: 'right', disableInInput: true }, PhotoView.LoadNext );
        $( '.image' ).click( function(){ // Photo tagging guy, check that ( --ted )
            PhotoView.LoadNext();
        });
    },
    Tag: {
        index: [],
        zindex: {},
        Friend: {
            list: {},
            Load: function(){
                var friendlist = $( 
                    '<div class="friendlist">' + 
                        '<label for="friendfield">Ποιος είναι αυτός;</label>' +
                        '<input type="text" id="friendfield" />' + 
                        '<ul><li class="friend_loading"><img src="http://static.zino.gr/revolution/loading.gif" /></li></ul>' + 
                        '<button class="save">Αποθήκευση</button>' + 
                        '<div class="controls">ή <a href="" class="cancel">ακύρωση</a></div>' + 
                    '</div>' );
                $( friendlist ).hide().appendTo( '.image' );
                $.get( 'friendsmutual/' + User, function( data ){
                    $( '.friendlist ul li.friend_loading' ).hide();
                    if( !PhotoView.Tag.Friend.list[ $( data ).find( 'friends' ).attr( 'id' ) ] ){
                        var li = $( '<li id="friend_' + $( data ).find( 'friends' ).attr( 'id' ) + '" class="me">' +
                                        '<a href="">εγώ<span style="display:none;">' + User + '</span></a>' + 
                                    '</li>' );
                        $( '.friendlist ul' ).append( li );
                    }
                    $( data ).find( 'friend' ).each( function(){
                        if( PhotoView.Tag.Friend.list[ $( this ).attr( 'id' ) ] === true ){
                            return;
                        }
                        var li = $( '<li id="friend_' + $( this ).attr( 'id' ) + '">' + 
                                        '<a href="">' + $( this ).children( 'name' ).text() + '</a>' +
                                    '</li>' );
                        $( '.friendlist ul' ).append( li );
                    });
                    $( '.friendlist a' ).click( function(){
                        var id = $( this ).parent().attr( 'id' ).split( '_' )[ 1 ];
                        PhotoView.Tag.Save( id );
                        return false;
                    });
                }, 'xml' );
            },
            Show: function(){
                var top = $( '.newtag' ).top();
                if( top > $( '.image' ).height() - 300 ){
                    var top = $( '.image' ).height() - 300;
                }
                $( '.friendlist' ).show().css({
                    top: top,
                    left: $( '.newtag' ).left() + $( '.newtag' ).width() + 20
                }).find( 'input' ).val( '' ).trigger( 'keyup' ).focus();
            },
            Type: function( text ){
                if( text.length ){
                    $( '.friendlist ul li:not(.friend_loading)' ).hide().removeClass( 'selected' )
                        .filter( ':containsCI(' + text + ')' ).show().end()
                        .filter( ':visible:first' ).addClass( 'selected' );
                }
                else{
                    $( '.friendlist ul li:not(.friend_loading)' ).removeClass( 'selected' ).show();
                }
            },
            Unload: function(){
                PhotoView.Tag.Friend.Initialized = false;
                PhotoView.Tag.Friend.list = {};
            },
            Init: function(){
                PhotoView.Tag.Friend.initialized = true;
                $( '.image .tag .name' ).each( function(){
                    PhotoView.Tag.Friend.list[ $( this ).attr( 'id' ).split( '_' )[ 1 ] ] = true;
                });
                PhotoView.Tag.Friend.Load();
                $( '.friendlist input' ).keyup( function( e ){
                    if( e.which == 27 ){ // esc
                        PhotoView.Tag.StopTagging();
                    }
                    if( e.which == 13 ){ // enter
                        PhotoView.Tag.Save();
                    }
                    PhotoView.Tag.Friend.Type( $( this ).val() );

                });
                $( '.friendlist .cancel' ).click( function(){
                    PhotoView.Tag.StopTagging();
                    return false;
                });
                $( '.friendlist .save' ).click( function(){
                    PhotoView.Tag.Save();
                    return false;
                });
            }
        },
        Save: function( id ){
            if( typeof( id ) == 'undefined' ){
                if( $( '.friendlist ul li.selected' ).length == 0 ){
                    return false;
                }
                id = $( '.friendlist ul li.selected' ).attr( 'id' ).split( '_' )[ 1 ];
            }
            var tosend = {
                personid: id,
                photoid: $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ],
                top: $( '.newtag' ).top(),
                left: $( '.newtag' ).left(),
                width: $( '.newtag' ).width(),
                height: $( '.newtag' ).height()
            };
            $( '.friendlist' ).hide();
            $( '.newtag' ).remove();
            
            var person = $( '#friend_' + id ).text();
            if( $( '#friend_' + id ).hasClass( 'me' ) ){
                person = $( '#friend_' + id + ' span' ).text();
            }
            $( '#friend_' + id ).remove();
            $.post( 'imagetag/create', tosend, function( data ){
                var tag = $( '<div class="tag">' + 
                                '<div class="namecontainer">' + 
                                    '<span class="name"></span>' + 
                                '</div>' +
                                '<div class="imagecontainer">' + 
                                    '<img />' + 
                                '</div>' + 
                            '</div>' );
                if( $( data ).find( 'top' ).text() > $( '.image' ).height() - 30 - $( data ).find( 'height' ).text() ){
                    $( tag ).children( '.namecontainer' ).addClass( 'top' );
                }
                if( $( data ).find( 'width' ).text() > 200 ){
                    $( tag ).children( '.namecontainer' ).removeClass( 'top' ).addClass( 'inside' );
                }
                tag.attr( 'id', 'tag_' + $( data ).find( 'imagetag' ).attr( 'id' ) )
                    .css({
                        left: parseInt( $( data ).find( 'left' ).text() ),
                        top: parseInt( $( data ).find( 'top' ).text() ),
                        width: parseInt( $( data ).find( 'width' ).text() ),
                        height: parseInt( $( data ).find( 'height' ).text() )
                    })
                    .find( '.namecontainer .name' ).text( person )
                        .attr( 'id', 'user_' + $( data ).find( 'person' ).attr( 'id' ) ).end()
                    .find( 'img' ).attr( 'src', $( '.image .maincontent' ).attr( 'src' ) )
                        .css({
                            top: $( data ).find( 'top' ).text() * ( -1 ),
                            left: $( data ).find( 'left' ).text() * ( -1 )
                        });
                tag.appendTo( '.image' ).hide();
                PhotoView.Tag.StopTagging();
                $( '#tag_' + $( data ).find( 'imagetag' ).attr( 'id' ) + ' .imagecontainer' ).mouseover();
            });
        },
        Cancel: function(){
            $( '.friendlist' ).hide();
            $( '.newtag' ).remove();
        },
        Down: function( pos ){
            PhotoView.Tag.Cancel();
            PhotoView.Tag.mousedown = true;
            PhotoView.Tag.downpos = pos;
            var newtag = $( '<div class="newtag"></div>' );
            newtag.css( pos ).appendTo( '.image' );
        },
        Move: function( pos ){
            var down = PhotoView.Tag.downpos;
            var height = $( '.newtag' ).height();
            var width = $( '.newtag' ).width();
            if( pos.top < down.top ){
                $( '.newtag' ).height( down.top - pos.top ).css( 'top', pos.top );
            }
            else{
                $( '.newtag' ).height( pos.top - down.top ).css( 'top', down.top );
            }
            if( pos.left < down.left ){
                $( '.newtag' ).width( down.left - pos.left ).css( 'left', pos.left );
            }
            else{
                $( '.newtag' ).width( pos.left - down.left ).css( 'left', down.left );
            }
        },
        Up: function(){
            PhotoView.Tag.mousedown = false;
            
            if( $( '.newtag' ).height() < 100 ){
                $( '.newtag' ).css({
                    top: $( '.newtag' ).top() - ( 100 - $( '.newtag' ).height() ) / 2,
                    height: 100
                });
            }
            if( $( '.newtag' ).width() < 100 ){
                $( '.newtag' ).css({
                    left: $( '.newtag' ).left() - ( 100 - $( '.newtag' ).width() ) / 2,
                    width: 100
                });
            }
            $( '.newtag' ).css( PhotoView.Tag.CorrectPos({
                top: $( '.newtag' ).top(),
                left: $( '.newtag' ).left(),
                width: $( '.newtag' ).width(),
                height: $( '.newtag' ).height()
            }) );
            PhotoView.Tag.Friend.Show();
        },
        CorrectPos: function( pos ){
            if( pos.left < 0 ){
                pos.left = 0;
            }
            if( pos.left + pos.width > $( '.image' ).width() - 4 ){
                pos.left = $( '.image' ).width() - 4 - pos.width;
            }
            if( pos.top < 0 ){
                pos.top = 0;
            }
            if( pos.top + pos.height > $( '.image' ).height() - 4 ){
                pos.top = $( '.image' ).height() - 7 - pos.height;
            }
            return pos;
        },
        StartTagging: function(){
            if( PhotoView.Tag.Friend.initialized !== true ){
                PhotoView.Tag.Friend.Init();
            }
            PhotoView.Tag.running = true;
            $( '.image .tag' ).hide();
            $( '.image' ).css( 'cursor', 'crosshair' );
            $( '#tagbutton' ).addClass( 'selected' );
            $( '.image img.maincontent' ).mousedown( function( e ){
                var imagepos = {
                    top: $( '.image' ).offset().top,
                    left: $( '.image' ).offset().left
                };
                PhotoView.Tag.Down({
                    top: e.pageY - imagepos.top,
                    left: e.pageX - imagepos.left
                });
                return false;
            });
            $( '.newtag' ).live( 'mousedown', function( e ){
                var imagepos = {
                    top: $( '.image' ).offset().top,
                    left: $( '.image' ).offset().left
                };
                var pos = {
                    top:  e.pageY - imagepos.top,
                    left: e.pageX - imagepos.left
                };
                PhotoView.Tag.Cancel();
                PhotoView.Tag.Down( pos );
                return false;
            });

            $( window ).mousemove( function( e ){
                if( PhotoView.Tag.mousedown !== true ){
                    return false;
                }
                var imagepos = {
                    top: $( '.image' ).offset().top,
                    left: $( '.image' ).offset().left,
                    width: $( '.image' ).width(),
                    height: $( '.image' ).height()
                };
                var pos = {
                    left: e.pageX - imagepos.left,
                    top:  e.pageY - imagepos.top
                };
                if( pos.left <= 0 ){
                    pos.left = 0;
                }
                if( pos.left >= imagepos.width - 4 ){ //4 px border
                    pos.left = imagepos.width - 4;
                }
                if( pos.top <= 0 ){
                    pos.top = 0;
                }
                if( pos.top >= imagepos.height - 7 ){ //4px border, +3 unknown
                    pos.top = imagepos.height - 7;
                }
                PhotoView.Tag.Move( pos );
            }).mouseup( function( e ){
                if( PhotoView.Tag.mousedown !== true ){
                    return false;
                }
                PhotoView.Tag.Up();
                return false;
            }).keyup( function( e ){
                if( e.which == 27 ){ //esc
                    PhotoView.Tag.StopTagging();
                }
            });
        },
        StopTagging: function(){
            $( window ).unbind( 'mousemove mouseup keyup' );
            $( '.maincontent' ).unbind( 'mousedown' );
            $( '.image .tag' ).show();
            $( '.image' ).css( 'cursor', 'default' );
            PhotoView.Tag.Cancel();
            $( '#tagbutton' ).removeClass( 'selected' );
            PhotoView.Tag.running = false;
        },
        Init: function(){
            $( '.image .tag .imagecontainer' ).live( 'mouseover', function(){
                $( this )
                    .siblings( '.namecontainer' ).show()
                    .parent().fadeTo( 0, 1 )
                        .siblings( '.tag' ).fadeTo( 0, 0 );
                $( '.image img.maincontent' ).stop( 1 ).fadeTo( 100, 0.4 );
            }).live( 'mouseout', function(){
                $( this ).siblings( '.namecontainer' ).hide();
                $( '.image img.maincontent' ).stop( 1 ).fadeTo( 100, 1, function( item ){
                    return function(){
                        $( item ).parent().show().fadeTo( 0, 0 );
                    }
                }( this ) );
            });
            $( '.image .tag .namecontainer.inside' ).live( 'mouseover', function(){
                $( this ).siblings( '.imagecontainer' ).mouseover();
            }).live( 'mouseout', function(){
                $( this ).siblings( '.imagecontainer' ).mouseout();
            });
            $( '#tagbutton' ).click( function(){
                if( PhotoView.Tag.running ){
                    PhotoView.Tag.StopTagging();
                    return false;
                }
                PhotoView.Tag.StartTagging();
                return false;
            });
            $( '.image img.maincontent' ).click( function( e ){
                if( PhotoView.Tag.running ){
                    e.stopImmediatePropagation();
                }
            });
            /* attempt to fix tag overlay failed :(
            $( '.tag' ).each( function(){
                PhotoView.Tag.index.push({
                    id: $( this ).attr( 'id' ),
                    area: parseInt( $( this ).css( 'width' ) ) * parseInt( $( this ).css( 'height' ) )
                });
            });
            PhotoView.Tag.index.sort( function( a, b ){
                return b.area - a.area;
            });
            for( var i in PhotoView.Tag.index ){
                $( '#' + PhotoView.Tag.index[ i ].id ).css( 'zIndex', i - 0 + 15 );
            }
            */
        }

    },
    LoadNext: function( evt ) {
        if( PhotoView.Tag.running ){
            return false;
        }
        var $next = $( '.navigation .nextid' );
        if ( $next.length ) {
            $( '.breadcrumb .nav.next img' ).animate( {
                opacity: 1,
                left: '6px'
            }, 100 );
            Kamibu.Go( '#photos/' + $next.text() );
        }
        return false;
    },
    LoadPrevious: function() {
        if( PhotoView.Tag.running ){
            return false;
        }
        var $previous = $( '.navigation .previousid' );
        if ( $previous.length ) {
            $( '.breadcrumb .nav.prev img' ).animate( {
                opacity: 1,
                left: '-6px'
            }, 100 );
            Kamibu.Go( '#photos/' + $previous.text() );
        }
        return false;
    }
};
