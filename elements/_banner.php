<?php
    function ElementBanner() {
		global $page;
		global $user;
		global $libs;
		global $xc_settings;
		global $rabbit_settings;
		
		$libs->Load( 'pm' );
        
		$page->AttachStylesheet( 'css/banner.css' );
        $page->AttachScript( 'js/search.js' );
		$page->AttachScript( 'js/animations.js' );
		$page->Attachscript( 'js/user.js' );
		
		?><div class="roku"><?php
			Element( 'user/box' );
		?></div>
		<div class="spacer">&nbsp;</div>
        <div class="roxas">
			<div style="float:right">
				<img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>roxasend.jpg" alt="" />
			</div>
			<a href="<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/"><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>logo-xc.jpg" alt="Chit-Chat" class="logo" /></a>
			<ul>
                <li>
                    <form action="" method="get">
                        <input type="hidden" name="p" value="search" />
                        <input type="text" name="q" id="q" value="Αναζήτηση" class="text" onfocus="Search.Focus(this)" onblur="Search.Blur(this);" /><a href="?p=search" onclick="this.parentNode.submit();return false;"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/magnifier.png" alt="Ψάξε" title="Αναζήτηση" /></a>
                    </form>
                </li><?php
				if ( !$user->IsAnonymous() ) {
					$ureadpms = PM_UserCountUnreadPms( $user );
					
					if ( $ureadpms == 0 ) {
						?><li><a class="messages messagesread" href="?p=pms" title="Μηνύματα"><img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
						?>icons/email.png" alt="Μηνύματα" style="width: 16px; height: 16px; vertical-align: bottom;" /></a></li><?php
					}
					else if ( $ureadpms == 1 ) {
						?><li><a class="messages messagesunread" href="?p=pms">1 Νέο Mήνυμα</a></li><?php
					}
					else {
						?><li><a class="messages messagesunread" href="?p=pms"><?php 
                        echo $ureadpms; 
                        ?> Νέα Μηνύματα</a></li><?php
					}
					?><li><a href="?p=faq" style="padding: 2px;" title="Πληροφορίες">
						<img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
						?>icons/help.png" alt="Πληροφορίες" style="width: 16px; height: 16px;vertical-align: bottom;" />
					</a></li>
					<li><a class="logout" href="do/user/logout">Έξοδος</a></li><?php
				}
				else {
					?><li><a href="?p=faq" title="Πληροφορίες">
						<img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
						?>icons/help.png" alt="Πληροφορίες" style="width: 16px; height: 16px;vertical-align:bottom;" />
					</a></li>
					<li><a href="?p=register" class="register">Νέος χρήστης</a></li><?php
				}
			?>
            </ul>
		</div>
		<br /><?php
	}
?>
