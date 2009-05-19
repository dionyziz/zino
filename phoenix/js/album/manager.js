/*todo:
moar obvious move to album
fix paginat0r
moar obvious drag over album
*/
var fade;
var draginfo;
function changePage() {
}
function checkPages() {
}

function upperOffset( jqelement ) { //this function returns the very first visible pixel's offset relative to the parent element
	if (!jqelement) {
		return false;
	}
	return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + 1;
}
function lowerOffset( jqelement ) { //this function returns the very last visible pixel's offset relative to the parent element
	if (!jqelement) {
		return false;
	}
	return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + jqelement.outerHeight() - 1;
}
function isScrollingElementVisible( eltVisible, eltScrolling ) {
	if ( !eltVisible || !eltScrolling ) {
		return false;
	}
	var scrollPos = eltScrolling.scrollTop;
	var scrollHeight = $( eltScrolling ).height();
	return ( upperOffset( $(eltVisible) ) > scrollPos ) && ( lowerOffset( $(eltVisible) ) < scrollPos + scrollHeight );
}
$(document).ready( function() {
	//event declarations
	//hover effect:
	var page=1;
	var perpage=20;
	$( $("div.photo img") ).hover(
		//mouseenter
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
		},
		//mouseout
		function () {
			if (fade) {
				clearTimeout( fade ); 
			} else {
				draginfo.fadeOut( "fast" );
			}
		}
	);
	$("div.photos > div#pages").pagination($("ul.photolist li").length,
	{	items_per_page: perpage,
		//callback: ,
		num_edge_entries: 2,
		num_display_entries: 8,
		callback: function (page_id, panel) { changePaginationPage( page_id ); }
	});
	function changePaginationPage( pageindex ) {
		//$("ul.photolist li, ul.photolist").stop(true, false);
		$("ul.photolist").fadeOut( 400, function () {
			$("ul.photolist li").hide();
			$("ul.photolist li").slice(perpage*(pageindex), perpage*(pageindex+1)).show();
			$("ul.photolist").fadeIn( 400 );
		} );
	}
	$("div.photos > div#pages").pagination.selectPage(0);	
	function updatePaginationPage() {
		$("div.photos > div").pagination.updatePages($("ul.photolist li").length);
		var pageindex = $("div.photos > div#pages").pagination.selectedPage();
		$("ul.photolist li:invisible").slice(perpage*(pageindex), perpage*(pageindex+1)).fadeIn(500);
	}
	//album selection routine
	$( "div.albumlist ul li" ).click( function () {
		$( "div.albumlist ul li" ).removeClass( "selected" ).droppable( "enable" );
		$( this ).addClass( "selected" );
		checkEnabledAlbumbs();
	});
	$( 'div.photo img' ).draggable( { 
		//start: function ( event, ui ) { $("img", this).addClass( "dragging" ); },
		stop: function ( event, ui ) { $("body").css( "cursor", "normal" ); }, //avoid  a common jquery glitch
		handle : 'div.photo > img',
		helper : 'original',
		revert : 'invalid',
		cursor : 'move',
		zIndex : 500,
		scroll: false,
		//opacity: 0.8
		cursorAt: { cursor: 'move', bottom: 50, left: 50 }
	} );
	//fade out the helper (or cancel the hover timer) when the drag starts
	$( "div.photo" ).bind( "dragstart", function(event, ui) {
		if (fade) {
			clearTimeout( fade ); 
		} else {
			draginfo.hide(200);
		}
	});
	//rendering the albums droppable
	$( "div.albumlist li" ).droppable({
		//hoverClass: 'ui-state-active',	
		accept: ".photo > img",
		tolerance: "pointer",
		areabound: $("div.albumlist"),
		drop: function(event, ui) {
			$(this).removeClass( "dropover");
			ui.draggable.parent().parent().fadeOut( "fast" ).animate( { width: "0" },
				function() {
					$(this).remove(); 
					updatePaginationPage();
				}) //animate photo shrink and remove
		},
		over: function(event, ui) {
			if ( !$(this).hasClass( "selected" ) ) {
				$(this).addClass("dropover");
			}
		},
		out: function(event, ui) {
			if ( $(this).hasClass( "dropover" ) ) {
				$(this).removeClass( "dropover");
			}
		}
	});
	function checkEnabledAlbumbs() {
		jQuery.each( $("div.albumlist li"), function() {
			if (isScrollingElementVisible(this,$("div.albumlist").get()[0])) {
				if (  $(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'enable' ); }
			} else {
				if ( !$(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'disable' ); }
			}
		});
		//if ( isScrollingElementVisible( $( "div.albumlist li.selected" ), $("div.albumlist").get()[0] ) ) { $(this).droppable( 'disable' ); }
		if ( !$( "div.albumlist li.selected" ).hasClass( "ui-droppable-disabled" ) ) { $( "div.albumlist li.selected" ).droppable( 'disable' ); }
	}
	checkEnabledAlbumbs();
	$("div.albumlist").scroll( function() { checkEnabledAlbumbs(); } );
});