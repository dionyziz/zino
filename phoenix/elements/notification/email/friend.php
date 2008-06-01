<?php
    /// Content-type: text/plain ///
	function ElementNotificationEmailFriend( Notification $notification ) {
        $from = $notification->FromUser;

        w_assert( $from instanceof User );
        w_assert( $from->Exists() );

        if ( $from->Gender == 'f' ) {
            ?>Η<?php
        }
        else {
            ?>Ο<?php
        }
        ?> <?php
        echo $from->Name;
        ?> σε πρόσθεσε <?php
        if ( $infriends ) { // TODO
            ?>και <?php
            if ( $from->Gender == 'f' ) {
                ?>αυτή<?php
            }
            else {
                ?>αυτός<?php
            }
            ?> <?php
        }
        ?>στους φίλους <?php
        if ( $from->Gender == 'f' ) {
            ?>της<?php
        }
        else {
            ?>του<?php
        }
        ?>.

        Για να δεις το προφίλ <?php
        if ( $from->Gender == 'f' ) {
            ?>της<?php
        }
        else {
            ?>του<?php
        }
        ?> <?php
        echo $from->Name;
        ?> κάνε κλικ στον παρακάτω σύνδεσμο:
        
        <?php
        Element( 'user/url', $from );

        Element( 'notification/email/footer' );

        return ''; // TODO: subject
	}
?>
