<?php
    class ElementUserProfileSidebarInfo extends Element {
        public function Render( $theuser, $schoolexists ) {
            ?><div class="info">
                <dl><?php
                    if ( $theuser->Profile->Age ) {
                        ?><dt id="birthday"><strong>Poso eim?</strong></dt>
                        <dd><?php
                        echo $theuser->Profile->Age;
                        ?></dd><?php
                    }
                    if ( $theuser->Profile->Placeid > 0 ) {
                        ?><dt><strong>Apo p?</strong></dt>
                        <dd><?php
                        echo htmlspecialchars( $theuser->Profile->Location->Name );
                        ?></dd><?php
                    }
					if ( $theuser->Profile->Schoolid > 0 && $theuser->Profile->Placeid > 0 ) {
						if ( $theuser->Profile->Education < 5 ) {
							?><dt><strong>Sxolio</strong></dt><dd><?php
						}
						else {
							?><dt><strong>pAnepiStimio</strong></dt><dd><?php
						}
                        if ( $schoolexists ) {
                            ?><a href="?p=school&amp;id=<?php
                            echo $theuser->Profile->Schoolid;
                            ?>"><?php
                        }
						Element( 'user/trivial/school', $theuser->Profile->School );
                        if ( $schoolexists ) {
                            ?></a><?php
                        }
						?></dd><?php
					}
                    if ( $theuser->Profile->Haircolor != '-' ) {
                        ?><dt><strong>T mallia m</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/haircolor' , $theuser->Profile->Haircolor );
                        ?></dd><?php
                    }
                    if ( $theuser->Profile->Eyecolor != '-' ) {
                        ?><dt><strong>T matia m</strong></dt>
                        <dd><?php
                        Element( 'user/trivial/eyecolor' , $theuser->Profile->Eyecolor );
                        ?></dd><?php
                    }
                ?></dl>
            </div><?php
        }
    }

?>
