<?php
	define( 'DICTIONARY_WORDSCOUNT', 76833 );
	
	function Dictionary_GetRandomWord() { // for the sake of speed
		global $db;
		global $water;
		
		$query = $db->Prepare("
			SELECT
				*
			FROM
				:dictionarywords
			WHERE
				`word_id` = :Random
			LIMIT :Limit
			;
		");
		
		$query->BindTable( 'dictionarywords' );
		$query->Bind( 'Random', rand( 1, 76833 ) );
		$query->Bind( 'Limit', 1 );
		
		$res = $query->Execute();

		if ( !$res->Results() ) {
			$water->Notice( 'No words found in dictionary' );
			return false;
		}
		
		$word = New DictionaryWord( $res->FetchArray() );
		
		return $word->Text;
	}
?>
