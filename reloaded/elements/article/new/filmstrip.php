<?php
	function ElementArticleNewFilmstrip( $onlyalbum ) {
		global $libs;
		global $page;
        global $user; 
		global $xc_settings;

		if ( $user->Rights() < $xc_settings[ 'allowuploads' ] ) {
			return false;
		}
		
		$libs->Load( 'image/image' );
		$libs->Load( 'search' );
        $page->AttachStylesheet( 'css/images.css' );
        
		?><div class="filmstrip" id="filmstrip"><div class="strip"><?php
		
        $search = new Search_Images_Latest( $user->Id() , $onlyalbum );
        $latest = $search->Get();
		
		foreach ( $latest as $image ) {
			?><div>
				<a onclick="NewArticle.Stag('[merlin:img <?php
					echo $image->Id();
					?>]');return false;" alt="" title=""><?php
					$propsize = $image->ProportionalSize( 100 , 100 );
					Element( 'image' , $image , $propsize[ 0 ] , $propsize[ 1 ] , '' , '' , '' , '' );
					?><span><?php
					echo $image->Id();
					?></span>
				</a>
			</div><?php
		}
		
		?></div></div>
		<?php
	}
?>
