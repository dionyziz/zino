<?php
    function ElementDeveloperJsLint() {
        global $page;
        global $rabbit_settings;
        
        $page->SetTitle( 'JSLINT' );
        $page->AttachScript( 'js/jslint/fulljslint.js', 'javascript', true );
        $page->AttachStylesheet( 'css/jslint.css' );
        
        $jspath = $rabbit_settings[ 'rootdir' ] . '/js';
        $dir = opendir( $jspath );
        if ( $dir === false ) {
            ?>Failed to open dir.<?php
            return;
        }
        $jslintsources = array();
        while ( false !== ( $file = readdir( $dir ) ) ) {
            switch ( $file ) {
                case '.':
                case '..':
                    break;
                default:
                    if ( !is_dir( $jspath . '/' . $file ) ) {
                        $jslintsources[ $file ] = file_get_contents( $jspath . '/' . $file );
                    }
            }
        }
        ?>
        <br /><br /><br />
        <dl id="jslintresults">
        </dl>
        <script type="text/javascript"><?php
            ob_start();
            ?>
            function Lint( file, source ) {
                var jssource;
                var results = document.getElementById( 'jslintresults' );
                var filename;
                
                filename = document.createElement( 'dt' );
                filelink = document.createElement( 'a' );
                filelink.href = 'js/' + file;
                var loader = document.createElement( 'img' );
                loader.src = 'http://static.chit-chat.gr/images/ajax-loader.gif';
                loader.style.cssFloat = 'right';
                filename.appendChild( loader );
                filelink.appendChild( document.createTextNode( file ) );
                filename.appendChild( filelink );
                results.appendChild( filename );
                var parseresult = document.createElement( 'dd' );
                results.appendChild( parseresult );
                
                setTimeout( function ( parseresult ) {
                    return function () {
                        var jslintresult = JSLINT( source, {} );
                        
                        if ( jslintresult === true ) {
                            parseresult.className = 'pass';
                            parseresult.appendChild( document.createTextNode( 'PASS' ) );
                        }
                        else {
                            parseresult.className = 'fail';
                            var table = document.createElement( 'table' );
                            var headlines = document.createElement( 'tr' );
                            var th;
                            var titles = [ 'error', 'source', 'line/char' ];
                            var i;
                            
                            for ( i = 0; i < titles.length; ++i ) {
                                th = document.createElement( 'th' );
                                th.appendChild( document.createTextNode( titles[ i ] ) );
                                headlines.appendChild( th );
                            }
                            table.appendChild( headlines );
                            var tr;
                            var td;
                            var evidence;
                            var chara;
                            var prefix;
                            var suffix;
                            var j = 0;
                            const MAXLINELEN = 61; // assert( ( MAXLINELEN - 1 ) % 2 == 0 )
                            const AREALEN = 5; // assert( ( AREALEN - 1 ) % 2 == 0 )
                            
                            for ( i = 0; i < JSLINT.errors.length; ++i ) {
                                if ( JSLINT.errors[ i ] !== null ) {
                                    tr = document.createElement( 'tr' );
                                    td = document.createElement( 'td' );
                                    td.appendChild( document.createTextNode( JSLINT.errors[ i ].reason ) );
                                    tr.appendChild( td );
                                    td = document.createElement( 'td' );
                                    if ( typeof JSLINT.errors[ i ].evidence == 'string' ) {
                                        evidence = JSLINT.errors[ i ].evidence;
                                        chara = JSLINT.errors[ i ].character;
                                        chara -= ( AREALEN - 1 ) / 2;
                                        if ( chara < 0 ) {
                                            chara = 0;
                                        }
                                        var leftpart = evidence.substr( 0, chara );
                                        var rightpart = evidence.substr( chara + AREALEN, evidence.length );
                                        var realevidence = evidence.substr( chara, AREALEN );
                                        
                                        chara = JSLINT.errors[ i ].character;
                                        chara -= ( MAXLINELEN - 1 ) / 2;
                                        if ( chara < 0 ) {
                                            chara = 0;
                                            prefix = '';
                                        }
                                        else {
                                            prefix = '...';
                                        }
                                        if ( evidence.length > chara + ( MAXLINELEN - 1 ) / 2 ) {
                                            suffix = '...';
                                        }
                                        else {
                                            suffix = '';
                                        }
                                        leftpart = prefix + leftpart.substr( chara, MAXLINELEN );
                                        rightpart = rightpart.substr( 0, ( MAXLINELEN - 1 ) / 2 ) + suffix;
                                        td.appendChild( document.createTextNode( leftpart ) );
                                        var b = document.createElement( 'span' );
                                        b.appendChild( document.createTextNode( realevidence ) );
                                        td.appendChild( b );
                                        td.appendChild( document.createTextNode( rightpart ) );
                                    }
                                    tr.appendChild( td );
                                    td = document.createElement( 'td' );
                                    td.appendChild( document.createTextNode( JSLINT.errors[ i ].line + '/' + JSLINT.errors[ i ].character ) );
                                    tr.appendChild( td );
                                    if ( j % 2 == 0 ) {
                                        tr.className = 'l';
                                    }
                                    table.appendChild( tr );
                                    ++j;
                                }
                            }
                            parseresult.appendChild( table );
                        }
                        var prev;
                        while ( prev = parseresult.previousSibling ) {
                            if ( prev.nodeName == 'dt' ) {
                                // remove the AJAX loader
                                prev.removeChild( prev.getElementsByTagName( 'img' )[ 0 ] );
                            }
                        }
                    }
                }( parseresult ), 50 );
            }
            var jslintsources = <?php
            echo w_json_encode( $jslintsources );
            ?>;
            
            j = 0;
            for ( i in jslintsources ) {
                if ( i.substr( i.length - 3, 3 ) == '.js' ) {
                    setTimeout( function ( file, source ) {
                        return function () {
                            Lint( file, source );
                        };
                    }( i, jslintsources[ i ] ), j * 100 );
                    ++j;
                }
            }
            
            <?php
            echo htmlspecialchars( ob_get_clean() );
            ?>
        </script><?php
    }
?>
