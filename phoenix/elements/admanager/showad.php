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
            
            ?>
            <div class="ad">
                <h4><a href="http://<?php
                echo htmlspecialchars( $ad->Url );
                ?>"><?php
                echo htmlspecialchars( $ad->Title );
                ?></a></h4><?php
                if ( $ad->Imageid ) {
                    ?><a href="http://<?php
                    echo htmlspecialchars( $ad->Url );
                    ?>"><?php
                    Element( 'image/view', $ad->Imageid, $ad->Userid, $ad->Image->Width, $ad->Image->Height, 
                             IMAGE_FULLVIEW, '', $ad->Title, '', false, 0, 0, 0 );
                    ?></a><?php
                }
                ?><p><a href="http://<?php
                echo htmlspecialchars( $ad->Url );
                ?>"><?php
                echo htmlspecialchars( $ad->Body );
                ?></a></p>
            </div>
            <!-- <h3><a href="?p=ads">Διαφημιστείτε στο Zino</a></h3>-->
            <?php
            
            $ad->WasShown();
        }
    }
?>
