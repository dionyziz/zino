<?php
	$xvardefaults = Array( 
		"sitename" => "Chit-Chat" ,
		"newusers" => "Νέοι Chit-Chatters" , 
		"user" => "Chit-Chatter" ,
		"hometext" => "H Ελληνική σελίδα με τα τελευταία νέα σχετικά με μουσική, cinema και πολλά άλλα!"
	);
	
	$sql = "SELECT * FROM `$exvars`;";
	$sqlr = $db->Query( $sql );
	if ( $sqlr->NumRows() ) {
		while( $sqlxvar = $sqlr->FetchArray( $sqlr ) ) {
			$xvars[ $sqlxvar[ "var_name" ] ] = $sqlxvar[ "var_value" ];
		}
	}
	
	$allvars = array_keys( $xvardefaults );
	for( $i = 0 ; $i < count( $allvars ) ; $i++ ) {
		$xv[ $allvars[ $i ] ] = new xVar( $allvars[ $i ] );
	}
	
	class xVar {
		var $mName;
		var $mValue;
		
		function Name() {
			return $this->mName;
		}
		function Value() {
			return $this->mValue;
		}
		function xVar( $name ) {
			global $xvars;
			global $xvardefaults;
			
			$this->mName = $name;
			if ( $xvars[ $name ] ) {
				$this->mValue = $xvars[ $name ];
			}
			else {
				$this->mValue = $xvardefaults[ $name ];
			}
		}
	}
?>