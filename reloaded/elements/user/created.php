<?php
	function ElementUserCreated() {
		global $user;
		global $rabbit_settings;
		global $page;
		
		$page->SetTitle( "Καλωσήρθες στην παρέα μας!" );
		
		?><div style="width:70%;">
			Συγχαρητήρια, <?php 
            echo $user->Username(); 
            ?>! Μόλις
			δημιούργησες ένα καινούργιο όνομα χρήστη. Την
			επόμενη φορά που θα επισκευθείς το <?php 
            echo $rabbit_settings['applicationname']; 
            ?> μπορείς
			να το χρησιμοποιήσεις για να εισέλθεις.
			<br /><br />
			<a href="?p=faq">Μάθε πώς μπορείς να γράφεις
			κι εσύ σχόλια, και να βρεις απαντήσεις σε άλλες συχνές ερωτήσεις σχετικά με το <?php
			echo $rabbit_settings['applicationname'];
			?></a>!
			<br /><br />
			<small>... ή <a href="index.php">επέστρεψε στην κεντρική
			σελίδα</a></small>.
		</div><?php
	}
?>
