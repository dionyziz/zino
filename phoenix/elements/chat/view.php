<?php
	/*
		Developer: Dionyziz
	*/
	
    class ElementChatView extends Element {
        public function Render() {
            global $page;
            global $libs;
            global $user;
            
            $page->SetTitle( 'Συζήτηση' );
            
            $page->AttachStylesheet( 'css/chat.css' );
            $page->AttachStylesheet( 'css/emoticons.css' );
            $page->AttachStylesheet( 'css/wysiwyg.css' );
            
            $page->AttachScript( 'js/settings.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/jquery.js' );
            $page->AttachScript( 'js/coala.js' );
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/comet.js' );
            $page->AttachScript( 'js/chat.js' );
            $page->AttachScript( 'js/wysiwyg.js' );
            
            $libs->Load( 'shoutbox' );
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
			$chats = $finder->FindByChannel( array_keys( $channels ), 0, 100 );
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
					?>
                    <div id="messages_<?php
                    echo $channelid;
                    ?>" class="channelmessages" style="display:none">
                    <?php
                    $finder = New ChatVideoFinder();
                    $videostreams = $finder->FindByChannelId( $channelid );

                    if ( count( $videostreams ) ) {
                        ?>
                        <div class="server">
                            <embed width="600" height="200" align="middle" type="application/x-shockwave-flash" salign="" allowscriptaccess="sameDomain" allowfullscreen="false" menu="true" name="zinoVideo" bgcolor="#ffffff" devicefont="false" wmode="window" scale="showall" loop="true" play="true" pluginspage="http://www.adobe.com/go/getflashplayer" quality="high" src="http://static.zino.gr/phoenix/video/zinovideo.swf" id="videochat_<?php
                            echo $channelid;
                            ?>" /><?php
                            foreach ( $videostreams as $stream ) {
                                ob_start();
                                if ( $stream->Userid == $user->Id ) { // we're broadcasting
                                    ?>$( '#videochat_<?php
                                    echo $channelid;
                                    ?>' )[ 0 ].publish( '<?php
                                    echo $stream->User->Name;
                                    ?>.<?php
                                    echo $stream->User->Authtoken;
                                    ?>' );<?php
                                }
                                else { // we're receiving
                                    ?>$( '#videochat_<?php
                                    echo $channelid;
                                    ?>' )[ 0 ].watch( '<?php
                                    echo $stream->User->Name;
                                    ?>.<?php
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
						foreach ( $chats as $chat ) {
							?><li id="s_<?php
							echo $chat->Id;
							?>"><?php
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
                    ?><div class="typehere">
                        <textarea>Πρόσθεσε ένα σχόλιο στη συζήτηση</textarea>
                    </div><?php
                }
            ?></div><?php
            
            return array( 'tiny' => true );
        }
    }
?>
