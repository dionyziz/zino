<?php
	function ActionArticleEditoradd( tInteger $id, tString $u ) {
    	global $libs;
		global $user;
    	
    	$libs->Load( 'article' );
		$libs->Load( 'user' );
		
		$articleid = $id->Get();
		$username = $u->Get();
		
		$article = New Article( $articleid );
		if ( !$article->CanModify( $user ) ) {
			die( 'Insufficient permissions to modify article' );
		}
		
		if ( !( $theuser = New User( $username ) ) ) {
			die( "No such user" );
		}
		
		$storycreated = $article->EditorAdd( $theuser );
		if ( $storycreated < 1 ) {
			switch ( $storycreated ) {
				case -1:
					// no priviledges
					die( 'Insufficient permissions to modify article' );
				case -2:
					die( "Error while creating article (-2)" );
				case -3:
					die( "No such user" );
				case -4:
					die( "This user is already an editor" );
				default:
					die( "Error while creating article (" . $storycreated . ")" );
					break;
			}
		}
		return Redirect( '?p=story&id=' . $storycreated );
	}
?>