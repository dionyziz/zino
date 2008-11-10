<?php
	class ElementSchoolInfo extends Element {
		
		public function Render( $schoolname , $institutionname , $avatar ) {
			?><div class="gname"><?php
				if ( $avatar->Exists() ) {
					?><img src="" alt="" title="" /><?php
				}
				?>
				<h2><?php
				echo htmlspecialchars( $schoolname );
				?></h2>
				<h3><?php
				echo htmlspecialchars( $institutionname );
				?></h3>
			</div>
			<div class="eof"></div><?php
		}
	}
?>