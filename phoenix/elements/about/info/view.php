<?php
    class ElementAboutInfoView extends Element {
        public function Render( tText $section, tBoolean $status ) {
            global $page;

            static $sections = array(
                'summary' => 'περιληπτικά',
                'workings' => 'πώς δουλεύει το zino;',
                'team' => 'ποιοι είναι πίσω απ\' το zino;',
                'contact' => 'επικοινωνία',
                'faq' => 'συχνές ερωτήσεις',
               // 'journalists' => 'δημοσιογράφοι',
               // 'ads' => 'διαφήμιση',
               // 'status' => 'κατάσταση',
               // 'jobs' => 'εργασία στο zino'
            );
            
            $page->AttachInlineScript( 'About.OnLoad();' );
            $section = $section->Get();
            $status = $status->Get();
            if ( !isset( $sections[ $section ] ) ) {
                $section = 'summary';
            }
            Element( 'about/info/sidebar', $section, $sections );
            ?><div id="aboutsection"><?php
            if ( $section == 'contact' ) {
                Element( 'about/info/' . $section, $status );
            }
            else {
                Element( 'about/info/' . $section );
            }
            ?><div class="eof"></div>
            </div><?php
        }
    }
?>
