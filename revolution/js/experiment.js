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

        var vote = $.post( 'pollvote/create', {
            pollid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
            optionid: this.value
        } );
        
        axslt( vote, '//options', function () {
            $( poll ).empty().append( $( this ) );
        } );
    } );
} );
