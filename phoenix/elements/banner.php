<?php
    
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ?><div class="header" id="banner">
            <h1><a href="<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>"><img src="http://static.zino.gr/phoenix/zino.png" alt="Zino" /></a></h1>
            <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
            <?php   
                if ( !$user->Exists() ) {
                    ?><form action="do/user/login" method="post">
                        <ul>
                        <li><a href="join" class="register icon"><span>&nbsp;</span>Δημιούργησε λογαριασμό</a></li>
                        <li>·</li>
                        <li><a href="?#login" onclick="Banner.Login();return false" class="login icon"><span>&nbsp;</span>Είσοδος</a></li>
                        <li style="display:none;">·</li>
                        <li style="display:none;">Όνομα: <input type="text" name="username" /> Κωδικός: <input type="password" name="password" /></li>
                        <li style="display:none;"><input type="submit" value="Είσοδος" class="button" /></li>
                        </ul>
                    </form><?php
                }
                else {
                    ?><ul>
                    <li title="Προβολή προφίλ"><a href="<?php
                    Element( 'user/url', $user->Id , $user->Subdomain );
                    ?>" class="profile"><?php
                    Element( 'image/view', $user->Avatar, IMAGE_CROPPED_100x100, '', $user->Name, $user->Name, '', true, 16, 16  );
                    Element( 'user/name', $user->Id , $user->Name , $user->Subdomain , false );
                    ?></a></li>
                    <li>·</li>
                    <li><a id="unreadmessages" href="messages" class="messages icon"><span>&nbsp;</span><?php
                        $unreadCount = $user->Count->Unreadpms;
                        if ( $unreadCount > 0 ) {
                            echo $unreadCount;
                            ?> νέ<?php
                            if( $unreadCount == 1 ) {
                                ?>ο μήνυμα<?php  
                            }
                            else {
                                ?>α μηνύματα<?php
                            }
                        }
                        else {
                            ?>Μηνύματα<?php
                        }
                    ?></a></li>
                    <li>·</li>
                    <li><a href="settings" class="settings icon"><span>&nbsp;</span>Ρυθμίσεις</a></li>
                    </ul><?php
                }
            if ( $user->Exists() ) {
                ?><form method="post" action="do/user/logout"><a href="" onclick="this.parentNode.submit(); return false;" class="logout">Έξοδος<span>&nbsp;</span></a></form><?php
            }
            ?>
            <div class="eof"></div>
            </div><?php
        }
    }
?>
