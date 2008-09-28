<?php
    function ActionUserSetPrivileges( tInteger $id, tInteger $rights ) {
    	$id = $id->Get();
    	$rights = $rights->Get();
    	
    	$userresult = AdminUser( $id , $rights );
    	
    	switch( $userresult ) {
    		case 1:
    			return Redirect( "?p=useradmin&id=$id" );
    		case 2:
    			die("AdminUser(): No user management rights");
    		case 3:
    			die("AdminUser(): Cannot give a greater rank than your own");
    		case 4:
    			die("AdminUser(): No such user");
    		case 5:
    			die("Δεν μπορείς να ορίσεις τα δικαιώματα ενός χρήστη με περισσότερα δικαιώματα από εσένα");
    		case 6:
    			die("AdminUser(): Only admins can set these rights");
    	}
    }
?>
