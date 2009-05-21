/*
  DEVELOPER: Chorvus
*/

function upperOffset( jqelement ) {
    //This function returns the very first visible pixel's offset relative to the parent element (accepts a jquery element object)
    if (!jqelement) {
        return false;
    }
    return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + 1;
}
function lowerOffset( jqelement ) {
    //This function returns the very last visible pixel's offset relative to the parent element (accepts a jquery element object)
    if (!jqelement) {
        return false;
    }
    return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + jqelement.outerHeight() - 1;
}
function isScrollingElementVisible( eltVisible, eltScrolling ) {
    //Returns true only if the WHOLE element is visible (accepts a DOM element and the scrolling container
    //WARNING: Usable only for vertical scrolling
    if ( !eltVisible || !eltScrolling ) {
        return false;
    }
    var scrollPos = eltScrolling.scrollTop;
    var scrollHeight = $( eltScrolling ).height();
    return ( upperOffset( $(eltVisible) ) > scrollPos ) && ( lowerOffset( $(eltVisible) ) < scrollPos + scrollHeight );
}

var PhotoManager = {
    fade: false,
    draginfo: false,
    perpage: 20,
    
    checkEnabledAlbumbs: function() {
        jQuery.each( $("div.albumlist li"), function() {
            if (PhotoManager.isScrollingElementVisible(this,$("div.albumlist").get()[0])) {
                if (  $(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'enable' ); }
            } else {
                if ( !$(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'disable' ); }
            }
        });
        if ( !$( "div.albumlist li.selected" ).hasClass( "ui-droppable-disabled" ) ) { $( "div.albumlist li.selected" ).droppable( 'disable' ); }
    }
    ,
    OnLoad: function () {
        //(Syre & Metefere) Bubble
        $( $("div.photo img") ).hover(
            //MouseEnter Event
            function () {
                draginfo = $("div", $(this).parent());
                if ( !$("img", draginfo.parent()).hasClass( "ui-draggable-dragging" )) {
                    fade = setTimeout( function() {
                        if (!draginfo.parent().hasClass( "ui-draggable-dragging" )) {
                            draginfo.fadeIn( "fast" );
                        }
                        fade=false;
                    }, 600 );
                }
            }
            ,
            //MouseOut Event
            function () {
                if (fade) {
                    clearTimeout( fade ); 
                } else {
                    draginfo.fadeOut( "fast" );
                }
            }
        );
        //Album selection
        $( "div.albumlist ul li" ).click( function () {
            $( this ).addClass( "selected" ).siblings().removeClass( "selected" );
            checkEnabledAlbumbs();
        } );
    }
}