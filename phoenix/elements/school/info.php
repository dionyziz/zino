<?php
	class ElementSchoolInfo extends Element {
		
		public function Render( $school , $link ) {
			?><div class="gname"><?php
                if ( $school->Institution->Exists() ) {
                    if ( $school->Institution->Avatar->Exists() ) {
                        $avatar = $school->Institution->Avatar;
                        Element( 'image/view', $avatar->Id, $avatar->Userid, $avatar->Width, $avatar->Height, IMAGE_PROPORTIONAL_210x210, '', $school->Institution->Name );
                    }
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
				?></h2><?php
                if ( $school->Institution->Exists() ) {
                    ?><h3><?php
                    echo htmlspecialchars( $school->Institution->Name );
                    ?></h3><?php
                }
                ?>
			</div>
			<div class="eof"></div><?php
		}
	}
?>
