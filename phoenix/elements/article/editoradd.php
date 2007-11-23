<?php
	function ElementArticleEditoradd( tInteger $id ) {
		global $user;
		global $water;
		global $libs;
		global $page;
		
        $id = $id->Get();

		$libs->Load( 'article' );
		$page->AttachStyleSheet( 'css/article.css' );

		$article = New Article( $id );
		if ( !$article->Exists() ) {
            $water->Notice( 'Article doesn\'t exist' , array( $id ) );
            return;
		}

		$articleeditors = $article->Editors();
		?><h3>Συγγραφείς: </h3><?php
		while( $editor = array_shift( $articleeditors ) ) {
			Element( "user/icon", $editor, true, true );
			Element( "user/static", $editor );
			if ( count( $articleeditors ) != 0 ) {
				echo ", ";
			}
		}
		?><br /><br />
		<form action="do/article/editoradd" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			Όνομα χρήστη: &nbsp;<input type="text" name="u" value="" />
			<input type="submit" value="Προσθήκη" />
		</form>
		<br /><br />
		Σημείωση: Όταν προστεθούν συγγραφείς, αυτοί θα έχουν πλήρη δικαιώματα επεξεργασίας και
		θα μπορούν να προσθέσεουν και άλλους συγγραφείς. Δεν θα υπάρχει η δυνατότητα αναίρεσης 
		αυτής της ενέργειας.
		<?php
		
		return true;
	}
?>