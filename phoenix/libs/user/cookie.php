<?php
    function User_GetCookie() {
        global $xc_settings;
        global $water;

        if ( empty( $_COOKIE[ $xc_settings[ 'cookiename' ] ] ) ) {
            return false;
        }

        $logininfo = $_COOKIE[ $xc_settings['cookiename'] ];
        $logininfos = explode( ':' , $logininfo );
        $userid = $logininfos[ 0 ];
        $userauth = $logininfos[ 1 ];
        $userid = ( int )$userid;
        if ( $userid <= 0 ) {
            $water->Trace( 'Cookie error: userid < 0' );
            return false;
        }
        if ( strlen( $userauth ) != 32 ) {
            $water->Trace( 'Cookie error: strlen( userauth ) != 32' );
            return false;
        }
        if ( !preg_match( '#^[a-zA-Z0-9]*$#', $userauth ) ) {
            $water->Trace( 'Cookie error: auth is invalid' );
            return false;
        }

        return array(
            'userid' => $userid,
            'authtoken' => $userauth
        );
    }
    function User_SetCookie( $userid, $authtoken ) {
        global $xc_settings;

        w_assert( is_int( $userid ) );
        w_assert( strlen( $authtoken ) == 32, 'Authtoken is not 32 characters long: ' . $authtoken );
        w_assert( preg_match( '#^[A-Za-z0-9]*$#', $authtoken ) );

        $eofw = 2147483646;
        setcookie( $xc_settings[ 'cookiename' ], "$userid:$authtoken" , $eofw, '/', $xc_settings[ 'cookiedomain' ], false, true );
    }
    function User_ClearCookie() {
        global $xc_settings;

        setcookie( $xc_settings[ 'cookiename' ], '', time() - 86400, '/', $xc_settings[ 'cookiedomain' ], false, true );
    }
?>
