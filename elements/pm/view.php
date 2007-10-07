<?php
	function ElementPMView( $pm ) {
		global $user;
		global $page;
        global $xc_settings;
		
		$page->AttachStyleSheet( 'css/comment.css' ); 
		?><div class="comment" style="margin-left:10px">
			<div class="upperline">
				<div class="leftcorner">&nbsp;</div>
				<div class="title"><?php
				if ( !( $pm->IsRead() ) && !( $pm->UserIsSender() ) ) {
					?><b><u>Νέο Μήνυμα:</u></b> <?php
				}
				if ( $pm->UserIsSender() ) {
					?>προς <?php
				}
				else {
					?>από <?php
				}
				Element( 'user/static', $pm->User(), $link = true, $bold = true );
				?>, <?php
				echo $pm->Time();
				?></div>
				<div class="fade">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="filler">&nbsp;</div>
			</div>
			<div class="avatar"><?php
				Element( 'user/icon', $pm->User() );
			?></div>
			<div class="text">
				<div><?php
						echo $pm->Text();
					?><br /><br /><br /><?php
					
					Element( 'user/sig', $pm->User() );
					
				?></div>
			</div>
			<div class="lowerline">
				<div class="leftcorner">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="middle">&nbsp;</div><?php

                if ( $user->Rights() >= $xc_settings[ 'readonly' ] ) {
                    ?><div class="toolbar">
                        <ul>
                            <li><a href="javascript: Pms.Answer( '<?php 
                                echo $pm->User()->Username() 
                            ?>' )"><?php
                            if ( $pm->UserIsSender() ) {
                                ?>Νέο Μήνυμα<?php
                            }
                            else {
                                ?>Απάντηση<?php
                            }
                            ?></a></li>
                        </ul>
                    </div><?php
                }
			?></div>
		</div><?php
	}

?>
