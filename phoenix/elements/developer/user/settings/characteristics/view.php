<?php

    class ElementDeveloperUserSettingsCharacteristicsView extends Element {
        public function Render() {
            ?><div class="option">
                <label>Χρώμα μαλλιών</label>
                <div class="setting" id="haircolor"><?php
                    Element( 'developer/user/settings/characteristics/haircolor' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div>
            <div class="option">
                <label>Χρώμα ματιών</label>
                <div class="setting" id="eyecolor"><?php
                    Element( 'developer/user/settings/characteristics/eyecolor' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div>
            <div class="option">
                <label>Ύψος</label>
                <div class="setting" id="height"><?php
                    Element( 'developer/user/settings/characteristics/height' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div>
            <div class="option">
                <label>Βάρος</label>
                <div class="setting" id="weight"><?php
                    Element( 'developer/user/settings/characteristics/weight' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div>
            <div class="option">
                <label>Καπνίζεις;</label>
                <div class="setting" id="smoker"><?php
                    Element( 'developer/user/settings/characteristics/smoker' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div>
            <div class="option">
                <label>Πίνεις;</label>
                <div class="setting" id="drinker"><?php
                    Element( 'developer/user/settings/characteristics/drinker' );
                ?></div>
            </div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div><?php    
            }
    }
?>
