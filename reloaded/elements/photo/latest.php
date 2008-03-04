<?php
    function ElementPhotoLatest() {
        global $libs;
        global $page;
        global $xc_settings;
		global $user;
        
        $libs->Load( 'search' );
        $libs->Load( 'image/image' );
        
        $page->AttachStylesheet( 'css/images.css' );
        $page->AttachScript( 'js/bumpstrip.js' );
		$page->AttachStyleSheet( 'css/frontpage.css' );
        
		$latest = Image_LatestUnique( 14 );
		
        ?><div class="bumpstrip" id="bumpstrip"><div class="strip">
            <?php
                foreach ($latest as $image) {
            		$dimensions = $image->ProportionalSize( 100 , 100 );
                    ?><div><a href="?p=photo&amp;id=<?php
                    echo $image->Id();
                    ?>"><?php
					$style = 'width:'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
					$photoname = htmlspecialchars( $image->Name() );
					Element( 'image' , $image , $dimensions[ 0 ] , $dimensions[ 1 ] , '' , $style , $photoname , $photoname );
                    if ( $image->NumComments() ) {
                        ?><span><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/comment_blue.gif" alt="Σχόλια" title="Σχόλια" /><?php
                        echo $image->NumComments();
                        ?></span><?php
                    }
                    ?></a></div><?php
                }
            ?>
        </div></div><?php
    }
?>
