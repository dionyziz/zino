<?php

	function ElementCategoryFill( $parentid, $indentation, $blockcategoryid, $parented ) {
		$text = "";
		if ( isset( $parented[ $parentid ] ) ) {
			foreach( $parented[ $parentid ] as $category ) {
				if ( $category->Id() == $blockcategoryid) {
					continue;
				}
				$text .= "<option value=\"";
				$text .= $category->Id();
				$text .= "\">";
				for( $i = 0; $i < $indentation; ++$i ) {
					$text .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				$text .= "- ";
				$text .= $category->Name();
				$text .= "</option>";
				$text .= Element( "category/fill", $category->Id(), $indentation + 1, $blockcategoryid, $parented  );
			}
		}
		return $text;
	}
	
?>