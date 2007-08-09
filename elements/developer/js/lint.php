<?php
    function ElementDeveloperJsLint() {
        global $page;
        global $rabbit_settings;
        
        $page->AttachScript( 'js/jslint/fulljslint.js' );
        $page->AttachScript( 'js/jslint/web.js' );
        
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
        ?><script type="text/javascript"><?php
            ob_start();
            ?>
            var jslintsources = <?php
            echo w_json_encode( $jslintsources );
            ?>;
            <?php
            echo htmlspecialchars( ob_get_clean() );
            ?>
        </script><?php
    }
?>
