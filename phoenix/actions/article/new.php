<?php
    
	function ActionArticleNew( tString $name, tString $story, tInteger $categoryid, tInteger $eid, tInteger $icon, tBoolean $showemoticons, tBoolean $preview, tBoolean $minor) {
    	global $libs;
		global $user;
    	
    	$libs->Load( 'article' );
    	
    	$name			= $name->Get();
    	$story			= $story->Get();
    	$categoryid		= $categoryid->Get();
    	$eid			= $eid->Get();
    	$icon			= $icon->Get();
    	$showemoticons  = $showemoticons->Get();
    	$preview 		= $preview->Get();
    	$minor			= $minor->Get();
    	
    	if ( $preview ) {
    		$_SESSION[ 's_sname' ] 			= $name;
    		$_SESSION[ 's_sstory' ] 		= $story;
    		$_SESSION[ 's_scategoryid' ] 	= $categoryid;
    		$_SESSION[ 's_seid' ] 			= $eid;
    		$_SESSION[ 's_sicon' ] 			= $icon;
    		$_SESSION[ 's_sshowemoticons' ] = $showemoticons;
            return Redirect( '?p=addstory&id=' . $eid . '&preview=yes' );
    	}
    	
    	$name = strip_tags( $name );

    	if ( !merlin_valid( $story ) || !merlin_valid( $name ) ) {
    		$_SESSION[ 's_sname' ] 			= $name;
    		$_SESSION[ 's_sstory' ] 		= $story;
    		$_SESSION[ 's_scategoryid' ] 	= $categoryid;
    		$_SESSION[ 's_seid' ] 			= $eid;
    		$_SESSION[ 's_sicon' ] 			= $icon;
    		$_SESSION[ 's_sshowemoticons' ] = $showemoticons;
    		return Redirect( 'index.php?p=addstory&id=' . $eid . '&preview=yes' );
    	}
    	else {
    		if ( $eid != 0 ) {
    			$article = New Article( $eid );
    			if ( !$article->CanModify( $user ) ) {
    				die( 'Insufficient permissions to modify article' );
    			}
    			$storycreated = $article->Update( $name , $story , $icon , $showemoticons, $categoryid , $minor );
    		}
    		else {
    			$storycreated = MakeArticle( $name , $story , $icon, $showemoticons, $categoryid );
    		}
    		
    		if ( $storycreated < 1 ) {
    			switch ( $storycreated ) {
    				case -1:
    					// no priviledges
    					Redirect( "?p=addstory&nopriviledges=yes&id=$categoryid" );
    				case -2:
    					die( "Error while creating article (-2)" );
    				default:
    					die( "Error while creating article (" . $storycreated . ")" );
    					break;
    			}
    		}
    		return Redirect( '?p=story&id=' . $storycreated );
    	}
    }
	
?>
