<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			global $user;
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			if( $user->HasPermission( 60 ) ) {
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
									<th>Όνομα</th>
									<th>Καλλιτέχνης</th>
									<th>Άλμπουμ</th>
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
