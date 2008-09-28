	<div class="box categorybox">
		<div class="header">
			<div style="float:right"><img src="images/soraright.jpg" /></div>
			<div style="float:left"><img src="images/soraleft.jpg" /></div>
			<h3>Κατηγορίες</h3>
		</div>
		<div class="body">
            <?php
                if ( $loggedin ) { // && can_edit_categories ...
                    ?><a href="" onclick="return false;" title="Νέα Κατηγορία"><img class="newcategory" src="images/icons/page_new.gif" /></a><?php
                }
			?><div class="category">
				<a href="" onclick="return false;" title="Μουσική"><img src="images/music.jpg" class="avatar" />
					<h3>Μουσική</h3></a><br />
				<a href="" onclick="">Συγκροτήματα</a>, <a href="" onclick="">Καλλιτέχνες</a>, <a href="" onclick="">Προτείνουμε</a>, <a href="" onclick="">Μουσικά νέα</a>
				<div style="clear:left">
				</div>
			</div>
			<div class="category">
				<a href="" onclick="return false;" title="Cinema"><img src="images/cinema.jpg" class="avatar" />
					<h3>Cinema</h3></a><br />
				<a href="" onclick="">Σειρές</a>, <a href="" onclick="">Ταινίες</a>, <a href="" onclick="">Νέες Προβολές</a>, 
				<a href="" onclick="">Κινούμενα Σχέδια</a>, <a href="" onclick="">Προσωπικότητες</a>
				<div style="clear:left">
				</div>
			</div>
			<div class="category">
				<a href="" onclick="return false;" title="Βιβλία"><img src="images/books.jpg" class="avatar" />
					<h3>Βιβλία</h3></a><br />
				<a href="" onclick="">Ελληνική Λογοτεχνία</a>, <a href="" onclick="">Ξένη Λογοτεχνία</a>, <a href="" onclick="">Περιοδικά και Εφημερίδες</a>, 
				<a href="" onclick="">Ποίηση</a>
				<div style="clear:left">
				</div>
			</div>
			<div class="category">
				<a href="" onclick="return false;" title="Θέματα για συζήτηση"><img src="images/themata.jpg" class="avatar" />
					<h3>Θέματα για συζήτηση</h3></a><br />
				<a href="" onclick="">Πολιτκή</a>, <a href="" onclick="">Καθημερινότητα</a>, <a href="" onclick="">Άσχετα</a>, 
				<a href="" onclick="">Προβληματισμοί</a>, <a href="" onclick="">Χιουμοριστικά</a>, <a href="" onclick="">Κοινωνικά</a>,
				<a href="" onclick="">Σχετικά με Chit-Chat</a>, <a href="" onclick="">Ιστορικά</a>
				<div style="clear:left">
				</div>
			</div>
			<div class="category">
				<a href="" onclick="return false;" title="Παιχνίδια"><img src="images/games.jpg" class="avatar" />
					<h3>Παιχνίδια</h3></a><br />
				<a href="" onclick="">Επιτραπέζια</a>, <a href="" onclick="">Παιχνίδια Υπολογιστή και Κονσόλας</a>, 
				<a href="" onclick="">Τα δικά μας παιχνίδια</a>
				<div style="clear:left">
				</div>
			</div>
			<a href="" class="arrow" onclick="return false" title="Όλες οι κατηγορίες"></a>
		</div>
	</div>
