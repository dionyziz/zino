<?php
	function ElementArticleRevisionView( tInteger $id, tInteger $r, tInteger $skip ) {
		global $page;
		global $libs;
		global $user;
		
        $id = $id->Get();
        $revisionid = $r->Get();
        $skip = $skip->Get();

		if ( !( $id > 0 ) ) {
			return;
		}
		if ( ( $skip < 0 ) ) {
			$skip = 0;
		}
		if ( $revisionid > 0 ) {
			$revisionvalid = true;
		}
		else {
			$revisionvalid = false;
		}
		
		$libs->Load( 'article' );

		if ( !$user->CanModifyStories() ) {	//is this redundant?
			return;
		}

		$article = New Article( $id );
		if ( !$article->CanModify( $user ) ) {	//is this necessary?
			return;
		}

		$array = Revisions_ByArticleId( $id, $skip );
		if ( !is_array( $array ) ) {
			return false;
		}
		
		$page->SetTitle( 'Ιστορικό &quot;' . htmlspecialchars( $article->Title() ) . '&quot;' );

		?><h2><?php
		if ( $skip >= 10 ) {
			?><a href="?p=revisions&amp;id=<?php
			echo $id; 
/*			if ( $revisionvalid ) { 
				?>&amp;r=<?php 
				echo $revisionid; 
			} */ //maybe we shouldn't actually display the revision contents when switching pages
			?>&amp;skip=<?php
			echo $skip-10;
			?>">&lt;</a>&nbsp;<?php
		}
		else {
			?>&lt;&nbsp;<?php
		}
		?>Ιστορικό <a href="?p=story&amp;id=<?php
		echo $id; 
		?>">&quot;<?php
		echo htmlspecialchars( $article->Title() );
		?>&quot;</a><?php
		if ( count($array) == 10 ) {
			?>&nbsp;<a href="?p=revisions&amp;id=<?php
			echo $id; 
/*			if ( $revisionvalid ) {
				?>&amp;r=<?php
				echo $revisionid;
			} */ //maybe we shouldn't actually display the revision contents when switching pages
			?>&amp;skip=<?php
			echo $skip+10;
			?>">&gt;</a><?php
		}
		else {
			?>&nbsp;&gt;<?php
		}
		?></h2><ul><?php
		foreach( $array as $revision ) {
			?><li><?php
			if ( $revision[ 'revision_id' ] == $revisionid ) {
				//no link to the revision, since it's already open. Only the date is printed out, as plain text
				echo MakeDate( $revision[ 'revision_updated' ] );

				if ( $revision[ 'revision_minor' ] == 'yes' ) {
					?><strong>μ</strong><?php
				}
			}
			else {
				?><a href="?p=revisions&amp;id=<?php
				echo $id;
				?>&amp;r=<?php
				echo $revision[ 'revision_id' ];
				?>&amp;skip=<?php echo $skip; ?>"><?php
				echo MakeDate( $revision[ 'revision_updated' ] );

				if ( $revision[ 'revision_minor' ] == 'yes' ) {
					?><strong>μ</strong><?php
				}
				?></a><?php 
			}
			?> - από <a href="user/<?php 
			echo htmlspecialchars( $revision[ 'user_name' ] );
			?>"><?php 
			echo htmlspecialchars( $revision[ 'user_name' ] );
			?></a><?php
			if ( strlen( $revision[ 'revision_title' ] ) > 0 ) {
				?>, &quot;<?php
				echo htmlspecialchars( $revision[ 'revision_title' ] );
				?>&quot;<?php 
			}
			if ( strlen( $revision[ 'revision_comment' ] ) > 0 ) {
				?> (<?php
				echo htmlspecialchars( $revision[ 'revision_comment' ] );
				?>)<?php 
			}
			?></li><?php
		}
		?></ul><?php
		
		if ( $revisionvalid ) {
			//Display given revision
			?><br />
            <span style="float:right;">
            	<form action="do/article/revert" method="post">
            		<input type="hidden" name="id" value="<?php echo $id; ?>" />
            		<input type="hidden" name="r" value="<?php echo $revisionid; ?>" />
            		<input type="submit" value="Επαναφορά" />
            	</form>
            </span><?php
			?><h4>Έκδοση <?php
            echo $revisionid;
            ?>η, <?php
            echo $revision['revision_updated'];
            ?></h4><?php

			$article = New Article( $id, $revisionid );
			if ( !$article->Exists() ) {
				?><h3>Η έκδοση του άρθρου που ζητήσατε δεν υπάρχει!</h3><?php
				return;
			}

			?><hr /><br /><h3><?php
            echo htmlspecialchars( $article->Title() );
            ?></h3><?php
			$formatted = mformatstories( array( $article->TextRaw() ) , $article->ShowEmoticons() );
			echo $formatted[ 0 ];
		}
		
		return true;
	}
?>
