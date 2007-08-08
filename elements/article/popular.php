<?php
	function ElementArticlePopular( $latestids ) {
		global $page;
		global $libs;
		
		$libs->Load( 'search' );
		$libs->Load( 'article' );
		$libs->Load( 'pageviews' );
		
		
		$search = New Search_Articles();
		$search->SetSortMethod( 'popularity', 'DESC' );
		$search->SetFilter( 'typeid', 0 );
		$search->SetFilter( 'delid', 0 );
		$search->SetNegativeFilter( 'category', 0 );
		$search->SetRequirement( 'text' );
		$search->SetRequirement( 'pageviews' );
		$search->SetRequirement( 'editors' );
		$search->SetLimit( 10 );
		$popular = $search->Get();
		
		?><div class="articles populararticles"><?php
			$i = 0;
			
			Article_FormatSmallMulti( $popular );
			
			foreach ( $popular as $article ) {
				if ( $i > 3 )
					break;
				if ( isset( $latestids[ $article->Id() ] ) ) { // TODO: this should be done using negative filters on the search
					continue;
				}
				Element( "article/small", $article );
				++$i;
			}
		?></div><?php
	}

?>