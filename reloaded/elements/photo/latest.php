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
		$page->AttachStyleSheet( 'css/profileview.css' );
        
		$latest = Image_LatestUnique( 15 );
		
		if ( $user->Id() == 58 || $user->Id() == 1 ) {
        ?><ul class="photolist">
			<div class="upperslide">			
				<div class="edge"><?php
					$image = $latest[ 9 ];
					$dimensions = $image->ProportionalSize( 100 , 100 );
					?><li><a href="?p=photo&amp;id=<?php
					echo $image->Id();
					?>"><?php
					$style = 'width'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
					$photoname = htmlspecialchars( $image->Name() );
					Element( 'image' , $image , $dimensions[ 0 ] , $dimensions[ 1 ], '' , $style, $photoname, $photoname );
					if ( $image->NumComments() ) {
                        ?><span><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/comment_blue.gif" alt="Σχόλια" title="Σχόλια" /><?php
                        echo $image->NumComments();
                        ?></span><?php
                    }
				?></a></li></div><?php
				for ( $i = 0; $i < 9; ++$i ) {
					$image = $latest[ $i ];
					$dimensions = $image->ProportionalSize( 100 , 100 );
					?><li><a href="?p=photo&amp;id=<?php
					echo $image->Id();
					?>"><?php
					$style = 'width'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
					$photoname = htmlspecialchars( $image->Name() );
					Element( 'image' , $image , $dimensions[ 0 ] , $dimensions[ 1 ], '' , $style, $photoname, $photoname );
					if ( $image->NumComments() ) {
                        ?><span><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/comment_blue.gif" alt="Σχόλια" title="Σχόλια" /><?php
                        echo $image->NumComments();
                        ?></span><?php
                    }
					?></a></li><?php
				}
				?>
			</div>
			<div class="rightslide"><?php
				for ( $i = 10; $i <= 14; ++$i ) {
					$image = $latest[ $i ];
					$dimensions = $image->ProportionalSize( 100 , 100 );
					?><li><a href="?p=photo&amp;id=<?php
					echo $image->Id();
					?>"><?php
					$style = 'width'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
					$photoname = htmlspecialchars( $image->Name() );
					Element( 'image' , $image , $dimensions[ 0 ] , $dimensions[ 1 ] , '' , $style , $photoname , $photoname );
					if ( $image->NumComments() ) {
						?><span><img src="<?php
						echo $xc_settings[ 'staticimagesurl' ];
						?>icons/comment_blue.gif" alt="Σχόλια" title="Σχόλια" /><?php
						echo $image->NumComments();
						?></span><?php
					}
					?></a></li><?php
				}
				?>
			</div>
		</ul><?php
		}
		else {
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
                        ?>icons/comment_blue.gif" alt="������" title="������" /><?php
                        echo $image->NumComments();
                        ?></span><?php
                    }
                    ?></a></div><?php
                }
            ?>
        </div></div><?php
		}
    }
?>
