<?php
    class ElementApiNotifications extends Element {
        public function Render( tInteger $id, tText $authtoken ) {
            global $libs;
            global $rabbit_settings;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'notify' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindByIdAndAuthtoken( $id->Get(), $authtoken->Get() );
            
            if ( $theuser !== false ) {
                $notifinder = New NotificationFinder();
                $notifs = $notifinder->FindByUser( $theuser );
                if ( !empty( $notifs ) ) {
                    foreach ( $notifs as $notif ) {
                        unset( $notifarray );
                        
                        if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                            $notifarray[ 'type' ] = 'comment';
                        }
                        else {
                            $notifarray[ 'type' ] = Notification_GetField( $notif );
                        }
                        $notifarray[ 'id' ] = $notif->Id;
                        
                        //FromUser
                        $notifarray[ 'fromuser' ][ 'subdomain' ] = $notif->FromUser->Subdomain;
                        $notifarray[ 'fromuser' ][ 'name' ] = $notif->FromUser->Name;
                        if ( $notif->FromUser->Avatar->Id == false ) {
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'anonymous' ] = true;
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'thumb150' ] = $rabbit_settings[ 'imagesurl' ] . 'anonymous150.jpg';
                        }
                        else {
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'id' ] = $notif->FromUser->Avatar->Id;
                            ob_start();
                            Element( 'image/url', $notif->FromUser->Avatar->Id , $notif->FromUser->Id , IMAGE_CROPPED_150x150 );
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'thumb150' ] = ob_get_clean();
                        }
                        //Type
                        if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                            ob_start();
                            Element( 'url' , $notif->Item );
                            $notifarray[ 'url' ] = ob_get_clean();                         
                            $comment = $notif->Item;
                            $text = $comment->GetText( 30 );
                            $notifarray[ 'comment' ][ 'text' ] = $text;
                            if ( mb_strlen( $comment->Text ) > 30 ) {
                                $notifarray[ 'comment' ][ 'text' ] .= "...";
                            }
                            switch ( $comment->Typeid ) {
                                case TYPE_USERPROFILE:
                                    $notifarray[ 'comment' ][ 'type' ] = 'profile';
                                    if ( $comment->Item->Id == $notif->Touserid ) {
                                        $notifarray[ 'comment' ][ 'you' ] = true;
                                    }
                                    else {
                                        $notifarray[ 'comment' ][ 'owner' ][ 'subdomain' ] = $notif->Item->Item->Subdomain;
                                        $notifarray[ 'comment' ][ 'owner' ][ 'subdomain' ] = $notif->Item->Item->Name;
                                    }
                                    break;
                                case TYPE_IMAGE:
                                    $notifarray[ 'comment' ][ 'type' ] = 'photo';
                                    $notifarray[ 'comment' ][ 'photo' ][ 'id' ] = $notif->Item->Item->Id;
                                    ob_start();
                                    Element( 'image/url', $comment->Item->Id , $comment->Item->User->Id , IMAGE_CROPPED_150x150 );
                                    $notifarray[ 'comment' ][ 'photo' ][ 'thumb150' ] = ob_get_clean();
                                    break;
                            }
                        }
                        else {
                            switch ( $notif->Typeid ) {
                                case EVENT_FRIENDRELATION_CREATED;
                                    $notifarray[ 'type' ] = 'friendship';
                                    $finder = New FriendRelationFinder();
                                    $hasyou = $finder->FindFriendship( $theuser, $notif->FromUser );
                                    $notifarray[ 'friendship' ][ 'yourfriend' ] = (bool) $hasyou;
                                    break;
                                case EVENT_IMAGETAG_CREATED:
                                    $notifarray[ 'type' ] = 'tag';
                                    $notifarray[ 'photo' ][ 'id' ] = $notif->Item->Item->Id;
                                    ob_start();
                                    Element( 'image/url', $comment->Item->Id , $comment->Item->User->Id , IMAGE_CROPPED_150x150 );
                                    $notifarray[ 'photo' ][ 'thumb150' ] = ob_get_clean();
                                    break;
                            }
                        }
                        $apiarray[] = $notifarray;
                    }
                }
            } else
            {
                $apiarray[ 'error' ][ 'description' ] = "Wrong username or authtoken";
            }
            if ( !$xml ) {
                echo w_json_encode( $apiarray );
            }
            else {
                echo 'XML Zino API not yet supported';
            }
        }
    }
?>