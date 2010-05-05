$.ajaxSetup( {
    dataType: 'xml'
} );
_aXSLT.defaultStylesheet = 'global.xsl';

$( '.time' ).each( function () {
    this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
    $( this ).addClass( 'processedtime' );
} );

$( function() {
    $( 'ul.options li input' ).click( function () {
        $.post( 'pollvote/create', {
            pollid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
            optionid: this.value
        } );
    } );
} );
