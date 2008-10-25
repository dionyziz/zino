<?php
    class ElementUserSettingsSidebar extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            
            ?><ol id="settingslist">
                <li class="personal"><a href="" onclick="Settings.SwitchSettings( 'personal' );return false"><span>&nbsp;</span>Πληροφορίες</a></li>
                <li class="characteristics"><a href="" onclick="Settings.SwitchSettings( 'characteristics' );return false"><span>&nbsp;</span>Χαρακτηριστικά</a></li>
                <li class="interests"><a href="" onclick="Settings.SwitchSettings( 'interests' );return false"><span>&nbsp;</span>Ενδιαφέροντα</a></li>
                <li class="contact"><a href="" onclick="Settings.SwitchSettings( 'contact' );return false"><span>&nbsp;</span>Επικοινωνία</a></li>
                <li class="settings"><a href="" onclick="Settings.SwitchSettings( 'settings' );return false"><span>&nbsp;</span>Λογαριασμός</a></li>
            </ol>
            <div class="savesettings">
                <span class="saving invisible"><img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>ajax-loader.gif" alt="Αποθήκευση" /> Αποθήκευση...
                </span> 
                <span class="saved invisible"><span>&nbsp;</span>Οι αλλαγές σου αποθηκεύτηκαν</span>
                <a href="" class="savebutton button disabled">Αποθήκευση ρυθμίσεων</a>
            </div>
            <a class="backtoprofile button" href="<?php
            Element( 'user/url' , $user->Id , $user->Subdomain );
            ?>">Επιστροφή στο προφίλ<span>&nbsp;</span></a><?php
        }
    }
?>
