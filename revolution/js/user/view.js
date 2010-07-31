var Profile = {
    CurrentValues: {},
    Init: function () {
        if ( $( '#accountmenu' ).length ) {
            $( '#accountmenu a:eq(0)' ).click( function () {
                axslt( false, 'call:user.modal.settings', function() {
                    var $modal = $( this ).filter( 'div' );
                    $modal.prependTo( 'body' ).modal();
                    $modal.find( 'a.save' ).click( function() {
                        var oldpass = $modal.find( 'input[name="oldpassword"]' ).val();
                        var newpass = $modal.find( 'input[name="newpassword"]' ).val();
                        var newpass2 = $modal.find( 'input[name="newpassword2"]' ).val();
                        if ( newpass != newpass2 ) {
                            alert( 'Η επιβεβαίωση του νέου κωδικού απέτυχε, ξαναγράψε τον νέο κωδικό σωστά και στα δύο πεδία' );
                            return;
                        }
                        alert( oldpass );
                        alert( newpass );
                        alert( newpass2 );
                        $modal.jqmHide();
                        return false;
                        /* oldpass/newpass */
                    } );
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
        // User Interests
        $( '.interestitems li .delete' ).click( function(){
            Profile.Interests.Remove( $( this ).parent() );
        });
        Comment.Init();
    },
    Interests: {
        Remove: function( li ){
            $.post( 'interest/delete', {
                id: li.attr( 'id' ).split( '_' )[ 1 ]
            }, function(){
                $( li ).remove();
            });
        },
        Create: function( text, type ){
            $.post( 'interest/create', {
                type: type,
                text: text
            }, function( data ){
                var tagitem = $( '#' + type ).children( '.last' ).clone()
                    .attr( 'id', 'tag_' + $( data ).attr( 'id' ) ).children( '.editable' ).text( $( data ).text() ).end();
                $( '#' + type ).children( ':last' ).removeClass( 'last' );
                $( '#' + type ).append( tagitem );
            });
        }
    },
    PrepareMoodPicker: function() {
        $( '.mood' ).addClass( 'editable' );
        $( '.mood .activemood' ).click( function() {
            axslt( $.get( 'moods' ), 'call:user.mood.edit', function() {
                $activemood = $( '.mood > .activemood' );
                $activemood.hide();
                $moodpicker = $( this ).filter( 'div' );
                $moodpicker.appendTo( '.mood' );
                $activetile = $moodpicker.find( '#mood_' + $activemood.attr( 'id' ).split( '_' )[1] );
                $activetile.closest( 'li' ).addClass( 'activemood' );
                $moodpicker.find( 'ul li:not( .activemood )' ).click( function() {
                    var $newactivemood = $( this ).find( '.moodtile' );
                    var moodid = $newactivemood.attr( 'id' ).split( '_' )[1];
                    $activemood.replaceWith( $newactivemood );
                    $newactivemood.attr( 'id', 'active' + $newactivemood.attr( 'id' ) ).addClass( 'activemood' );
                    $moodpicker.hide().remove();
                    $.post( 'user/update', { 'moodid': moodid } );
                    Profile.PrepareMoodPicker();
                } );
                $moodpicker.find( '.modalclose, ul li.activemood' ).click( function() {
                    $moodpicker.hide().remove();
                    $activemood.show();
                } );
            }, { 'gender': Profile.CurrentValues[ 'gender' ] } );
        } );
    },
    PrepareInlineEditables: function() {
        Profile.PrepareMoodPicker();
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
            //TODO default value if empty
            axslt( false, 'call:user.modal.location', function() {
                $modal = $( this ).filter( 'div' );
                $modal.prependTo( 'body' ).modal();
                var location_id = $( '.asl .location span' ).attr( 'id' ).split( '_' )[1];
                var location_text = $( '.asl .location span' ).text();
                var $select = $modal.find( 'select.location' );
                $( '<option value=' + location_id + '>' + location_text + '</option>' ).appendTo( $select );
                axslt( $.get( 'places' ), 'call:user.modal.location.options', function() { 
                    $modal.find( 'select.location' ).empty()
                        .append( $( this ).filter( 'option' ) ).val( location_id )
                        .change( function() {
                            $.post( 'user/update', { placeid: $modal.find( 'select.location' ).val() } );
                            $modal.jqmHide();
                            return false;
                        } );
                } );
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
                   'height': 'li.height > span',
                   'weight': 'li.weight > span'
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
                if ( field == 'height' || field == 'weight' ) {
                    Profile.CurrentValues[ field ] = oldselect.val() || '-3';
                }
                else {
                    Profile.CurrentValues[ field ] = oldselect.val() || '-';
                }
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
                    var postvars = {};
                    postvars[ field ] = $( this ).val();
                    $.post( 'user/update', postvars );
                } );
                select.appendTo( element ).css( 'display', 'block' );
        }
    },
    PopulateSelect: function( element, field ) {
        var map = UserDetails.GetMap( field, Profile.CurrentValues[ 'gender' ] );
        var nbsp = String.fromCharCode( 160 );
        var select = $( element ).find( 'select' );
        select.empty();
        if ( typeof( map[ -2 ] ) != 'undefined' ) {
            $( '<option />' ).attr( 'value', -2 ).text( nbsp + map[ -2 ] + nbsp).appendTo( select );
        }
        for ( key in map ) {
            if ( key != -2 ) {
                $( '<option />' ).attr( 'value', key ).text( nbsp + map[ key ] + nbsp).appendTo( select );
            }
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
