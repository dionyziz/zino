<?php
    function Sequence_Increment( $key ) {
        global $db;

        $query = $db->Prepare( 'INSERT INTO :sequences (`sequence_key`, `sequence_value`) 
                        VALUES (:key, 0) 
                        ON DUPLICATE KEY UPDATE `sequence_value`=`sequence_value` + 1' );
        $query->BindTable( 'sequences' );
        $query->Bind( 'key' , $key );
        $query->Execute();
    }

    define( 'SEQUENCE_SHOUT', TYPE_SHOUT );
    define( 'SEQUENCE_COMMENT', TYPE_COMMENT );
    define( 'SEQUENCE_IMAGE', TYPE_PHOTO );
    define( 'SEQUENCE_JOURNAL', TYPE_JOURNAL );
    define( 'SEQUENCE_POLL', TYPE_POLL );

    define( 'SEQUENCE_FRONTPAGEIMAGECOMMENTS', 1001 );

    class SequenceFinder extends Finder {
        protected $mModel = 'Sequence';

        public function FindFrontpage() {
            static $frontpagetypes = array( SEQUENCE_SHOUT, SEQUENCE_COMMENT, SEQUENCE_IMAGE, SEQUENCE_JOURNAL, SEQUENCE_POLL, SEQUENCE_FRONTPAGEIMAGECOMMENTS );

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :sequences
                WHERE
                    `sequence_key` IN :frontpage'
            );
            $query->BindTable( 'sequences' );
            $query->Bind( 'frontpage', $frontpagetypes );

            $sequences = $this->FindBySqlResource( $query->Execute() );

            $ret = array();
            foreach ( $sequences as $sequence ) {
                $ret[ $sequence->Key ] = $sequence->Value;
            }
            foreach ( $frontpagetypes as $type ) {
                if ( !isset( $ret[ $type ] ) ) {
                    $ret[ $type ] = -1;
                }
            }

            return $ret;
        }
    }

    class Sequence extends Satori {
        protected $mDbTableAlias = 'sequences';
    }
?>
