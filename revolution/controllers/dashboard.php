<?php
    class ControllerDashboard {
        public static function View() {
            isset( $_SESSION[ 'user' ] ) or die( 'not allowed' );
            clude( 'models/db.php' );
            clude( 'models/chat.php' );
            clude( 'models/comment.php' );

            $chatmessages = ChatMessage::ListByChannel( 0, 0, 10 );
            $lastcomments = Comment::ListLatest( 0, 40 );
            
            Template( 'dashboard/view', compact( 'lastcomments', 'chatmessages' ) );
        }
    }
?>
