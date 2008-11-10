<?php
	class ElementSchoolInfo extends Element {
		
		public function Render( $school , $link ) {
			?><div class="gname"><?php
				if ( $school->Institution->Avatar->Exists() ) {
					?><img src="" alt="" title="" /><?php
				}
				?><h2><?php
				if ( $link ) {
					?><a href="?p=school&amp;id=<?php
					echo $school->Id;
					?>"><?php
				}
				echo htmlspecialchars( $school->Name );
				if ( $link ) {
					?></a><?php
				}
				?></h2>
				<h3><?php
				echo htmlspecialchars( $school->Institution->Name );
				?></h3>
			</div>
			<div class="eof"></div><?php
		}
	}
?>