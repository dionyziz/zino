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
            <div style="z-index:100;background-image:url('http://static.zino.gr/phoenix/halloween/haunted-hat.gif');background-repeat:none;width:82px;height:42px;position:absolute;bottom:117px;left:145px">
            </div>
            <!-- <a class="hoodie" href="http://zino.gr/store.php?p=product&amp;id=27">
                <img src="http://static.zino.gr/phoenix/store/hoodie-footer.png" alt="hoodie" />
            </a>-->
            <?php
        }
    }
?>
