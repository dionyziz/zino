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
		
		?><div class="articles populararticles"><ul style="list-style:none"><?php
			$i = 0;
			?><h4>Τα παιχνίδια μας</h4><?php	
			foreach ( $popular as $article ) {
				if ( $i > 3 )
					break;
				if ( isset( $latestids[ $article->Id() ] ) ) { // TODO: this should be done using negative filters on the search
					continue;
				}
				?><li><a href="?p=story&amp;id=<?php
				echo $article->Id();
				?>">&raquo;<?php
				echo htmlspecialchars( $article->Name() );
				?></a></li><?php
				++$i;
			}
		?><li style="display:inline;"><a href="?p=story&amp;id=goutsou">&raquo;Λετ δε γκέιμ μπεγκίν!</a></li>
		<li style="display:inline;"><a href="?p=story&amp;id=skata">&raquo;Παιχνίδι Συνειρμών</a></li></ul></div><?php
	}

?>