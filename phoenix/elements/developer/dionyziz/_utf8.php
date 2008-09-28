<?php
    class ElementDeveloperDionyzizUTF8 extends Element {
        public function Render() {
            ?><br /><br /><form action="do/album/rename" accept-charset="utf8" method="post">
                Album Id: <input type="text" value="" name="albumid" /><br />
                Album Name: <input type="text" value="" name="albumname" />
                <br /><input type="submit" value="Rename" /><?php
            ?></form><?php
        }
    }
?>
