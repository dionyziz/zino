<?php
    class ElementStoreMain extends Element {
        public function Render() {
            global $user;
            global $water;
            global $page;
            global $libs;
            global $rabbit_settings;
            global $xc_settings;
            
            $page->AttachScript( 'http://www.google-analytics.com/urchin.js' );
            $page->AttachScript( $xc_settings[ 'staticjsurl' ] . 'analytics.js?' . $xc_settings[ 'jsversion' ] );
            $page->AttachStylesheet( 'css/store.css?' . $xc_settings[ 'cssversion' ] );
            $page->AddMeta( 'author', 'Kamibu Development Team' );
            $page->AddKeyword( array( 'greek', 'friends', 'chat', 'community', 'greece', 'meet', 'people', 'store' ) );
            $page->AddMeta( 'description', 'Το ' . $rabbit_settings[ 'applicationname' ] . ' είναι η παρέα σου online - είσαι μέσα;' );
            
            ob_start();
            $info = Element::MasterElement();
            $res = $info[ 0 ];
            $masterelement = $info[ 1 ];
            $master = ob_get_clean();
            if ( $res === false ) {
                Element( '404' );
            }
            else {
                ?><div id="content"><?php
                    echo $master;
                ?></div><?php
            }

            // pass
            return $res;
        }
    }
?>
