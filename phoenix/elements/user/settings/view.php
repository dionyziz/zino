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
                </div>
                <div id="settingsloader">
                    <img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" /> Φόρτωση Ρυθμίσεων...
                </div>
            </div>
            <div class="eof"></div><?php
            $page->AttachInlineScript( 'Settings.OnLoad();' );
            $page->AttachInlineScript( 'Suggest.OnLoad();' );
        }
    }
?>
