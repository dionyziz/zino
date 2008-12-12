<?php
    class ElementSchoolMembersMembers extends Element {

        public function Render( $members ) { 
            global $xc_settings;

			?><div class="members">
				<h4>Μέλη<?php
				if ( count( $members ) > 5 ) {
					?> <span>(<a href="?p=schoolmembers">προβολή όλων</a>)</span><?php
				}
				?></h4><?php
				if ( count( $members ) == 0 ) {
					?>Το εκπαιδευτικό ίδρυμα δεν έχει μέλη<?php
				}
				else {
					Element( 'user/list' , $members );
				}
			?></div>
			<div class="eof"></div><?php
        }
    }
?>
