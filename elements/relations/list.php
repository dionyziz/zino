<?php
	function ElementRelationsList() {
		global $page;
		global $user;
		global $libs;
		global $xc_settings;
		
		if( !( $user->CanModifyStories() ) ) {
			return Redirect();
		}
		
		$libs->Load( 'relations' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/relations.js' );
		$page->AttachScript( 'js/modal.js' );
		$page->AttachStylesheet( 'css/modal.css' );
		$page->SetTitle( 'Σχέσεις' );
		
		?><h3>Σχέσεις</h3><?php
		
		$relations = AllRelations();
		
		if( count( $relations ) ) {
			?><br /><a id="newr" onclick="Relations.create();return false;" alt="Νέα Σχέση" title="Νέα Σχέση" style="cursor: pointer;">Δημιούργησε μια νέα σχέση</a>
			<form id="newrform" action="do/relations/new" method="post" style="display: none;">
			<fieldset style="width: 40px">
			<legend><font color="#2412FE"><b>Δημιουργία Σχέσης</b></font></legend>
			<input type="text" name="type" class="bigtext" value="Γράψε εδώ την νέα Σχέση!" onfocus="((this.value=='Γράψε εδώ την νέα Σχέση!') ? this.value='' : this.value=this.value);" onkeypress="function( e ) {
											return submitenter(this,e);
										}
			"/>
			<a onclick="g( 'newrform' ).submit();return false;" style="cursor: pointer;" alt="Δημιουργία" title="Δημιουργία"><img src="<?php 
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/accept.png" /></a>&nbsp;
			<a onclick="Relations.cancelCreate();return false;" style="cursor: pointer;" alt="Ακύρωση" title="Ακύρωση"><img src="<?php 
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/cancel.png" /></a>
			</fieldset>
			</form>
			<br /><br /><br />
			
			<div style="float: right;">
			Ta top relations tha emfanizonte<br />
			edo se ena table
			</div>
			
			<ul id="relations" style="list-style-type: none;"><?php
			
			foreach( $relations as $relation ) {
				$id = $relation->Id;
				?><li <?php
                if ( $user->CanModifyCategories() ) {
                    ?>onmouseout="Relations.hideLinks( <?php
                        echo $id;
                    ?> )" onmouseover="Relations.showLinks( <?php
                        echo $id;
                    ?> )" <?php
                }
                ?>id="relation_<?php
				echo $id;
				?>"><span id="rraw_<?php
				echo $id;
				?>"><?php
				echo htmlspecialchars( $relation->Type );
				?></span><?php
				if( $user->CanModifyCategories() ) {
					?>&nbsp;
					<a id="reditlink_<?php
					echo $id;
					?>" style="cursor: pointer; display: none;" onclick="Relations.edit( <?php
					echo $id;
					?> );"><img src="<?php 
					echo $xc_settings[ 'staticimagesurl' ];
					?>icons/edit.png" width="12" height="12" alt="Επεξεργασία" title="Επεξεργασία" /></a>
					<a id="rdeletelink_<?php
					echo $id;
					?>" style="cursor: pointer; display: none;" onclick="Relations.deleteM( <?php
					echo $id;
					?> );"><img src="<?php 
					echo $xc_settings[ 'staticimagesurl' ];
					?>icons/delete.png" width="12" height="12" alt="Διαγραφή" title="Διαγραφή" /></a><?php
				}
				?></li><?php
			}
		?></ul><?php
		}
		else {
			?>Δεν υπάρχουν Σχέσεις<?php
		}
	}
?>
