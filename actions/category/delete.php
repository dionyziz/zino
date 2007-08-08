<?php
    function ActionCategoryDelete( tInteger $id ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'category' );
    	
    	if ( !( $user->CanModifyCategories() ) ) {
    		die( "Δεν έχετε τα απαραίτητα δικαιώματα για διαγραφή κατηγοριών!" );
    	}
    	
    	$id = $id->Get();
    	$sqlcategory = MyCategory( $id );
    	if ( $sqlcategory == "" ) {
    		die( "Η κατηγορία που προσπαθείς να διαγράψεις δεν υπάρχει!" );
    	}
    	else {
    		$parentcategoryid = $sqlcategory[ "parentcategoryid" ];
    		$categorydeleted = KillCategory( $id );
    		
    		switch ( $categorydeleted ) {
    			case 1:
    				if ( $parentcategoryid == 0 ) {
                        return Redirect();
    				}
                    return Redirect( "?p=category&id=$parentcategoryid" );
    			default:
    				die( "KillCategory() error: Return code: " . $categorydeleted );
    		}
    	}
    }
?>
