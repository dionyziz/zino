<?php
	
	$loggedin = true;
	
	include "banner.php";

?>
<div style="margin-top:40px; margin-bottom: 20px;">
Καλωσήρθες στις Συχνές Ερωτήσεις του Chit-Chat.<br />
Εδώ θα βρεις τις απαντήσεις στις ερωτήσεις σου!
</div>
<div class="faq">
	<div class="searchbox">
		<div class="upperline">
			<div class="leftupcorner"></div>
			<div class="rightupcorner"></div>
			<div class="middle"></div>
		</div>
		<div class="registeropts" style="padding-top: 0px; margin-top: 0px;">
			<form>
				<input type="submit" value="Αναζήτηση" style="font-weight: bold; display:inline;" /><input type="text" size="70" name="faqsearch" />
			</form>
		</div>
		<div class="downline">
			<div class="leftdowncorner"></div>
			<div class="rightdowncorner"></div>
			<div class="middledowncss"></div>
		</div>
	</div>
	<div class="sidebar leftbar">
		<div class="box">
			<div class="header">
				<div style="float:right"><img src="images/soraright.jpg" /></div>
				<div style="float:left"><img src="images/soraleft.jpg" /></div>
				<h3>Κατηγορίες</h3>
			</div>
			<div class="body">
				<ul class="categories">
					<li><a href="" onclick="return false;">Γενικά</a></li>
					<li><a href="" onclick="return false;">Διάφορα</a></li>
					<li><a href="" onclick="return false;">Μερικά</a></li>
					<li><a href="" onclick="return false;">Απροσδιόριστα</a></li>
					<li><a href="" onclick="return false;">Συγκεκριμένα</a></li>
					<li><a href="" onclick="return false;">Περιληπτικά</a></li>
					<li><a href="" onclick="return false;">Λεπτομερή</a></li>
					<li><a href="" onclick="return false;">Εκνευριστικά</a></li>
					<li><a href="" onclick="return false;">Blah</a></li>
				</ul>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<div style="float:right"><img src="images/soraright.jpg" /></div>
				<div style="float:left"><img src="images/soraleft.jpg" /></div>
				<h3>Νεότερες Ερωτήσεις</h3>
			</div>
			<div class="body">
				<ul>
					<li><a href="" onclick="return false;">Πώς μπορώ να με κάνω ban?</a></li>
					<li><a href="" onclick="return false;">Μήπως θα έπρεπε να σας δώσω κάποια χρήματα?</a></li>
					<li><a href="" onclick="return false;">Γιατί δεν πάμε όλοι στο διάολο?</a></li>
					<li><a href="" onclick="return false;">Ποιος είναι αυτός ο finlandos που μου πρήζει τα @@?</a></li>
					<li><a href="" onclick="return false;">O Blink δεν αντέχεται. Τι να κάνω?</a></li>
					<li><a href="" onclick="return false;">Τι σημαίνει XML Parsing Error?</a></li>
					<li><a href="" onclick="return false;">Θα ξεκολλήσετε ποτέ από το PC?</a></li>
					<li><a href="" onclick="return false;">Βλέπω παντού Fatal Error. Είναι καλό αυτό?</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="faqq">
		<div class="header">
			<img src="images/anonymous.jpg" class="avatar" style="width:50px; height:50px; float:left;" />
			<h2>Κατηγορία: Γενικά</h2><br />
			<small>Αυτή είναι μία κατηγορία για γενικές ερωτήσεις</small><br /><br />
		</div><br />
		<div style="margin-top: 3px;" class="popularq"><?php
			
				$faqquestion = "Τι είναι αυτό?";
				$faqanswer = "Κάτι είναι. Δεν καταλαβαίνω γιατί κάνεις τέτοιες ηλίθιες ερωτήσεις";
				
				for ( $i = 0; $i < 5; ++$i ) {
					include "faqsmall.php";
				}
		
			?><div class="pagifier">
				<span>
					<a href="" class="nextbacklinks" style="font-weight:bold">1</a>, 
					<a href="" class="nextbacklinks">2</a>,
					<a href="" class="nextbacklinks">3</a>,
					<a href="" class="nextbacklinks">4</a>,
					<a href="" class="nextbacklinks">5</a>
					...
				</span>
				<span class="rightpage">
					<a href="" class="nextbacklinks">Επόμενη&#187;</a>
				</span>
			</div>
		</div>
	</div>
</div>