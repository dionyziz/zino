<?php
	function UnitRelationsDelete( tInteger $relid ) {
		global $user;
		global $libs;
		
        $relid = $relid->Get();
        
		$libs->Load( 'relations' );
		
		if ( $user->CanModifyCategories() ) {
			$relation = New Relation( $relid );
			$relation->Delete();
		}
	}
?>
