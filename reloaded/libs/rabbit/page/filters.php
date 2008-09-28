<?php
    // DEPRECATED and unused
    return;
    
	function css_filter( $str ) {
		/*
			Decrease loading time by removing unnecessary CSS whitespace
			In a CSS file, approximately the 35% of the data stored is whitespace.
		*/
		$rtn = $str;
		// tabs + newlines + winnewlines become spaces so that we can filter them out later
		$rtn = preg_replace( "#([\t\n\r])#" , " " , $rtn );
		$rtn = trim( $rtn );
		// remove /* CSS Comments */
		$rtn = preg_replace( "#([/][\*](.+?)[\*][/])#" , "" , $rtn );
		// remove spaces around CSS operators
		// e.g. body { background-color: white; }
		// will become body{background-color:white;}
		// perhaps we need to check for single quotation marks and url
		// and not replace within those as in background-image: url('test (image).jpg'); shouldn't become background-image:url('test(image).jpg');
		// TODO
		$rtn = preg_replace( "#[ ]*(\{|\}|\:|\(|\)|;)[ ]*#" , "\\1" , $rtn );
		return $rtn;
	}

	function html_filter( $src ) {
		global $nofilters;
		// function html_filter(): Minimizes download time 
		// for given source code by stripping out unnecessary stuff
		
		if( $nofilters )
			return $src;
			
		$space = " ";
		$dspace = $space . $space;
		
		$src = preg_replace( "#([\t\n\r])#" , " " , $src );
		$src = trim( $src );
		
		//replace all double-triple-.. spaces with single ones
		while ( strpos( $src , $dspace ) !== false ) {
			$src = str_replace( $dspace , $space , $src );
		}
		
		//strip unnecessary spaces between tags
		$src = str_replace( "> <" , "><" , $src );
		
		return $src;
	}
?>
