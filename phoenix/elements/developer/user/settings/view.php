<?php
    class ElementDeveloperUserSettingsView extends Element {
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
                <div class="sidebar"><?php
                    Element( 'developer/user/settings/sidebar' );
                ?></div>
                <div class="tabs">
                    <form id="personalinfo" action="" style="display:none"><?php
                        Element( 'developer/user/settings/personal/view' );
                    ?></form>
                    <form id="characteristicsinfo" action="" style="display:none"><?php
                        Element( 'developer/user/settings/characteristics/view' );
                    ?></form>
                    <form onsubmit="return false" id="interestsinfo" action="" style="display:none"><?php
                        Element( 'developer/user/settings/interests' );
                    ?></form>
                    <form id="contactinfo" action="" style="display:none"><?php
                        Element( 'developer/user/settings/contact' );
                    ?></form>
                    <form id="settingsinfo" action="" style="display:none"><?php
                        Element( 'developer/user/settings/settings' );
                    ?></form>
                </div>
            </div>
            <div class="eof"></div><?php
            $page->AttachInlineScript( 'Settings.SettingsOnLoad();' );
            $page->AttachInlineScript( 'Suggest.OnLoad();' );
        }
    }
?>
