<?php

    function ElementDeveloperAbresasMassComments() {
        ?><h2>Mass Comments</h2>
        <form method="post" action="/phoenix/do/comment/fill">
            Typeid: <select name="typeid">
            <option name="Journal" value="4" />
            <option name="Poll" value="1" />
            <option name="Image" value="2" />
            <option name="User Profile" value="3" />
            </select><br />
            Itemid: <input type="text" name="itemid" /><br /><br />
            <input type="submit" value="Fill with comments!" />
        </form><?php
    }

?>
