<?php
    class ElementiPhoneBanner extends Element {
        public function Render() {
            global $rabbit_settings;

            ?><div class="logo"><img src="<?php
            echo $rabbit_settings[ 'imagesurl' ];
            ?>iphone/zino.png" width="50" height="17" /></div><?php
        }
    }
?>
