<?php
	function ElementArticleEditoradd( tInteger $id ) {
		global $user;
		global $water;
		global $libs;
		global $page;
		
        $id = $id->Get();

		$libs->Load( 'article' );

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
			<input type="text" name="u" value="" />
			<input type="submit" value="Προσθήκη" />
		</form><?php
		
		return true;
	}
?>