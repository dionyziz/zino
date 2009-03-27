<?php
    class ElementAdManagerView extends Element {
        public function Render( Ad $ad, $clickable = true ) {
            ob_start();
            ?><a<?php
            if ( $ad->Url ) {
                ?> href="http://<?php
                echo htmlspecialchars( $ad->Url );
                ?>"<?php
                if ( !$clickable ) {
                    ?> onclick="return false;"<?php
                }
            }
            ?>><?php
            $anchorstart = ob_get_clean();
            ?><div class="ad">
                <h4><?php
                echo $anchorstart;
                echo htmlspecialchars( $ad->Title );
                ?></a></h4><?php
                if ( $ad->Imageid ) {
                    echo $anchorstart;
                    Element( 'image/view', $ad->Imageid, $ad->Userid, $ad->Image->Width, $ad->Image->Height, 
                             IMAGE_FULLVIEW, '', $ad->Title, '', false, 0, 0, 0 );
                    ?></a><?php
                }
                ?><p><?php
                echo $anchorstart;
                echo htmlspecialchars( $ad->Body );
                ?></a></p>
            </div>
            <?php
        }
    }
?>
