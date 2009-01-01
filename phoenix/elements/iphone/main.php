<?php
    class ElementiPhoneMain extends Element {
        public function Render() {
            global $user;
            global $water;
            global $page;
            global $libs;
            global $rabbit_settings;
            global $xc_settings;
            
            $page->AttachStylesheet( 'css/iphone.css?' . $xc_settings[ 'cssversion' ] );
            $page->AttachScript( 'http://www.google-analytics.com/urchin.js' );
            $page->AttachScript( 'js/jquery.js' );
            $page->AttachScript( 'js/iphone.js?' . $xc_settings[ 'jsversion' ] );
            $page->AddMeta( 'author', 'Kamibu Development Team' );
            $page->AddMeta( 'keywords', 'greek friends chat community greece meet people' );
            $page->AddMeta( 'description', 'Το ' . $rabbit_settings[ 'applicationname' ] . ' είναι μία ελληνική κοινότητα φίλων - είσαι μέσα;' );
            
            ob_start();
            $res = Element::MasterElement();
            $master = ob_get_clean();
            if ( $res === false ) { //If the page requested is not in the pages available
                ?><div class="content" id="content"><?php
                Element( '404' );
                ?></div><?php
            }
            else {
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    Element( 'iphone/banner' );
                }
                ?><div class="content" id="content"><?php    
                echo $master;
                ?></div><?php
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    Element( 'iphone/footer' );
                }
            }
            if ( !$page->TitleFinal() ) {
                if ( $page->Title() != '' ) { // If the title's page is not blank
                    $page->SetTitle( $page->Title() . ' | ' . $rabbit_settings[ 'applicationname' ] );
                }
                else {
                    $water->Notice( 'Title not defined for page' );
                    $page->SetTitle( $rabbit_settings[ 'applicationname' ] );
                }
            }

            // pass
            return $res;
        }
    }
?>
