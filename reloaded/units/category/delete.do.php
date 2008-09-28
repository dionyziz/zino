<?php
	function UnitCategoryDelete( tInteger $categoryid ) {
		global $user;
    	global $libs;
    	
        $categoryid = $categoryid->Get();
        
    	$libs->Load( 'category' );
    	
    	if ( !( $user->CanModifyCategories() ) ) {
    		die( "alert( \"Δεν έχετε τα απαραίτητα δικαιώματα για διαγραφή κατηγοριών!\")" );
		}
    	
    	$sqlcategory = New Category( $categoryid );
    	if ( $sqlcategory == "" ) {
    		die( "alert( \"Η κατηγορία που προσπαθείς να διαγράψεις δεν υπάρχει!\")" );
    	}
    	else {
    		$parentcategoryid = $sqlcategory->ParentId();
    		$categorydeleted = KillCategory( $categoryid );
    		
    		switch ( $categorydeleted ) {
    			case 1:
    				if ( $parentcategoryid == 0 ) {
                        ?>window.location.href='';<?php
    				}
                    ?>window.location.href="?p=category&id=<?php echo $parentcategoryid;?>";<?php
					break;
    			default:
    				die( "KillCategory() error: Return code: " . $categorydeleted );
    		}
    	}
	}

?>
