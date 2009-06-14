<?php
    class ElementAboutInfoView extends Element {
        public function Render( tText $section ) {
            static $sections = array(
                'summary' => 'περιληπτικά',
                'workings' => 'πώς δουλεύει το zino;',
                'team' => 'ποιοι είναι πίσω απ\' το zino;',
                'contact' => 'επικοινωνία',
                'faq' => 'συχνές ερωτήσεις',
                'journalists' => 'δημοσιογράφοι',
                'ads' => 'διαφήμιση',
                'status' => 'κατάσταση',
                'jobs' => 'εργασία στο zino'
            );
            
            $section = $section->Get();
            if ( !isset( $sections[ $section ] ) ) {
                $section = 'summary';
            }
            Element( 'about/info/sidebar', $section, $sections );
            ?><div id="aboutsection"><?php
            Element( 'about/info/' . $section );
            ?><div class="eof"></div>
            </div><?php
        }
    }
?>
