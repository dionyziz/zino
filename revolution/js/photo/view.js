var ImageView = {
    Title: {
        Rename: function( id, title ){
            $.post( 'index.php?resource=photo&method=update', {
                id: id,
                title: title
            });     
        },
        Init: function(){
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
                $( this ).removeClass( 'hover' ).addClass( 'focus' )[ 0 ].select();
            }).blur( function(){
                $( this ).removeClass( 'focus' ).hide().siblings().show();
                if( $( this ).val() != $( this ).siblings().text() && !( $( this ).val() == '' && $( this ).siblings().hasClass( 'empty' ) ) ){
                    ImageView.Title.Rename( $( this ).closest( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ], $( this ).val() );
                    $( this ).siblings().text( $( this ).val() );
                    if( $( this ).val() == "" ){
                        $( this ).siblings().addClass( 'empty' ).text( 'Γράψε τίτλο για τη φωτογραφία' );
                    }
                    else{
                        $( this ).siblings().removeClass( 'empty' );
                    }
                }
            }).keydown( function( event ){
                if( event.which == 13 ){
                    $( this ).blur();
                }
                if( event.which == 27 ){
                    $( this ).val( $( this ).siblings().text() );
                    $( this ).blur();
                }
            }); 
        }
    },
    Init: function(){
        ImageView.Title.Init();
    }
};
