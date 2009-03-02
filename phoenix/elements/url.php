<?php
    /// Content-type: text/plain ///
    class ElementURL extends Element {
        public function Render( $target ) {
            global $rabbit_settings;
            global $xc_settings;

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
                    Element( 'user/url', $target->User->Id , $target->User->Subdomain );
                    ?>journals/<?php
                    echo $target->Url;
                    return;
                case 'Poll':
                    Element( 'user/url', $target->User->Id, $target->User->Subdomain );
                    ?>polls/<?php
                    echo $target->Url;
                    return;
                case 'School':
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/?p=school&id=<?php
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
                    return;
                case 'FriendRelation':
                    ob_start();
                    Element( 'url', $target->Friend );
                    $url = ob_get_clean();
                    echo $url;
                    return;
                default:
                    throw New Exception( 'Unknown comment target item "' . get_class( $target ) . '"' );
            }
        }
    }
?>
