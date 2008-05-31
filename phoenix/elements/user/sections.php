<?php

	function ElementUserSections( $section , $theuser ) {
		?><div class="usersections">
			<a href="?p=user&amp;subdomain=<?php
				echo $theuser->Subdomain;
				?>"><?php
				Element( 'user/avatar' , $theuser , 150 , '' , '' );
				?><span class="name"><?php
				echo $theuser->Name;
				?></span>
			</a>
			<ul>
				<li<?php
				if ( $section == 'album' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=albums&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Albums</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'poll' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=polls&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Δημοσκοπήσεις</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'journal' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=journals&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Ημερολόγιο</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'space' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=space&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Χώρος</a></li>
			</ul>
		</div><?php
	}
?>
