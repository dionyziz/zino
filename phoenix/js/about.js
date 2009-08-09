var About = {
    VisiblePerson: 'noone',
    OnLoad: function() {
        if ( $( '#aboutpeople' ).length ) {
            $( '#aboutpeople li a' ).click( function () {
                var username = $( this ).find( 'img' )[ 0 ].alt;
                
                if ( username == About.VisiblePerson ) {
                    return false;
                }
                
                $( this ).parent().parent().find( 'li' ).removeClass( 'selected' );
                $( this ).parent().addClass( 'selected' );
                if ( About.VisiblePerson ) {
                    $( $( '#aboutperson div#iam' + About.VisiblePerson )[ 0 ] ).animate( {
                        left: '-100%'
                    }, 400, 'swing', function ( removed, added ) {
                        return function () {
                            $( '#iam' + added ).removeClass( 'aboutonepersonslide' );
                            $( '#iam' + removed ).addClass( 'aboutonepersonslide' );
                            $( '#iam' + removed ).css( { left: '100%' } );
                        }
                    }( About.VisiblePerson, username ) );
                }
                $( $( '#aboutperson div#iam' + username )[ 0 ] ).animate( {
                    left: 0
                }, 400, 'swing' );
                About.VisiblePerson = username;
                return false;
            } );
        }
        if ( $( '#aboutcontact' ).length ) {
            $( '#aboutcontact select#reason' ).change( function () {
                var options = document.getElementById( 'reason' ).options;
                
                for ( var i = 1; i < options.length; ++i ) { // skip the first empty item
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        document.getElementById( 'contact_' + option.value ).style.display = '';
                        switch ( option.value ) {
                            case '':
                            case 'purge':
                                document.getElementById( 'submit' ).style.display = 'none';
                                break;
                            default:
                                document.getElementById( 'submit' ).style.display = '';
                        }
                    }
                    else {
                        document.getElementById( 'contact_' + option.value ).style.display = 'none';
                    }
                }
            } );
            $( '#aboutcontact select#bugos' ).change( function () {
                var options = document.getElementById( 'bugos' ).options;
                
                for ( var i = 1; i < options.length; ++i ) { // skip the first empty item
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        $( '#bug_osinfo_' + option.value ).css( { 'display': '' } );
                    }
                    else {
                        $( '#bug_osinfo_' + option.value ).css( { 'display': 'none' } );
                    }
                }
            } );
            $( '#aboutcontact select#bugdevice' ).change( function () {
                var options = document.getElementById( 'bugdevice' ).options;
                
                for ( var i = 0; i < options.length; ++i ) {
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        $( '#bug_deviceinfo_' + option.value ).css( { 'display': '' } );
                    }
                    else {
                        $( '#bug_deviceinfo_' + option.value ).css( { 'display': 'none' } );
                    }
                }
                switch ( document.getElementById( 'bugdevice' ).value ) {
                    case 'computer':
                        $( '#bugcomputeros' ).change();
                        break;
                    default:
                        var options = document.getElementById( 'bugcomputeros' ).options;
                        
                        for ( var i = 1; i < options.length; ++i ) {
                            $( '#bug_osinfo_' + options[ i ].value ).css( { 'display': 'none' } );
                        }
                        break;
                }
            } );
            $( '#bugbrowser' ).change( function () {
                var options = document.getElementById( 'bugbrowser' ).options;
                
                for ( var i = 1; i < options.length; ++i ) {
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        $( '#bug_browserinfo_' + option.value ).css( { 'display': '' } );
                    }
                    else {
                        $( '#bug_browserinfo_' + option.value ).css( { 'display': 'none' } );
                    }
                }
            } );
            $( '#aboutcontact select#bugcomputeros' ).change( function () {
                var options = document.getElementById( 'bugcomputeros' ).options;
                
                for ( var i = 1; i < options.length; ++i ) { // skip the first empty item
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        $( '#bug_osinfo_' + option.value ).css( { 'display': '' } );
                    }
                    else {
                        $( '#bug_osinfo_' + option.value ).css( { 'display': 'none' } );
                    }
                }
            } );
            // try to detect browser and set that as the default for the user in the bug reporting page
            var browser = '';
            if ( navigator.userAgent.indexOf( "Chrome" ) > -1 ) { // it's Chrome
                browser = 'chrome';
            }
            else if ( navigator.userAgent.indexOf( "Firefox" ) > -1 ) { // it's Firefox
                browser = 'ff';
            }
            else if ( navigator.userAgent.indexOf( "MSIE" ) > -1 ) { // it's Internet Explorer
                browser = 'ie';
            }
            else if ( navigator.userAgent.indexOf( "Opera" ) > -1 ) { // it's Opera
                browser = 'opera';
            }
            else if ( navigator.userAgent.indexOf( "Safari" ) > -1 ) { // it's Opera
                browser = 'safari';
            }
            document.getElementById( 'bugbrowser' ).value = browser;
            $( '#bugbrowser' ).change();
            
            // try to detect the OS and set it as default in the bug reporting page
            var os = '';
            if ( navigator.platform.indexOf( 'Win' ) > -1 ) {
                os = 'windows';
                var winver = '';
                if ( navigator.userAgent.indexOf( 'Windows NT 6.1' ) > -1 ) {
                    winver = '7';
                }
                else if ( navigator.userAgent.indexOf( 'Windows NT 6.0' ) > -1 ) {
                    winver = 'vista';
                }
                else if ( navigator.userAgent.indexOf( 'Windows NT 5.1' ) > -1 
                          || navigator.userAgent.indexOf( 'Windows XP' ) > -1 ) {
                    winver = 'xp';
                }
                else if ( navigator.userAgent.indexOf( 'Windows NT 5.0' ) > -1 ) {
                    winver = '2000';
                }
                else if ( navigator.userAgent.indexOf( 'Windows ME' ) > -1 
                          || navigator.userAgent.indexOf( 'Win 9x 4.90' ) > -1 ) {
                    winver = 'me';
                }
                else if ( navigator.userAgent.indexOf( 'Win98' ) > -1 ) {
                    winver = '98';
                }
                document.getElementById( 'bugwinversion' ).value = winver;
            }
            else if ( navigator.platform.indexOf( 'Linux' ) > -1 ) {
                os = 'linux';
                var linuxdistro = '';
                if ( navigator.userAgent.indexOf( 'Ubuntu' ) > -1 ) {
                    linuxdistro = 'ubuntu';
                }
                else if ( navigator.userAgent.indexOf( 'Debian' ) > -1 ) {
                    linuxdistro = 'debian';
                }
                document.getElementById( 'buglinuxdistro' ).value = linuxdistro;
            }
            else if ( navigator.platform.indexOf( 'Mac' ) > -1 ) {
                os = 'mac';
            }
            document.getElementById( 'bugcomputeros' ).value = os;
            $( '#bugcomputeros' ).change();
        }
    },
};
