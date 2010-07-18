var PhotoView = {
    Title: {
        Empty: 'Γράψε τίτλο για τη φωτογραφία',
        Rename: function( id, title ){
            $.post( 'index.php?resource=photo&method=update', {
                id: id,
                title: title
            });     
        },
        Init: function(){
            if ( $( '.contentitem .details a.username' ).text() != User ) {
                return;
            }
            PhotoView.Title.Title = $( '.title input' ).val();
            if( PhotoView.Title.Title == '' ){
                $( '.title input' ).addClass( 'empty' ).val( PhotoView.Title.Empty );
            }
            PhotoView.Id = $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ];
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
            }).blur( function(){
                $( this ).removeClass( 'focus' );
                PhotoView.Title.Title = $( this ).val();
                if( PhotoView.Title.Title == '' ){
                    $( this ).addClass( 'empty' ).val( PhotoView.Title.Empty );
                }
                else{
                    $( this ).removeClass( 'empty' );
                }
                PhotoView.Title.Rename( PhotoView.Id, PhotoView.Title.Title );
            }).mouseup( function(){
                return false;
            }).keydown( function( event ){
                if( event.which == 13 ){
                    $( this ).blur();
                }
                if( event.which == 27 ){
                    $( this ).val( PhotoView.Title.Title );
                    $( this ).blur();
                }
            });
        }
    },
    Init: function(){
        PhotoView.Title.Init();
		ItemView.Init( 2 );
    }
};
