<?php
    class ElementUserSettingsView extends Element {
        public function Render() {
            global $user;
            global $rabbit_settings;
            global $page;
            global $libs;
            
            $libs->Load( 'user/settings' );
            
            $page->SetTitle( 'Ρυθμίσεις' );
            if ( !$user->Exists() ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( $rabbit_settings[ 'webaddress' ] );
            }
            ?><div class="settings">
                <div class="sections"><?php
                    Element( 'user/settings/sections' );
                ?></div>
                <div class="tabs">
                    <form id="personalinfo" action="" style="display:none"><?php
                        Element( 'user/settings/personal/view' );
                    ?></form>
                    <form id="characteristicsinfo" action="" style="display:none"><?php
                        Element( 'user/settings/characteristics/view' );
                    ?></form>
                    <form onsubmit="return false" id="interestsinfo" action="" style="display:none"><?php
                        Element( 'user/settings/interests' );
                    ?></form>
                    <form id="contactinfo" action="" style="display:none"><?php
                        Element( 'user/settings/contact' );
                    ?></form>
                    <form id="settingsinfo" action="" style="display:none"><?php
                        Element( 'user/settings/settings' );
                    ?></form>
                </div>
            </div>
            <div class="eof"></div><?php
            $page->AttachInlineScript( 'Settings.OnLoad();' );
            $page->AttachInlineScript( 'Suggest.OnLoad();' );
        }
    }
?>
