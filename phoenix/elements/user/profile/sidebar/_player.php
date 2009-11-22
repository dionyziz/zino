<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			?>
			<div id="mplayersearchmodal">
				<div class="search">
					<div class="input">
						<input type="text" value="Αναζήτηση..." />
						<input type="image" src="" alt="" />
					</div>
				</div>
				<div class="list">
					<table>
						<tbody>
							<tr class="head">
								<td>Όνομα</td>
								<td>Καλλιτέχνης</td>
								<td>Άλμπουμ</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><?php
		}
    }
?>
