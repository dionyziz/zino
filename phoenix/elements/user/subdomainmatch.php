<?php
    class ElementUserSubdomainmatch extends Element {
        public function Render() {
            global $page;
            global $rabbit_settings;
            global $xc_settings;

            $subdomains = explode( '*', $xc_settings[ 'usersubdomains' ], 2 );
            $subdomains[ 0 ] = preg_quote( $subdomains[ 0 ], '#' );
            $subdomains[ 1 ] = preg_quote( $subdomains[ 1 ], '#' );

            if ( !empty( $_SERVER[ 'HTTPS' ] ) ) {
                $currentaddress = 'https://';
            }
            else {
                $currentaddress = 'http://';
            }

            $currentaddress .= $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

            if ( preg_match( '#^' . $subdomains[ 0 ] . '[a-zA-Z0-9-]+' . $subdomains[ 1 ] . '#', $currentaddress, $matches ) ) {
                $coalabase = $matches[ 0 ];
            }
            else {
                $coalabase = $rabbit_settings[ 'webaddress' ] . '/';
            }

            ob_start();
            ?>Coala.BaseURL = <?php
            echo w_json_encode( $coalabase );
            ?>;<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
