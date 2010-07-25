<?php
    class Place {
        public static function Listing() {
            return db_array(
                'SELECT
                    place_id AS id, place_name AS name
                FROM
                    places'
            );
        }
    }
?>
