<?php
    class ElementDeveloperDionyzizWYSIWYG extends Element {
        public function Render() {
            ?><div class="wysiwyg-youtube-preview">
                <img src="http://img.youtube.com/vi/LYI_hjGY5bU/2.jpg" alt="YouTube Video Preview" />
                <span onclick="WYSIWYG.VideoPlay( 'LYI_hjGY5bU', this.parent )"></span>
            </div><?php
        }
    }
?>
