<?php
    class ElementDeveloperAlbumPhotoSmall extends Element {
        public function Render( $image , $showdesc = false ,  $showcomnum = false ) {
            if ( $image->Name != '' ) {
                $title = htmlspecialchars( $image->Name );
            }    
            else {
                $title = htmlspecialchars( $image->Album->Name );
            }
            ?><div class="photo">
                <a href="?p=photo&amp;id=<?php
                echo $image->Id;
                ?>"><?php
                    Element( 'image/view', $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_PROPORTIONAL_210x210, '' , $title , '' , false , 0 , 0 , 0 ); 
                    if ( $showdesc && $image->Name != '') {
                        ?><br /><?php
                        echo htmlspecialchars( $image->Name );
                    }
                ?></a><?php
                if ( $showcomnum ) {
                    ?><div><?php
                        if ( $image->Numcomments > 0 ) {
                            ?><span class="small1"><span class="s1_0027">&nbsp;</span><?php
                            echo $image->Numcomments;
                            ?></span><?php
                        }
                    ?></div><?php
                }
            ?></div><?php
        }
    }
?>
