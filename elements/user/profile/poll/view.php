<?php

    function ElementUserProfilePollView( $poll, $theuser ) {
        global $page;

        $page->AttachStylesheet( "css/poll.css" );

        ?><div class="userpoll">
            <h4>Ποιος είναι ο πιο hot tokio hotel member?!</h4>
            <ul>
                <li><dl>
                    <dt><input type="radio" id="p_4_0" name="option" value="0" /></dt>
                    <dd><label for="p_4_0">Bill!</label></dd>
                </dl></li>
                <li><dl class="l">
                    <dt><input type="radio" id="p_4_1" name="option" value="1" /></dt>
                    <dd><label for="p_4_1">Tom!</label></dd>
                </dl></li>
                <li><dl>
                    <dt><input type="radio" id="p_4_2" name="option" value="2" /></dt>
                    <dd><label for="p_4_2">Georg!</label></dd>
                </dl></li>
                <li><dl class="l">
                    <dt><input type="radio" id="p_4_3" name="option" value="3" /></dt>
                    <dd><label for="p_4_3">Gustav!</label></dd>
                </dl></li>
            </ul>
        </div><?php
    }

?>
