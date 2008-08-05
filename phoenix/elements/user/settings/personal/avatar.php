<?php

    class ElementUserSettingsPersonalAvatar extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            //$libs->Load( 'image' );
            Element( 'user/avatar' , $user , 150 , 'avie' , '' );
            ?><div class="changeavatar">
            <a href="" onclick="Settings.ShowAvatarChange();return false;">Αλλαγή εικόνας</a>
            </div>
            <div class="avatarlist" id="avatarlist">
                <h3>Επέλεξε μια φωτογραφία</h3>
                <div class="uploaddiv">
                    <?php
                    if ( UserBrowser() == 'MSIE' ) {
                        ?>
                        <iframe border="0" style="height: 50px;" src="?p=upload&amp;albumid=<?php
                        echo $user->Egoalbumid;
                        ?>&amp;typeid=1" class="uploadframe" id="uploadframe">
                        </iframe>
                        <?php
                    }
                    else {
                        ?>
                        <object style="height:250px;" data="?p=upload&amp;albumid=<?php
                        echo $user->Egoalbumid;
                        ?>&amp;typeid=1" class="uploadframe" id="uploadframe" type="text/html">
                        </object>
                        <?php
                    }
                    ?>
                </div><?php
                $egoalbum = New Album( $user->Egoalbumid );
                $finder = New ImageFinder();
                ?><ul><?php    
                if ( $egoalbum->Numphotos > 0 ) {
                    $images = $finder->FindByAlbum( $egoalbum , 0 , $egoalbum->Numphotos );
                    foreach ( $images as $image ) {    
                        ?><li><?php
                        Element( 'user/settings/personal/photosmall' , $image );
                        ?></li><?php
                    }
                }
                ?></ul>
                <div class="cancel">
                    <a href="" onclick="Modals.Destroy();return false;" class="button">
                    Ακύρωση
                    </a>
                </div>
            </div><?php
        }
    }
?>
