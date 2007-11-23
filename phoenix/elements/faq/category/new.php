<?php
	function ElementFaqCategoryNew( tInteger $eid, tBoolean $noname, tBoolean $nodescription, tBoolean $noparent ) {
		global $page;
		global $libs;
		global $user;
		
        $eid = $eid->Get();
        $noname = $noname->Get();
        $nodescription = $nodescription->Get();
        $noparent = $noparent->Get();
        
		$libs->Load( 'faq' );
		$page->AttachStylesheet( 'css/faq.css' );
		
		if ( !FAQ_CanModify( $user ) ) {
			return Element( '404' );
		}
		
		if ( ValidId( $eid ) ) {
			$category = New FAQ_Category( $eid );
			$onedit = true;
		}
		else {
			$category = New FAQ_Category( array() );
			$onedit = false;
		}
		
		$page->SetTitle( "Συχνές Ερωτήσεις: " . ( $onedit ? "Επεξεργασία" : "Δημιουργία" ) . " Κατηγορίας" );
	
		?><h3 style="margin-top: 20px;"><?php
		if ( $onedit ) {
			?>Επεξεργασία<?php
		}
		else {
			?>Δημιουργία<?php
		}
		?> Κατηγορίας</h3><?php

		?><div style="float: left; width: 600px; border-right: 1px solid #ccc;">
			<form enctype="multipart/form-data"  method="post" action="do/faq/category/new"><?php
				if ( $onedit ) {
					?><input type="hidden" name="eid" value="<?php
					echo $eid;
					?>" /><?php
				}
				?>Όνομα:  <input type="text" name="name" id="name" value="<?php
					echo htmlspecialchars( $category->Name() );
				?>" /><br /><br />
				Περιγραφή:<br /> <textarea name="description" id="description" cols="60" rows="10"><?php
					echo htmlspecialchars( $category->Description() );
				?></textarea><br /><br />
				Εικονίδιο: <?php
				
				if ( $onedit ) {
					?><img src="image.php?id=<?php
					echo $category->IconId();
					?>" alt="<?php
					echo htmlspecialchars( $category->Name() );
					?>" style="width: 50px; height: 50px;" />
					<br />
					Kαινούργιο Εικονίδιο: <input type="file" name="icon" size="50" /> <small>(Άφησέ το κενό εάν δε θέλεις το εικονίδιο να αλλάξει)</small>
					<?php
				}
				else {
					?><input type="file" name="icon" size="50" /><?php
				}
				
				?><br /><br />
				<input type="submit" value="<?php
					if ( $onedit ) {
						?>Επεξεργασία<?php
					}
					else {
						?>Δημιουργία<?php
					}
					?>" />	<input type="reset" value="Επαναφορά" />
				</form><?php
				
				if ( $noname ) {
					?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ πληκτρολογήστε ένα όνομα</b><?php
				}
				if ( $nodescription ) {
					?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ πληκτρολογήστε μια περιγραφή</b><?php
				}
				if ( $noparent ) {
					?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ επιλέξτε μια κατηγορία-γονέα</b><?php
				}
				
			?></div>
			<div style="margin-left: 608px;">
				<h4>Υπάρχουσες Κατηγορίες</h4>
				
				<ul style="padding-left: 30px; list-style-type: none;"><?php
				
					$ocategories = FAQ_AllCategories();
					
					foreach ( $ocategories as $ocategory ) {
						?><li>
							<img src="image.php?id=<?php
							echo $ocategory->IconId();
							?>" alt="<?php
							echo $ocategory->Name();
							?>" style="width: 16px; height: 16px;" />
							<a href="?p=faqc&amp;id=<?php
							echo $ocategory->Id();
							?>"><?php
							echo $ocategory->Name();
							?></a>
						</li><?php
					}
					
				?></ul>
			</div>
			
			<div style="clear:both" />
			<br /><br /><a href="?p=faq" style="color: #3568eb; font-size: 10pt; font-weight: bold;">&#171;Επιστροφή στις Συχνές Ερωτήσεις</a><?php
		
	}

?>