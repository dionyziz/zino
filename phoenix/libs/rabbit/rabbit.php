<?php
    /*
        Developer: Dionyziz
    */
    
    function Rabbit_Construct( $mode = 'HTML' ) {
        global $water;
        global $libs;
        
        if ( function_exists( 'Rabbit_Include' ) ) {
            die( 'Rabbit_Construct() must only be called once!' );
        }
        
        require_once 'libs/rabbit/primitive.php';
        
        $pageclass = 'Page' . $mode;
        if ( !class_exists( $pageclass ) ) {
            if ( !isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // debug
                echo "No page class!\n";
            }
            throw New Exception( 'Invalid Rabbit_Contrust pagetype used: ' . $mode );
            return;
        }
        
        $page = New $pageclass(); // MAGIC
        if ( !isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // debug
            echo "Loaded page system!\n";
        }
        $page->SetNaturalLanguage( $rabbit_settings[ 'language' ] );
        $page->SetBaseIncludePath( $rabbit_settings[ 'rootdir' ] );
        if ( method_exists( $page, 'SetBase' ) ) {
            $page->SetBase( $rabbit_settings[ 'webaddress' ] . '/' );
        }
        if ( method_exists( $page, 'SetWaterDump' ) ) {
            $page->SetWaterDump( !$rabbit_settings[ 'production' ] );
        }
        
        if ( !isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // debug
            echo "Initialized page attributes!\n";
        }
        if ( function_exists( 'Project_Construct' ) ) {
            if ( !isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // debug
                echo "Calling Project_Construct!\n";
            }
            return Project_Construct( $mode );
        }
        if ( !isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // debug
            echo "End of Rabbit_Construct!\n";
        }
        $water->Notice( 'Project_Construct() is not defined; please define it in libs/project.php' );
    }
    
    function Rabbit_Destruct() {
        global $water;
        
        if ( function_exists( 'Project_Destruct' ) ) {
            return Project_Destruct();
        }
        $water->Notice( 'Project_Destruct() is not defined; please define it in libs/project.php' );
    }
    
    function Rabbit_ClearSuperGlobals() {
        // clear deprecated superglobals, if any
        $clearme = array(
            'HTTP_SERVER_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'HTTP_POST_FILES', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS'
        );
        foreach ( $clearme as $superglobal ) {
            if ( isset( $$superglobal ) ) {
                foreach ( $var as $key => $value ) {
                    unset( $$superglobal[ $key ] );
                }
            }
        }
    }
    
    function Rabbit_ClearPostGet() {
        foreach ( $_GET as $key => $value ) {
            unset( $_GET[ $key ] );
        }
        foreach ( $_POST as $key => $value ) {
            unset( $_POST[ $key ] );
        }
        foreach ( $_FILES as $key => $value ) {
            unset( $_FILES[ $key ] );
        }
    }
?>
