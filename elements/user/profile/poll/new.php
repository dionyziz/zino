<?php

    function ElementUserProfilePollNew( $theuser ) {
        global $page;
        global $user;

        if ( $theuser != $user ) {
            return false;
        }

        $page->AttachStylesheet( 'css/newpoll.css' );
        $page->AttachScript( 'js/newpoll.js' );

        ?><div class="userpoll" style="opacity:0.5;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" id="newpoll">
            <h4 style="height:18px"><a href="" onclick="CreatePoll();return false;" style="background-image:url('http://static.chit-chat.gr/images/icons/add.png');background-repeat:no-repeat;padding-left:20px;height:18px;display:block">δημιουργία δημοσκόπησης</a></h4>
            <ul>
            </ul>
        </div><?php
    }

?>
