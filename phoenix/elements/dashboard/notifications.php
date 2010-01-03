<?php
    class ElementDashboardNotifications extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            $libs->Load( 'notify/notify' );
            
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user );
            
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
                                echo '1 ενημέρωση';
                            }
                            else {
                                if ( $notifycount <= 10 ) {
                                    echo $notifycount;
                                }
                                else {
                                    echo '10+';
                                }
                                echo ' νέες ενημερώσεις';
                            }
                        ?></h2>
                        <ol>

                        </ol>
                    </div>

                </div>
            </div><?php
        }
    }
?>
