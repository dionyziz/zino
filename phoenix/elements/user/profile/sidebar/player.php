<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			global $user;

			Element( 'user/profile/sidebar/flash', $theuser );
			?>
			<div id="mplayersearchmodal" class="modal">
				<h2>Βάλε μουσική στο προφίλ σου!</h2>
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
			</div>
			<?php
		}
    }
?>
