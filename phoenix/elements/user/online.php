<?php
	function ElementUserOnline() {
		global $user;
		global $page;
        global $xc_settings;
        
        $page->AttachScript( 'js/frontpage.js' );
        
		$onlineusers = findOnlineUsers();
		$newusers = getNewUsers();
		
		if ( empty( $onlineusers ) ) { // no user is online (and of course the user is anonymous)
			return;
		}
		
		?><div class="box onlinenow">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" alt="" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" alt="" /></div>
				<h3>
					<span id="onlineuheader" style="filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);opacity: 1; cursor: pointer" onclick="Frontpage.SwitchOnlineUsersBox( 'online' );">Συνδεδεμένοι τώρα</span> /
					<span id="newuheader" style="filter:progid:DXImageTransform.Microsoft.Alpha(opacity=30);opacity: 0.3; cursor: pointer;" onclick="Frontpage.SwitchOnlineUsersBox( 'new' );">Νεότεροι</span>
				</h3>
			</div>
			<div class="body">
				<div id="onlineuserscontent"><?php
					$i = 1;
					while( $theuser = array_shift( $onlineusers ) ) {
						?><div><?php
						Element( 'user/icon' , $theuser );
						?></div><?php
						++$i;
						if ( $i > 20 ) {
							break;
						}
					}
					?><div style="clear:both"></div><?php
					if ( count( $onlineusers ) ) {
						?><div id="moreonlineusers" class="boxexpand"><?php
						$i = 1;
						while( $theuser = array_shift( $onlineusers ) ) {
							?><div><?php
							Element( 'user/icon' , $theuser );
							?></div><?php
							++$i;
							if ( $i > 100 ) {
								break;
							}
						}
						?><div style="clear:both"></div>
						</div>
						<a id="onlineuserslink" href="" onclick="ShowMore('onlineusers');return false;" class="arrow" title="Περισσότεροι Συνδεδεμένοι Χρήστες"></a><?php
					}
				?></div>
				<div id="newuserscontent" style="display: none;"><?php
					while( $theuser = array_shift( $newusers ) ) {
						Element( 'user/static', $theuser );
						?><span style="color: #ccc;"> πριν <?php
						echo $theuser->CreateSince();
						?></span>
						<br /><?php
					}
					?><div style="width: 97%; text-align: right;"><a href="?p=userlist&amp;offset=1">Προβολή Όλων</a></div>
				</div>
			</div>
		</div><?php
	
	}
?>
