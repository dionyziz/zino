<?php
    class ElementFooter extends Element {
        public function Render() {
            global $user;
            
            ?><div>
                <a class="wlink" href="about">Πληροφορίες</a>
                <a class="wlink" href="legal">Νομικά</a>
                <a class="wlink" href="?p=ads">Διαφήμιση</a>
            </div>
            <div id="copyleft">
                <span>&copy; 2009</span> <a class="wlink" href="http://www.kamibu.com/">Kamibu</a>
            </div><?php
        }
    }
?>
