<?php
	class ElementDeveloperDionyzizContacts extends Element {
		public function Render( tText $username, tText $password ) {
			global $libs;

			$username = $username->Get();
			$password = $password->Get();

			$libs->Load( 'contacts/fetcher' );

			$gmail = New ContactsFetcher();

			if ( $gmail->Login( $username, $password ) ) {
				$contacts = $gmail->Retrieve();
				?><p><?php
				echo count( $contacts );
				?> contacts found.</p>
				<ul><?php
				foreach ( $contacts as $email => $name ) {
					?><li><?php
					echo htmlspecialchars( $email );
					?></li><?php
				}
				?></ul><?php
			}
			else {
				?>Invalid login details provided.<?php
			}
		}
	}
?>
