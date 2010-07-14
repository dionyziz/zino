var ImageView = {
    Title: {
        Empty: 'Γράψε τίτλο για τη φωτογραφία',
        Rename: function( id, title ){
            $.post( 'index.php?resource=photo&method=update', {
                id: id,
                title: title
            });     
        },
        Init: function(){
            ImageView.Id = $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ];

            $( '.title input' ).mouseover( function(){
                if( !$( this ).hasClass( 'focus' ) ){
                    $( this ).addClass( 'hover' );
                    if( $( this ).hasClass( 'empty' ) ){
                        $( this ).val( '' );
                    }
                }
            }).mouseout( function(){
                $( this ).removeClass( 'hover' );
                if( !$( this ).hasClass( 'focus' ) && $( this ).hasClass( 'empty' ) ){
                    $( this ).val( ImageView.Title.Empty );
                }
            }).focus( function(){
                $( this ).removeClass( 'hover' ).removeClass( 'empty' ).addClass( 'focus' )[ 0 ].select();
                ImageView.Title.Title = $( this ).hasClass( 'empty' ) ? '' : $( this ).val();
            }).blur( function(){
                $( this ).removeClass( 'focus' );
                ImageView.Title.Title = $( this ).val();
                if( ImageView.Title.Title == '' ){
                    $( this ).addClass( 'empty' ).val( ImageView.Title.Empty );
                }
                else{
                    $( this ).removeClass( 'empty' );
                }
                ImageView.Title.Rename( ImageView.Id, ImageView.Title.Title );
            }).mouseup( function(){
                return false;
            }).keydown( function( event ){
                if( event.which == 13 ){
                    $( this ).blur();
                }
                if( event.which == 27 ){
                    $( this ).val( ImageView.Title.Title );
                    $( this ).blur();
                }
            });
        }
    },
    Init: function(){
        ImageView.Title.Init();
    }
};
