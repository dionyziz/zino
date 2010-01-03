<?php
    class ElementDashboardNotifications extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            $libs->Load( 'notify/notify' );
            
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user, 0, 21 );
            
            $notifycount = $notifs->TotalCount();
            
            ?><div id="notifications">
                <div style="" class="shadow-left"></div>
                <div style="" class="shadow-right"></div>
                <div style="" class="shadow-bottom">
                    <div class="real"></div>
                </div>
                <div style="" class="shadow-bl"></div>
                <div style="" class="shadow-br"></div>
                <div id="notifybox">
                    <div class="border">
                        <a href="notifications" class="maximize" title="Μεγιστοποίηση"></a>
                        <a href="notifications" class="minimize" title="Ελαχιστοποίηση"></a>
                        <h2><?php
                            if ( $notifycount == 0 ) {
                                echo 'καμία ενημέρωση';
                            }
                            elseif ( $notifycount == 1 ) {
                                echo '1 νέα ενημέρωση';
                            }
                            else {
                                if ( $notifycount <= 20 ) {
                                    echo $notifycount;
                                }
                                else {
                                    echo '20+';
                                }
                                echo ' νέες ενημερώσεις';
                            }
                        ?></h2>
                        <ol><?php
                            if ( $notifications->TotalCount() > 0 ) {
                                $notifs = $notifs->ToArray();
                                $vnotifs = array_slice( $notifs , 0 , 5 );
                                $inotifs = array_slice( $notifs , 5 );
                                foreach ( $vnotifs as $notif ) {
                                    Element( 'notif/view', $notif );
                                }
                            }
                        ?></ol>
                    </div>

                </div>
            </div><?php
        }
    }
?>
