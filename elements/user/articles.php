<?php

	function ElementUserArticles( $theuser , $articles ) {
		global $user;
		
		if ( $user->CanModifyStories() ) {
			?><br />
			<a href="?p=story&amp;id=21">Γράφοντας Άρθρα</a>
			<br /><?php
		}
		if ( $theuser->CanModifyStories() && count( $articles ) > 0 ) {
			?><div class="articles newestarticles"><?php
				Article_FormatSmallMulti( $articles );
				foreach( $articles as $article ) {
					Element( "article/small", $article );
				}
				?><br />
			</div><?php
		}
		else if ( $theuser->CanModifyStories() && $user->Id() == $theuser->Id() ) {
			?><div style="margin-top: 50px;">
				Δεν έχεις γράψει κάποιο άρθρο. <a href="?p=addstory">Ξεκίνα ένα άρθρο τώρα!</a>
			</div><?php
		}
		else if ( $user->Id() == $theuser->Id() ) {
			?><div style="margin-top: 50px;">
				Για να γράψεις άρθρα πρέπει να γίνεις πρώτα δημοσιογράφος. Μάθε πώς στο <a href="?p=story&amp;id=21">Γράφοντας Άρθρα</a>
			</div><?php
		}
	}
	
?>
