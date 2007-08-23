<?php
	function ActionArticleRevert( tInteger $id, tInteger $r ) {
    	global $libs;
		global $user;
    	
    	$libs->Load( 'article' );
		$articleid = $id->Get();
		$revision = $r->Get();
		
		$article = New Article( $articleid, $revision );
		if ( !$article->CanModify( $user ) ) {
			die( 'Insufficient permissions to modify article' );
		}
		$storycreated = $article->Revert();
		if ( $storycreated < 1 ) {
			switch ( $storycreated ) {
				case -1:
					// no priviledges
					die( 'Insufficient permissions to modify article' );
				case -2:
					die( "Error while creating article (-2)" );
				case -3:
					//already at head revision
					die( "Cannot revert. Already at this revision." );
				default:
					die( "Error while creating article (" . $storycreated . ")" );
					break;
			}
		}
		return Redirect( '?p=story&id=' . $storycreated );
	}
?>
