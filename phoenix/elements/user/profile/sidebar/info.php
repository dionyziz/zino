<?php
    class ElementUserProfileSidebarInfo extends Element {
        public function Render( $theuser ) {
            ?><div class="info">
                <dl><?php
                    if ( $theuser->Profile->Age ) {
                        ?><dt><strong>Ηλικία</strong></dt>
                        <dd><?php
                        echo $theuser->Profile->Age;
                        ?></dd><?php
                    }
                    if ( $theuser->Profile->Placeid > 0 ) {
                        ?><dt><strong>Περιοχή</strong></dt>
                        <dd><?php
                        echo htmlspecialchars( $theuser->Profile->Location->Name );
                        ?></dd><?php
                    }
                    /*if ( $theuser->Profile->Schoolid > 0 && $theuser->Profile->Placeid > 0 && $theuser->Profile->Education == $theuser->Profile->School->Typeid ) {
                        ?><dt><strong>Πανεπιστήμιο</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/school', $theuser->Profile->School );
                        ?></dd><?php
                    }*/
					if ( $theuser->Profile->Schoolid > 0 && $theuser->Profile->Placeid > 0 ) {
						if ( $theuser->Profile->Education < 5 ) {
							?><dt><strong>Σχολείο</strong></dt><dd><?php
						}
						else {
							?><dt><strong>Πανεπιστήμιο</strong></dt><dd><?php
						}
						Element( 'user/trivial/school', $theuser->Profile->School );
						?></dd><?php
					}
                    if ( $theuser->Profile->Haircolor != '-' ) {
                        ?><dt><strong>Χρώμα μαλλιών</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/haircolor' , $theuser->Profile->Haircolor );
                        ?></dd><?php
                    }
                    if ( $theuser->Profile->Eyecolor != '-' ) {
                        ?><dt><strong>Χρώμα ματιών</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/eyecolor' , $theuser->Profile->Eyecolor );
                        ?></dd><?php
                    }
                ?></dl>
            </div><?php
        }
    }

?>
