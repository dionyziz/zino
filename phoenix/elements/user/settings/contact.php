<?php
    class ElementUserSettingsContact extends Element {
        public function Render() {
            global $user;
            global $rabbit_settings;
            
            ?><div class="option">
                <label>E-mail:</label>
                <div class="setting" id="email">
                    <input type="text" name="email" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Email );
                    ?>" />
                    <span>
                        <img src="<?php
                        echo $rabbit_settings[ "imagesurl" ];
                        ?>exclamation.png" /> Το email δεν είναι έγκυρο
                    </span>
                    <div class="explanation">Το e-mail δεν εμφανίζεται στο προφίλ σου.</div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>MSN:</label>
                <div class="setting" id="msn">
                    <input type="text" name="msn" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Msn );
                    ?>" />
                    <span>
                        <img src="<?php
                        echo $rabbit_settings[ "imagesurl" ];
                        ?>exclamation.png" /> Το MSN δεν είναι έγκυρο
                    </span>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Gtalk:</label>
                <div class="setting" id="gtalk">
                    <input type="text" name="gtalk" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Gtalk );
                    ?>" />
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Skype:</label>
                <div class="setting" id="skype">
                    <input type="text" name="skype" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Skype );
                    ?>" />
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Yahoo:</label>
                <div class="setting" id="yahoo">
                    <input type="text" name="yahoo" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Yim );
                    ?>" />
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Ιστοσελίδα:</label>
                <div class="setting" id="web">
                    <input type="text" name="yahoo" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Homepage );
                    ?>" />
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div><?php
        }
    }
?>
