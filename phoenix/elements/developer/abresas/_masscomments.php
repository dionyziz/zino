<?php

    function ElementDeveloperAbresasMassComments() {
        ?><form method="post" action="/do/comment/fill">
            Typeid: <input type="text" name="typeid" /><br />
            Itemid: <input type="text" name="itemid" /><br />
            <input type="submit" value="Fill with comments!" />
        </form><?php
    }

?>
