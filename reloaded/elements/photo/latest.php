<?php
    function ElementPhotoLatest() {
        global $libs;
        global $page;
        global $xc_settings;
        
        $libs->Load( 'search' );
        $libs->Load( 'image/image' );
        
        $page->AttachStylesheet( 'css/images.css' );
        $page->AttachScript( 'js/bumpstrip.js' );
        
        /*$search = new Search_Images_Latest( 0 , true );
        $search->SetLimit( 12 );
        $latest = $search->Get();*/
		
		$latest = Image_LatestUnique( 12 );
        
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
					/*
					<img src="image.php?id=<?php
                    echo $image->Id();
                    ?>&amp;width=<?php
                    echo $dimensions[ 0 ];
                    ?>&amp;height=<?php
                    echo $dimensions[ 1 ];
                    ?>" style="width:<?php
                    echo $dimensions[ 0 ];
                    ?>px;height:<?php
                    echo $dimensions[ 1 ];
                    ?>px" alt="<?php
                    echo htmlspecialchars( $image->Name() );
                    ?>" title="<?php
                    echo htmlspecialchars( $image->Name() );
                    ?>" />
					*/
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
