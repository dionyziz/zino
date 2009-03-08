<?php
    class ElementShoutboxComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Meteor.hostid = '<?php
            echo $user->Id;
            ?>';
            Meteor.host = "universe.www.zino.gr";
            Meteor.registerEventCallback( "process", function ( data ) {
                Frontpage.Shoutbox.OnMessageArrival( data );
            } );
            Meteor.joinChannel( "shoutbox", 0 );
            Meteor.mode = 'stream';
            Meteor.connect();
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>