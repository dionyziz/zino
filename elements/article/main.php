<?php
	function ElementArticleMain( $articleid , $articletitle , $articleicon , $articletext , $articledate , 
								$articlepageviews , $articleeditors , $articlemodifyuser , $cid,
								$cicon , $cname , $commentsnum , $oldcomments ) {
        global $xc_settings;
        
		?><div class="header">
            <?php
            Element( 'image', $articleicon, 100, 100, "articleicon", '', $articletitle, $articletitle );
			?><h2><?php 
			echo htmlspecialchars( $articletitle ); 
			?></h2><br />
			<div>
				<small><?php
					foreach ( $articleeditors as $editor ) {
						Element( "user/icon", $editor, true, true );
                    }
					?><span class="description">από <?php
					while( $editor = array_shift( $articleeditors ) ) {
						Element( "user/static", $editor );
						if ( count( $articleeditors ) != 0 ) {
							echo ", ";
						}
					}
					?><span class="details">, πριν από <?php echo dateDistance( $articledate ); ?></span>
					</span>
					<br /><?php
					if ( $cid > 0 ) {
						?><a href="?p=category&amp;id=<?php
						echo $cid;
						?>"><?php
                        Element( 'image', $cicon, 20, 20, 'categoryicon' );
                        ?></a><?php
					}
					?><span class="description"><?php
						if ( $cid > 0 ) {
							?>στο <a href="?p=category&amp;id=<?php
							echo $cid;
							?>"><?php 
							echo $cname; 
							?></a>,<?php
						}
						?><span class="details">
						<span id="numcomments"><?php 
						echo $commentsnum; 
						if ( $commentsnum == 1 ) {
							?> σχόλιο<?php
						}
						else { 
							?> σχόλια<?php
						}
						if ( $oldcomments !== -1 ) {
							if ( $commentsnum > 50 && $oldcomments === false ) {
								?> <a href="?p=story&amp;id=<?php
								echo $articleid;
								?>&amp;oldcomments=yes">(προβολή όλων)</a><?php
							}
							else if ( $commentsnum > 50 ) {
								?> <a href="?p=story&amp;id=<?php
								echo $articleid;
								?>">(προβολή μόνο νεότερων)</a><?php
							}
						}
						?>, </span><?php 
						if ( $articlepageviews > 0 ) {
							echo $articlepageviews;
							if ( $articlepageviews == 1 ) { 
								?> προβολή <?php
							}
							else { 
								?> προβολές <?php
							} 
						}
						?></span>
					</span>
				</small>
				<br />
				<small class="toolbox"><?php
					if ( $articlemodifyuser ) { 
						?><form style="display:none" id="deleteform" action="do/article/delete" method="post">
							<input type="hidden" name="id" value="<?php
							echo $articleid;
							?>" />
						</form>
						<ul>
							<li><a href="?p=addstory&amp;id=<?php
							echo $articleid;
							?>"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/icon_wand.gif" /> Επεξεργασία</a></li>
							<li><a href="" onclick="if (confirm('Θέλεις σίγουρα να διαγράψεις αυτό το άρθρο;')) {document.getElementById('deleteform').submit();};return false;"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/page_cross.gif" /> Διαγραφή</a></li>
						</ul><?php
					}
				?></small>
			</div>
			<br />
		</div>
		<div class="body"><?php
			if ( is_array( $articletext ) ) {
				echo $articletext[ 0 ];
			}
			else {
				echo $articletext;
			}
		?></div><?php
	}
?>
