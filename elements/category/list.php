<?php
	function ElementCategoryList() {
		global $user;
		global $libs;
		global $page;
        global $xc_settings;
        
		$libs->Load( 'category' );

		?><div class="box categorybox">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" alt="" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" alt="" /></div>
				<h3>Κατηγορίες</h3>
			</div>
			<div class="body"><?php
				if ( $user->CanModifyCategories() ) {
					?><a href="?p=nc"><img class="newcategory" src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/page_new.gif" title="Νέα Κατηγορία" alt="+" /></a><?php
				}
				
				$parented = Subcategories();
				$parentid = 0;
				$maincategoriescnt = count( $parented[ 0 ] );
				
				if ( $maincategoriescnt == 0 ) {
					?><br />&nbsp;&nbsp;Δεν υπάρχει καμία κατηγορία.<br /><br /><?php
				}
							
				$i = 0;
				foreach( $parented[ $parentid ] as $category ) { // maincategories have 0 as parent
					if ( $i == 5 ) {
						?><div id="morecategories" class="boxexpand"><?php // hide these categories. press the arrow to see 'em!
					}
					?><div class="category">
						<a href="?p=category&amp;id=<?php 
                            echo $category->Id(); 
                            ?>" title="<?php 
                            echo htmlspecialchars( $category->Name() );
                            ?>"><?php
                            Element( 'image', $category->Icon(), 50, 50, 'avatar', '', $category->Name(), $category->Name );
                            ?>
                        </a>
							<h3><a href="?p=category&amp;id=<?php 
                            echo $category->Id(); 
                            ?>" title="<?php 
                            echo htmlspecialchars( $category->Name() );
                            ?>"><?php 
                            echo htmlspecialchars( $category->Name() ); 
                            ?></a></h3>
						    <br /><?php
							if ( isset( $parented[ $category->Id() ] ) ) {
								$j = 1;
								foreach( $parented[ $category->Id() ] as $subcategory ) { // show every category that has the current category as parent
									if( $j != 1 ) {
										?>, <?php
									}
									?><a href="?p=category&amp;id=<?php 
                                    echo $subcategory->Id(); 
                                    ?>"><?php
                                    echo $subcategory->Name(); 
                                    ?></a><?php
									++$j;
								}
							}
						?><div style="clear:left"></div>
					</div><?php
					
					if ( $i == $maincategoriescnt - 1 && $i > 4 ) {	// last category. Display "Show all" link, end "morecategories" <div> and  show "more categories" link...
							?><div class="boxlink">
								<a href="index.php?p=category&amp;id=0" id="categorieslink">Προβολή Όλων</a>
							</div>
						</div><a id="categorieslink" href="javascript:ShowMore('categories' );" class="arrow" title="Περισσότερες κατηγορίες"></a><?php
					}
					++$i;
				}
			?></div>
		</div><?php
	}
?>
