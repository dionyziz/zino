<?php
    
    class ElementFooter extends Element {
        protected $mPersistent = array();
        public function Render() {
            ?><div class="footer">
                <ul>
                    <li><a href="contact">Επικοινώνησε</a></li>
                    <li><a href="tos">Όροι χρήσης</a></li>
                    <li><a href="advertise">Διαφήμιση</a></li>
                </ul>
                <div class="copy">
                    &copy; <?php
                        echo date( 'Y'); ?> 
                    <a href="http://www.kamibu.com/">Kamibu</a>
                </div>
            </div><?php
        }
    }
?>
