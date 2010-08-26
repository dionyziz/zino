<?php
    function UserIp() {
        if ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            return ip2long( $_SERVER["HTTP_CLIENT_IP"] );
        } 
        else {
            return ip2long( $_SERVER["REMOTE_ADDR"] );
        }
    }

?>
