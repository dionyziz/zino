<?php
	include "banner.php";
	$xc_settings[ 'name' ] = "Chit-Chat";
	
	$searchterm = $_GET[ 'term' ];
	$search = New Search( 'articles' );
	$search->SetFilter( 'body', 'foo bar blah' );
	$search->SetSortMethod( 'date', 'desc' );
	$search->Get();
?>
<br /><br /><br /><br />
<br /><br /><br /><br />	
<div class="searching">
	
	
	<span class="searchcat">Αναζήτηση στο <?php 
	echo $xc_settings['name']; ?>
	</span>
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
			<div class="articles newestaticles">
				<div>
					<h2><a href="" onclick="return false"><img class="storyicon" src="images/naruto.jpg" style="width:100px;height:100px" /> Naruto</a></h2><br />
					<small>από <a href="" onclick="return false" class="operator">Blink</a><span>, πριν μία ώρα και 12 λεπτά</span></small><br />
					<div class="summary">
					Πριν πω οτιδήποτε, πρέπει να αναφέρω την έννοια Anime. Anime είναι η Ιαπωνέζικη λέξη για τα cartoon.
					Σε γενικές γραμμές, ο κόσμος αναφέρεται με τον όρο Anime στα γιαπωνέζικα cartoon και αυτή είναι και η χρήση του στο συγκεκριμένο άρθρο.

					Με αυτά και με αυτά, σήμερα θα σας μιλήσω για τον γνωστό σε πολλούς (και είμαι σίγουρος ότι πολλοί άλλοι θα τον έχετε ακουστά), Naruto.
					To Naruto είναι ένα manga από τον Masashi Kishimoto μαζί με μία προσαρμογή του, βέβαια, στην τηλεόραση.
					O κεντρικός του ήρωας, ονόματι Uzumaki Naruto, είναι ένας φασαριόζος, υπερενεργητικός, έφηβος ninja που επιζητά συνεχώς την αναγνώριση από τον υπόλοιπο κόσμο καθώς και την υψηλότερη θέση στην ιεραρχία των ninja με τίτλο Hokage.
					</div>
					<div class="stuff">στο <a href="" onclick="">Κινούμενα Σχέδια</a><span>, 3 σχόλια<span>, 16 προβολές</span></span></div>
					<div style="clear:both"></div>
				</div>
				<div><br />
					<h2><a href="" onclick="return false"><img class="storyicon" src="images/rodes.jpg" style="width:100px;height:100px" /> Στη γιορτή της Φαντασίας - Ρόδες</a></h2><br />
					<small>από <a href="" onclick="return false" class="operator">Blink</a><span>, πριν 3 ώρες και 50 λεπτά</span></small>
					<div class="summary">
					Και ήρθε η ώρα να μάθουμε και λίγη ποιοτική ελληνική μουσική από ένα πρωτοεμφανιζόμενο συγκρότημα (δεν λέω ότι δεν υπάρχουν ήδη αξιόλογα συγκροτήματα) ονόματι Ρόδες.
					Η πραγματικότητα είναι ότι άκουγα εδώ και αρκετά χρόνια τον Νικήτα (X-Ray) Κλιντ στους Active Member και κατά την γνώμη μου ήταν ο καλύτερος και πιο κατάλληλος, για την Low Bap μουσική, άνθρωπος.
					Η αποχώρησή του από τους Active Member με ανυσήχησε και ήλπιζα να τον ξαναδώ σύντομα σε κάτι καινούργιο και καλύτερο, πράγμα το οποίο χρειαζότανε πολύ η hip hop μουσική!
					(Βέβαια ανησυχούσα μήπως δεν τον ξαναδούμε καθόλου γιατί, όπως είπα και πιο πριν, ήταν από τους πολύ καλούς.)
					</div>
					<div class="stuff">στο <a href="" onclick="">Συγκροτήματα</a><span>, 34 σχόλια<span>, 145 προβολές</span></span></div>
					<div style="clear:both"></div>
				</div>
				<div><br />
					<h2><a href="" onclick="return false"><img class="storyicon" src="images/anekdota.jpg" style="width:100px;height:100px" /> Ανέκδοτα</a></h2><br />
					<small>από <a href="" onclick="return false" class="operator">Blink</a><span>, πριν ένα μήνα</span></small>
					<div class="summary">
					Σκέφτηκα ότι λείπει λίγο το γέλιο από το Chit Chat και είπα να φτιάξω ένα άρθρο στο οποίο θα λέμε ότι καινούργιο ανέκδοτο - αστείο μαθαίνουμε!
					Μπορούμε επίσης να βαθμολογούμε κάποια ανέκδοτα ανάλογα με το πόσο αστεία είναι (για αυτό θα συννενοηθώ με τον Διονύση)! 
					Πολλοί από σας θα πούν... Τι διαφορά έχει από το Meep? E λοιπόν, καμία, αλλά στο Meep δεν πατάει κανείς, οπότε σκέφτηκα να κάνω κάτι καινούργιο ώστε να ανέβει λίγο και η χαρά σε αυτό το site γιατί μετά τα χριστούγεννα μας πήρε όλους από κάτω!
					</div>
					<div class="stuff">στο <a href="" onclick="">Χιουμοριστικά</a><span>, 368 σχόλια<span>, 996 προβολές</span></span></div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
		<br /><br />
		<span class="searchcat">Αναζήτηση στα σχόλια</span><br /><br />
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
		
		<span class="searchcat">Αναζήτηση στα ημερολόγια</span><br /><br />
		<div class="sblogs">
			<span class="thearticle">Στο προφίλ της</span> <a href="#" class="articlename">Skater</a><br />
			<span class="includedtext">...ν ειπωθεί πολλά για τους λόγους που χτυπήθηκαν οι πύργοι και τους λόγους που έπεσαν, ωστόσο αυτή η ταινία δε...</span><br /><br />
			<span class="thearticle">Στο προφίλ της</span> <a href="#" class="articlename">Kafrin</a><br />
			<span class="includedtext">...ς θέσεις στα charts. Οι επιτυχίες συνεχίστηκαν και με άλλα κομμάτια που ακολούθησαν ( "Move your Ass" ,...</span><br /><br />
		</div>
	</div>



</div>