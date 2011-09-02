<?php
    class Report {
        public static function Create( $userid ) {
            $reports = Report::Listing( $userid );
            ++$reports;
            $res = db( '
                UPDATE
                    `users`
                SET
                    `user_reports` = :reports
                WHERE
                    `user_id` = :userid
                LIMIT 1;', compact( 'reports', 'userid' )
            );
            return $res;
        }
        public static function Listing( $userid ) {
            if ( $userid != 0 ) {
                $res = db_array(
                    'SELECT
                        `user_reports` AS reports
                    FROM
                        `users`
                    WHERE
                        `user_id` = :userid
                    LIMIT 1;', compact( 'userid' )
                );
                return $res[ 0 ][ 'reports' ];
            }

            $res = db_array(
                'SELECT
                    *
                FROM
                    `users`
                WHERE
                    `user_reports` != 0
                '
            );
            return $res;
        }
    }
?>
