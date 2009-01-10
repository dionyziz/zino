<?php

    class ElementUserSettingsPersonalView extends Element {
        public function Render() {
            global $water;
            global $user;
            global $rabbit_settings;

            $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;
            ?><div class="option">
                <label for="dateofbirth">Ημερομηνία Γέννησης:</label>
                <div class="setting" id="dateofbirth"><?php
                    Element( 'user/settings/personal/dob' );
                ?><span class="invaliddob"><span class="s_invalid">&nbsp;</span>Η ημερομηνία δεν είναι έγκυρη
                </span>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="gender">Φύλο:</label>
                <div class="setting" id="gender"><?php
                    Element( 'user/settings/personal/gender' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="place">Περιοχή:</label>
                <div class="setting" id="place"><?php
                    Element( 'user/settings/personal/place', $user->Profile->Placeid );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="education">Εκπαίδευση:</label>
                <div class="setting" id="education"><?php
                    Element( 'user/settings/personal/education' );
                    ?><div class="forstudents<?php
                    if ( $user->Profile->Education >= 5 ) {
                        ?> invisible<?php
                    }
                    ?>">Αν είσαι φοιτητής όρισε την περιοχή και το είδος του εκπαιδευτικού ιδρύματος</div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option<?php
            if ( !$showschool ) {
                ?> invisible<?php
            }
            ?>">
                <label for="school">Πανεπιστήμιο</label>
                <div class="setting" id="school"><?php
                   if ( $showschool ) {
                        Element( 'user/settings/personal/school', $user->Profile->Placeid, $user->Profile->Education  );
                    } 
                ?></div>
            </div>
            <div id="unibarfade" class="barfade<?php
            if ( !$showschool ) {
                ?> invisible<?php
            }
            ?>">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="photo">Φωτογραφία:</label>
                <div class="setting" id="photo"><?php
                    Element( 'user/settings/personal/avatar' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="mood">Διάθεση:</label>
                <div class="setting" id="mood"><?php
                    Element( 'user/settings/personal/mood' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="sexualorientation">Σεξουαλικές προτιμήσεις:</label>
                <div class="setting" id="sex"><?php
                    Element( 'user/settings/personal/sex' , $user->Profile->Sexualorientation , $user->Gender );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="religion">Θρήσκευμα:</label>
                <div class="setting" id="religion"><?php
                    Element( 'user/settings/personal/religion' , $user->Profile->Religion , $user->Gender );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="politics">Πολιτικές πεποιθήσεις:</label>
                <div class="setting" id="politics"><?php
                    Element( 'user/settings/personal/politics' , $user->Profile->Politics , $user->Gender );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="slogan">Slogan:</label>
                <div class="setting" id="slogan"><?php
                    Element( 'user/settings/personal/slogan' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="aboutme">Λίγα λόγια για μένα:</label>
                <div class="setting" id="aboutme"><?php
                    Element( 'user/settings/personal/aboutme' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label for="favquote">Αγαπημένο ρητό:</label>
                <div class="setting" id="favquote"><?php
                    Element( 'user/settings/personal/favquote' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div><?php
        }
    }

?>
