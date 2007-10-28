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
		
		?><div class="articles populararticles">
		<h4 style="display:inline;margin-right: 7px;">Τα παιχνίδια μας:</h4>
		<ul style="display:inline;list-style:none;margin:0;padding:0;"><?php
			$i = 0;
			?><?php	
			foreach ( $popular as $article ) {
				if ( $i > 3 )
					break;
				if ( isset( $latestids[ $article->Id() ] ) ) { // TODO: this should be done using negative filters on the search
					continue;
				}
				?><li style="margin-right: 7px;"><a href="?p=story&amp;id=<?php
				echo $article->Id();
				?>"><?php
				echo htmlspecialchars( $article->Name() );
				?></a></li><?php
				++$i;
			}
		?><li style="display:inline;padding: 0 10px 0 0;"><a href="?p=story&amp;id=goutsou">Λετ δε γκέιμ μπεγκίν!</a></li>
		<li style="display:inline;padding: 0 10px 0 0;"><a href="?p=story&amp;id=skata">Παιχνίδι Συνειρμών</a></li></ul></div><?php
	}

?>