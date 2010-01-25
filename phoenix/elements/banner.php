<?php
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            global $libs;
            
            ?>
           <div id="lbanner" class="s1_0057">
                <h1>
                    <a href="<?php
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>" class="s1_0055"> </a>
                </h1>
           </div>
           <div id="rbanner" class="s1_0056">
           </div>
           <div id="mbanner" class="sx_0006">
                <div<?php
                if ( $user->Exists() ) {
                    ?> id="loggedinmenu"<?php   
                }
                ?>><?php
                    if ( $user->Exists() ) {
                        if ( $user->Avatarid > 0 ) {
                            $libs->Load( 'image/image' );
                            
                            ?><div style="display:none"><?php
                            Element( 'image/view' , $user->Avatarid , $user->Id , $user->Avatar->Width , $user->Avatar->Height , IMAGE_CROPPED_100x100 , 'banneravatar' , $user->Name , '' , true , 50 , 50 , 0 );
                            ?></div><?php
                        }
                        else {
                            ?><div style="display:none">
                            <span class="imageview">
                                <img src="http://static.zino.gr/phoenix/anonymous100.jpg" alt="<?php
                                echo htmlspecialchars( $user->Name );
                                ?>" title="<?php
                                echo htmlspecialchars( $user->Name );
                                ?>" class="banneravatar" />
                            </span>
                            </div><?php
                        }
                        ?><ul>
                            <li>
                            <a href="<?php
                            ob_start();
                            Element( 'user/url' , $user->Id , $user->Subdomain );
                            echo htmlspecialchars( ob_get_clean() );
                            ?>" class="bannerinlink">Προφίλ</a>
                            
                            </li>
                            <li>
                                <a href="settings" class="bannerinlink">Ρυθμίσεις</a>
                            </li>
                            <li>
                                <a id="unreadmessages" href="messages" class="bannerinlink<?php
                                $libs->Load( 'user/count' );
                                
                                $unreadcount = $user->Count->Unreadpms;
                                if ( $unreadcount > 0 ) {
                                    ?> unread<?php
                                }
                                ?>"><?php
                                if ( $unreadcount > 0 ) {
                                    echo $unreadcount;
                                    ?> νέ<?php
                                    if( $unreadcount == 1 ) {
                                        ?>ο μήνυμα<?php  
                                    }
                                    else {
                                        ?>α μηνύματα<?php
                                    }
                                }
                                else {
                                    ?>Μηνύματα<?php
                                }
                                ?></a>
                            </li>
                            <li id="zinostorelink">
                                <a href="http://store.zino.gr/?p=product&amp;id=27" class="bannerinlink" style="font-weight:bold" title="♥">Εκπτώσεις<span class="shadowN">Εκπτώσεις</span><span class="shadowNE">Εκπτώσεις</span><span class="shadowE">Εκπτώσεις</span><span class="shadowSE">Εκπτώσεις</span><span class="shadowS">Εκπτώσεις</span><span class="shadowSW">Εκπτώσεις</span><span class="shadowW">Εκπτώσεις</span><span class="shadowNW">Εκπτώσεις</span><span class="wizard">Εκπτώσεις</span></a>
                            </li>
                            <li>
                                <form method="post" action="do/user/logout">
                                    <a href="#" class="bannerinlink" onclick="this.parentNode.submit();return false;">Έξοδος</a>
                                </form>
                            </li>
                        </ul><?php
                    }
                    else {
                        ?><form id="loginform" action="do/user/login" method="post"><div>
                            <input id="lusername" class="s2_0008" type="text" name="username" value="ψευδώνυμο" />
                            <input id="lpassword" class="s2_0008" type="text" name="password" value="κωδικός" />
                            <input type="submit" class="s2_0007" id="loginbutton" value="Είσοδος" />
                            <span>
                                ή <a href="join" class="wlink">Εγγραφή</a>
                            </span>
                        </div></form><?php
                    }
                ?></div>
           </div><?php
           $page->AttachInlineScript( 'Banner.OnLoad();' );
       }
    }
?>
