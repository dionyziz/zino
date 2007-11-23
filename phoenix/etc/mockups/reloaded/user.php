<div class="userprofile"><br /><br /><br />
	<div class="top">
		<img src="images/blink.jpg" class="avatar" />
		<h1>Blink</h1>
		<h3>Άντε μην τα πάρω με την αργοπορία σας!</h3>
		<div class="tabs" id="userprofile_tabs">
			<div class="rightism"></div>
			<div class="tab"><a>Albums</a></div>
			<div class="leftism"></div>
			<div class="rightism"></div>
			<div class="tab"><a>Άρθρα</a></div>
			<div class="leftism"></div>
			<div class="rightism"></div>
			<div class="tab"><a>Φίλοι</a></div>
			<div class="leftism"></div>
			<div class="rightism"></div>
			<div class="tab"><a>Ερωτήσεις</a></div>
			<div class="leftism"></div>
			<div class="rightism"></div>
			<div class="tab"><a>Ημερολόγιο</a></div>
			<div class="leftism"></div>
			<div class="rightism activeright"></div>
			<div class="tab active"><a>Blink</a></div>
			<div class="leftism activeleft"></div>
			<br />
		</div>
	</div>
	
	<div id="tab0" class="profile">
		<?php
			// profile content
		?>		
		<div class="leftbar" style="padding-top:20px">
			<div class="info">
	            <div class="ccrelated">
	    			<h4>σχετικά με chit-chat</h4>
	                <ul>
	        			<li><dl>
	        				<dt>ρόλος:</dt>
	        				<dd>διοικητής</dd>
	        			</dl></li>
	        			<li><dl class="l">
	        				<dt>κατάταξη:</dt>
	        				<dd>γάτος των πάγων</dd>
	        			</dl></li>
	        			<li><dl>
	        				<dt>μέλος:</dt>
	        				<dd>εδώ και 11 μήνες και 2 μέρες</dd>
	        			</dl></li>
	        			<li><dl class="l">
	        				<dt>συνδεδεμένος:</dt>
	        				<dd>πριν 16 ώρες και ένα τέταρτο</dd>
	        				<!-- or <div>τώρα</div> -->
	        			</dl></li>
	                </ul>
	            </div>
	            <div class="personal">
	    			<h4>προσωπικές πληροφορίες</h4>
	                <ul>
	        			<li><dl>
	        				<dt>φύλο</dt>
	        				<dd>άνδρας</dd>
	        			</dl></li>
	        			<li><dl class="l">
	        				<dt>ηλικία</dt>
	        				<dd>16</dd>
	        			</dl></li>
	        			<li><dl>
	        				<dt>γενέθλια</dt>
	        				<dd>14 Φεβρουαρίου</dd>
	        			</dl></li>
	        			<li><dl class="l">
	        				<dt>περιοχή</dt>
	        				<dd>Ιωάννινα</dd>
	        			</dl></li>
	        			<li><dl>
	        				<dt>ενδιαφέροντα</dt>
	        				<dd>
	        					Chit-Chatting, Magic The Gathering, Studying, Reading, Spending my time in front of my pc doing nothing, 
	        					graffiti drawing, music, sleeping when there is time, science practice και πολλά άλλα που δεν μου έρχονται τώρα...
	        				</dd>
	        			</dl></li>
	                </ul>
	            </div>
			</div>
		</div>
		<div class="rightbar">
			<div class="contact">
				<h4>επικοινωνία</h4>
				<ul>
					<li><dl>
						<dt><img src="images/icons/msn.png" alt="MSN" /></dt>
						<dd>blink.master.gr@gmail.com</dd>
					</dl></li>
					<li><dl class="k">
						<dt><img src="images/icons/gtalk.png" alt="Gtalk" /></dt>
						<dd>blink.master.gr@gmail.com</dd>
					</dl></li>
				</ul>
			</div>
			<div class="statistics">
				<h4>στατιστικά χρήστη</h4>
				<ul>
					<li><dl>
						<dt>σχόλια</dt>
						<dd>1342</dd>
					</dl></li>
					<li><dl class="k">
						<dt>ρουμπίνια</dt>
						<dd>680</dd>
					</dl></li>
					<li><dl>
						<dt>χρήση συνομιλίας</dt>
						<dd>21 λεπτά / μέρα</dd>
					</dl></li>
					<li><dl class="k">
						<dt>προβολές προφίλ</dt>
						<dd>12,512</dd>
					</dl></li>
					<li><dl>
						<dt>δημοτικότητα προφίλ</dt>
						<dd>12%</dd>
					</dl></li>
					<li title="Ενεργητικότητα την τελευταία εβδομάδα"><dl class="k">
						<dt>ενεργητικότητα</dt>
						<dd>57%</dd>
					</dl></li>
				</ul>
				<h4>στατιστικά δημοσιογράφου</h4>
				<ul>	
					<li><dl>
						<dt>άρθρα</dt>
						<dd>12</dd>
					</dl></li>
					<li><dl class="k">
						<dt>μικρά νέα</dt>
						<dd>29</dd>
					</dl></li>
					<li><dl>
						<dt>εικόνες</dt>
						<dd>47</dd>
					</dl></li>
					<li><dl class="k">
						<dt>δημοτικότητα άρθρων</dt>
						<dd>44%</dd>
					</dl></li>
				</ul>
			</div>
		</div>

        <div style="clear:both" />
        
		<br /><br />
		<div class="comments">
		<?php
			$comments = array(
				array(
					'type' => 'operator',
					'nick' => 'titi',
					'time' => 'πριν μία ώρα',
					'text' => 'Γεια σου ρε Blinko, όλεεεε'
				),
				array(
					'type' => 'developer',
					'nick' => 'Dionyziz',
					'time' => 'πριν 46 λεπτά',
					'text' => 'Έχεις καεί εντελώς μιλάμε.'
				),
				array(
					'type' => 'operator',
					'nick' => 'Blink',
					'time' => 'πριν ένα τέταρτο',
					'text' => 'ΣΚΑΣΤΕ ΓΙΔΙΑ'
				),
			);
			
			$indent = 0;
			foreach ( $comments as $comment ) {
				$type = $comment[ 'type' ];
				$nick = $comment[ 'nick' ];
				$time = $comment[ 'time' ];
				$text = $comment[ 'text' ];
				++$indent;
				include 'comment.php';
			}
		?>
		</div>
	</div>
	<div id="tab1" style="display:none">
		<?php
			// journal content
		?><br />
		<a style="text-decoration:none" href="#" onclick="return false;"><img src="images/edit.png" /> Επεξεργασία ημερολογίου</a>
		<br /><br />
		<div>
			Conquest to the lover,<br />
			And your love to the fire,<br />
			Permanence unfolding in the absolute.<br />
			<br />
			Forgivness is<br />
			The ultimate sacrifice.<br />
			Eloquence belongs,<br />
			To the conqueror.<br />
			<br />
			The pictures of time and space are rearranged,<br />
			In this little piece of typical tragedy.<br />
			<br />
			Justified Candy!<br />
			Brandy for the nerves,<br />
			Eloquence belongs,<br />
			To the conqueror.<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			I forgot to<br />
			I forgot to let you know that...<br />
			<br />
			Justified Candy!<br />
			Brandy for the nerves,<br />
			Eloquence belongs,<br />
			To the conqueror.<br />
			<br />
			Conquest to the lover,<br />
			And your love to the fire,<br />
			Permanence unfolding in the absolute.<br />
			<br />
			Forgivness is<br />
			The ultimate sacrifice.<br />
			Eloquence belongs,<br />
			To the conqueror.<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			Generation..............<br />
			<br />
			What is in us that turns a deaf ear to the cries of human suffering?!!!<br />
			<br />
			WOAH!!!!!!!<br />
			<br />
			Suffering, suffering now!<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			You and me will all go down in history,<br />
			With a sad Statue of Liberty,<br />
			And a Generation that didn't agree.<br />
			<br />
			Generation..........<br />
			<br />
			System of a Down - Sad Statue<br />
		</div>
	</div>
	<div id="tab2" style="display:none">
		<?php	
			// questions content
		?><br />
		<div>
			<div class="label">
				Αν εμφανιζόταν ένα τζίνι και σου έλεγε πως μπορεί να πραγματοποιήσει μια ευχή σου, τι θα του ζητούσες?
			</div>
			<div class="qanswer">
				Άλλες 100 ευχές και αν αυτό δεν γινότανε, τότε θα του ζητούσα να είχα μια ευχάριστη και όμορφη ζωή! <a href="#" onclick="return false;" title="Επεξεργασία απάντησης"><img src="images/edit.png" /></a>
			</div>
		</div><br />
		<div>
			<div class="label">
				Σου αρέσει ο Tarantino?
			</div>
			<div class="qanswer">
				Γιατί αφήνεται τον nightdreamer να βάζει ερωτήσεις; <a href="#" title="Επεξεργασία απάντησης" onclick="return false;"><img src="images/edit.png" /></a>
			</div>
		</div><br />
		<div>
			<div class="label">
				Υπάρχει ζωή μετά το θάνατο?	
			</div>
			<div class="qanswer">
				Κοίτα... Δεν ξέρω αν υπάρχει ζωή μετά τον θάνατο,
				αλλά θάνατος μετά την ζωή δεν υπάρχει! <a href="#" title="Επεξεργασία απάντησης" onclick="return false;"><img src="images/edit.png" /></a>
			</div>
		</div><br />
	</div>
	<div id="tab3" style="display:none">
	<?php 
		//friends content
	?>
	Here are my friends
	
	</div>
	<div id="tab4" style="display:none">
		<?php
			//user articles content
		?>
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
	<div id="tab5" style="display:none">
			<br /><br />
			<div class="content">
				<div class="register" style="display:block;overflow:hidden;width:900px;padding-left:0;">
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album1.jpg" title="supersnow.jpg" /><br />
												<span class="albumname">
													Χειμωνιάτικες διακοπές
												</span>
											</a><br />
										</div>
										<div class="albuminfo">
											Εικόνες από τις χειμερινές διακοπές στη Γερμανία
										</div><br />
										<div class="albuminfo">
											12 φωτογραφίες<br />
											38 σχόλια<br />
											257 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album2.jpg" title="green.jpg" /><br />
												<span class="albumname">
													Γερμανία
												</span>
											</a>
											<br />
										</div>
										<div class="albuminfo">
											Ωραιά πράσινα τοπία
										</div><br />
										<div class="albuminfo">
											1 φωτογραφία<br />
											2 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album3.jpg" title="myself.jpg" /><br />
												<span class="albumname">
													Φωτογραφίες μου
												</span>
											</a>
											<br />
										</div>
										<div class="albuminfo">
											Μερικές φωτογραφίες μου
										</div><br />
										<div class="albuminfo">
											6 φωτογραφίες<br />
											1760 σχόλια<br />
											84460 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album4.jpg" title="exoticcar.jpg" /><br />
												<span class="albumname">
													Ωραία αυτοκίνητα
												</span>
											</a>
											<br />
										</div>
										<div class="albuminfo">
											Αυτά είναι αυτοκίνητα!
										</div><br />
										<div class="albuminfo">
											58 φωτογραφίες<br />
											469 σχόλια<br />
											586 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album5.jpg" title="sebastian.jpg" /><br />
												<span class="albumname">
													Οικογένεια
												</span>
											</a>
											<br />
										</div>
										<div class="albuminfo">
											Μέλη της οικογένειάς μου
										</div><br />
										<div class="albuminfo">
											3 φωτογραφίες<br />
											4 σχόλια<br />
											39 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>
						<div class="opties" style="display:inline;overflow:hidden;margin-right:20px;margin-top:20px;margin-bottom:20px;">
							<div style="display:table-cell;">
								<div class="upperline">
									<div class="leftupcorner"></div>
									<div class="rightupcorner"></div>
									<div class="middle"></div>
								</div>
								<div class="rectangleopts mainalbum" style="height:300px;width:220px;">
									
									<div class="albumshow">
										<div style="text-align:center;">
											<a href="#" class="enteralbum">
												<img src="images/album6.jpg" title="flyhigh.jpg" /><br />
												<span class="albumname">
													Άλλες ωραίες φωτογραφίες
												</span>
											</a>
											<br />
										</div>
										<div class="albuminfo">
											Διάφορες φωτογραφίες
										</div><br />
										<div class="albuminfo">
											3 φωτογραφίες<br />
											11 σχόλια<br />
											54 προβολές
										</div><br />
										<div class="links">
											<a href="#">Επεξεργασία&#187;</a><br />
											<a href="#">Διαγραφή&#187;</a>
										</div>
									</div>
									
								</div>
								<div class="downline">
									<div class="leftdowncorner"></div>
									<div class="rightdowncorner"></div>
									<div class="middledowncss"></div>
								</div>
							</div>
						</div>						
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">/* <![CDATA[ */
    // instant tab switching script
	var UserTabs = {
		activated : 0,
		Activate: function( tabindex ) {
			parentdiv = document.getElementById( 'userprofile_tabs' );
			children_divs = parentdiv.getElementsByTagName( 'div' );
			firstelem = children_divs.length - tabindex * 3 - 3;
			children_divs[ firstelem ].className = "rightism activeright";
			children_divs[ firstelem + 1 ].className = "tab active";
			children_divs[ firstelem + 2 ].className = "leftism activeleft";
			for ( i in children_divs ) {
				if ( i < firstelem || i > firstelem + 2 ) {
					child_div = children_divs[ i ];
					switch ( child_div.className ) {
						case 'rightism activeright':
							child_div.className = "rightism";
							break;
						case 'tab active':
							child_div.className = "tab";
							break;
						case 'leftism activeleft':
							child_div.className = "leftism";
					}
				}
			}
			document.getElementById( 'tab' + UserTabs.activated ).style.display = "none";
			document.getElementById( 'tab' + tabindex ).style.display = "";	
			UserTabs.activated = tabindex;
		}
	}

			
	
	parentdiv = document.getElementById( 'userprofile_tabs' );
	children_divs = parentdiv.getElementsByTagName( 'div' );
	for ( i in children_divs ) {
		child_div = children_divs[ i ];
		j = Math.floor( ( children_divs.length - 1 - i ) / 3 );
		child_div.onclick = ( function ( index ) {
			return function () {
				UserTabs.Activate( index );
			};
		} )( j );
	}
/* ]]> */</script>
