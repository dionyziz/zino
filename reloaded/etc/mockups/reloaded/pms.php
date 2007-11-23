<?php
	$loggedin = true;
	$collapse = true;
	$allowtoggle = false;
	include 'banner.php';
?>
<div class="pms">
	<div class="top">
		<div id="pmstitle" class="title">Εισερχόμενα</div>
		<ul>
			<li onclick="Pms.Display( 'inbox' );" id="inbox_link" class="active">Εισερχόμενα</li>
			<li onclick="Pms.Display( 'outbox' );" id="outbox_link" class="inactive">Απεσταλμένα</li>
			<li onclick="Pms.Display( 'new' );" id="new_link" class="inactive">Νέο Μήνυμα</li>
		</ul>
	</div>
	<div id="inbox_div" style="display: block">
		<div class="main">
			<div class="details">
				τελευταίο: πριν από ένα τέταρτο
			</div>
			<br /><br />
			<div class="comments" style="margin-top: 50px;">
			<?php
				$pms = array(
					array(
						'read' => false,
						'type' => 'operator',
						'nick' => 'Blink',
						'time' => 'πριν ένα τέταρτο',
						'text' => 'ΑΝΤΕ ΜΗ ΦΩΝΑΞΩ ΤΟΝ ΞΑΔΕΡΦΟ ΜΟΥ',
						'usersender' => false
					),
					array(
						'read' => true,
						'type' => 'developer',
						'nick' => 'Dionyziz',
						'time' => 'πριν 46 λεπτά',
						'text' => 'We are running out of time.',
						'usersender' => false
					),
					array(
						'read' => true,
						'type' => 'operator',
						'nick' => 'Blink',
						'time' => 'πριν μία ώρα',
						'text' => 'Xρήστη Finlandos...

						Αναφέρθηκες πρόσφατα στον χρήστη Izual και του έδωσες την ευθύνη του απόσκατου προγραμματιστή! Θα θέλαμε αν μπορείς να αλλάξεις το σχόλιο σου καθώς περιέχει προσβλητικές αναφορές αλλιώς θα αναγκαστούμε να ακολουθήσουμε διαδικασία υπαρξιασμού. Δεν θα υπάρχει δεύτερη προειδοποίηση...


						Από την διοικητική ομάδα του cc.',
						'usersender' => false
					)
				);
				
				$indent = 0;
				foreach ( $pms as $pm ) {
					$read = $pm[ 'read' ] == 'yes';
					$type = $pm[ 'type' ];
					$nick = $pm[ 'nick' ];
					$time = $pm[ 'time' ];
					$text = $pm[ 'text' ];
					$usersender = $pm[ 'usersender' ];
					include 'pm.php';
				}
			?>
			</div>
		</div>
	</div>
	<div id="outbox_div" style="display: none;">
		<div class="main">
			<div class="details">
				τελευταίο: πριν από δύο ώρες
			</div>
			<br /><br />
			<div class="comments" style="margin-top: 50px;">
			<?php
				$pms = array(
					array(
						'read' => true,
						'type' => 'operator',
						'nick' => 'Blink',
						'time' => 'πριν δύο ώρες',
						'text' => 'Αποφασίσαμε με τον Izual να σε κάνουμε σούπα',
						'usersender' => true
					),
					array(
						'read' => true,
						'type' => 'developer',
						'nick' => 'Dionyziz',
						'time' => 'πριν τρεις ώρες και 10 λεπτά',
						'text' => 'LOOOL το bash.org είναι γαμάτο!',
						'usersender' => true
					)
				);
				
				$indent = 0;
				foreach ( $pms as $pm ) {
					$read = $pm[ 'read' ];
					$type = $pm[ 'type' ];
					$nick = $pm[ 'nick' ];
					$time = $pm[ 'time' ];
					$text = $pm[ 'text' ];
					$usersender = $pm[ 'usersender' ];
					include 'pm.php';
				}
			?>
			</div>
		</div>
	</div>
	<div id="new_div" style="display: none;">
		<form action="newpm.php" method="post" class="newpm">
			Προς: <input type="text" name="to" class="mytext" size="35" /><br /><br />
			<textarea cols="80" rows="15" name="text"></textarea><br /><br />
			<input type="submit" value="Αποστολή" /><input type="reset" value="Επαναφορά" />
		</form>
	</div>
</div>
<script type="text/javascript">/* <![CDATA[ */

	var Pms = {
		activecontent : 'inbox',
		Display: function( index ) {
			document.getElementById( Pms.activecontent + '_link' ).className = "inactive";
			document.getElementById( index + '_link' ).className = "active";
			var element = document.getElementById( 'pmstitle' );
			while ( element.firstChild ) {
				element.removeChild(element.firstChild);
			}
			element.appendChild( document.createTextNode( Pms.Title( index ) ) );
			document.getElementById( Pms.activecontent + '_div' ).style.display = "none";
			document.getElementById( index + '_div' ).style.display = "block";
			Pms.activecontent = index;
		},
		Title: function( index ) {
			switch( index ) {
				case 'outbox':
					return 'Εξερχόμενα';
					break;
				case 'new':
					return 'Νέο Μήνυμα';
					break;
				case 'inbox':
				default:
					return 'Εισερχόμενα';
					break;
			}
		}
	};

/* ]]> */</script>
	