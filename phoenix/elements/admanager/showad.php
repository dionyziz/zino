<?php
    class ElementAdManagerShowAd extends Element {
        public function Render() {
            global $libs;
            global $xc_settings;
            
            $libs->Load( 'admanager' );
            $libs->Load( 'image/image' );
            
            $adfinder = New AdFinder();
            $ad = $adfinder->FindToShow();
            
            if ( $ad === false ) {
                return;
            }
            
            Element( 'admanager/view', $ad );
            
            ?>
            <!-- <h3><a href="?p=ads">Διαφημιστείτε στο Zino</a></h3>-->
            <?php
            
            $ad->WasShown();
        }
    }
?>
