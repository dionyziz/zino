<?php
    
    class ElementUserProfileMainPhotos extends Element {
        public function Render( $images , $egoalbum , $theuserid ) {
            global $water;
            global $user;
          
            ?><embed src="multifileUploader.swf" quality="high" pluginspage="http://www.adobe.com/go/getflashplayer" play="true" loop="true" scale="showall" wmode="window" devicefont="false" bgcolor="#ffffff" name="multifileUploader" menu="true" allowfullscreen="false" allowscriptaccess="sameDomain" salign="" type="application/x-shockwave-flash" align="middle" height="400" width="550"> 
<noscript>

			<ul class="lst ul1 border"><?php
                if ( $user->Id == $theuserid && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                   ?><li class="addphoto"><a href="" class="s1_0048" title="Ανέβασε μια φωτογραφία">&nbsp;</a></li><?php
                }
                foreach( $images as $image ) {
                    ?><li><a href="?p=photo&amp;id=<?php
                    echo $image->Id;
                    ?>"><?php
                    Element( 'image/view' , $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , '' , false , 0 , 0 , $image->Numcomments );
                    ?></a></li><?php
                }
            ?></ul><?php    
        }
    }
?>
