<?php
	function UnitUserSettingsTagsSuggest( tString $text, tString $type, tCoalaPointer $callback ) {
		global $libs;
		
		$libs->Load( 'tag' );
		
		$text = $text->Get();
		$type = $type->Get();
		
		switch( $type ) {
			case 'hobbies':
				$act_type = TAG_HOBBIE;
				break;
			case 'movies':
				$act_type = TAG_MOVIE;
				break;
			case 'books':
				$act_type = TAG_BOOK;
				break;
			case 'songs':
				$act_type = TAG_SONG;
				break;
			case 'artists':
				$act_type = TAG_ARTIST;
				break;
			case 'games':
				$act_type = TAG_GAME;
				break;
			case 'shows':
				$act_type = TAG_SHOW;
				break;
			default:
				$type = -1;
		}
		
		$finder = New TagFinder();
		$res = $finder->FindSuggestions( $text, $act_type );
		
		?>alert( "<?php
		echo count( $res );
		?>" );<?php
		$arr = "var arr = new Array( ";
		foreach( $res as $i ) {
			$arr .= w_json_encode( $i );
			$arr .= ", ";
		}
		/*
		$len = strlen( $arr );
		$arr[ $len-1 ] = $arr[ $len-2 ] = ''; */
		
		$arr .= " 'asd' );";

		echo $arr;		
		echo $callback;
		?>( <?php
		echo w_json_encode( $type );
		?>, arr );<?php
	}
?>
