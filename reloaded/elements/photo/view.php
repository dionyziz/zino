<?php
	function ElementPhotoView( tInteger $id, tBoolean $oldcomments ) {
		global $page;
		global $libs;
		global $water;
		global $user;
		global $rabbit_settings;
		
        $photoid = $id->Get();
        $oldcomments = $oldcomments->Get();
        
		if ( !ValidId( $photoid ) ) {
			$page->SetTitle( 'Λάθος φωτογραφία' );
			return;
		}
		$libs->Load( 'image/image' );
		$libs->Load( 'search' );
		$libs->Load( 'comment' );
		$page->AttachStyleSheet( 'css/photos.css' );
		
		$photo = New Image( $photoid );
		if ( $photo->AlbumId() == 0 ) {
			?>Αυτή η φωτογραφία δεν ανήκει σε κανένα album.<?php
			return;
		}
		$photoname = NoExtensionName( $photo->Name() );
		$page->SetTitle( $photoname );
		$page->AttachScript( 'js/photos.js' );
		$photodescription = htmlspecialchars( $photo->Description() );
		$propsize = $photo->ProportionalSize( 900 , 700 );
		$commentsnumber = $photo->NumComments();
		
		$water->Trace( 'comments number: '.$commentsnumber );
		
		$pageviews = $photo->Pageviews();
		++$pageviews;
		if ( $commentsnumber == 1 ) {
			$commentstext = ' σχόλιο';
		}
		else {
			$commentstext = ' σχόλια';
		}
		if ( $pageviews == 1 ) {
			$pageviewstext = ' προβολή';
		}
		else {
			$pageviewstext = ' προβολές';
		}
		if ( !isset( $args[ 'lstoffset' ] ) ) {
			$offset = 1;
		}
		else {
			$offset = $args[ 'lstoffset' ];
		}
		$photo->AddPageview();
		?><br /><br /><br />
		<div class="photobigview" id="onephotoview"><?php
			Element( 'photo/header' , $photo , $oldcomments );
			?><br /><br />
			<div style="text-align:center;"><?php
				$style = 'width:' . $propsize[ 0 ] . 'px;height:' . $propsize[ 1 ] . 'px;';
				Element( 'image' , $photo , $propsize[ 0 ] , $propsize[ 1 ] , 'thisphotoview' , $style , $photoname , $photoname );
				?>
			</div><br /><br />
			<a href="index.php?p=album&amp;id=<?php
			echo $photo->AlbumId();
			?>&amp;offset=<?php
			echo $offset;
			?>" class="photolinks">&#171;Επιστροφή</a>
			<br /><br /><br />
			<div class="comments" id="comments"><?php
				$search = New Search_Comments();
				$search->SetFilter( 'typeid', 2 ); // 0: article, 1: userspace 2:photos
				$search->SetFilter( 'page', $photo->Id() ); //show all comments of an article 
				$search->SetFilter( 'delid', 0 ); // do not show deleted comments
				$search->SetSortMethod( 'date', 'DESC' ); //sort by date, newest shown first
				if ( $oldcomments ) {
					$search->SetLimit( 10000 );
				}
				else {
					$search->SetLimit( 50 );  //show no more than 50 comments
				}
				$comments = $search->GetParented( true ); //get comments
                Element( 'comment/import' );
				Element( 'comment/reply' , $photo , 2 );
				Element( 'comment/list' , $comments , 0 , 0 );
			?></div>
		</div><br />
		<a href="index.php?p=advertise">&#187;Διαφημιστείτε στο <?php
		echo $rabbit_settings[ 'applicationname' ];
		?></a><br /><br /><?php
        Element( "ad/leaderboard" );
	}
?>
