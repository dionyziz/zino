<?php
	/*
		Developer: Dionyziz

        MASKED
            By: Dionyziz
            Reason: Video chat
	*/

    define( 'CHAT_HISTORY_DEFAULT_LIMIT', 50 );
	
    class ElementChatView extends Element {
        public function Render() {
            global $page;
            global $libs;
            global $user;
            
            $page->SetTitle( 'Συζήτηση' );
            
            $page->AttachStylesheet( 'css/_chat.css' );
            $page->AttachStylesheet( 'css/emoticons.css' );
            $page->AttachStylesheet( 'css/wysiwyg.css' );
            
            $page->AttachScript( 'js/settings.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/jquery.js' );
            $page->AttachScript( 'js/coala.js' );
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/comet.js' );
            $page->AttachScript( 'js/_chat.js' );
            $page->AttachScript( 'js/wysiwyg.js' );
            
            $libs->Load( 'chat/message' );
            $libs->Load( 'chat/channel' );
            $libs->Load( 'chat/video' );
			
			if ( $user->Exists() ) {
				$channels = ChannelFinder::FindByUserid( $user->Id );
			}
			else {
				$channels = array();
			}
			$channels[ 0 ] = array( 'authtoken' => '', 'participants' => array() );
			
			$finder = New ShoutboxFinder();
			$chats = $finder->FindByChannel( array_keys( $channels ), 0, CHAT_HISTORY_DEFAULT_LIMIT );
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
			
            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew0' );
            Comet.Subscribe( 'FrontpageShoutboxTyping0' );
			<?php
			if ( $user->Exists() ) {
				?>
				Comet.Subscribe( 'FrontpageShoutboxNew<?php
				echo $user->Id;
				?>x<?php
				echo substr( $user->Authtoken, 0, 10 );
				?>' );
				Comet.Subscribe( 'FrontpageShoutboxTyping<?php
				echo $user->Id;
				?>x<?php
				echo substr( $user->Authtoken, 0, 10 );
				?>' );
				<?php
			}
			?>
            User = '<?php
            echo $user->Name;
            ?>';
			Frontpage.Shoutbox.Init( <?php
			echo w_json_encode( $channels );
			?> );
            <?php
            $page->AttachInlineScript( ob_get_clean() );
            
			if ( count( $channels ) > 1 ) {
				?>
				<div id="tabs">
					<ul><?php
						ksort( $channels );
						
						foreach ( $channels as $channelid => $channeldata ) {
							?><li<?php
							if ( $channelid == 0 ) {
								?> class="main focus"<?php
							}
							?>><a href="" id="switchchannel_<?php
							echo $channelid;
							?>" style="display:none"><?php
							if ( $channelid == 0 ) {
								$name = 'Zino';
							}
							else {
								if ( count( $channeldata[ 'participants' ] ) == 1 ) {
									$name = $channeldata[ 'participants' ][ 0 ][ 'name' ];
									?><img src="<?php
									Element(
										'image/url',
										$channeldata[ 'participants' ][ 0 ][ 'avatar' ],
										$channeldata[ 'participants' ][ 0 ][ 'id' ],
										IMAGE_CROPPED_100x100
									);
									?>" /><?php
								}
								else {
									$name = 'Συνομιλία ' . ( count( $channeldata[ 'participants' ] ) + 1 ) . ' ατόμων';
								}
							}
							?><span><?php
							echo $name;
							?></span></a></li><?php
						}
						?>
					</ul>
				</div><?php
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
                        $count = 0;
                        $broadcast = false;
                        foreach ( $videostream as $stream ) {
                            if ( $stream->Userid != $user->Id ) {
                                switch ( $stream->User->Gender ) {
                                    case 'f':
                                        $item = 'η';
                                        $gender = 'f';
                                        break;
                                    case 'm':
                                    default:
                                        $item = 'ο';
                                        $gender = 'm';
                                }
                                $item .= ' ' . $stream->User->Name;
                                $who[] = $item;
                                ++$count;
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
                            if ( $count > 1 ) {
                                ?>τους<?php
                            }
                            else {
                                switch ( $gender ) {
                                    case 'f':
                                        ?>της<?php
                                        break;
                                    case 'm':
                                    default:
                                        ?>του<?php
                                }
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
                                    ?>Frontpage.Shoutbox.Watch( '<?php
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
					?><ol><?php
						$prevuser = '';
						$prevtime = '';
                        if ( count( $chats ) >= CHAT_HISTORY_DEFAULT_LIMIT ) {
                            ?><li class="history">
                                &bull;<a href="">Παλιότερα μηνύματα</a>&bull;
                            </li><?php
                        }

						foreach ( $chats as $chat ) {
							?><li id="s_<?php
							echo $chat->Id;
							?>" class="text"><?php
							ob_start();
							Element( 'date/diff', $chat->Created );
							$time = ob_get_clean();
							if ( $time != $prevtime ) {
								$prevtime = $time;
								?><span class="time"><?php
								echo $prevtime;
								?></span><?php
							}
							?> <strong<?php
							if ( $chat->User->Id == $user->Id ) {
								?> class="u"<?php
							}
							?>><?php
							if ( $prevuser != $chat->User->Name ) {
								$prevuser = $chat->User->Name;
								Element( 'user/name', $chat->User->Id, $chat->User->Name, $chat->User->Subdomain, false );
							}
							else {
								?>&#160;<?php
							}
							?></strong> <div class="text"><?php
							echo nl2br( $chat->Text );
							?></div></li><?php
						}
					?>
					</ol></div><?php
				}
                if ( $user->Exists() ) {
                    ?>
                        
                    <div class="bottom">
                        <div class="toolbox">
                            <div class="video" title="Δείξε την κάμερά μου">
                            </div>
                        </div>
                        <div class="typehere">
                            <textarea>Πρόσθεσε ένα σχόλιο στη συζήτηση</textarea>
                        </div>
                    </div>
                    
                    <?php
                }
            ?></div><?php
            
            return array( 'tiny' => true );
        }
    }
?>
