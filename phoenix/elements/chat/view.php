<?php
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
			
			if ( $user->Exists() ) {
				$channels = ChannelFinder::FindByUserid( $user->Id );
			}
			else {
				$channels = array();
			}
			$channels[ 0 ] = array( 'authtoken' => '', 'participants' => array() );
			
			$finder = New ShoutboxFinder();
			$chats = $finder->FindByChannel( 0, 0, 100 );
				
			$chats = array_reverse( $chats );
			
            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew0' );
            Comet.Subscribe( 'FrontpageShoutboxTyping0' );
            User = '<?php
            echo $user->Name;
            ?>';
			var Channels = <?php
			echo w_json_encode( $channels );
			?>;
            <?php
            $page->AttachInlineScript( ob_get_clean() );
            
            ?>
			<div id="tabs">
				<ul><?php
					foreach ( $channels as $channelid => $channeldata ) {
						?><li<?php
						if ( $channelid == 0 ) {
							?> class="main focus"<?php
						}
						?>><a href=""><?php
						if ( $channelid == 0 ) {
							$name = 'Zino';
						}
						else {
							if ( count( $channeldata[ 'participants' ] ) == 1 ) {
								$name = $channeldata[ 'participants' ][ 0 ][ 'name' ];
								$img = $channeldata[ 'participants' ][ 0 ][ 'avatar' ];
								?><img src="http://images2.zino.gr/media/<?php
								echo $img;
								?>/<?php
								echo $img;
								?>_100.jpg" alt="<?php
								echo $name;
								?>" /><?php
							}
							else {
								$name = 'Συνομιλία ' . count( $channeldata[ 'participants' ] ) . ' ατόμων';
							}
						}
						?><span><?php
						echo $name;
						?></span></a></li><?php
					}
					?>
				</ul>
			</div>
            <div>
                <ol><?php
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
                </ol><?php
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
