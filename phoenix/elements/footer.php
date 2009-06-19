<?php
    class ElementFooter extends Element {
        public function Render() {
            ?><div>
                <a class="wlink" href="contact">Επικοινώνησε</a>
                <a class="wlink" href="tos">Όροι χρήσης</a>
                <a class="wlink" href="?p=ads">Διαφήμιση</a>
            </div>
            <div id="copyleft">
                <span>&copy; 2009</span> <a class="wlink" href="http://www.kamibu.com/">Kamibu</a>
            </div><?php
        }
    }
?>
