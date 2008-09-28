<?php
    define( 'DICTIONARY_WORDSCOUNT', 76833 );
    
    function Dictionary_GetRandomWord() { // for the sake of speed
        global $db;
        global $dictionarywords;
        global $water;
        
        $sql = "SELECT
                    *
                FROM
                    `$dictionarywords`
                WHERE
                    `word_id` = " . rand( 1, 76833 ) . "
                LIMIT 1;";
        $res = $db->Query( $sql );
        if ( !$res->Results() ) {
            $water->Notice( 'No words found in dictionary' );
            return false;
        }
        
        $word = New DictionaryWord( $res->FetchArray() );
        
        return $word->Text;
    }
    
    final class Dictionary extends Satori {
        protected $mId;
        protected $mLanguage;
        protected $mWordsCount;
        
        protected function LoadDefaults() {
            $this->mWordsCount = false;
        }
        protected function WordsCount() {
            global $dictionarywords;
            
            $sql = "SELECT
                        COUNT(*) AS wordscount
                    FROM
                        `$dictionarywords`
                    WHERE
                        `word_dictionaryid` = '" . $this->mId . "'";
            $res = $this->mDb->Query( $sql );
            $row = $res->FetchArray();
            
            return $this->mWordsCount = $row[ 'wordscount' ];
        }
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
            $res = $this->mDb->Query( $sql );
            if ( !$res->Results() ) {
                $water->Notice( 'Dictionary not found' );
                $this->Satori( false );
                return;
            }
            $row = $res->FetchArray();
            
            $this->Satori( $row );
        }
        /* // this is very slow
        public function GetRandomWord() {
            global $dictionarywords;
            global $water;
            
            $sql = "SELECT
                        *
                    FROM
                        `$dictionarywords`
                    WHERE
                        `word_dictionaryid` = " . $this->mId . "
                    LIMIT " . rand( 0, $this->WordsCount() - 1 ) . ",1;";
            $res = $this->mDb->Query( $sql );
            if ( !$res->Results() ) {
                $water->Notice( 'No words found in dictionary' );
                return false;
            }
            
            $word = New DictionaryWord( $res->FetchArray() );
            
            return $word->Text;
        }
        */
    }
    
    final class DictionaryWord extends Satori {
        protected $mId;
        protected $mText;
        protected $mDictionaryId;
        
        public function DictionaryWord( $construct ) {
            global $db;
            global $dictionarywords;
            
            $this->mDb = $db;
            $this->mDbTable = $dictionarywords;
            $this->SetFields( array(
                'word_id'           => 'Id',
                'word_text'         => 'Text',
                'word_dictionaryid' => 'DictionaryId'
            ) );
            
            $this->Satori( $construct );
        }
    }
?>
