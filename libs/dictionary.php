<?php
    final class Dictionary extends Satori {
        protected $mId;
        protected $mLanguage;
        
        public function Dictionary( $construct = 'greek' ) {
            global $db;
            global $dictionaries;
            
            $this->mDb = $db;
            $this->mDbTable = $dictionaries;
            $this->SetFields( array(
                'dictionary_id' => 'Id',
                'dictionary_language' => 'Language'
            ) );
            w_assert( $construct == 'greek' ); // for now
            $construct = addslashes( $construct );
            $sql = "SELECT
                        *
                    FROM
                        `$dictionaries`
                    WHERE
                        `dictionary_language`='" . $construct . "'
                    LIMIT 1;";
            $res = $db->Query( $sql );
            if ( !$res->Results() ) {
                $water->Notice( 'Dictionary not found' );
                $this->Satori( false );
                return;
            }
            $row = $res->FetchArray();
            
            $this->Satori( $row );
        }
        public function GetRandomWord() {
            global $db;
            global $dictionarywords;
            global $water;
            
            $sql = "SELECT
                        *
                    FROM
                        `$dictionarywords`
                    WHERE
                        `word_dictionaryid` = " . $this->mId . "
                    ORDER BY
                        RAND()
                    LIMIT 1;";
            $res = $db->Query( $sql );
            if ( !$res->Results() ) {
                $water->Notice( 'No words found in dictionary' );
                return false;
            }
            
            $word = New DictionaryWord( $res->FetchArray() );
            
            return $word->Text;
        }
    }
    
    final class DictionaryWord extends Satori {
        protected $mId;
        protected $mWord;
        protected $mDictionaryId;
        
        public function DictionaryWord( $construct ) {
            global $db;
            global $dictionarywords;
            
            $this->mDb = $db;
            $this->mDbTable = $dictionarywords;
            $this->SetFields( array(
                'word_id'           => 'Id',
                'word_text'         => 'Word',
                'word_dictionaryid' => 'DictionaryId'
            ) );
            
            $this->Satori( $construct );
        }
    }
?>
