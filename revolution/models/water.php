<?php
    function w_assert( $condition, $description = '' ) {
        if ( !$condition ) {
            throw New Exception( $description );
        }
    }
    class Water {
        public static function ProfileStart( $description ) {
        }
        public static function ProfileEnd() {
        }
        public static function QueryStart( $sql ) {
        }
        public static function QueryEnd() {
        }
        public static function Trace( $description, $data ) {
        }
        public static function Init() {
        }
        public static function TotalTime() {
        }
    }
?>
