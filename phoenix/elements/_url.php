<?php
    /// Content-type: text/plain ///
    class ElementURL extends Element {
        public function Render( $target ) {
            global $rabbit_settings;

            w_assert( is_object( $target ) );

            switch ( get_class( $target ) ) {
                case 'Favourite':
                    return Element( 'url', $target->Item );
                case 'UserProfile':
                    return Element( 'user/url', $target->User->Id , $target->User->Subdomain );
                case 'User':
                    return Element( 'user/url', $target->Id , $target->Subdomain );
                case 'Image':
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=photo&id=<?php // do not escape this & -- we're in plaintext mode; use output buffering in your caller if you want to htmlspecialchars()
                    echo $target->Id;
                    return;
                case 'ImageTag':
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=photo&id=<?php
                    echo $target->Imageid;
                    return;
                case 'Album':
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=album&id=<?php
                    echo $target->Id;
                    return;
                case 'Journal':
                    $username = $target->User->Name;
                    $url = $target->Url;
                    // TODO
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=journal&id=<?php
                    echo $target->Id;
                    return;
                case 'Poll':
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=poll&id=<?php
                    echo $target->Id;
                    return;
                case 'Comment':
                    ob_start();
                    Element( 'url', $target->Item );
                    $url = ob_get_clean();
                    echo $url;
                    if ( strpos( $url, '&' ) !== false ) {
                        ?>&<?php
                    }
                    else {
                        ?>?<?php
                    }
                    ?>commentid=<?php
                    echo $target->Id;
                    ?>#comment_<?php
                    echo $target->Id;
                    return;
                default:
                    throw New Exception( 'Unknown comment target item "' . get_class( $target ) . '"' );
            }
        }
    }
?>
