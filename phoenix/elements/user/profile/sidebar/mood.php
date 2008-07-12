<?php
	class ElementUserProfileSidebarMood extends Element {
        public function Render( $theuser ) {
            global $xc_settings;
            
            if ( $theuser->Profile->Mood->Exists() ) {
                ?><div class="mood">
                    <strong>Διάθεση</strong> 
                    <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>moods/<?php
                    echo $theuser->Profile->Mood->Url;
                    ?>"<?php
                    if ( $theuser->Gender == 'f' ) {
                        ?> alt="<?php
                        echo htmlspecialchars( $theuser->Profile->Mood->Labelfemale );
                        ?>" title="<?php
                        echo htmlspecialchars( $theuser->Profile->Mood->Labelfemale );
                        ?>"<?php
                    } 
                    else {
                        ?> alt="<?php
                        echo htmlspecialchars( $theuser->Profile->Mood->Labelmale );
                        ?>" title="<?php
                        echo htmlspecialchars( $theuser->Profile->Mood->Labelmale );
                        ?>"<?php
                    }
                    ?>/>
                </div><?php
            }
        }
    }
?>
