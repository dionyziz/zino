<?php
    
	function ActionArticleNew( tString $name, tString $articlehtml, tInteger $categoryid, tInteger $eid, tInteger $icon, tBoolean $showemoticons, tBoolean $preview, tBoolean $minor, tString $comment ) {
    	global $libs;
		global $user;
    	
    	$libs->Load( 'article' );
    	
    	$name			= $name->Get();
    	$articlehtml    = $articlehtml->Get();
    	$categoryid		= $categoryid->Get();
    	$eid			= $eid->Get();
    	$icon			= $icon->Get();
    	$showemoticons  = $showemoticons->Get();
    	$preview 		= $preview->Get();
    	$minor			= $minor->Get();
		$comment		= $comment->Get();
    	
    	if ( $preview ) {
    		$_SESSION[ 's_sname' ] 			= $name;
    		$_SESSION[ 's_sarticlehtml' ]   = $articlehtml;
    		$_SESSION[ 's_scategoryid' ] 	= $categoryid;
    		$_SESSION[ 's_seid' ] 			= $eid;
    		$_SESSION[ 's_sicon' ] 			= $icon;
    		$_SESSION[ 's_sshowemoticons' ] = $showemoticons;
            return Redirect( '?p=addstory&id=' . $eid . '&preview=yes' );
    	}
    	
    	$name = strip_tags( $name );

    	if ( !merlin_valid( $story ) || !merlin_valid( $name ) ) {
    		$_SESSION[ 's_sname' ] 			= $name;
    		$_SESSION[ 's_sarticlehtml' ]   = $articlehtml;
    		$_SESSION[ 's_scategoryid' ] 	= $categoryid;
    		$_SESSION[ 's_seid' ] 			= $eid;
    		$_SESSION[ 's_sicon' ] 			= $icon;
    		$_SESSION[ 's_sshowemoticons' ] = $showemoticons;
    		return Redirect( '?p=addstory&id=' . $eid . '&preview=yes' );
    	}
    	else {
    		if ( $eid != 0 ) {
    			$article = New Article( $eid );
    			if ( !$article->CanModify( $user ) ) {
    				die( 'Insufficient permissions to modify article' );
    			}
    			$articlecreated = $article->Update( $name , $articlehtml , $icon , $showemoticons, $categoryid , $minor, $comment );
    		}
    		else {
    			$articlecreated = MakeArticle( $name , $articlehtml , $icon, $showemoticons, $categoryid );
    		}
    		
    		if ( $articlecreated < 1 ) {
    			switch ( $articlecreated ) {
    				case -1:
    					// no priviledges
    					Redirect( "?p=addstory&nopriviledges=yes&id=$categoryid" );
    				case -2:
    					die( "Error while creating article (-2)" );
    				default:
    					die( "Error while creating article (" . $articlecreated . ")" );
    			}
    		}
    		return Redirect( '?p=story&id=' . $articlecreated );
    	}
    }
	
?>
