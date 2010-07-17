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
            ImageView.Id = $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ];
            $( '.title input' ).mouseover( function(){
                if( !$( this ).hasClass( 'focus' ) ){
                    $( this ).addClass( 'hover' );
                }
            }).mouseout( function(){
                $( this ).removeClass( 'hover' );
            }).focus( function(){
                if( $( this ).hasClass( 'empty' ) ){
                    ImageView.Title.Title = '';
                    $( this ).val( '' );
                }
                else{
                    ImageView.Title.Title = $( this ).val();
                }
                $( this ).removeClass( 'hover' ).removeClass( 'empty' ).addClass( 'focus' )[ 0 ].select();
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
		ItemView.Init( 2 );
    }
};
