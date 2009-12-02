<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash, tBoolean $firsttime ) {
            global $libs;
            global $user;
            
            $libs->Load( 'user/profile' );
            $libs->Load( 'rabbit/helpers/http' );

            $userid = $userid->Get();
            $hash = $hash->Get();
            $firsttime = $firsttime->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>Η επιβεβαίωση του e-mail σου δεν ήταν δυνατό να πραγματοποιηθεί.<br />
                Παρακαλούμε ξαναδοκίμασε.</p><?php
                return;
            }
            
            $myuser = New $user( $userid );
            $myuser->UpdateLastLogin();
            $myuser->Save();
            $_SESSION[ 's_userid' ] = $myuser->Id;
            $_SESSION[ 's_authtoken' ] = $myuser->Authtoken;
            User_SetCookie( $myuser->Id, $myuser->Authtoken );
            if ( isset( $_SESSION[ 'teaser_comment' ] ) && $firsttime ) {
                $libs->Load( 'comment' );
                $libs->Load( 'wysiwyg' );

                w_assert( is_array( $_SESSION[ 'teaser_comment' ] ) );
                w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'text' ] ) );
                w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'parentid' ] ) );
                w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'typeid' ] ) );
                w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'itemid' ] ) );

                $text = $_SESSION[ 'teaser_comment' ][ 'text' ];
                $parentid = $_SESSION[ 'teaser_comment' ][ 'parentid' ];
                $typeid = $_SESSION[ 'teaser_comment' ][ 'typeid' ];
                $itemid = $_SESSION[ 'teaser_comment' ][ 'itemid' ];

                $comment = New Comment();
                $text = nl2br( htmlspecialchars( $text ) );
                $text = WYSIWYG_PostProcess( $text );
                $comment->Text = $text;
                $comment->Userid = $user->Id;
                $comment->Parentid = $parent;
                $comment->Typeid = $type;
                $comment->Itemid = $compage;
                $comment->Save();

                unset( $_SESSION[ 'teaser_comment' ] );

                ob_start();
                Element( 'url', $comment );
                $url = ob_get_clean();
                return Redirect( $url );
            }
            if ( isset( $_SESSION[ 'destuser_id' ] ) ) { // TODO: maybe change this to a URL?
                $destuser = New User( $_SESSION[ 'destuser_id' ] );
                ob_start();
                Element( 'user/url', $destuser->Id, $destuser->Subdomain );
                return Redirect( ob_get_clean() );
            }
            if ( !$firsttime ) {
                return Redirect( '' );
            }
            else {
                return Redirect( '?p=joined' );
            }
        }
    }
?>
