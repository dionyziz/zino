<?php
    function w_assert( $condition, $description = '' ) {
        if ( !$condition ) {
            throw New Exception( $description );
        }
    }
    class Water {
        private static $mRunningProfiles = array();
        private static $mProfiles = array();
        private static $mTraces = array();
        private static $mQueries = array();
        private static $mQueryStartTime;
        private static $mQuerySQL;
        private static $mStartTime;

        public static function ProfileStart( $description ) {
            self::$mRunningProfiles[] = array( 'description' => $description, 'start' => microtime( true ) );
        }
        public static function ProfileEnd() {
            $profile = array_pop( self::$mRunningProfiles );
            $profile[ 'time' ] = ( microtime( true ) - $profile[ 'start' ] ) * 1000;
            self::$mProfiles[] = $profile;
        }
        public static function QueryStart( $sql ) {
            self::$mQuerySQL = $sql;
            self::$mQueryStartTime = microtime( true );
        }
        public static function QueryEnd() {
            self::$mQueries[] = array(
                'sql' => self::$mQuerySQL,
                'time' => ( microtime( true ) - self::$mQueryStartTime ) * 1000
            );
        }
        public static function Trace( $description, $data ) {
            self::$mTraces[] = compact( 'description', 'data' );
        }
        public static function GetProfiles() {
            return self::$mProfiles;
        }
        public static function GetQueries() {
            return self::$mQueries;
        }
        public static function GetTraces() {
            return self::$mTraces;
        }
        public static function Init() {
            self::$mStartTime = microtime( true );
        }
        public static function TotalTime() {
            return ( microtime( true ) - self::$mStartTime ) * 1000;
        }
    }

    Water::Init();
?>
