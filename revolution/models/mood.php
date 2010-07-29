<?php

    class Mood {
        public static function Listing() {
            $res = db(
                "SELECT 
                    `mood_id` AS id, 
                    `mood_labelmale` AS labelmale, 
                    `mood_labelfemale` AS labelfemale, 
                    `mood_url` AS url
                FROM
                    `moods`;"
            );

            $moods = array();
            while ( $mood = mysql_fetch_array( $res ) ) {
                $mood[ 'id' ] = (int)$mood[ 'id' ];
                $moods[] = $mood;
            }

            return $moods;
        }
    }

?>
