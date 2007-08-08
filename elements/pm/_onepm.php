<?php
function ElementPmOnepm( $pmobj ) {
	global $user;
	global $water;
	
	$usersended = $pmobj->Sender;
	$water->Trace( 'username of sender is :' . $usersended->Username() );
	?>
	<div class="message" style="width:620px;"><?php
		/*
		<div class="infobar" onclick="pms.ExpandPm( this );return false;">
			<a href="" style="float:right;padding: 3px 2px 1px 6px;" onclick="pms.DeleteMessage( this.parentNode.parentNode , <?php
			echo $pmobj->Id;
			?> );return false;"><img src="http://static.chit-chat.gr/images/cross.png" /></a>
			<div class="infobar_info" style="padding: 3px;height:21px;">από τον <?php /*<a href="" class="user"><b><?php
			echo $pmobj->Sender->Username();
			?></b></a>
			Element( 'user/static' , $pmobj->Sender );
			?>, πριν μία εβδομάδα και μία μέρα</div>
		</div>
		*/
		?>
		<div class="infobar">
			<a href="" style="float:right;" onclick="pms.DeletePm( this.parentNode.parentNode , <?php
			echo $pmobj->Id;
			?> );return false;"><img src="http://static.chit-chat.gr/images/cross.png" /></a>
			<div class="infobar_info" style="padding: 3px;height:21px;display:inline;" onclick="pms.ExpandPm( this );return false;">από τον </div><div style="display:inline" class="infobar_info"><?php
			Element( 'user/static' , $pmobj->Sender );
			?></div><div onclick="pms.ExpandPm( this );return false;" style="display:inline;" class="infobar_info">, πριν μία εβδομάδα και μία μέρα</div>
		</div>

		<div class="text" style="background-color: #f8f8f6;display:none;">
			<div>
				<?php
				echo $pmobj->Text;
				?><br /><br /><br /><br />
            </div>
		</div>
		<div class="lowerline" style="background-color: #f8f8f6;display:none;">
			<div class="leftcorner"> </div>
			<div class="rightcorner"> </div>
			<div class="middle"> </div>
			<div class="toolbar">
				<ul>
					<li><a href="" onclick="<?php
					ob_start();
					?>pms.NewMessage( <?php
					echo w_json_encode( $pmobj->Sender->Username() );
					?> , <?php
					echo w_json_encode( $pmobj->Text );
					?> );return false;<?php
					echo htmlspecialchars( ob_get_clean() );
					?>">Απάντηση</a></li>
				</ul>
			</div>
		</div>
	</div><?php
}
?>