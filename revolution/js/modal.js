( function($) {
    $.fn.modal = function( modalTrigger, config ) {
        var defconfig = {
            position: 'center',
            overlayClass: 'modaloverlay',
            noClose: false,
            noFrame: false,
            modal: true
        };
        config = $.extend( config, defconfig );
        //TODO: prependTo( 'body' );
        this.jqm( config );
        if ( !config.noClose ) {
            //close = document.createElement( 'span' );
            //$( close ).addClass( 'close' )
            /*.click( 
                function ( modalElement ) {
                    return function () {
                        modalElement.jqmHide();
                    }
                } ( this )
            );*/
            _close = document.createElement( 'span' );
            //close = jQuery.create( "span", { 'class': 'close' } );
            $( _close ).addClass( 'close' );
            this.append( _close );
            this.jqmAddClose( _close );
        }
        if ( config.position == 'center' ) {
            this.center();
        }
        if ( modalTrigger ) {
            if ( typeof modalTrigger == 'string' ) {
                modalTrigger = $( modalTrigger );
            }
            this.jqmAddTrigger( modalTrigger );
        }
        if ( !modalTrigger ) {
            this.jqmShow();
        }
        return this;
    };
} ) ( jQuery );
