<?php
    function Sequence_Increment( $key ) {
        global $db;

        $query = $db->Prepare( 'INSERT INTO :sequences (`sequence_key`, `sequence_value`) 
                        VALUES (:key, 0) 
                        ON DUPLICATE KEY UPDATE `value`=`value` + 1' );
        $query->BindTable( 'sequences' );
        $query->Bind( 'key' , $key );
    }

    class SequenceFinder extends Finder {
        protected $mModel = 'Sequence';

        protected function FindFrontpage() {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :sequences
                WHERE
                    `sequence_name` IN :frontpage'
            );
            $query->BindTable( 'sequences' );
            $query->Bind( 'frontpage', array( TYPE_SHOUT, TYPE_COMMENT, TYPE_IMAGE, TYPE_JOURNAL, TYPE_POLL ) );

            return $this->FindBySqlResource( $query->Execute() );
        }
    }

    class Sequence extends Satori {
        protected $mDbTableAlias = 'sequences';
    }
?>
