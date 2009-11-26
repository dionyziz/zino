<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			global $user;

			//firebug lite for ie...
			if ( $user->Id == 3890 ) {
				global $page;
				
				$page->AttachScript( 'http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js' );
			}
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid, $theuser );
			if ( $theuser->HasPermission( 60 ) ) {
				?>
				<div id="mplayersearchmodal">
					<div class="toolbar">
						<div class="exit"></div>
					</div>
					<div class="search">
						<div class="input">
							<input type="text" value="Αναζήτηση..." />
							<div class="search"></div>
						</div>
					</div>
					<div class="list">
						<table>
							<thead>
								<tr class="hidden">
									<th class="name">Όνομα</th>
									<th class="artist">Καλλιτέχνης</th>
									<th class="album">Άλμπουμ</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div><?php
			}
		}
    }
?>
