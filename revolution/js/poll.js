var Poll = {
    NewOptions: 2,
    PreCreate: function() {
        axslt( false, 'call:poll.new', function() {
            $( '.col1, .col2, #notifications' ).remove();
            $( this ).appendTo( 'body' );
            $( '.newpoll' ).find( 'input.question' ).focus();
            $( '.newpoll' ).find( 'input.option' ).keydown( function() {
                Poll.OptionChange( this );
            } );
            $( '.newpoll form' ).submit( function() { return Poll.Create(); } );
            $( '.newpoll ul.toolbox a.button.big' ).click( function() { return Poll.Create(); } );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.option:eq(0)' ) );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.option:eq(1)' ) );
        } );
        return false;
    },
    Create: function() {
        var question = $( '.newpoll' ).find( 'input.question' ).val();
        var options = [];
        $( 'input.option' ).each( function() {
            if ( !$( this ).hasClass( 'blured' ) && $( this ).val() !== '' ) {
                options.push( $( this ).val() );
            }
        } );
        if ( options.length < 2 ) {
            return false;
        }
        if ( question === '' ) {
            return false;
        }
        $.post( 'poll/create', { 'question': question, 'options': options }, function( xml ) { 
            window.location.href = 'polls/' + $( xml ).find( 'poll' ).attr( 'id' );
        } );
        return false;
    },
    OptionChange: function( node ) {
        //This is the last, and every else is filled
        if ( $( node ).attr( 'id' ).split( '_' )[1] == Poll.NewOptions ) {
            for ( var i = 1; i < Poll.NewOptions; ++i ) {
                if ( $( '#newoption_' + i ).val() === '' || $( '#newoption_' + i ).hasClass( 'blured' ) ) {
                    return true;
                }
            }
            var newoption = $( '<li><input /></li>' )
                .find( 'input' )
                .attr( 'id', 'newoption_' + ( ++Poll.NewOptions ) )
                .attr( 'class', 'option' )
                .keydown( function() {
                    Poll.OptionChange( this );
                } ).end();
            $( '.newpoll ul.options' ).append( newoption );
            Kamibu.ClickableTextbox( newoption.find( 'input' ), 'Eπιπλέον επιλογή;', 'black', 'grey' );
        }
    },
    Init: function(){
        ItemView.Init( Type.Poll );
        $( 'ul.options li input' ).click( function () {
            var poll = $( this ).parents( 'ul' )[ 0 ];

            var vote = $.post( 'pollvote/create', {
                pollid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
                optionid: this.value
            } );
            
            axslt( vote, '//options', function () {
                $( poll ).empty().append( $( this ) );
            } );
        } );
        $( '#deletebutton' ).click( function(){
            if ( confirm( 'Θέλεις να διαγράψεις αυτήν τη δημοσκόπιση;' ) ) {
                Poll.Remove( $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ] );
            }
        });
    },
    Remove: function( id ) {
        $.post( 'index.php?resource=poll&method=delete', {
            id: id
        }, function(){
            window.location = 'polls/' + User;
        });     
    }
};
