<?php
	function ElementSearchView( $q ) { 
		global $page;
		global $water;
		global $libs;
		
		$libs->Load( 'search' );
		$libs->Load( 'articles' );
		
		$page->AttachStyleSheet( 'css/search.css' );
		$searchterm = $q;
		$search = New Search_Articles();
		$search->SetSortMethod( 'date', 'DESC' );
		$search->SetComplexFilter( 'content', $searchterm );
		$search->SetFilter( 'delid', 0 );
		$search->SetFilter( 'typeid', 0 );
		$search->SetNegativeFilter( 'category', 0 );
		$articles = $search->Get();
	
		?><br /><br /><br /><br />
		<br /><br /><br /><br />	
		<div class="searching">
			<span class="searchcat">Αναζήτηση στο <?php 
			echo $xc_settings['name']; 
			?></span>
			<div style="width:48%">
				<div class="upperline">
					<div class="leftupcorner"></div>
					<div class="rightupcorner"></div>
					<div class="middle"></div>
				</div>
				<div class="registeropts">
					<input type="text" size="35" /> &nbsp;&nbsp;&nbsp;<a class="next" href="#">Αναζήτηση >></a>
				</div>
				<div class="downline">
					<div class="leftdowncorner"></div>
					<div class="rightdowncorner"></div>
					<div class="middledowncss"></div>
				</div>
			</div>
			
			<div class="searchresults">
				<span class="searchcat">Αναζήτηση στα άρθρα</span>
				<div class="sarticles">
					<div class="articles newestaticles"><?php
						$water->trace( 'results number: '.count( $articles ) );
						foreach ( $articles as $article ) { 
							$article = New Article( $article );
							Element( 'article/small' , $article ); 
						} ?>
					</div>
				</div>
				<br /><br />
				<span class="searchcat">Αναζήτηση στα σχόλια</span><br /><br /><?php // TODO! ?>
				<div class="scomments">
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">Linkin Park</a><br />
					<span class="includedtext">...μια διδασκαλια.. LOL Δυο βουδιστες μοναχοι, ο μαθητης και ο δασκαλος περπατουσαν στα βουνα, επιστρεφοντ...</span><br /><br />
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">Nickelback</a><br />
					<span class="includedtext">...ουραστώ λιγάκι και να απαλλάξω από την παρουσία μου και μερικούς που με νιωθουν σαν σπυρί στον κώλο... Αλλά...</span><br /><br />
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">World Trade Center</a><br />
					<span class="includedtext">... πήραμε φόρα στο προφίλ, είπαμε να αφήνουμε και σχόλια στην πρώτη ενότητα. Κάτι σαν προσωπικά μηνύματα δη...</span><br /><br />
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">San Remos...Σαν Άνεμος!</a><br />
					<span class="includedtext">.... Δύσκολοι οι καιροί στην Ελλάδα τη δεκαετία του 60, οπότε οι γονείς του αποφάσισαν να μεταναστεύσο...</span><br /><br />
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">Scooter</a><br />
					<span class="includedtext">...λματα και τισ φιγούρες στον αέρα. Σε άλλες χώρες το Parkour είναι αρκετα γνωστό σαν άθλημα αλλά και σα...</span><br /><br />
					<span class="thearticle">Στο άρθρο</span> <a href="#" class="articlename">Puddle of Mud</a><br />
					<span class="includedtext">...ρας του, ο Κιμ Μοϊσέγεβιτς Βάινσταϊν. Ηταν απόγευμα όταν οι γονείς του προσπαθούσαν, χωρίς...</span><br /><br />
				
				</div>
				
				<span class="searchcat">Αναζήτηση στα ημερολόγια</span><br /><br /><?php // TODO! ?>
				<div class="sblogs">
					<span class="thearticle">Στο προφίλ της</span> <a href="#" class="articlename">Skater</a><br />
					<span class="includedtext">...ν ειπωθεί πολλά για τους λόγους που χτυπήθηκαν οι πύργοι και τους λόγους που έπεσαν, ωστόσο αυτή η ταινία δε...</span><br /><br />
					<span class="thearticle">Στο προφίλ της</span> <a href="#" class="articlename">Kafrin</a><br />
					<span class="includedtext">...ς θέσεις στα charts. Οι επιτυχίες συνεχίστηκαν και με άλλα κομμάτια που ακολούθησαν ( "Move your Ass" ,...</span><br /><br />
				</div>
			</div>
		</div><?php
	}
?>

