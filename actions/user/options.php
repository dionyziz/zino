<?php
    function ActionUserOptions( tString $signature, tString $oldpassword, tString $newpassword, tString $newpassword2, tString $email, tString $gender, tInteger $dob_day, tInteger $dob_month, tInteger $dob_year, tString $slogan, tInteger $place ) {
    	global $libs;
        global $user;
    	
    	$libs->Load( 'image/image' );
    	
    	$signature 		= $signature->Get();
    	$oldpassword 	= $oldpassword->Get();
    	$newpassword 	= $newpassword->Get();
    	$newpassword2 	= $newpassword2->Get();
    	$email 			= $email->Get();
    	$gender			= $gender->Get();
    	$dobd			= $dob_day->Get();
    	$dobm			= $dob_month->Get();
    	$doby			= $dob_year->Get();
    	
    	// echo( "<b>1:</b> " . $doby . "-" . $dobm . "-" . $dobd );
    	$slogan			= $slogan->Get();
    	$place			= $place->Get();
    	
    	if ( !( $dobd > 0 && $dobd < 32 && $dobm > 0 && $dobm < 13 && $doby > 1900 && $doby < 2100 ) ) {
    		$dobd = $dobm = $doby = 0;
    		$dob = "0000-00-00";
    		die( "The date specified was invalid!" );
    	}
		if ( checkdate( $dobm , $dobd , $doby ) === false ) {
			$dob = $user->DateOfBirth();
			$invaliddob = true;
			// echo( "<b>2.1:</b> " . $dob );
		}
		else {
			$dob = $doby."-".$dobm."-".$dobd;
			// echo( "<b>2.2:</b> " . $dob );
		}
    	
    	if ( $oldpassword != "" ) {
    		if ( $newpassword != $newpassword2 ) {
                return Redirect( 'index.php?p=p&match=0' );
    		}
    		if ( md5( $oldpassword ) != $user->Password() ) {
    			return Redirect( 'index.php?p=p&invalid=1' );
    		}
    		if ( $newpassword == "" ) {
    			return Redirect( 'index.php?p=p&newpassword=0' );
    		}
    	}
    	$updateduser = UpdateUser( $signature ,$newpassword ,$email, $gender, $dob, $slogan, $place );
    	
    	if ( $updateduser != 1 ) {
    		die( "useroptions.php error while updating user information. UpdateUser() return code: " . $updateduser );
    	}
    	
    	if ( $_FILES['usericon']['name'] ) {
    		$imageid = strtolower( basename($_FILES['usericon']['name']) );
    		$extpos = strpos( $imageid , "." );
    		$extension = substr( $imageid , $extpos );
    		
    		// check for valid extension
    		if ( $extension == ".png" ) {
    			$convert_to_jpg = true;
    		}
    		else if ( $extension != ".jpg" && $extension != ".jpeg" ) {
                return Redirect( 'index.php?p=p&extok=no' );
    		}
    		
    		$image = substr( $imageid , 0 , $extpos );
    		
    		$tempfile = $_FILES['usericon']['tmp_name'];
    		
    		$handle = fopen($tempfile, "rb");
    		$contents = fread($handle, filesize($tempfile));
    		fclose($handle);
    		
    	    /* NO GD	
    		$icon = imagecreatefromstring( $contents );
    		
    		if ( !( $icon ) ) {
    			die( "useroptions.php: Failed to imagecreatefromstring()!" );
    		}
    		$width = ImageSX( $icon );
    		$height = ImageSY( $icon );
    		
    		if ( $width != 50 || $height != 50 ) {
    			UserRemoveIcon();
                return Redirect( 'index.php?p=p&sizeok=0' );
    		}
            */

    		if ( $convert_to_jpg ) {
    			ob_start();
    			imagejpeg( $icon );
    			$contents = ob_get_clean();
    		}
    		
    		$icon = submit_photo( $_FILES['usericon']['name'], $tempfile, 0, '', Array( 50, 50 ) );
    		
    		UserSetIcon( $icon );
    	}
    	
    	if ( $invaliddob ) {
    		$appenderrors = "&dobvalid=0";
    	}
    	
        return Redirect( "?p=p&saved=yes$appenderrors" );
    }
?>
