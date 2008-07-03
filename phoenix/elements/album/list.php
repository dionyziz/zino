<?php
	
	function ElementAlbumList( tText $username , tText $subdomain , tInteger $pageno ) {
		global $page;
		global $user;
		global $rabbit_settings;
		global $water;
		
        Element( 'user/subdomainmatch' );
        
		$username = $username->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			if ( strtolower( $username ) == strtolower( $user->Name ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindByName( $username );
			}
		}
		else if ( $subdomain != '' ) {
			if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindBySubdomain( $subdomain );
			}
		}	
		if ( !isset( $theuser ) || $theuser === false ) {
			return Element( '404' );
		}
		
		if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
			$page->SetTitle( $theuser->Name . " Albums" );
		}
		else {
			$page->SetTitle( $theuser->Name . " albums" );
		}

		$pageno = $pageno->Get();
		if ( $pageno <= 0 ) {
			$pageno = 1;
		}
		
		$finder = New AlbumFinder();
        $limit = 12;
		$albums = $finder->FindByUser( $theuser, ( $pageno - 1 ) * $limit, $limit );
		Element( 'user/sections', 'album' , $theuser );
		?><ul class="albums"><?php
			if ( $pageno == 1 ) {
				$egoalbum = New Album( $theuser->Egoalbumid );
				if ( $user->Id == $theuser->Id || $egoalbum->Numphotos > 0 ) {
					?><li><?php
					Element( 'album/small' , $egoalbum , false );
					?></li><?php
				}
			}
			foreach ( $albums as $album ) {
				if ( $album->User->Egoalbumid != $album->Id ) {
					if ( $user->Id == $theuser->Id || $album->Numphotos > 0 ) {
						?><li><?php
							Element( 'album/small' , $album , false );
						?></li><?php
					}
				}
			}
			if ( $theuser->Id == $user->Id ) {
				?><li class="create">
					<a href="" class="new"><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>add3.png" alt="Δημιουργία album" title="Δημιουργία album" />Δημιουργία album</a>
				</li><?php
			}
		?></ul><?php
		if ( $theuser->Id == $user->Id ) {
			?><div class="creationmakeup"><?php
				Element( 'album/small' , false , true );
			?></div>
			<div class="creating">
				<img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" /> Δημιουργία album
			</div><?php
		}
		?><div class="eof"></div>
		<div class="pagifyalbums"><?php

        ob_start();
        Element( 'user/url', $theuser );
        $link = ob_get_clean() . '/albums&pageno=';
        $total_pages = ceil( $theuser->Count->Albums / $limit );
		Element( 'pagify', $pageno, $link, $total_pages );

		?></div><?php
	}
?>
