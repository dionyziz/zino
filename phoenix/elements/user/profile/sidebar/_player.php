<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			?>
			<div id="mplayersearchmodal">
				<h3 class="modaltitle">Αναζήτηση τραγουδιών...</h3>
				<form>
					<div class="input">
						<input type="text" />
						<input type="submit" style="display: none" />
					</div>
					<table>
						<tbody>
							<tr class="head">
								<td>Name</td>
								<td>Artist</td>
								<td>Album</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div><?php
		}
    }
?>
