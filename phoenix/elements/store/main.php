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
            $page->AttachScript( $xc_settings[ 'staticjsurl' ] . 'global.js?' . $xc_settings[ 'jsversion' ] );
            $page->AttachStylesheet( 'css/store.css?' . $xc_settings[ 'cssversion' ] );
            $page->AttachStylesheet( 'css/emoticons.css?' . $xc_settings[ 'cssversion' ] );
            $page->AddMeta( 'author', 'Kamibu Development Team' );
            $page->AddKeyword( array( 'greek', 'friends', 'chat', 'community', 'greece', 'meet', 'people', 'store' ) );
            $page->AddMeta( 'description', 'Το Zino είναι η παρέα σου online - είσαι μέσα;' );
            
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
            
            //Coppied from elements/main
            if ( !$page->TitleFinal() ) {
                if ( $page->Title() != '' ) { // If the title's page is not blank
                    $page->SetTitle( $page->Title() . ' | ' . 'ZinoSTORE' );
                }
                else {
                    $water->Notice( 'Title not defined for page' );
                    $page->SetTitle( 'ZinoSTORE' );
                }
            }
            
            // pass
            return $res;
        }
    }
?>
