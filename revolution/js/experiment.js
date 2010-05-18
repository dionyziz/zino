$.ajaxSetup( {
    dataType: 'xml'
} );
_aXSLT.defaultStylesheet = 'global.xsl';

$( function() { $( '.time' ).each( function () {
    this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
    $( this ).addClass( 'processedtime' );
} ); } );

$( function() {
    $( 'ul.options li input' ).click( function () {
        var poll = $( this ).parents( 'ul' )[ 0 ];

        $.post( 'pollvote/create', {
            pollid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
            optionid: this.value
        }, function ( res ) {
            var options = $( res ).find( 'option' );
        } );
    } );
} );
