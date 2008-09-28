<?php

	class ElementUserSections extends Element {
		public function Render( $section , $theuser ) {
			?><div class="usersections">
				<a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>"><?php
					Element( 'user/avatar' , $theuser->Avatar->Id , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 150 , '' , '' , false , 0 , 0 );
					?><span class="name"><?php
					echo $theuser->Name;
					?></span>
				</a>
				<ul>
					<li<?php
					if ( $section == 'album' ) {
						?> class="selected"<?php
					}
					?>><a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>albums">Albums</a></li>
					<li>·</li>
					<li<?php
					if ( $section == 'poll' ) {
						?> class="selected"<?php
					}
					?>><a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>polls">Δημοσκοπήσεις</a></li>
					<li>·</li>
					<li<?php
					if ( $section == 'journal' ) {
						?> class="selected"<?php
					}
					?>><a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>journals">Ημερολόγιο</a></li>
					<li>·</li>
					<li<?php
					if ( $section == 'favourites' ) {
						?> class="selected"<?php
					}
					?>><a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>favourites">Αγαπημένα</a></li>
					<li>·</li>
					<li<?php
					if ( $section == 'relations' ) {
						?> class="selected"<?php
					}
					?>><a href="<?php
					Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
					?>friends">Φίλοι</a></li>
				</ul>
			</div><?php
		}
	}
?>
