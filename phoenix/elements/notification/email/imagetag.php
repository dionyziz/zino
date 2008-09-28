<?php
	/// Content-type: text/plain ///
	class ElementNotificationEmailImagetag extends Element {
		public function Render( Notification $notification ) {
			global $rabbit_settings;
			global $user;
		
			$image = New Image( $notification->Item->Imageid );
		
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
			?> σε αναγνώρισε <?php
			if ( $image->Name != '' ) {
				?>στην εικόνα "<?php
				echo $image->Name;
				?>"<?php
			}
			else if ( $image->Album->Id == $image->User->Egoalbumid ) {
				?>στις φωτογραφίες <?php
				if ( $image->User->Id == $user->Id ) {
					?>σου<?php
				}
				else if ( $image->User->Gender == 'f' ) {
					?>της <?php
				}
				else {
					?>του <?php
				}
				if ( $image->User->Id != $user->Id ) {
					echo $image->User->Name;
				}
			}
			else {
				?>σε μια εικόνα του Album "<?php
				echo $image->Album->Name;
				?>"<?php
			}
			/*
			σε μια εικόνα<?php
			if ( !empty( $image->Name ) ) {
				?>, την "<?php
				echo $image->Name;
				?>"<?php
			}*/
			$subject = ob_get_clean();
			echo $subject;
			
			?>.
			
Για να δεις την εικόνα στην οποία σε αναγνώρισε <?php
			if ( $from->Gender == 'f' ) {
				?>η<?php
			}
			else {
				?>ο<?php
			}
			?> <?php
			echo $from->Name;
			?> κάνε κλικ στον παρακάτω σύνδεσμο:
<?php
			Element( 'url', $image );
			
			Element( 'email/footer' );
			
			return $subject;
		}
	}
?>