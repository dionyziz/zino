<?php
    class ElementDeveloperPetrosPagination extends Element {
        public function Render() {
			global $libs;
			$libs->Load( 'comment' );
			$finder = New CommentFinder();
			$speccomment = New Comment( 1149616 );
			$entity = New Image( 100589 );
			
			$findnear = $finder->FindNear( $entity, $speccomment );
			
			var_dump( $findnear );
			die( 'test' );
        }
    }
?>
