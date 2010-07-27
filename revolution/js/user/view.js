var Profile = {
    CurrentValues: {},
    Init: function () {
        if ( $( '#accountmenu' ).length ) {
            $( '#accountmenu a:eq(0)' ).click( function () {
                axslt( false, 'call:user.modal.settings', function() {
                    $( this ).filter( 'div' ).prependTo( 'body' ).modal();
                } );
                return false;
            } );
            $( '#accountmenu a:eq(1)' ).click( function () {
                document.body.style.cursor = 'pointer';
                $.post( 'session/delete', {}, function () {
                    window.location.href = 'login';
                } );
                return false;
            } );
            
            Profile.PrepareInlineEditables();
        }
        if ( $( '#friendship' ).length ) {
            $( '#friendship' )[ 0 ].getElementsByTagName( 'a' )[ 0 ].onclick = function () {
                $.post( this.action, {
                    friendid: this.getElementsByTagName( 'input' )[ 0 ].value
                }, function () {
                    this.innerHTML = "OK";
                } );
                return false;
            };
        }
        Comment.Init();
    },
    PrepareInlineEditables: function() {
        Profile.PopulateEditables();
        $( 'li.aboutme > span' ).addClass( 'editable' ).click( function() {
            axslt( false, 'call:user.modal.aboutme', function() {
                var $modal = $( this ).filter( 'div' );
                $modal.prependTo( 'body' ).modal();
                $modal.find( 'textarea.aboutme' ).val( $( 'li.aboutme > span' ).text() ).focus();
                $modal.find( 'a.save' ).click( function() {
                    var text = $modal.find( 'textarea.aboutme' ).val();
                    $( 'li.aboutme > span' ).text( text );
                    $.post( 'user/update', { 'aboutme': text } );
                    $modal.jqmHide();
                    return false;
                } );
            } );
            return false;
        } );
        $( '.asl .location span' ).addClass( 'editable' ).click( function() {
            //TODO: don't re-open the modal;
            axslt( false, 'call:user.modal.location', function() {
                $modal = $( this ).filter( 'div' );
                $modal.prependTo( 'body' ).modal();
                var location_id = $( '.asl .location span' ).attr( 'id' ).split( '_' )[1];
                var location_text = $( '.asl .location span' ).text();
                var $select = $modal.find( 'select.location' );
                $( '<option value=' + location_id + '>' + location_text + '</option>' ).appendTo( $select );
                //axslt( 'places', 'call:
                $select.val( location_id );
            } );
            return false;
        } );
    },
    UpdatableFields: { 'gender': '.asl .gender',
                   'smoker': 'li.smoker > span',
                   'drinker': 'li.drinker > span',
                   'relationship': 'li.relationship > span',
                   'politics': 'li.politics > span',
                   'religion': 'li.religion > span',
                   'sexualorientation': 'li.sexualorientation > span',
                   'eyecolor': 'li.eyecolor > span',
                   'haircolor': 'li.haircolor > span',
                 },
    PopulateEditables: function() {
        UserDetails.Init();
        var field;
        for ( field in Profile.UpdatableFields ) {            
            Profile.MakeEditable( $( Profile.UpdatableFields[ field ] ), field );
        }
    },
    MakeEditable: function( element, field ) {
        element.addClass( 'editable' );
        switch( field ) {
            default:
                var oldselect = $( element ).find( 'select.dropdown' );
                Profile.CurrentValues[ field ] = oldselect.val() || '-';
                oldselect.empty();
                var select = $( '<select />' ).addClass( 'dropdown' );
                
                oldselect.replaceWith( select );
                Profile.PopulateSelect( element, field );
                
                $( select ).change( function() {
                    Profile.CurrentValues[ field ] = $( this ).val();
                    var span = $( this ).siblings().filter( 'span' );
                    Profile.UpdateField( span, field );
                    
                    if ( field == 'gender' ) {
                        var ifield;
                        for ( ifield in Profile.UpdatableFields ) { 
                            if ( ifield != 'gender' ) {
                                Profile.PopulateSelect( $( Profile.UpdatableFields[ ifield ] ), ifield );
                            }
                        }
                    }
                    
                    $.post( 'user/update', { field: $( this ).val() } );
                } );
                select.appendTo( element ).css( 'display', 'block' );
        }
    },
    PopulateSelect: function( element, field ) {
        var map = UserDetails.GetMap( field, Profile.CurrentValues[ 'gender' ] );
        var nbsp = String.fromCharCode( 160 );
        var select = $( element ).find( 'select' );
        select.empty();
        for ( key in map ) {
            $( '<option />' ).attr( 'value', key ).text( nbsp + map[ key ] + nbsp).appendTo( select );
        }
        select.val( Profile.CurrentValues[ field ] );
        
        Profile.UpdateField( $( element ).find( 'span' ), field );
    },
    UpdateField: function( span, field ) {
        var text = UserDetails.GetString( field, Profile.CurrentValues[ field ], Profile.CurrentValues[ 'gender' ] );
        $( span ).removeClass( 'notshown' ).text( text );
        if ( Profile.CurrentValues[ field ] == '-' ) {
            span.addClass( 'notshown' );
        }
    }
}
