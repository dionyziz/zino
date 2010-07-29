<?php

    class Mood {
        public static function Listing() {
            $res = db(
                "SELECT 
                    `mood_id` AS id, 
                    `mood_labelmale` AS male, 
                    `mood_labelfemale` AS female, 
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
