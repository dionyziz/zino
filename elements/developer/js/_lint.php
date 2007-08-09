<?php
    function ElementDeveloperJsLint() {
        global $page;
        global $rabbit_settings;
        
        $page->AttachScript( 'js/jslint/fulljslint.js', 'javascript', true );
        $page->AttachScript( 'js/jslint/web.js', 'javascript', true );
        
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
                        $jslintsources[] = array(
                            $file => file_get_contents( $jspath . '/' . $file )
                        );
                    }
            }
        }
        ?>
        <dl id="jslintresults">
        </dl>
        <script type="text/javascript"><?php
            ob_start();
            ?>
            function Lint() {
                var jslintsources = <?php
                echo w_json_encode( $jslintsources );
                ?>;
                var jssource;
                var jslintresult;
                var results = document.getElementById( 'jslintresults' );
                var filename;
                var parseresult;
                
                for ( i in jslintsources ) {
                    jssource = jslintsources[ i ];
                    filename = document.createElement( 'dt' );
                    filename.appendChild( document.createTextNode( jssource ) );
                    results.appendChild( filename );
                    jslintresult = JSLINT( jssource, {} );
                    parseresult = document.createElement( 'dd' );
                    if ( jslintresult === false ) {
                        parseresult.appendChild( document.createTextNode( 'PASS' ) );
                    }
                    else {
                        parseresult.appendChild( document.createTextNode( 'FAIL' ) );
                    }
                    results.appendChild( parseresult );
                }
            }
            Lint();
            <?php
            echo htmlspecialchars( ob_get_clean() );
            ?>
        </script><?php
    }
?>
