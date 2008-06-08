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
				foreach ( $events as $event ) {
					if ( $event->Typeid == EVENT_USERPROFILE_EDUCATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_RELIGION_UPDATED || $event->Typeid == EVENT_USERPROFILE_POLITICS_UPDATED || $event->Typeid == EVENT_USERPROFILE_SMOKER_UPDATED || $event->Typeid == EVENT_USERPROFILE_DRINKER_UPDATED || $event->Typeid == EVENT_USERPROFILE_ABOUTME_UPDATED || $event->Typeid == EVENT_USERPROFILE_MOOD_UPDATED || $event->Typeid == EVENT_USERPROFILE_LOCATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_HEIGHT_UPDATED || $event->Typeid == EVENT_USERPROFILE_WEIGHT_UPDATED || $event->Typeid == EVENT_USERPROFILE_HAIRCOLOR_UPDATED || $event->Typeid == EVENT_USERPROFILE_EYECOLOR_UPDATED ) {
						$type = 'profile_update';
					}	
					else {
						$type = $event->Typeid;
					}
					$info[ $event->User->Id ][ $type ] = $event;
					$visited[ $event->User->Id ][ $type ] = false;	
				}
				foreach ( $events as $event ) {
					if ( $event->Typeid == EVENT_USERPROFILE_EDUCATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_RELIGION_UPDATED || $event->Typeid == EVENT_USERPROFILE_POLITICS_UPDATED || $event->Typeid == EVENT_USERPROFILE_SMOKER_UPDATED || $event->Typeid == EVENT_USERPROFILE_DRINKER_UPDATED || $event->Typeid == EVENT_USERPROFILE_ABOUTME_UPDATED || $event->Typeid == EVENT_USERPROFILE_MOOD_UPDATED || $event->Typeid == EVENT_USERPROFILE_LOCATION_UPDATED || $event->Typeid == EVENT_USERPROFILE_HEIGHT_UPDATED || $event->Typeid == EVENT_USERPROFILE_WEIGHT_UPDATED || $event->Typeid == EVENT_USERPROFILE_HAIRCOLOR_UPDATED || $event->Typeid == EVENT_USERPROFILE_EYECOLOR_UPDATED ) {
						$type = 'profile_update';
					}
					else {
						$type = $event->Typeid;
					}
					if ( !$visited[ $event->User->Id ][ $type ] ) {
						$eventlist = $info[ $event->User->Id ][ $type ];
						$visited[ $event->User->Id ][ $type ] = true;
						?><div class="event">
							<div class="toolbox">
							</div>
							<div class="who"><?php
								Element( 'user/display' , $event->User );
								switch ( $type ) {
									case EVENT_IMAGE_CREATED:
										?> ανέβασε <?php
										if ( count( $eventlist ) > 1 ) {
											?>τις εικόνες<?php
										}
										else {
											?>την εικόνα<?php
										}
										break;
									case EVENT_JOURNAL_CREATED:
										?> καταχώρησε στο ημερολόγιο<?php
										break;
									case EVENT_POLL_CREATED:
										?> δημιούργησε <?php
										if ( count( $eventlist ) > 1 ) {
											?>τις δημοσκοπήσεις<?php
										}
										else {
											?>τη δημοσκόπηση<?php
										}
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
									case 'profile_update':
										?> ανανέωσε το προφίλ<?php
										break;
									case EVENT_USER_CREATED:
										?> δημιούργησε λογαριασμό<?php
										break;			
								}
							?></div>
							<div class="subject"><?php
								$water->Trace( 'type is ' . $type );
								switch ( $type ) {
									case EVENT_IMAGE_CREATED:
										?>eikona<?php
										foreach ( $eventlist as $one ) {
											?><a href="?p=photo&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											Element( 'image' , $one->Item , IMAGE_CROPPED_100x100 , '' , $one->User->Name , $one->User->Name , 'margin-right:3px;' , false , 0 , 0 );
											?></a><?php
										}
										break;
									case EVENT_JOURNAL_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="?p=journal&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											echo htmlspecialchars( $one->Item->Title );
											?></a><?php
										}
										break;
									case EVENT_POLL_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="?p=poll&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											echo htmlspecialchars( $one->Item->Question );
											?></a><?php
										}
										break;
									case EVENT_USERSPACE_UPDATED:
										?><a href="?p=space&amp;subdomain=<?php
										echo $event->User->Name;
										?>">Προβολή χώρου</a><?php
										break;
									case EVENT_FRIENDRELATION_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="<?php
											Element( 'user/url' , $one->Item->Friend );
											?>"><?php
											Element( 'user/avatar' , $one->Item->Friend, 100 , '' , 'margin-right:3px;' , false , 0 , 0 );
											?></a><?php
										}
										break;
									case 'profile_update':
										foreach ( $eventlist as $one ){ 
											switch ( $one->Typeid ) {
												case EVENT_USERPROFILE_EDUCATION_UPDATED:
													if ( $one->User->Gender =='f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> πάει <?php
													Element( 'user/trivial/education' , $one->User->Profile->Education );
													break;
												case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι <?php
													Element( 'user/trivial/sex' , $one->User->Profile->Sexualorientation , $one->User->Gender );
													break;
												case EVENT_USERPROFILE_RELIGION_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι <?php
													Element( 'user/trivial/religion' , $one->User->Profile->Religion );
													break;
												case EVENT_USERPROFILE_POLITICS_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι <?php
													Element( 'user/trivial/politics' , $one->User->Profile->Politics );
													break;
												case EVENT_USERPROFILE_SMOKER_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													switch ( $one->User->Profile->Smoker ) {
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
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													switch ( $one->User->Profile->Drinker ) {
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
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
														$self = 'της';
													}
													else {
														?>O <?php
														$self = 'του';
													}
													echo $one->User->Name;
													?> έγραψε για τον εαυτό <?php
													echo $self;
													?> "<?php
													echo htmlspecialchars( utf8_substr( $one->User->Profile->Aboutme , 0 , 20 ) );
													?>"<?php
													break;
												case EVENT_USERPROFILE_MOOD_UPDATED:
												case EVENT_USERPROFILE_LOCATION_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι από <?php
													echo htmlspecialchars( $one->User->Profile->Location->Name );
													break;
												case EVENT_USERPROFILE_HEIGHT_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι <?php
													strtolower( Element( 'user/trivial/height' , $one->User->Profile->Height ) );
													break;
												case EVENT_USERPROFILE_WEIGHT_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													?> είναι <?php
													strtolower( Element( 'user/trivial/weight' , $one->User->Profile->Weight ) );
													break;
												case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													echo $one->User->Name;
													if ( $one->User->Profile->Haircolor == 'highlights' ) {
														?> έχει ανταύγειες<?php
													}
													else if ( $one->User->Profile->Haircolor == 'skinhead' ) {
														?> είναι skinhead<?php
													}
													else {
														?> έχει <?php 
														strtolower( Element( 'user/trivial/haircolor' , $one->User->Profile->Haircolor ) );
														?> μαλλί<?php
													}
													break;
												case EVENT_USERPROFILE_EYECOLOR_UPDATED:
													if ( $one->User->Gender == 'f' ) {
														?>Η <?php
													}
													else {
														?>O <?php
													}
													?> έχει <?php
													echo $one->User->Name;
													Element( 'user/trivial/eyecolor' , $one->User->Profile->Eyecolor );
													?> χρώμα ματιών<?php
											}
										}
										break;		
									case EVENT_USER_CREATED:
										?> δημιούργησε λογαριασμό<?php
										break;			
								}
								?>
							</div>					
						</div><?php
					}
				}
			?></div>
		</div><?php
	}
?>
