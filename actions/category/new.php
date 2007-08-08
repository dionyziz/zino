<?php
    function ActionCategoryNew( tInteger $id, tString $name, tString $description, tInteger $parentcategory, tInteger $icon ) {
    	global $libs;
    	global $water;
    	
    	$libs->Load( 'category' );
    	
    	$id				= $id->Get();
    	$name			= $name->Get();
    	$description	= $description->Get();
    	$parentcategory	= $parentcategory->Get();
    	$icon			= $icon->Get();
    	
    	if ( $id != 0 ) {
    		$catcreated = UpdateCategory( $id , $name , $description , $parentcategory , $icon );
    		$water->Trace( 'updated succesfully' );
    	}
    	else {
    		$catcreated = MakeCategory( $name , $description , $parentcategory , $icon );
    	}
    	
    	switch ( $catcreated ) {
    		case 1:
    			// ok
    			// navigate to parent category
                return Redirect( '?p=category&id=' . $parentcategory );
    		case 2:
    			// no priviledges
                return Redirect( "?p=nc&norights=yes&name=$name&description=$description&parentcategory=$parentcategory" );
    		case 3:
    			// existing category
                return Redirect( "?p=nc&categoryexists=yes&name=$name&description=$description&parentcategory=$parentcategory" );
    		case 4:
    			// invalid parent category
                return Redirect( "?p=nc&invalidparent=yes&name=$name&description=$description&parentcategory=$parentcategory" );
    		case 5:
    			// invalid category
                return Redirect( "?p=nc&invalidcategory=yes&e=$id&name=$name&description=$description&parentcategory=$parentcategory" );
    		case 6:
    			// parent of self
                return Redirect( "?p=nc&selfparent=yes&e=$id&name=$name&description=$description&parentcategory=$parentcategory" );
    	}
    }
?>
