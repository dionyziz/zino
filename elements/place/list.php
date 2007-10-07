<?php
	function ElementPlaceList() {
		global $page;
		global $user;
		global $libs;		
		global $xc_settings;
        
		if ( !( $user->CanModifyStories() ) || $user->Rights() < $xc_settings[ 'readonly' ] ) {
            return Redirect();
		}
		else {
			$libs->Load( 'place' );
			$page->AttachScript( 'js/places.js' );
			$page->AttachScript( 'js/coala.js' );
			
			$page->SetTitle( 'Περιοχές' );
			
			?><h3>Περιοχές</h3><?php
			
			$places = AllPlaces();
			if ( count( $places ) ) {
				?><br />
				<a href="javascript:Places.create()" id="newp">Δημιούργησε μία Περιοχή</a><br />
				<form id="newpform" action="do/place/new" method="post" style="display: none;" onkeypress="return submitenter(this, event);">
					<input type="hidden" name="action" value="create" />
					<input type="text" name="name" class="bigtext" value="Γράψε εδώ την νέα Περιοχή!" onfocus="((this.value=='Γράψε εδώ την νέα Περιοχή!') ? this.value='' : this.value=this.value);" /> 
					<input type="submit" value="Δημιουργεία" class="mybutton" onclick="Places.create(this.form);" />
					<input type="button" value="Ακύρωση" class="mybutton" onclick="Places.cancelCreate(this.form);" />
				</form>
				<br /><br />
				<ul id="places" style="list-style-type: none;"><?php
				
				foreach ( $places as $place ) {
					?><li <?php
					if ( $user->CanModifyCategories() ) {
						?>onmouseout="Places.hideLinks( <?php
							echo $place->Id;
						?> )" onmouseover="Places.showLinks( <?php
							echo $place->Id;
						?> )" <?php
					}
					?>id="place_<?php
					echo $place->Id;
					?>"><span id="praw_<?php
					echo $place->Id;
					?>"><?php
					echo htmlspecialchars( $place->Name );
					?></span><?php
					if ( $user->CanModifyCategories() ) {
						?>&nbsp;<a id="peditlink_<?php
						echo $place->Id;
						?>" style="cursor: pointer; display: none;" onclick="Places.edit( <?php
						echo $place->Id;
						?> )"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/edit.png" width="12" height="12" alt="Επεξεργασία" title="Επεξεργασία" /></a>
						<a id="pdeletelink_<?php
						echo $place->Id;
						?>" style="cursor: pointer; display: none;" onclick="Places.deletep( <?php
						echo $place->Id;
						?> )"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/delete.png" width="12" height="12" alt="Διαγραφή" title="Διαγραφή" /></a><?php
					}
					?></li><?php
				}
				
				?></ul><?php
			}
			else {
				?>Δεν υπάρχουν περιοχές.<br /><?php
			}
		}
	}
?>
