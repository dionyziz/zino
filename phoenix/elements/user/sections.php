<?php
	function ElementUserSections( $section ) {
		global $page;
		
		$page->AttachStyleSheet( 'css/user/sections.css' );
		
		?><div class="usersections">
			<a href="">
				<img src="images/avatars/dionyziz.200.jpg" style="width:150px;height:150px;" alt="dionyziz" />
				<span class="name">dionyziz</span>
			</a>
			<ul>
				<li<?php
				if ( $section == 'album' ) {
					?> class="selected"<?php
				}
				?>><a href="">Albums</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'poll' ) {
					?> class="selected"<?php
				}
				?>><a href="">Δημοσκοπήσεις</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'journal' ) {
					?> class="selected"<?php
				}
				?>><a href="">Ημερολόγιο</a></li>
			</ul>
		</div><?php
	}
?>