<?php
    function ElementDeveloperJsLint() {
        global $page;
        global $rabbit_settings;
        
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
                var parseresult;
                
                filename = document.createElement( 'dt' );
                filelink = document.createElement( 'a' );
                filelink.href = 'js/' + file;
                filelink.appendChild( document.createTextNode( file ) );
                filename.appendChild( filelink );
                results.appendChild( filename );
                jslintresult = JSLINT( source, {} );
                parseresult = document.createElement( 'dd' );
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
                    
                    for ( i = 0; i < JSLINT.errors.length; ++i ) {
                        if ( JSLINT.errors[ i ] !== null ) {
                            tr = document.createElement( 'tr' );
                            td = document.createElement( 'td' );
                            td.appendChild( document.createTextNode( JSLINT.errors[ i ].reason ) );
                            tr.appendChild( td );
                            td = document.createElement( 'td' );
                            if ( JSLINT.errors[ i ].evidence !== null ) {
                                evidence = JSLINT.errors[ i ].evidence;
                                chara = JSLINT.errors[ i ].character;
                                chara -= 50;
                                if ( chara < 0 ) {
                                    chara = 0;
                                    prefix = '';
                                }
                                else {
                                    prefix = '...';
                                }
                                if ( evidence.length > chara + 50 ) {
                                    suffix = '...';
                                }
                                else {
                                    suffix = '';
                                }
                                evidence = prefix + evidence.substr( chara, 50 ) + suffix;
                                td.appendChild( document.createTextNode( JSLINT.errors[ i ].evidence ) );
                            }
                            tr.appendChild( td );
                            td = document.createElement( 'td' );
                            td.appendChild( document.createTextNode( JSLINT.errors[ i ].line + '/' + JSLINT.errors[ i ].character ) );
                            tr.appendChild( td );
                            table.appendChild( tr );
                        }
                    }
                    parseresult.appendChild( table );
                }
                results.appendChild( parseresult );
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
