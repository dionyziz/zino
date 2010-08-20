var Journal = {
    Init: function(){
        ItemView.Init( Type.Journal );
        $( '#deletebutton' ).click( function(){
            if ( confirm( 'Θέλεις να διαγράψεις αυτό το ημερολόγιο;' ) ) {
                Journal.Remove( $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ] );
            }
        });
        $( '.journaleditmenu a.edit' ).click( function() { return Journal.Edit(); } );
    },
    Edit: function() {
        //var $wysiwyg = $( '<div class="wysiwyg" />' );
        var $wysiwyg = $( '.document' ).clone();
        $wysiwyg.addClass( 'wysiwyg' );
        $( '.journaleditmenu' ).find( 'a' ).show().find( '.edit' ).hide();
        
        $( '.document' ).replaceWith( $( '<form id="editjournal"/>' ).append( $wysiwyg ) );
        WYSIWYG.Create( $wysiwyg.get( 0 ), 'text', {}, 3 );
        return false;
    },
    Remove: function( id ) {
        $.post( 'journal/delete', {
            id: id
        }, function(){
            window.location = 'journals/' + User;
        });     
    },
    PreCreate: function() {
        axslt( false, 'call:journal.new', function() {
            $screen = $( this ).filter( 'div' );
            $( '#content' ).empty().append( $screen );
            Notifications.Hide();
            $screen.find( '.toolbox .button.big' ).click( function() {
                var title = $screen.find( '.title' ).val();
                var text = $screen.find( '.edit textarea' ).val();
                
                if ( title.length === 0 ) {
                    alert( 'Όρισε έναν τίτλο!' );
                    return false;
                }
                if ( text.length === 0 ) {
                    alert( 'Γράψε κάτι στο ημερολόγιο!' );
                    return false;
                }
                $.post( 'journal/create', { 'title': title, 'text': text }, function( xml ) { 
                    Kamibu.Go( 'journals/' + $( xml ).find( 'journal' ).attr( 'id' ) );
                } );
                return false;
            } );
            $screen.find( '.toolbox .linkbutton' ).click( function() {
                Kamibu.Go( 'news' );
                return false;
            } );
        } );
        return false;
    }
};
