<?php
	function ElementArticleNewCategories( $allcategories , $root = 0 , $indent = 0 , $selectcat = 0 ) {
		if ( !isset( $allcategories[ $root ] ) ) {
			return;
		}
		foreach ( $allcategories[ $root ] as $child ) {
			?><option value="<?php
			echo $child->Id();
			?>" <?php
			if ( $selectcat == $child->Id() ) {
				?>selected="selected"<?php
			}
			?>><?php
			for ( $i = 0 ; $i <= $indent ; ++$i ) {
				?>--<?php
			}
			echo htmlspecialchars( $child->Name() );
			?></option><?php
			Element( 'article/new/categories' , $allcategories , $child->Id() , $indent + 1 , $selectcat );
		}
	}
?>
