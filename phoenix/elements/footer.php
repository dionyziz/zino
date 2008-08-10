<?php
    
    class ElementFooter extends Element {
        protected $mPersistent = array();
        public function Render() {
            ?><div class="footer">
                <ul>
                    <li><a href="?p=contact">Επικοινώνησε</a></li>
                    <li><a href="?p=tos">Όροι χρήσης</a></li>
                    <li><a href="?p=advertise">Διαφημίσου εδώ</a></li>
                </ul>
                <div class="copy">
                    &copy; 2008 <a href="http://www.kamibu.com/">Kamibu</a>
                </div>
            </div><?php
        }
    }
?>
