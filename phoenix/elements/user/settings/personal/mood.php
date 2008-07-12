<?php
	class ElementUserSettingsPersonalMood extends Element {
        public function Render() {
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
                    ?>moods/<?php
                    echo $user->Profile->Mood->Url;
                    ?>" alt="<?php
                    if ( $user->Gender == 'f' ) {
                        echo htmlspecialchars( $user->Profile->Mood->Labelfemale );
                    }
                    else {
                        echo htmlspecialchars( $user->Profile->Mood->Labelmale );
                    }
                    ?>" title="<?php
                    if ( $user->Gender == 'f' ) {
                        echo htmlspecialchars( $user->Profile->Mood->Labelfemale );
                    }
                    else {
                        echo htmlspecialchars( $user->Profile->Mood->Labelmale );
                    }
                    ?>" class="selected" />
                </div>
                <div class="pick">
                    <ul><?php
                        $moods = $moodfinder->FindAll();
                        foreach ( $moods as $mood ) {
                            ?><li><a href="" onclick="MoodDropdown.Select( 'moodid', <?php
                            echo $mood->Id;
                            ?>, this );return false;"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>moods/<?php
                            echo $mood->Url;
                            ?>" alt="<?php
                            if ( $user->Gender == 'f' ) {
                                echo htmlspecialchars( $mood->Labelfemale );
                            }
                            else {
                                echo htmlspecialchars( $mood->Labelmale );
                            }
                            ?>" title="<?php
                            if ( $user->Gender == 'f' ) {
                                echo htmlspecialchars( $mood->Labelfemale );
                            }
                            else {
                                echo htmlspecialchars( $mood->Labelmale );
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
    }
?>
