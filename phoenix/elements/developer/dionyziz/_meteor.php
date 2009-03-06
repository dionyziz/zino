<?php
    class ElementDeveloperDionyzizMeteor extends Element {
        public function Render() {
            global $page;
            
            $page->AttachScript( 'http://universe.zino.gr/meteor.js' );
            ob_start();
            ?>
            Meteor.hostid = '409897502705';
            Meteor.host = "universe.zino.gr";
            Meteor.registerEventCallback( "process", function ( data ) { alert( data ); } );
            Meteor.joinChannel("test", 5);
            Meteor.mode = 'stream';
            Meteor.connect();
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
