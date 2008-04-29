<?php

	function ElementUserSections( $section , $theuser ) {
		?><div class="usersections">
			<a href="">
				<img src="http://static.zino.gr/phoenix/mockups/dionyziz.200.jpg" style="width:150px;height:150px;" alt="dionyziz" />
				<span class="name"><?php
				echo $theuser->Name;
				?></span>
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
