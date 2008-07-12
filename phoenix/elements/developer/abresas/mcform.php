<?php

    class ElementDeveloperAbresasMcform extends Element {
        public function Render() {
            ?><br /><br /><br /><form method="post" action="do/testmc">
            Value: <input type="text" name="value" /><br />
            <input type="submit" value="Submit" />
            </form><?php
        }

    }
?>
