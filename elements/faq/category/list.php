<?php
	function ElementFaqCategoryList( $parentcategory = 0 ) {		
        global $xc_settings;
		global $user;
        
		?><div class="box">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" /></div>
				<h3>Κατηγορίες</h3>
			</div>
			<div class="body">
				<ul class="categories" style="vertical-align: top;"><?php
				
					$categories = FAQ_AllCategories();
					
					foreach ( $categories as $category ) {
						?><li style="clear:left;">
							<a href="?p=faqc&amp;id=<?php
							echo $category->Id();
							?>"><?php
							
							Element( 'image', $category->Icon(), 16, 16, "", "padding:2px;", $category->Name() );
							
							echo $category->Name();
							?></a>
						</li><?php
					}
				
				if ( $user->CanModifyCategories() ) {
					?><div style="width: 100%; text-align: right;">
						<a href="?p=addfaqc">
							Προσθήκη <img src="<?php
	                        echo $xc_settings[ 'staticimagesurl' ];
	                        ?>icons/page_new.gif" width="16" height="16" alt="Προσθήκη κατηγορίας FAQ" />
						</a>
					</div><?php
				}
				?></ul>
			</div>
		</div><?php
	}

?>