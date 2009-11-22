<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			?>
			<div id="mplayersearchmodal">
				<div class="search">
					<div class="input">
						<input type="text" value="Αναζήτηση..." />
						<input type="image" src="http://t2.gstatic.com/images?q=tbn:pytqWl5NIsyw8M:http://www.bcbsm.com/homepage/images/icon_search.gif" />
					</div>
				</div>
				<div class="list">
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
