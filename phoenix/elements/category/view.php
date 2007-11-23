<?php
	function ElementCategoryView( tInteger $id ) {
		global $page; 
		global $libs;
		global $user;
		global $water;
	    global $xc_settings;
        
        $catid = $id->Get();
        
		$page->AttachStyleSheet( 'css/rounded.css' );
		$page->AttachStyleSheet( 'css/category.css' );
		
		if ( $user->CanModifyCategories() ) {
			$page->AttachScript( 'js/coala.js' );
		}
		
		$libs->Load( 'category' );
		$libs->Load( 'search' );
		$libs->Load( 'article' );
		
		$parentcategory = New Category( $catid );
		
		$page->SetTitle( htmlspecialchars( $parentcategory->Name() ) );
		
		$categories = $parentcategory->Children();
		
		$search = New Search_Articles();
		$search->SetSortMethod( 'date', 'DESC' );
		$search->SetFilter( 'typeid', 0 );
		$search->SetFilter( 'delid', 0 );
		$search->SetFilter( 'category', $parentcategory->Id() );
		$categoryarticles = $search->Get();
		
		?><br /><br /><br />
		<div class="categoryview"><h1><?php
		if ( $catid == 0 ) {
			?>Γενική κατηγορία<?php
		}
		else {
			?>Κατηγορία: <?php
			echo htmlspecialchars( $parentcategory->Name() );
		}
		?></h1><span style="padding-left:20px;" class="categorydescription"><?php
		echo htmlspecialchars( $parentcategory->Description() ); ?></span><br /><br />
		<div class="articles newestarticles" style="margin-left:40px;"><?php
		foreach ( $categoryarticles as $article ) {
			Element( "article/small" , $article );
		}
		?></div><div class="allcategories"><?php
		foreach ( $categories as $category ) {
			?><div class="outercategory" style="width:350px;">
				<div class="thecategory" style="width:350px;">
					<div class="opties" style="width:350px;">
						<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div class="rectanglesopts" style="height:100px;padding:2px 1px 2px 1px;">
							<a href="index.php?p=category&amp;id=<?php
							echo $category->Id(); 
                            ?>" class="categorylink"><?php
							Element( 'image' , $category->Icon() ); 
							echo htmlspecialchars( $category->Name() );
							?></a><br /><span class="categorydescription"><?php
							$description = $category->Description();
							if ( strlen( $description ) > 233 ) {
								echo htmlspecialchars( substr( $description , 0 , 230 ) );
								?>...<?php
							}
							else {
								echo htmlspecialchars( $description );
							}
							?></span><br /><?php
							$subcategoriesnum = $category->CountChildren();
							//$water->Trace( "Category children: ".$subcategoriesnum );
							if ( $subcategoriesnum > 0 ) { 
								if ( $subcategoriesnum == 1 ) { 
									$subtext = "υποκατηγορία";
								}
								else {
									$subtext = "υποκατηγορίες";
								}
								?><br /><br /><span class="categorystats"><?php
								echo $subcategoriesnum . " " . $subtext;
								?></span><?php
								$dsplkoma = true;
							} 
							$articlesnum = $category->CountArticles();
							//echo( "Category articles: ".$articlesnum." Category children: ".$subcategoriesnum );
							if ( $articlesnum > 0 ) {
								if ( $articlesnum == 1 ) {
									$subtext = "άρθρο";
								}
								else {
									$subtext = "άρθρα";
								}
								?><span class="categorystats"><?php
									if ( $dsplkoma ) { 
										?>, <?php
									}
									echo $articlesnum . " " . $subtext;
									?></span><?php 
							}
							?>
						</div>
						<div class="downline">
							<div class="leftdowncorner"></div>
							<div class="rightdowncorner"></div>
							<div class="middledowncss"></div>
						</div>
					</div>
				</div>
			</div><?php
		} 
		?></div><?php
		if ( $user->CanModifyStories() && $user->Rights() >= $xc_settings[ 'readonly' ] ) { 
			if ( $user->CanModifyCategories() && $catid != 0 ) {
				?><a href="index.php?p=nc&amp;id=<?php
				echo $parentcategory->Id();
				?>">Επεξεργασία κατηγορίας &gt;&gt;</a><br />
				<a onclick="if ( confirm( 'Θέλεις σίγουρα να διαγράψεις τη συγκεκριμένη κατηγορία;' ) ) { Coala.Warm( 'category/delete' , {'categoryid':<?php
				echo $catid;
				?>} ); }">Διαγραφή κατηγορίας &gt;&gt;</a><br /><?php
			}
			?><br /><?php
			if ( $catid != 0 ) {
				?><a href="index.php?p=addstory&amp;catid=<?php
				echo $catid;
				?>">Νέο άρθρο &gt;&gt;</a><?php
			}
		}
		?></div><?php
	}
?>
