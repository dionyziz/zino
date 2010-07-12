var ImageView = {
    Title: {
        rename: function( id, title ){
            alert( 'title of image ' + id + ': ' + title );
        },
        init: function(){
            $( '.title span' ).mouseover( function(){
                $( this ).hide().siblings().show().addClass( 'hover' );
                if( $( this ).hasClass( 'empty' ) ){
                    $( this ).siblings().val( '' );
                }
                else{
                    $( this ).siblings().val ( $( this ).text() );
                }
            }).siblings().mouseout( function(){
                if( !$( this ).hasClass( 'focus' ) ){
                    $( this ).removeClass( 'hover' ).hide().siblings().show();
                }
            }).focus( function(){
                $( this ).removeClass( 'hover' ).addClass( 'focus' );
            }).blur( function(){
                $( this ).removeClass( 'focus' ).hide().siblings().show();
                ImageView.Title.rename( $( this ).closest( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ], $( this ).val() );
            }).keydown( function( event ){
                if( event.which == 13 ){
                    if( $( this ).val() != '' && $( this ).val() != $( this ).siblings().text() ){
                        $( this ).siblings().text( $( this ).val() );
                        ImageView.Title.rename( $( this ).closest( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ], $( this ).val() );
                    }
                    $( this ).blur();
                }
                if( event.which == 27 ){
                    $( this ).blur();
                }
            }); 
        }
    },
    init: function(){
        ImageView.Title.init();
    }
};
