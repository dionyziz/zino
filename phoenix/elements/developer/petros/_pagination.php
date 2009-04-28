<?php
    class ElementDeveloperPetrosPagination extends Element {
        public function Render() {
			$finder = New CommentFinder();
			$speccomment = New Comment( 1149616 );
			$entity = New Image( 100589 );
			$findnear = $finder->FindNear( $entity, $speccomment );
			var_dump( $findnear );
        }
    }
?>
