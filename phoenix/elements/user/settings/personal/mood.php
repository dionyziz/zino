<?php
	function ElementUserSettingsPersonalMood() {
		global $user, $libs, $xc_settings;
		
        $libs->Load( 'mood' );
        $moodfinder = New MoodFinder();

		?>
        <div class="moodpicker" style="overflow:hidden">
            <div class="view" onclick="MoodDropdown.Push( this.parentNode );">
                <a href="" onclick="return false;">
                    <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>dropdown.png" alt="v" />
                </a>
                <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                echo $user->Profile->Mood->Url;
                ?>" alt="<?php
                if ( $user->Gender == 'f' ) {
                    echo htmlspecialchars( $user->Profile->Mood->LabelFemale );
                }
                else {
                    echo htmlspecialchars( $user->Profile->Mood->LabelMale );
                }
                ?>" title="<?php
                if ( $user->Gender == 'f' ) {
                    echo htmlspecialchars( $user->Profile->Mood->LabelFemale );
                }
                else {
                    echo htmlspecialchars( $user->Profile->Mood->LabelMale );
                }
                ?>" class="selected" />
            </div>
            <div class="pick">
                <ul><?php
                    $moods = $moodfinder->FindAll();
                    foreach ( $moods as $mood ) {
                        ?><li><a><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        echo $mood->Url;
                        ?>" alt="<?php
                        if ( $user->Gender == 'f' ) {
                            echo htmlspecialchars( $mood->LabelFemale );
                        }
                        else {
                            echo htmlspecialchars( $mood->LabelMale );
                        }
                        ?>" title="<?php
                        if ( $user->Gender == 'f' ) {
                            echo htmlspecialchars( $mood->LabelFemale );
                        }
                        else {
                            echo htmlspecialchars( $mood->LabelMale );
                        }
                        ?>" /></a></li><?php
                    }
                ?>
                </ul>
                <div class="eof"></div>
            </div>
        </div>
		<?php
	}
?>
