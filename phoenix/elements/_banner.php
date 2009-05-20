<?php
    
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ?>
           <div id="lbanner">
                <h1>
                    <a href="http://www.zino.gr">
                        <img src="http://static.zino.gr/phoenix/logo.png" />
                    </a>
                </h1>
           </div>
           <div id="rbanner">
           </div>
           <div id="mbanner">
                <div id="loggedinmenu">
                    <span class="avatar50">
                        <img src="http://static.zino.gr/phoenix/dionyziz.png" alt="dionyziz" title="dionyziz" />
                        <span class="rounded50"></span>
                    </span>
                    <a href="#" class="bannerinlink">Προφίλ</a>
                    <a href="#" class="bannerinlink">Ρυθμίσεις</a>
                    <a href="#" class="bannerinlink">Βρες φίλους</a>
                    <a href="#" class="bannerinlink">Έξοδος</a>
                </div>
           </div><?php
       }
    }
?>
