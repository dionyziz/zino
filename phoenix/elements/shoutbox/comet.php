<?php
    class ElementShoutboxComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Meteor.hostid = '<?php
            echo $user->Id;
            ?>';
            Meteor.host = "universe." + location.hostname;
            Meteor.registerEventCallback( "process", Frontpage.Shoutbox.OnMessageArrival );
            Meteor.joinChannel( "shoutbox", 0 );
            Meteor.mode = 'stream';
            Meteor.connect();
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>