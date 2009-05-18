<?php
    
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ?><div class="header" id="banner">
            <h1><a href="<?php
            echo $rabbit_settings[ 'webaddress' ];
?>">&nbsp;</a></h1>
            <div style="z-index:500;position:absolute;top:2px;right:100px">
                <a href="http://intze.zino.gr/journals/Genoktonia_twn_Ellinwn_tou_Pontou_19_5" title="90 χρόνια από τη γενοκτονία του Πόντου">
                    <img src="http://static.zino.gr/images/aetos.png" alt="90 χρόνια από τη γενοκτονία του Πόντου" />
                </a>
            </div>
            <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
            <?php   
                if ( !$user->Exists() ) {
                    ?><form action="do/user/login" id="loginForm" method="post">
                        <ul>
                        <li><a href="join" class="register icon">Δημιούργησε λογαριασμό</a></li>
                        <li>·</li>
                        <li><a href="?#login" onclick="Banner.Login();return false" class="login icon">Είσοδος</a></li>
                        <li style="display:none">·</li>
                        <li style="display:none">Όνομα: <input type="text" name="username" /> Κωδικός: <input id="bannerPasswd" type="password" name="password" /></li>
                        <li style="display:none"><input type="submit" value="Είσοδος" class="button" /></li>
                        </ul>
                    </form><?php
                }
                else {
                    ?><ul>
                    <li title="Προβολή προφίλ"><a href="<?php
                    ob_start();
                    Element( 'user/url', $user->Id , $user->Subdomain );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>" class="profile"><?php
                    if ( $user->Avatar->Id > 0 ) {
                        Element( 'image/view', $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height ,  IMAGE_CROPPED_100x100 , '' , $user->Name, '' , true , 16 , 16 , 0 );
                    }
                    Element( 'user/name', $user->Id , $user->Name , $user->Subdomain , false );
                    ?></a></li>
                    <li>·</li>
                    <li><a id="unreadmessages" href="messages" class="messages icon<?php
                    $unreadCount = $user->Count->Unreadpms;
                    if ( $unreadCount > 0 ) {
                        ?> unread<?php
                    }
                    ?>"><span>&nbsp;</span><?php
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
                ?><form method="post" action="do/user/logout"><a href="" onclick="this.parentNode.submit(); return false" class="logout">Έξοδος<span>&nbsp;</span></a></form><?php
            }
            ?>
            <a class="search" href="?p=search" title="Αναζήτησε φίλους!">&nbsp;</a>
            <div class="eof"></div>
            </div><?php
            $page->AttachInlineScript( 'Banner.OnLoad();' );
        }
    }
?>
