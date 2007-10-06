<?php
	function ElementNotifyFrontpage() {
		global $user;
		global $page;
		global $libs;
		
		$libs->Load( 'notify' );
		$page->AttachStyleSheet( 'css/notify.css' );
		$page->AttachScript( 'js/coala.js' );
        
		$notifies = Notify_GetByUser( $user->Id() , 1 , 5 , true );
		if ( count( $notifies ) > 0 ) {
			?><div id="notify">
				<ol><?php
				foreach ( $notifies as $i => $notif ) {
					$fromuser = $notif->UserFrom();
					$typeid = $notif->Typeid();
					?><li class="<?php
							if ( $i != 0 ) {
								?>next <?php
							}
							if ( $notif->Typeid() <= 3 ) {
								?>comment<?php
							}
							else {
								?>friend<?php
							}
							?>"><a href="" class="hide" title="Απόκρυψη" onclick="Coala.Warm( 'notify/markasread' , { notifyid : <?php
                            echo $notif->Id();
                            ?> , navigate : false , hidewhat : this.parentNode } );return false;"></a><?php
                            if ( $fromuser->Exists() ) {
    							if ( $fromuser->Gender() == 'female' ) {
    								?>H<?php
    							}
    							else {
    								?>Ο<?php
    							}
    							?> <?php
    							Element( 'user/static' , $fromuser , true , false );
    							if ( $notif->Typeid() <= 2 ) {
                                    ?> απάντησε <?php
                                }
                            }
                            else {
                                ?>Απάντηση <?php
                            }
							if ( $notif->Typeid() <= 3 ) {
								?> στο <a href="" onclick="Coala.Warm( 'notify/markasread' , { notifyid : <?php
								echo $notif->Id();
								?> , navigate : true , hidewhat : false } );return false;">σχόλιό σου</a><?php
							}
							else {
								?> σε πρόσθεσε <a href="" onclick="Coala.Warm( 'notify/markasread' , { notifyid : <?php
								echo $notif->Id();
								?> , navigate : true , hidewhat : false } );return false;">στους φίλους</a><?php
							}
							?></li>
							<?php
				}
			?></ol>
			</div><?php
		}
	}
?>