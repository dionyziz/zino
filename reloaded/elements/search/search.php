<?php
	function ElementSearchSearch( tString $q ) { 
    
        return false;

		global $page;
		global $water;
		global $libs;
		
		$libs->Load( 'search' );
		$libs->Load( 'article' );
		$libs->Load( 'comment' );
		$libs->Load( 'magic' );
		$libs->Load( 'userspace' );
		
		$page->SetTitle( 'Αναζήτηση ' . $q->Get() );
		$page->AttachStyleSheet( 'css/search.css' );
		
		$q = $q->Get();
		
		$searchterm = $q;
		
		if ( !empty( $q ) ) {	
			$search = New Search_Articles();
			$search->SetSortMethod( 'date', 'DESC' );
			$search->SetFilter( 'content', $searchterm );
			$search->SetFilter( 'delid', 0 );
			$search->SetFilter( 'typeid', 0 );
			$search->SetNegativeFilter( 'category', 0 );
			$search->SetLimit( 20 );
			$articles = $search->Get();
			
			$search = New Search_Comments();
			$search->SetSortMethod( 'date', 'DESC' );
			$search->SetNegativeRequirement( 'comment_parentid' );
			$search->SetNegativeRequirement( 'comment_created' );
			$search->SetFilter( 'body', $searchterm );
			$search->SetFilter( 'delid', 0 );
			$search->SetFilter( 'typeid', 0 );
			$search->SetLimit( 20 );
			$comments = $search->Get();
			
			foreach ( $comments as $comment ) {
				$articleids[] = $comment->PageId();
			}
			
			$articles = Article_ById( $articleids );
			
			foreach ( $comments as $comment ) {
				$comment->SetPage( $articles[ $comment->PageId() ] );
			}
			
			$search = New Search_Userspaces();
			$search->SetSortMethod( 'date', 'DESC' );
			$search->SetFilter( 'delid', 0 );
			$search->SetFilter( 'body', $searchterm );
			$search->SetRequirement( 'userid' );
			$search->SetRequirement( 'username' );
			$search->SetRequirement( 'body' );
			$search->SetLimit( 20 );
			$userspaces = $search->Get();
			
			$suser = Search_User( $q );
		}
		
		?><br /><br /><br /><br />
		<br /><br /><br /><br />	
		<div class="searching">
			<span class="searchcat">Αναζήτηση στο Zino</span>
			<div style="width:48%">
				<div class="upperline">
					<div class="leftupcorner"></div>
					<div class="rightupcorner"></div>
					<div class="middle"></div>
				</div>
				<div class="registeropts">
					<form action="index.php" method="get" id="searchform">
						<input type="hidden" name="p" value="search" />
						<input type="text" name="q" size="35" value="<?php
						echo htmlspecialchars( $q );
						?>" /> &nbsp;&nbsp;&nbsp;<a onclick="this.parentNode.submit();return false;" class="next" href="">Αναζήτηση >></a>
					</form>
				</div>
				<div class="downline">
					<div class="leftdowncorner"></div>
					<div class="rightdowncorner"></div>
					<div class="middledowncss"></div>
				</div>
			</div>
			<br /><br /><?php
			
			if ( count( $articles ) == 0 && count( $comments ) == 0 && count( $userspaces ) == 0 ) { // no results at all!
				?>Η αναζήτηση - <?php
				echo htmlspecialchars( $q );
				?> - δε βρήκε κάποιο αποτέλεσμα.<?php
			}
			
			else if ( !empty( $q ) ) {
				if ( $suser->Exists() ) {
					?><div style="margin-top: 10px; margin-bottom: 20px;">
						Μήπως ψάχνεις τον χρήστη <?php
							Element( 'user/static', $suser, true, true );
						?>;</div><?php
				}
				
				?><div class="searchresults">
					<span class="searchcat">Αναζήτηση στα άρθρα</span>
					<div class="sarticles">
						<div class="articles newestaticles"><?php
							$water->trace( 'results number: '. count( $articles ) );
							if ( count( $articles ) == 0 ) {
								?><br />Δεν βρέθηκε κανένα άρθρο με τα κριτήρια αναζήτησής σου<?php
							}
							Article_FormatSmallMulti( $articles );
							foreach ( $articles as $article ) {
								Element( 'article/small' , $article ); 
							}
						?></div>
					</div>
					<br /><br />
					<span class="searchcat">Αναζήτηση στα σχόλια</span><br /><br />
					<div class="scomments"><?php
						if ( count( $comments ) == 0 ) {
							?><br />Δεν βρέθηκε κανένα σχόλιο με τα κριτήρια αναζήτησής σου<?php
						}
						Comment_FormatSearchMulti( $comments, $searchterm );
						foreach ( $comments as $comment ) {
							$text = $comment->SearchText();
							if( $comment->Page() != false ) { // If the page in which the comment was made is not deleted
								?><span class="thearticle">Στο άρθρο</span> <a href="?p=story&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>" class="articlename"><?php
								echo $comment->Page()->Title();
								?></a><br />
								<span class="includedtext">...<?php
								echo $text;
								?>...</span>
								<br /><br /><?php
							}
						}
					
					?></div>
					
					<span class="searchcat">Αναζήτηση στους χώρους</span><br /><br />
					<div class="sblogs"><?php
						if ( count( $userspaces ) == 0 ) {
							?><br />Δεν βρέθηκε κανένας χώρος με τα κριτήρια αναζήτησής σου<?php
						}
						
						Userspace_FormatSearchMulti( $userspaces, $searchterm );
						foreach ( $userspaces as $userspace ) {
							$text = $userspace->SearchText();
							?><span class="thearticle">Στο προφίλ τ<?php
								if ( $userspace->User()->Gender() == "female" ) {
									?>ης <?php
								}
								else {
									?>ου <?php
								}
								
							?></span> <a href="user/<?php
							echo $userspace->User()->Username(); 
							?>" class="articlename"><?php
							echo $userspace->User()->Username();
							?></a><br />
							<span class="includedtext">...<?php
							echo $text;
							?>...</span><br /><br /><?php
						}
						
					?></div>
					
				</div><?php
			}
		?></div><?php
	}
?>
