<?php
    class ElementEventList extends Element {
        public function Render() {
            global $libs;
            global $xc_settings;
            
            $libs->Load( 'event' );
            
            $profiletypes = array( EVENT_USERPROFILE_EDUCATION_UPDATED, EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED, EVENT_USERPROFILE_RELIGION_UPDATED, 
                                   EVENT_USERPROFILE_POLITICS_UPDATED, EVENT_USERPROFILE_SMOKER_UPDATED, EVENT_USERPROFILE_DRINKER_UPDATED,
                                   EVENT_USERPROFILE_ABOUTME_UPDATED, EVENT_USERPROFILE_MOOD_UPDATED, EVENT_USERPROFILE_LOCATION_UPDATED,
                                   EVENT_USERPROFILE_HEIGHT_UPDATED, EVENT_USERPROFILE_WEIGHT_UPDATED, EVENT_USERPROFILE_HAIRCOLOR_UPDATED,
                                   EVENT_USERPROFILE_EYECOLOR_UPDATED 
            );

            $finder = New EventFinder();
            $events = $finder->FindLatest( 0, 15 );
            ?><div class="latestevents">
                <h2>Συνέβησαν πρόσφατα</h2>
                <div class="list"><?php
                    foreach ( $events as $i => $event ) {
                        if ( in_array( $event->Typeid, $profiletypes ) ) { 
                            $type = 'profile_update';
                        }    
                        else {
                            $type = $event->Typeid;
                        }
                        $info[ $event->User->Id ][ $type ][] = $event;
                        $visited[ $event->User->Id ][ $type ] = false;
                    }
                    $j = 0;
                    foreach ( $events as $event ) {
                        if ( in_array( $event->Typeid, $profiletypes ) ) {
                            $type = 'profile_update';
                        }
                        else {
                            $type = $event->Typeid;
                        }
                        if ( !$visited[ $event->User->Id ][ $type ] ) {
                            ++$j;
                            if ( $j > 20 ) {
                                break;
                            }
                            $eventlist = $info[ $event->User->Id ][ $type ];
                            $visited[ $event->User->Id ][ $type ] = true;
                            ?><div class="event">
                                <div class="toolbox">
                                </div>
                                <div class="who"><?php
                                    Element( 'user/display', $event->User->Id, $event->User->Avatar->Id, $event->User );
                                    switch ( $type ) {
                                        case EVENT_IMAGE_CREATED:
                                            ?> ανέβασε <?php
                                            if ( count( $eventlist ) > 1 ) {
                                                ?>τις εικόνες<?php
                                            }
                                            else {
                                                ?>την εικόνα<?php
                                            }
                                            break;
                                        case EVENT_JOURNAL_CREATED:
                                            ?> καταχώρησε στο ημερολόγιο<?php
                                            break;
                                        case EVENT_POLL_CREATED:
                                            ?> δημιούργησε <?php
                                            if ( count( $eventlist ) > 1 ) {
                                                ?>τις δημοσκοπήσεις<?php
                                            }
                                            else {
                                                ?>τη δημοσκόπηση<?php
                                            }
                                            break;
                                        case 'profile_update':
                                            ?> ανανέωσε το προφίλ<?php
                                            break;
                                        case EVENT_USER_CREATED:
                                            ?> δημιούργησε λογαριασμό<?php
                                            break;
                                        case EVENT_FRIENDRELATION_CREATED:
                                            ?> πρόσθεσε στους φίλους<?php
                                            break;
                                    }
                                ?></div>
                                <div class="subject"><?php
                                    switch ( $type ) {
                                        case EVENT_IMAGE_CREATED:
                                            foreach ( $eventlist as $one ) {
                                                ?><a href="?p=photo&amp;id=<?php
                                                echo $one->Item->Id;
                                                ?>"><?php
                                                Element( 'image/view', $one->Item->Id, $one->Item->User->Id, $one->Item->Width, $one->Item->Height, IMAGE_CROPPED_100x100, '', $one->User->Name, 'margin-right:3px;', false, 0, 0, 0 );
                                                ?></a><?php
                                            }
                                            break;
                                        case EVENT_JOURNAL_CREATED:
                                            $helper = array();
                                            foreach ( $eventlist as $i => $one ) {
                                                if ( $one->Item->Exists() ) {
                                                    $domain = str_replace( '*', urlencode( $one->Item->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                                                    $url = $domain . 'journals/' . $one->Item->Url;
                                                    $helper[ $i ] =  "<a href=\"$url\">" . htmlspecialchars( $one->Item->Title ) . '</a>';
                                                }
                                            }
                                            echo implode( ', ', $helper );
                                            break;
                                        case EVENT_POLL_CREATED:
                                            $helper = array();
                                            foreach ( $eventlist as $i => $one ) {
                                                if ( $one->Item->Exists() ) {
                                                    $helper[ $i ] = '<a href="?p=poll&amp;id=' . $one->Item->Id .'">' . htmlspecialchars( $one->Item->Question ) . '</a>';
                                                }
                                            }
                                            echo implode( ', ', $helper );
                                            break;
                                        case EVENT_FRIENDRELATION_CREATED:
                                            foreach ( $eventlist as $one ) {
                                                ?><a href="<?php
                                                Element( 'user/url', $one->Item->Friend->Id, $one->Item->Friend->Subdomain );
                                                ?>"><?php
                                                Element( 'user/avatar', $one->Item->Friend->Avatar->Id, $one->Item->Friend->Id, $one->Item->Friend->Avatar->Width, $$one->Item->Friend->Avatar->Height, $one->Item->Friend->Name, 100, '', 'margin-right:3px;', false, 0, 0 );
                                                ?></a><?php
                                            }
                                            break;
                                        case 'profile_update':
                                            Element( 'event/profileupdate', $eventlist );
                                            break;        
                                        case EVENT_USER_CREATED:
                                            ?><a href="<?php
                                            Element( 'user/url', $event->Item->Id, $event->Item->Subdomain );
                                            ?>"><?php
                                            Element( 'user/avatar', $event->Item->Avatar->Id, $event->Item->Id, $event->Item->Avatar->Width, $event->Item->Avatar->Height, $event->Item->Name, 100, '', 'margin-right:3px;', false, 0, 0 );
                                            ?></a><?php
                                            break;
                                    }
                                    ?>
                                </div>                    
                            </div><?php
                        }
                    }
                ?></div>
            </div><?php
        }
    }
?>
