<?php
	function UnitUserSettingsTagsSuggest( tString $text, tString $type, tCoalaPointer $callback ) {
		global $libs;
		
		$libs->Load( 'tag' );
		
		$text = $text->Get();
		$type = $type->Get();
		
		switch( $type ) {
			case 'hobbies':
				$act_type = 1;
				break;
			case 'movies':
				$act_type = 2;
				break;
			case 'books':
				$act_type = 3;
				break;
			case 'songs':
				$act_type = 4;
				break;
			case 'artists':
				$act_type = 5;
				break;
			case 'games':
				$act_type = 6;
				break;
			case 'shows':
				$act_type = 7;
				break;
			default:
				$type = -1;
		}
		
		$finder = New TagFinder();
		$res = $finder->FindSuggestions( $text, $act_type );
		
		$arr = "{ ";
		$len = count( $res );
		for( $i = 0; $i < $len; ++$i ) {
			$arr .= w_json_encode( $res[ $i ] );
			if ( $i != $len-1 ) {
				$arr .= ", ";
			}
		}
		$arr .= "}";

		echo $callback;
		?>( <?php
		echo w_json_encode( $type );
		?>, <?php
		echo $arr;
		?> );<?php
	}
?>
