<?php

    function ElementDeveloperAbresasMassComments() {
        ?><h2>Mass Comments</h2>
        <form method="post" action="/phoenix/do/comment/fill">
            Typeid: <input type="text" name="typeid" /><br />
            Itemid: <input type="text" name="itemid" /><br />
            <input type="submit" value="Fill with comments!" />
        </form><?php
    }

?>
