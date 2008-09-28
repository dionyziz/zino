<?php
	/// Content-type: text/plain ///
	class ElementNotificationEmailFriend extends Element {
		public function Render( Notification $notification ) {
			$relationfinder = New FriendRelationFinder();

			$from = $notification->FromUser;

			w_assert( $from instanceof User );
			w_assert( $from->Exists() );

			ob_start();
			if ( $from->Gender == 'f' ) {
				?>Η<?php
			}
			else {
				?>Ο<?php
			}
			?> <?php
			echo $from->Name;
			?> σε πρόσθεσε <?php
			if ( $relationfinder->IsFriend( $from, $notification->ToUser ) == FRIENDS_BOTH ) {
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
			$subject = ob_get_clean();
			echo $subject;
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
			Element( 'user/url', $from->Id , $from->Subdomain );

			Element( 'email/footer' );

			return $subject;
		}
	}
?>
