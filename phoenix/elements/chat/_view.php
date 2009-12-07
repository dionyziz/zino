<?php
// Touch
	/*
		Developer: Dionyziz

        MASKED
            By: Dionyziz
            Reason: Video chat
	*/

    class ElementChatView extends Element {
        public function Render() {
            global $page;
            global $libs;
            global $user;
            
            $libs->Load( 'chat/message' );
            $libs->Load( 'chat/channel' );
            $libs->Load( 'chat/video' );
			
			$channels = ChannelFinder::FindByUserid( 1 );
			$channels[ 0 ] = array( 'authtoken' => '', 'participants' => array() );
			
			$finder = New ShoutboxFinder();
			$chats = $finder->FindByChannel( array_keys( $channels ), 0, 50 );
			$channelmessages = array();
			foreach ( $channels as $channelid => $channeldata ) {
				$channelmessages[ $channelid ] = array();
			}
			foreach ( $chats as $chat ) {
				$channelmessages[ $chat->Channelid ][] = $chat;
			}
			foreach ( $channelmessages as $channelid => $messages ) {
				$channelmessages[ $channelid ] = array_reverse( $messages );
			}
			
            ?><div><?php
				foreach ( $channelmessages as $channelid => $chats ) {
                    $finder = New ChatVideoFinder();
                    $videostreams = $finder->FindByChannelId( $channelid );
					?>
                    <div id="messages_<?php
                    echo $channelid;
                    ?>" class="channelmessages<?php
                    if ( count( $videostreams ) ) {
                        $who = array();
                        $broadcast = false;
                        foreach ( $videostreams as $stream ) {
                            if ( $stream->Userid != $user->Id ) {
                                $item = ' ' . $stream->User->Name;
                                $who[] = $item;
                            }
                            else {
                                $broadcast = true;
                            }
                        }
                        $who = implode( ', ', $who );
                        $who = ucfirst( $who );
                        ?> video<?php
                        if ( $broadcast ) {
                            ?> notice<?php
                        }
                    }
                    ?>" style="visibility:hidden; height: 0;">
                    <?php

                    if ( count( $videostreams ) ) {
                        ?>
                        <div class="server">
                            <span><?php
                            echo $who;
                            if ( $count > 1 ) {
                                ?> έχουν<?php
                            }
                            else {
                                ?> έχει<?php
                            }
                            ?> ενεργοποιήσει την camera <?php
							switch ( $gender ) {
								case 'f':
									?>της<?php
									break;
								case 'm':
								default:
									?>του<?php
							}
                            ?>. <strong>Προβολή video.</strong></span>
                            <embed width="267" height="200" align="middle" type="application/x-shockwave-flash" salign="" allowscriptaccess="always" allowfullscreen="false" menu="true" name="zinoVideo" bgcolor="#ffffff" devicefont="false" wmode="window" scale="showall" loop="true" play="true" pluginspage="http://www.adobe.com/go/getflashplayer" quality="high" src="http://static.zino.gr/phoenix/video/zinovideo.swf" id="videochat_<?php
                            echo $channelid;
                            ?>"<?php
                            if ( !$broadcast ) {
                                ?> style="display:none"<?php
                            }
                            ?> /><?php
                            foreach ( $videostreams as $stream ) {
                                ob_start();
                                if ( $stream->Userid == $user->Id ) { // we're broadcasting
                                    ?>document.getElementById( 'videochat_<?php
                                    echo $channelid;
                                    ?>' ).publish( '<?php
                                    echo $stream->User->Name;
                                    ?>.<?php
                                    echo $stream->Authtoken;
                                    ?>' );<?php
                                }
                                else { // we're receiving
                                    ob_start();
                                    ?>Frontpage.Shoutbox.Watch( <?php
                                    echo $channelid;
                                    ?>, '<?php
                                    echo $stream->User->Name;
                                    ?>', '<?php
                                    echo $stream->Authtoken;
                                    ?>' );<?php
                                }
                                $page->AttachInlineScript( ob_get_clean() );
                            }
                            ?>
                        </div><?php
                    }
					?></div><?php
				}
            ?></div><?php
            
            return array( 'tiny' => true );
        }
    }
?>
