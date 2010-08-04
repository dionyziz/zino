var News = {
    Prepare: function( collection ) {
        $( collection ).click( 
            function() { 
                var id = $( this ).attr( 'id' ).split( '_' );
                return News.Preview.call( this, id[1], id[0] );
            } );
    },
    Preview: function( itemid, type ) {
        if ( $( this ).hasClass( 'previewing' ) ) {
            return true;
        }
        $( this ).addClass( 'previewing' ).siblings().removeClass( 'previewing' );
        var infotext = $( '<span />' )
            .text( 'Παρακαλώ περιμένετε' )
            .addClass( 'infotext' )
            .hide();
            
        setTimeout( ( function( infotext ) {
                return function() {
                    if ( infotext ) {
                        infotext.fadeIn( 1000 );
                    }
                };
            } )( infotext ), 500 );
        $( '#preview .content' ).empty().append( infotext );
        infotext.center();
        
        var data = $.get( type + 's/' + itemid, { 'verbose': 0 } );
        axslt( data, '/social/' + type, function() {
            $( '#preview .content' ).empty().append( $( this ).filter( '.contentitem' ) );
            if ( type == 'poll' ) {
                $( '#preview .content ul.options li input' ).click( function () {
                    var poll = $( this ).parents( 'ul' )[ 0 ];

                    var vote = $.post( 'pollvote/create', {
                        pollid: $( '#preview .content .contentitem' )[ 0 ].id.split( '_' )[ 1 ],
                        optionid: this.value
                    } );
                    
                    axslt( vote, '//options', function () {
                        $( poll ).empty().append( $( this ) );
                    } );
                } );
            }
        } );
        return false;
    },
    Init: function() {
        $( '#preview .infotext' ).center( { horizontal: false } );
        News.Prepare( $( '.stream ul li' ) );
        $( 'a.newjournal' ).click( function() {
            return Journal.PreCreate();
        } );
        $( 'a.newpoll' ).click( function() {
            return Poll.PreCreate();
        } );
    }
};
