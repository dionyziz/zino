<?php

	function ElementUserProfileCCRelated( $theuser ) {
		global $rabbit_settings;
		
		?><div class="ccrelated">
			<h4>σχετικά με <?php
		echo $rabbit_settings[ 'applicationname' ];
		?></h4>
			<ul>
                <?php
                if ( $theuser->Username() == '_daemon_' || $theuser->Rights() < 20 ) {
                    ?>
    				<li><dl>
    					<dt>ρόλος</dt>
    					<dd><?php
                            switch ( $theuser->Username() ) {
                                case '_daemon_':
                                    ?>The Fallen One<?php
                                    break;
                                default:
            						echo mystrtolower( $theuser->Rank() );
                                    break;
                            }
    					?></dd>
    				</dl></li><?php
                }
                ?>
				<li><dl class="l">
					<dt>κατάταξη</dt>
					<dd><?php
						strtolower( Element( 'user/avatar/title', $theuser ) );
					?></dd>
				</dl></li>
				<li><dl>
					<dt>μέλος</dt>
					<dd>εδώ και <?php
						echo $theuser->RegisterSince();
					?></dd>
				</dl></li><?php		
			?></ul>
		</div><?php
	}
	
?>
