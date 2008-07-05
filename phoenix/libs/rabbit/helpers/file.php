<?php
    function File_NoExtensionName( $filename ) {
		$dotposition = strrpos( $filename , "." );
		if ( $dotposition === false ) {
			return $filename;
		}
		$filename = substr( $filename , 0 , $dotposition );	
		
		return $filename;
	}
	function File_GetExtension( $filename ) {
		$strlength = strlen( $filename );
		$dotposition = strrpos( $filename , "." );
		$extension = substr( $filename , $dotposition + 1 , $strlength - $dotposition + 1 );	
		
		return $extension;
	}
	function File_MimeByFilename( $filename  ) {
		$ext = Image_GetExtension( $filename );
		$ext = strtolower( $ext );
		
		return File_MimeByExtension( $ext );
	}
	function File_MimeByExtension( $ext ) {
		$mimetypes = array( 
			"jpg" => "image/jpeg" , 
			"png" => "image/png" , 
			"bmp" => "image/bmp" ,
			"gif" => "image/gif" ,
			"tiff" =>"image/tiff" ,
			"tif" =>"image/tiff" ,
			"ico" =>"image/x-icon" , 
			"jpe" =>"image/jpeg" ,
			"pjpeg" =>"image/jpeg",
			"jpeg" =>"image/jpeg" ,
			"rgb" =>"image/x-rgb" ,
		);	
		
		if ( !$mimetypes[ $ext ] ) {
			return false;
		}
		else {
			return $mimetypes[ $ext ];
		}	
	}
?>
