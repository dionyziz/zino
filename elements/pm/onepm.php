<?php
function ElementPmOnepm( $pmobj , $folder ) {
	global $user;
	global $water;

	$usersended = $pmobj->Sender;
	?><div class="message" style="width:620px;" id="pm_<?php
        echo $pmobj->Id;
        ?>">
		<div class="infobar"<?php
		if ( $folder != -2 ) {
			?> onmousedown="pms.DragPm( 'pm_<?php
			echo $pmobj->Id;
			?>' );"<?php
		}
		else {
			?> style="cursor:default;"<?php
		}
		?>><?php
			if ( $folder != -2 ) {
				?><a href="" style="float:right;" onclick="pms.DeletePm( this.parentNode.parentNode.parentNode , <?php
				echo $pmobj->Id;
				?> , <?php
				if ( $pmobj->IsRead() ) {
					?>true<?php
				}
				else {
					?>false<?php
				}
				?> );return false;"><img src="http://static.chit-chat.gr/images/cross.png" /></a><?php
			}
			if ( !$pmobj->IsRead() && $folder != -2 ) {
				?><img style="float:left;padding: 0px 4px 3px 2px;" src="http://static.chit-chat.gr/images/email_open_image.png" alt="Νέο μήνημα" title="Νέο μήνυμα" /><?php
			}
			?><div class="infobar_info" onclick="pms.ExpandPm( this , <?php
			if ( $folder != -2 ) {
				if ( !$pmobj->IsRead() ) {
					?> true<?php
				}
				else {
					?> false<?php
				}
			}
			else {
				?> false<?php
			}
			?> , <?php
			echo $pmobj->Id;
			?> );return false;"><?php
			if ( $folder != -2 ) {
                ?> από τ<?php
                $pmuser = $pmobj->Sender;
			}
			else {
				?> προς τ<?php
                $pmuser = $pmobj->Receivers;
			}
            if ( is_array( $pmuser ) && count( $pmuser ) > 1 ) {
                ?>ους<?php
            }
            else if ( is_array( $pmuser ) ) {
                $pmuser = $pmuser[ 0 ];
            }
            if ( !is_array( $pmuser ) && is_object( $pmuser) && $pmuser->Gender() == 'female' ) {
                ?>η<?php
                switch ( strtolower( substr( $pmuser->Username() , 0 , 1 ) ) ) {
                    case 'a':
                    case 'e':
                    case 'o':
                    case 'u':
                    case 'i':
                    case 't':
                    case 'p':
                    case 'k':
                        ?>ν<?php
                        break;
                    default:
                }
            }
            else if ( !is_array( $pmuser ) && is_object( $pmuser ) ) {
                ?>ο<?php
                switch ( strtolower( substr( $pmuser->Username() , 0 , 1 ) ) ) {
                    case 'a':
                    case 'e':
                    case 'o':
                    case 'u':
                    case 'i':
                    case 't':
                    case 'p':
                    case 'k':
                        ?>ν<?php
                        break;
                    default:
                }
            }
			?> </div><div style="display:inline" class="infobar_info"><?php
			if ( $folder != -2 ) {
				Element( 'user/static' , $pmobj->Sender );
			}
			else {
				$receivers = $pmobj->Receivers;
				while ( $receiver = array_shift( $receivers ) ) {
					Element( 'user/static' , $receiver );
                    if ( count( $receivers ) ) {
                        ?>, <?php
                    }
				}
			}
			?></div><div onclick="pms.ExpandPm( this , <?php
			if ( !$pmobj->IsRead() ) {
				?> true<?php
			}
			else {
				?> false<?php
			}
			?>, <?php
			echo $pmobj->Id;
			?> );return false;" style="display:inline;" class="infobar_info">, πριν <?php
			echo dateDistance( $pmobj->Date );
			?></div>
		</div>

		<div class="text" style="background-color: #f8f8f6;display:none;">
			<div>
				<?php
				echo nl2br( htmlspecialchars( $pmobj->Text ) );
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
