<?php
	function ElementArticleArchive( tInteger $offset ) {
		global $libs;
		global $water;
		global $page;
		
        $offset = $offset->Get();
        
		$page->SetTitle( 'Άρθρα' );
		$page->AttachStyleSheet( 'css/articles.css' );
		$libs->Load( 'search' );
		$libs->Load( 'article' );
		$length = 20;
		if ( !ValidId( $offset ) ) {
			$offset = 1;
		}
		$testing = $offset*$length - $length;
		$search = New Search_Articles();
	    $search->SetSortMethod( 'date', 'DESC' );
		$search->SetFilter( 'typeid', 0 );
		$search->SetFilter( 'delid', 0 );
		$search->SetNegativeFilter( 'category', 0 );
		$search->SetLimit( 20 );
		$search->SetOffset( $testing );
		$search->NeedTotalLength( true );
		$pagearticles = $search->Get();
		
		$totalarticles = $search->Length();
		$pages = $totalarticles / 20;
		if ( $pages % 20 != 0 ) {
			++$pages;
		}
		if ( $offset < 0 || $offset > $pages ) {
			return;
		}
		if ( $offset == 0 ) {
			$offset = 1 ;
		}
		?><br /><br /><br /><br /><span style="color: #4c4845;font-size: 12pt;font-family: tahoma;font-weight: bold;">Άρθρα</span>
		<div class="articles newestarticles" style="margin-left:40px;"><?php
		
		Article_FormatSmallMulti( $pagearticles );
		
		foreach ( $pagearticles as $article ) {
			Element( 'article/small' , $article );
		}
		?></div><br /><div style="text-align:center;"><?php
		Element( 'pagify' , $offset , 'allarticles' , $totalarticles , 20 );
		?></div><?php
	}
?>