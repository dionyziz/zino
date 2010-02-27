<?php

    function Resource_Init() {
        $resource = '';
        if ( isset( $_GET[ 'resource' ] ) ) {
            $resource = $_GET[ 'resource' ];
            unset( $_GET[ 'resource' ] );
        }
        $method = '';
        if ( isset( $_GET[ 'method' ] ) ) {
            $method = $_GET[ 'method' ];
            unset( $_GET[ 'method' ] );
        }
        switch ( $resource ) {
            case 'photo': case 'session': case 'comment': case 'favourite':
                break;
            default:
                $resource = 'photo';
        }
        switch ( $method ) {
            case 'view': case 'listing': case 'create': case 'delete': case 'update':
                break;
            default:
                $method = 'listing';
        }
        if ( $method != 'listing' && $method != 'view' ) {
            $_SERVER[ 'REQUEST_METHOD' ] == 'POST' or die;
            $vars = $_POST;
        }
        else {
            $vars = $_GET;
        }

        return array( $resource, $method, $vars );
    }

    function Resource_Call( $resource, $method, $vars ) {
        include 'controllers/' . $resource . '.php';
        call_user_func_array( $method, $vars );
    }

?>
