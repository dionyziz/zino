<?php
	function ElementCategoryNew( tInteger $id, tBoolean $categoryexists, tBoolean $invalidparent, tBoolean $norights, tBoolean $invalidcategory, tBoolean $selfparent ) {
		global $user;
		global $xc_settings;
		global $page;
		global $libs;
        
        $id = $id->Get();
        
		$page->AttachStylesheet( 'css/rounded.css' );
		$page->SetTitle( "Νέα Kατηγορία" );
		$page->AttachScript( 'js/category.js' );
		$libs->Load( 'category' );

		?><div class="content"><?php
			?><h1><?php
			if ( $id > 0 ) {
				$page->SetTitle( "Επεξεργασία Κατηγορίας" );
				?>Επεξεργασία<?php
				$ecategory = new Category( $id );
				$name = $ecategory->Name();
				$description = $ecategory->Description();
				$parentcategory = $ecategory->ParentId();
				$icon = $ecategory->Icon();
			}
			else {
				?>Δημιουργία<?php
				$icon = 0;
				$id = -1;
				$name = '';
				$description = '';
			}
			?> Κατηγορίας</h1><br/><?php
			
			$pagetitle = "Κατηγορία";
			
			if ( $categoryexists ) {
				?>Το όνομα κατηγορίας που επιλέξατε υπάρχει ήδη!<?php
			}
			else if ( $invalidparent ) {
				?>Η γενική κατηγορία που επιλέξατε δεν είναι έγκυρη!<?php
			}
			else if ( $norights || !$user->CanModifyCategories() ) {
				header( 'Location: http://' . $xc_settings[ 'webaddress' ] );
				return;
			}
			else if ( $invalidcategory ) {
				?>Προσπαθείτε να επεξεργαστείτε μία κατηγορία που δεν υπάρχει!<?php
			}
			else if ( $selfparent ) {
				?>Μία κατηγορία δεν μπορεί να έχει ως γενική κατηγορία τον εαυτό της!<?php
			}
			?>
			
			<form action="do/category/new" method="POST" id="newcategory"><?php
			
			if ( $id > 0 ) {
				?><input type="hidden" name="id" value="<?php
				echo $id; 
				?>" /><?php
			}

			$parented = SubCategories();
			$allcategories = Element( "category/fill", 0, 1, $id, $parented );
			
			?><div class="rectangles">
				<div class="opties">
					<div class="upperline">
						<div class="leftupcorner"></div>
						<div class="rightupcorner"></div>
						<div class="middle"></div>
					</div>
					<div class="rectanglesopts">
						<img src="images/no1tip.png" />
						<span class="directions">Όνομα Κατηγορίας:</span><br />
						<span class="tip">(Αυτό το όνομα θα εμφανίζεται ως επικεφαλίδα στην νέα κατηγορία.)</span><br />
						<input type="text" name="name" size="54" value="<?php
							echo $name; 
						?>"/><br />
						<br />
					</div>
					<div class="downline">
						<div class="leftdowncorner"></div>
						<div class="rightdowncorner"></div>
						<div class="middledowncss"></div>
					</div>
				</div>
			
				<div class="opties">
					<div class="upperline">
						<div class="leftupcorner"></div>
						<div class="rightupcorner"></div>
						<div class="middle"></div>
					</div>
					<div class="rectanglesopts">
						<img src="images/no2tip.png" />
						<span class="directions">Περιγραφή:</span><br />
						<span class="tip">(Πληκτρολόγησε μία σύντομη περιγραφή για την κατηγορία.)</span><br />
						<input name="description" type="text" size="54" value="<?php
							echo $description; 
						?>" class="mybigtext" /><br />
						<br />
					</div>
					<div class="downline">
						<div class="leftdowncorner"></div>
						<div class="rightdowncorner"></div>
						<div class="middledowncss"></div>
					</div>
				</div>

				<div class="opties">
					<div class="upperline">
						<div class="leftupcorner"></div>
						<div class="rightupcorner"></div>
						<div class="middle"></div>
					</div>
					<div class="rectanglesopts">
						<img src="images/no3tip.png" />
						<span class="directions">Γενική Κατηγορία</span><br />
						<span class="tip">(Αν αυτή η κατηγορία είναι υποκατηγορία κάποιας άλλης, επελεξέ την, αλλιώς επέλεξε "Γενική Κατηγορία".)</span><br />
						<select name="parentcategory">
						<option value="0">Γενική Κατηγορία</option><?php
						echo $allcategories;
						?></select><br />
						<br />
					</div>
					<div class="downline">
						<div class="leftdowncorner"></div>
						<div class="rightdowncorner"></div>
						<div class="middledowncss"></div>
					</div>
				</div>
			</div>
			<br/>
			<div id="nextlink" style="text-align:center"><a href="javascript:Categories.submitnewcat();" class="next">Συνέχεια >></a></div>
			</form>
		</div><?php
	}
?>
