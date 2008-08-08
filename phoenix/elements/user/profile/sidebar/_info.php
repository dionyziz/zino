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
                    if ( $theuser->Profile->Schoolid > 0 && $theuser->Profile->Placeid > 0 && $theuser->Profile->Education != "-" ) {
                        $school = New School( $theuser->Profile->Schoolid );
                        ?><dt><strong>Πανεπιστήμιο</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/school', $school );
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
					if ( $theuser->LastActivity->Updated != '0000-00-00 00:00:00' ) {
						?><dt><strong>Online</strong></dt>
						<dd><?php
						if ( $theuser->LastActivity->IsOnline() ) {
							?>αυτή τη στιγμή!<?php
						}
						else {
							Element( 'date/diff' , $theuser->LastActivity->Updated );
						}
						?></dd><?php
					}
                ?></dl>
            </div><?php
        }
    }

?>
