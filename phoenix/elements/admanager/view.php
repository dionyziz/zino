<?php
    class ElementAdManagerView extends Element {
        public function Render( Ad $ad ) {
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
            <?php
        }
    }
?>
