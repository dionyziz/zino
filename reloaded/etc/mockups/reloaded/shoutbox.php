<div class="box shoutbox" id="shoutbox">
	<div class="header">
		<div style="float:right"><img src="images/soraright.jpg" /></div>
		<div style="float:left"><img src="images/soraleft.jpg" /></div>
		<h3>Μικρά νέα</h3>
	</div>
	<div class="body" style="margin:0;">
        <?php
            if ( $loggedin ) { // && can_edit_shoutbox ...
                ?><a href="" onclick="return false;" title="Προσθήκη μικρού νέου"><img class="newshout" src="images/icons/page_new.gif" /></a><?php
            }
		?><div>
			<a href="" onclick="return false;" title="Thug"><img src="images/thug.jpg" class="avatar" /></a> Τι μας λες ρε Blink!
			<div style="clear:left">
			</div>
		</div>
		<div>
			<a href="" onclick="return false;" title="Blink"><img src="images/blink.jpg" class="avatar" /></a> Έλεος παιδιά, δεν δημιουργήθηκαν για αυτόν τον λόγο τα μικρά νέα!
			<div style="clear:left">
			</div>
		</div>
		<div>
			<a href="" onclick="return false;" title="noel1"><img src="images/noel1.jpg" class="avatar" /></a> Πουλάω αυθεντικό Black Lotus σε τιμή ευκαιρίας, τρέξτε να προλάβετε
			μόνο 1539 ευρώ ΜΑΖΙ με κάλυμα και θήκη!!
			<div style="clear:left">
			</div>
		</div>
		<a href="" class="arrow" onclick="return false" title="Περισσότερα μικρά νέα"></a>
	</div>
</div>
