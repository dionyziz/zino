<?php

    function ElementPmView( UserPM $pm, PMFolder $folder ) {
        global $user;
        global $water;

        $usersended = $pm->Sender;
        ?><div class="message" style="width:620px;" id="pm_<?php
            echo $pm->Id;
            ?>">
            <div class="infobar"<?php
            if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
                ?> onmousedown="pms.DragPm( 'pm_<?php
                echo $pm->Id;
                ?>' );"<?php
            }
            else {
                ?> style="cursor:default;"<?php
            }
            ?>><?php
                if ( $folder != -2 ) {
                    ?><a href="" style="float:right;" onclick="pms.DeletePm( this.parentNode.parentNode, <?php
                    echo $pm->Id;
                    ?>, <?php
                    if ( $pm->IsRead() ) {
                        ?>true<?php
                    }
                    else {
                        ?>false<?php
                    }
                    ?> );return false;"><img src="http://static.chit-chat.gr/images/cross.png" /></a><?php
                }
                if ( !$pm->IsRead() ) {
                    ?><img style="float:left;padding: 0px 4px 3px 2px;" src="http://static.chit-chat.gr/images/email_open_image.png" alt="Νέο μήνημα" title="Νέο μήνυμα" /><?php
                }
                ?><div class="infobar_info" onclick="pms.ExpandPm( this, <?php
                if ( $folder != -2 ) {
                    if ( !$pm->IsRead() ) {
                        ?> true<?php
                    }
                    else {
                        ?> false<?php
                    }
                }
                else {
                    ?> false<?php
                }
                ?>, <?php
                echo $pm->Id;
                ?> );return false;"><?php
                if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
                    ?> από τ<?php
                    $pmuser = $pm->Sender;
                }
                else {
                    ?> προς τ<?php
                    $pmuser = $pm->Receivers;
                }
                if ( is_array( $pmuser ) && count( $pmuser ) > 1 ) {
                    ?>ους<?php
                }
                else if ( is_array( $pmuser ) ) {
                    $pmuser = $pmuser[ 0 ];
                }
                if ( !is_array( $pmuser ) && is_object( $pmuser) && $pmuser->Gender == 'f' ) {
                    ?>η<?php
                    switch ( strtolower( substr( $pmuser->Name, 0, 1 ) ) ) {
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
                    switch ( strtolower( substr( $pmuser->Name, 0 , 1 ) ) ) {
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
                    Element( 'user/name', $pm->Sender );
                }
                else {
                    $receivers = $pm->Receivers;
                    while ( $receiver = array_shift( $receivers ) ) {
                        Element( 'user/name', $receiver );
                        if ( count( $receivers ) ) {
                            ?>, <?php
                        }
                    }
                }
                ?></div><div onclick="pms.ExpandPm( this, <?php
                if ( !$pm->IsRead() ) {
                    ?> true<?php
                }
                else {
                    ?> false<?php
                }
                ?>, <?php
                echo $pm->Id;
                ?> );return false;" style="display:inline;" class="infobar_info">, πριν <?php
                echo $pm->Since;
                ?></div>
            </div>

            <div class="text" style="background-color: #f8f8f6;display:none;">
                <div><?php
                    echo nl2br( htmlspecialchars( $pm->Text ) );
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
                        echo w_json_encode( $pm->Sender->Name );
                        ?>, <?php
                        echo w_json_encode( $pm->Text );
                        ?> );return false;<?php
                        echo htmlspecialchars( ob_get_clean() );
                        ?>">Απάντηση</a></li>
                    </ul>
                </div>
            </div>
        </div><?php
    }

?>
