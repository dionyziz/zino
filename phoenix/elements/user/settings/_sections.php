<?php
    class ElementUserSettingsSidebar extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            
            ?><ul id="settingslist">
                <li class="personal"><a href="" onclick="Settings.SwitchSettings( 'personal' );return false"><span class="s1_0036">&nbsp;</span>Πληροφορίες</a></li>
                <li class="characteristics"><a href="" onclick="Settings.SwitchSettings( 'characteristics' );return false"><span class="s1_0039">&nbsp;</span>Χαρακτηριστικά</a></li>
                <li class="interests"><a href="" onclick="Settings.SwitchSettings( 'interests' );return false"><span class="s1_0037">&nbsp;</span>Ενδιαφέροντα</a></li>
                <li class="contact"><a href="" onclick="Settings.SwitchSettings( 'contact' );return false"><span class="s1_0040">&nbsp;</span>Επικοινωνία</a></li>
                <li class="account"><a href="" onclick="Settings.SwitchSettings( 'account' );return false"><span class="s1_0038">&nbsp;</span>Λογαριασμός</a></li>
                <li class="savesettings"><a href="" onclick="return false"><span class="s1_0045">&nbsp;</span>Αποθήκευση ρυθμίσεων</a></li>
                <li class="backtoprofile"><a href="" onclick="return false"><span class="s1_0018">&nbsp;</span>Επιστροφή στο προφίλ</a></li>
            </ul><?php
        }
    }
?>
