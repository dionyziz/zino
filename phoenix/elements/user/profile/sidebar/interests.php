<?php 
	class ElementUserProfileSidebarInterests extends Element {
		public function Render( $theuser ) {	
			global $libs;
			
			$libs->Load( 'tag' );
			$finder = New TagFinder();
			$tags = $finder->FindByUser( $theuser );
			$bytype = array(
				TAG_HOBBIE => array(),
				TAG_MOVIE => array(),
				TAG_BOOK => array(),
				TAG_SONG => array(),
				TAG_ARTIST => array(),
				TAG_GAME => array(),
				TAG_SHOW => array()
			);
			if ( !empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$bytype[ $tag->Typeid ][] = $tag->Text;
				}
				?><dl><?php
					$hobbies = $bytype[ TAG_HOBBIE ];
					if ( !empty( $hobbies ) ) {
						?><dt><strong>Hobbies</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $hobbies ) );
						?></dd><?php
					}
					$songs = $bytype[ TAG_SONG ];
					if ( !empty( $songs ) ) {
						?><dt><strong>Αγαπημένα τραγούδια</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $songs ) );
						?></dd><?php
					}
					$movies = $bytype[ TAG_MOVIE ];
					if ( !empty( $movies ) ) {
						?><dt><strong>Αγαπημένες ταινίες</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $movies ) );
						?></dd><?php
					}
					$shows = $bytype[ TAG_SHOW ];
					if ( !empty( $shows ) ) {
						?><dt><strong>Αγαπημένες σειρές</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $shows ) );
						?></dd><?php
					}
					$books = $bytype[ TAG_BOOK ];
					if ( !empty( $books ) ) {
						?><dt><strong>Αγαπημένα βιβλία</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $books ) ) ;
						?></dd><?php
					}
					$artists = $bytype[ TAG_ARTIST ];
					if ( !empty( $artists ) ) {
						?><dt><strong>Αγαπημένοι καλλιτέχνες</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $artists ) );
						?></dd><?php
					}
					$games = $bytype[ TAG_GAME ];
					if ( !empty( $games ) ) {
						?><dt><strong>Αγαπημένα παιχνίδια</strong></dt>
						<dd><?php
							echo htmlspecialchars( implode( ", " , $games ) );
						?></dd><?php
					}
					if ( $theuser->Profile->Favquote != '' ) {
						?><dt><strong>Αγαπημένα ρητό</strong></dt>
						<dd><?php
						echo htmlspecialchars( $theuser->Profile->Favquote );
						?></dd><?php
					}
				?></dl><?php
			}
		}
	}
?>
