<?php

    function ElementPmView( $pm, $folder ) {
        global $user;
        global $water;
        global $rabbit_settings;

        ?><div class="message" style="width:620px;" id="pm_<?php
            echo $pm->Pmid;
            ?>">
            <div class="infobar<?php
            
            if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
                ?> received"<?php
            }
            else {
                ?>" style="cursor:default;"<?php
            }
            ?>><?php
                if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
                    ?><a href="" style="float:right;" onclick="pms.DeletePm( this.parentNode.parentNode, '<?php
                    echo $pm->Pmid;
                    ?>', <?php
                    echo $folder->Id;
                    ?>, '<?php
                    if ( $pm->IsRead() ) {
                        ?>true<?php
                    }
                    else {
                        ?>false<?php
                    }
                    ?>' );return false;"><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>delete.png" /></a><?php
                }
                if ( !$pm->IsRead() && $folder->Typeid != PMFOLDER_OUTBOX ) {
                    ?><img style="float:left;padding: 0px 4px 3px 2px;" src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>email_open.png" alt="Νέο μήνημα" title="Νέο μήνυμα" /><?php
                }
                ?><div class="infobar_info" onclick="pms.ExpandPm( this, <?php
                if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
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
                echo $pm->Pmid;
                ?>, <?php
                echo $folder->Id;
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
                    if ( !isset( $pmuser[ 0 ] ) ) {
                        die( print_r( array( $pm->Pmid, $pm->Folderid, $pm->Sender->Id, $pm->Receivers ) ) );
                    }
                    w_assert( isset( $pmuser[ 0 ] ) );
                    $pmuser = $pmuser[ 0 ];
                }
                if ( !is_object( $pmuser ) ) {
                    var_dump( $pmuser );
                    die();
                }
                if ( $pmuser->Gender == 'female' ) {
                    ?>ην<?php
                }
                else {
                    ?>ον<?php
                }
                ?> </div><div style="display:inline" class="infobar_info"><?php
                if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
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
                echo $pm->Pmid;
                ?>, <?php
                echo $folder->Id; 
                ?>);return false;" style="display:inline;" class="infobar_info">, πριν <?php
                echo $pm->Since;
                ?></div>
            </div>

            <div class="text" style="background-color: #f8f8f6;display:none;">
                <div><?php
                    echo $pm->Text;
                ?><br /><br /><br /><br />
                </div>
            </div>
            <div class="lowerline" style="background-color: #f8f8f6;display:none;">
                <div class="leftcorner"> </div>
                <div class="rightcorner"> </div>
                <div class="middle"> </div>
                <div class="toolbar"><?php
                    if ( $folder->Typeid != PMFOLDER_OUTBOX ) {
                        ?><ul>
                            <li><a href="" onclick="<?php
                            ob_start();
                            ?>pms.NewMessage( <?php
                            echo w_json_encode( $pm->Sender->Name );
                            ?>, <?php
                            echo w_json_encode( $pm->Text );
                            ?> );return false;<?php
                            echo htmlspecialchars( ob_get_clean() );
                            ?>">Απάντηση</a></li>
                        </ul><?php
                    }
                ?></div>
            </div>
        </div><?php
    }

?>
