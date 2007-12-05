<?php
	
	$loggedin = true;
	
	include "banner.php";

?>
<div style="margin-top:40px; margin-bottom: 20px;">
Καλωσήρθες στις Συχνές Ερωτήσεις του Zino.<br />
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
				<ul class="categories" style="vertical-align: top;">
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Γενικά</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Διάφορα</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Μερικά</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Απροσδιόριστα</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Συγκεκριμένα</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Περιληπτικά</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Λεπτομερή</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Εκνευριστικά</a></li>
					<li style="clear:left;"><a href="" onclick="return false;"><img src="images/anonymous.jpg" class="avatar" style="width:16px; height: 16px;" alt="Γενικά" />Blah</a></li>
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
	<div class="popularq"><?php
		
		$faqquestion = "Τι είναι αυτό?";
		$faqanswer = "Κάτι είναι. Δεν καταλαβαίνω γιατί κάνεις τέτοιες ηλίθιες ερωτήσεις";
		
		for ( $i = 0; $i < 6; ++$i ) {
			include "faqsmall.php";
		}
	?></div>
</div>
