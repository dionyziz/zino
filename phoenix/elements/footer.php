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
            </div>
            <!-- <a class="hoodie" href="http://zino.gr/store.php?p=product&amp;id=27">
                <img src="http://static.zino.gr/phoenix/store/hoodie-footer.png" alt="hoodie" />
            </a>-->
            <?php
        }
    }
?>
