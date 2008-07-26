<?php
    class ElementUserProfileSidebarMood extends Element {
        protected $mPersistent = array( 'moodid', 'gender' );

        public function Render( Mood $mood, $moodid, $gender ) {
            global $xc_settings;
            
            if ( $mood->Exists() ) {
                ?><div class="mood">
                    <strong>Διάθεση</strong> 
                    <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>moods/<?php
                    echo $mood->Url;
                    ?>"<?php
                    if ( $gender == 'f' ) {
                        ?> alt="<?php
                        echo htmlspecialchars( $mood->Labelfemale );
                        ?>" title="<?php
                        echo htmlspecialchars( $mood->Labelfemale );
                        ?>"<?php
                    } 
                    else {
                        ?> alt="<?php
                        echo htmlspecialchars( $mood->Labelmale );
                        ?>" title="<?php
                        echo htmlspecialchars( $mood->Labelmale );
                        ?>"<?php
                    }
                    ?>/>
                </div><?php
            }
        }
    }
?>
