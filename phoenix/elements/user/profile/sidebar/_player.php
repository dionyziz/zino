<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			?>
			<div id="mplayersearchmodal">
				<div class="search">
					<div class="input">
						<input type="text" />
						<input type="submit" style="display: none" />
					</div>
				</div>
				<div class="list">
					<h3 class="modaltitle">Αναζήτηση τραγουδιών...</h3>
					<table>
						<tbody>
							<tr class="head">
								<td>Name</td>
								<td>Artist</td>
								<td>Album</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><?php
		}
    }
?>
