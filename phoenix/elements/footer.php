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
                <span>&copy; 2010</span> <a class="wlink" href="http://www.kamibu.com/">Kamibu</a>
            </div>
            <div id="xmas1"></div>
            <div id="xmas2"></div>
            <div id="xmas3"></div>
            <div id="xmas4"></div>
            <?php
        }
    }
?>
