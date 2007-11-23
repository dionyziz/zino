<?php
	function ElementArticleSmall( $article ) {
		global $user;
		global $page;
        global $xc_settings;
        
		$page->AttachStylesheet( 'css/articles.css' );
		
		?><div><br />
			<h2><a href="index.php?p=story&amp;id=<?php 
			echo $article->Id(); 
			?>"><?php
            Element( 'image', $article->Icon(), 100, 100, 'storyicon', '', $article->Title(), $article->Title() );
			echo htmlspecialchars( $article->Title() );
			?></a></h2><br />
			<small>από <?php
				$editors = $article->Editors();
				while( $editor = array_shift( $editors ) ) {
					Element( "user/static", $editor );
					if( count( $editors ) != 0 ) {
						echo ", ";
					}
				}
			?><span>, πριν <?php
			echo DateDistance( $article->SubmitDate() );
			?></span></small><br />
			<div class="summary"><?php
			echo $article->SmallStory();
			?></div>
			<div class="stuff"><?php
			if( $article->Category()->Name() != "" ) {
				?>στο <a href="?p=category&amp;id=<?php 
				echo $article->Category()->Id(); 
				?>"><?php
				echo $article->Category()->Name();
				?></a><span>, <?php
			}
			else {
				?><span><?php
			}
			echo $article->NumComments();
			?> σχόλι<?php
			echo( $article->NumComments() != 1 ? "α" : "o" );
			?><span>, <?php
			echo $article->PageViews();
			?> προβολ<?php
			echo( $article->PageViews() != 1 ? "ές" : "ή" );
			?></span></span></div>
			<div style="clear:both"></div>
		</div><?php
	}
?>
