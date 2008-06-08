<?php
	function ElementEventList() {
		global $libs;
		global $water;
		
		$libs->Load( 'event' );
		
		$finder = New EventFinder();
		$events = $finder->FindByType( array( 
		EVENT_IMAGE_CREATED, 
		EVENT_JOURNAL_CREATED,
		EVENT_POLL_CREATED,
		EVENT_USERSPACE_UPDATED,
		EVENT_FRIENDRELATION_CREATED,
		EVENT_USERPROFILE_EDUCATION_UPDATED,
		EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED,
		EVENT_USERPROFILE_RELIGION_UPDATED,
		EVENT_USERPROFILE_POLITICS_UPDATED,
		EVENT_USERPROFILE_SMOKER_UPDATED,
		EVENT_USERPROFILE_DRINKER_UPDATED,
		EVENT_USERPROFILE_ABOUTME_UPDATED,
		EVENT_USERPROFILE_MOOD_UPDATED,
		EVENT_USERPROFILE_LOCATION_UPDATED,
		EVENT_USERPROFILE_HEIGHT_UPDATED,
		EVENT_USERPROFILE_WEIGHT_UPDATED,
		EVENT_USERPROFILE_HAIRCOLOR_UPDATED,
		EVENT_USERPROFILE_EYECOLOR_UPDATED,
		EVENT_USER_CREATED
		) , 0 , 20 );
		?><div class="latestevents">
			<h2>Συνέβησαν πρόσφατα</h2>
			<div class="list"><?php
				$water->Trace( 'event number: ' . count( $events ) );
				foreach ( $events as $event ) {
					?><div class="event">
						<div class="toolbox">
						</div>
						<div class="who"><?php
							Element( 'user/display' , $event->User );
							switch ( $event->Typeid ) {
								case EVENT_IMAGE_CREATED:
									?> ανέβασε την εικόνα<?php
									break;
								case EVENT_JOURNAL_CREATED:
									?> καταχώρησε στο ημερολόγιο<?php
									break;
								case EVENT_POLL_CREATED:
									?> δημιούργησε τη δημοσκόπηση<?php
									break;
								case EVENT_USERSPACE_UPDATED:
									?> ανανέωσε τον χώρο<?php
									break;
								case EVENT_FRIENDRELATION_CREATED:
									?> πρόσθεσε στους φίλους<?php
									break;
								case EVENT_USERPROFILE_EDUCATION_UPDATED:
									?> όρισε εκπαίδευση<?php
									break;
								case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
									?> όρισε σεξουαλικές προτιμήσεις<?php
									break;
								case EVENT_USERPROFILE_RELIGION_UPDATED:
									?> όρισε τις θρησκευτικές πεποιθήσεις<?php
									break;
								case EVENT_USERPROFILE_POLITICS_UPDATED:
									?> όρισε τις πολιτικές πεποιθήσεις<?php
									break;
								case EVENT_USERPROFILE_SMOKER_UPDATED:
								case EVENT_USERPROFILE_DRINKER_UPDATED:
								case EVENT_USERPROFILE_ABOUTME_UPDATED:
								case EVENT_USERPROFILE_MOOD_UPDATED:
								case EVENT_USERPROFILE_LOCATION_UPDATED:
								case EVENT_USERPROFILE_HEIGHT_UPDATED:
								case EVENT_USERPROFILE_WEIGHT_UPDATED:
								case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
								case EVENT_USERPROFILE_EYECOLOR_UPDATED:
									?> ανανέωσε το προφίλ<?php
									break;
								case EVENT_USER_CREATED:
									?> δημιούργησε λογαριασμό<?php
									break;			
							}
						?></div>
						<div class="subject"><?php
							switch ( $event->Typeid ) {
								case EVENT_IMAGE_CREATED:
									?><a href="?p=photo&amp;id=<?php
									echo $event->Item->Id;
									?>"><?php
									Element( 'image' , $event->Item , IMAGE_CROPPED_100x100 , '' , $event->User->Name , $event->User->Name , '' , false , 0 , 0 );
									?></a><?php
									break;
								case EVENT_JOURNAL_CREATED:
									?><a href="?p=journal&amp;id=<?php
									echo $event->Item->Id;
									?>"><?php
									echo htmlspecialchars( $event->Item->Title );
									?></a><?php
									break;
								case EVENT_POLL_CREATED:
									?><a href="?p=poll&amp;id=<?php
									echo $event->Item->Id;
									?>"><?php
									echo htmlspecialchars( $event->Item->Question );
									?></a><?php
									break;
								case EVENT_USERSPACE_UPDATED:
									?><a href="?p=space&amp;subdomain=<?php
									echo $event->User->Name;
									?>">Προβολή χώρου</a><?php
									break;
								case EVENT_FRIENDRELATION_CREATED:
									?><a href="<?php
									Element( 'user/url' , $event->Item->Friend );
									?>"><?php
									Element( 'user/avatar' , $event->Item->Friend, 100 , '' , '' , false , 0 , 0 );
									?></a><?php
									break;
								case EVENT_USERPROFILE_EDUCATION_UPDATED:
									if ( $event->User->Gender =='f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> πάει <?php
									Element( 'user/trivial/education' , $event->User->Profile->Education );
									break;
								case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι <?php
									Element( 'user/trivial/sex' , $event->User->Profile->Sexualorientation , $event->User->Gender );
									break;
								case EVENT_USERPROFILE_RELIGION_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι <?php
									Element( 'user/trivial/religion' , $event->User->Profile->Religion );
									break;
								case EVENT_USERPROFILE_POLITICS_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι <?php
									Element( 'user/trivial/politics' , $event->User->Profile->Politics );
									break;
								case EVENT_USERPROFILE_SMOKER_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									switch ( $event->User->Profile->Smoker ) {
										case 'yes':
											?> καπνίζει<?php
											break;
										case 'no':
											?> δεν καπνίζει<?php
											break;
										case 'socially':
											?> καπνίζει με παρέα<?php
											break;
									}
									break;
								case EVENT_USERPROFILE_DRINKER_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									switch ( $event->User->Profile->Drinker ) {
										case 'yes':
											?> πίνει<?php
											break;
										case 'no':
											?> δεν πίνει<?php
											break;
										case 'socially':
											?> πίνει με παρέα<?php
											break;
									}
									break;
								case EVENT_USERPROFILE_ABOUTME_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
										$self = 'της';
									}
									else {
										?>O <?php
										$self = 'του';
									}
									echo $event->User->Name;
									?> έγραψε για τον εαυτό <?php
									echo $self;
									?> "<?php
									echo htmlspecialchars( utf8_substr( $event->User->Profile->Aboutme , 0 , 20 ) );
									?>"<?php
									break;
								case EVENT_USERPROFILE_MOOD_UPDATED:
								case EVENT_USERPROFILE_LOCATION_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι από <?php
									echo htmlspecialchars( $event->User->Profile->Location->Name );
									break;
								case EVENT_USERPROFILE_HEIGHT_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι <?php
									strtolower( Element( 'user/trivial/height' , $event->User->Profile->Height ) );
									break;
								case EVENT_USERPROFILE_WEIGHT_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									?> είναι <?php
									strtolower( Element( 'user/trivial/weight' , $event->User->Profile->Weight ) );
									break;
								case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									echo $event->User->Name;
									if ( $event->User->Profile->Haircolor == 'highlights' ) {
										?> έχει ανταύγειες<?php
									}
									else if ( $event->User->Profile->Haircolor == 'skinhead' ) {
										?> είναι skinhead<?php
									}
									else {
										?> έχει <?php 
										strtolower( Element( 'user/trivial/haircolor' , $event->User->Profile->Haircolor ) );
										?> μαλλί<?php
									}
									break;
								case EVENT_USERPROFILE_EYECOLOR_UPDATED:
									if ( $event->User->Gender == 'f' ) {
										?>Η <?php
									}
									else {
										?>O <?php
									}
									?> έχει <?php
									echo $event->User->Name;
									Element( 'user/trivial/eyecolor' , $event->User->Profile->Eyecolor );
									?> χρώμα ματιών<?php
									break;
								case EVENT_USER_CREATED:
									?> δημιούργησε λογαριασμό<?php
									break;			
							}
							?>
						</div>					
					</div><?php
				}
			?></div>
		</div><?php
	}
?>
