<?php
	function ElementArticleView( tInteger $id, tBoolean $oldcomments ) {
		global $user;
		global $water;
		global $libs;
		global $page;
		
        $id = $id->Get();
        $oldcomments = $oldcomments->Get();

		$libs->Load( 'search' );
		$libs->Load( 'article' );
		$libs->Load( 'comment' );
		$page->AttachStyleSheet( 'css/article.css' );
		
		$article = New Article( $id );
		if ( !$article->Exists() ) {
            $water->Notice( 'Article doesn\'t exist' , array( $arguments , $id , $oldcomments ) );
            return;
		}

		$articleid = $article->Id();
		$articletitle = $article->Title();
		$articleicon = $article->Icon();
		$articletext = $article->Text();
		$articledate = $article->Submitdate();
		$articlepageviews = $article->Pageviews();
		$articleeditors = $article->Editors();
		$articlemodifyuser = $article->CanModify( $user );
		$commentsnum = $article->NumComments();
		$article->AddPageView();
		$cid = $article->Category()->Id();
		$cname = $article->Category()->Name();
		$cicon = $article->Category()->Icon();
		
		$page->SetTitle( $articletitle );
		?><br />
		<div class="article"><?php
			Element( 'article/main' , $article, $articleeditors, $articlemodifyuser, $oldcomments );
		?><br />
			<div class="tabs">
			</div>
			<br />
			<a href="index.php?p=advertise">&#187;Διαφημιστείτε στο chit-chat</a><br /><br /><?php
            Element( "ad/leaderboard" );
            ?><br />
			<div class="comments" id="comments"><?php	
				$search = New Search_Comments();
				$search->SetFilter( 'typeid', 0 ); // 0: article, 1: userspace
				$search->SetFilter( 'page', $article->Id() ); //show all comments of an article 
				$search->SetFilter( 'delid', 0 ); // do not show deleted comments
				$search->SetSortMethod( 'date', 'DESC' ); //sort by date, newest shown first
				if ( $oldcomments ) {
					$search->SetLimit( 10000 );
				}
				else {
					$search->SetLimit( 50 );  //show no more than 50 comments
				}
				$comments = $search->GetParented(); //get comments
				
                Element( 'comment/import' );
				Element( 'comment/list' , $comments , 0 , 0 );
				Element( 'comment/reply', $article, 0 );
			?></div>
		</div><?php
	}
?>
