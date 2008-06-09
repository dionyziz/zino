<?php
	function ElementEventList() {
		global $libs;
		global $water;
		
		$libs->Load( 'event' );
		
		$finder = New EventFinder();
        $events = $finder->FindLatest( 0, 20 );
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
					$info[ $event->User->Id ][ $type ][] = $event;
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
									case 'profile_update':
										?> ανανέωσε το προφίλ<?php
										break;
									case EVENT_USER_CREATED:
										?> δημιούργησε λογαριασμό<?php
										break;			
								}
							?></div>
							<div class="subject"><?php
								$water->Trace( 'eventlist has ' . count( $eventlist ) );
								switch ( $type ) {
									case EVENT_IMAGE_CREATED:
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
                                        ?><div><?php
                                        if ( $eventlist[ 0 ]->User->Gender =='f' ) {
                                            ?>Η <?php
                                        }
                                        else {
                                            ?>O <?php
                                        }
                                        echo $eventlist[ 0 ]->User->Name;
                                        $info = array();
										foreach ( $eventlist as $one ) {
                                            ob_start();
											switch ( $one->Typeid ) {
												case EVENT_USERPROFILE_EDUCATION_UPDATED:
													?>πάει <?php
													ob_start();
													Element( 'user/trivial/education' , $one->User->Profile->Education );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
													?>είναι <?php
													ob_start();
													Element( 'user/trivial/sex' , $one->User->Profile->Sexualorientation , $one->User->Gender );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_RELIGION_UPDATED:
													?>είναι <?php
													ob_start();
													Element( 'user/trivial/religion' , $one->User->Profile->Religion , $one->User->Gender );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_POLITICS_UPDATED:
													?>είναι <?php
													ob_start();
													Element( 'user/trivial/politics' , $one->User->Profile->Politics , $one->User->Gender );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_SMOKER_UPDATED:
													switch ( $one->User->Profile->Smoker ) {
														case 'yes':
															?>καπνίζει<?php
															break;
														case 'no':
															?>δεν καπνίζει<?php
															break;
														case 'socially':
															?>καπνίζει με παρέα<?php
															break;
													}
													break;
												case EVENT_USERPROFILE_DRINKER_UPDATED:
													switch ( $one->User->Profile->Drinker ) {
														case 'yes':
															?>πίνει<?php
															break;
														case 'no':
															?>δεν πίνει<?php
															break;
														case 'socially':
															?>πίνει με παρέα<?php
															break;
													}
													break;
												case EVENT_USERPROFILE_ABOUTME_UPDATED:
													?>έγραψε για τον εαυτό <?php
													echo $self;
													?> "<?php
													echo htmlspecialchars( utf8_substr( $one->User->Profile->Aboutme , 0 , 20 ) );
													?>"<?php
													break;
												case EVENT_USERPROFILE_MOOD_UPDATED:
												case EVENT_USERPROFILE_LOCATION_UPDATED:
													?>είναι από <?php
													echo htmlspecialchars( $one->User->Profile->Location->Name );
													break;
												case EVENT_USERPROFILE_HEIGHT_UPDATED:
													?>είναι <?php
													ob_start();
													Element( 'user/trivial/height' , $one->User->Profile->Height );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_WEIGHT_UPDATED:
													ob_start();
													Element( 'user/trivial/weight' , $one->User->Profile->Weight );
													echo strtolower( ob_get_clean() );
													break;
												case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
													if ( $one->User->Profile->Haircolor == 'highlights' ) {
														?>έχει ανταύγειες<?php
													}
													else if ( $one->User->Profile->Haircolor == 'skinhead' ) {
														?>είναι skinhead<?php
													}
													else {
														?>έχει <?php 
														ob_start();
														Element( 'user/trivial/haircolor' , $one->User->Profile->Haircolor );
														echo strtolower( ob_get_clean() );
														?> μαλλιά<?php
													}
													break;
												case EVENT_USERPROFILE_EYECOLOR_UPDATED:
													?>έχει <?php
													ob_start();
													Element( 'user/trivial/eyecolor' , $one->User->Profile->Eyecolor );
													echo strtolower( ob_get_clean() );
													?> μάτια<?php
											}
                                            $info[] = ob_get_clean();
										}
                                        if ( count( $info ) > 1 ) {
                                            $info[ count( $info ) - 2 ] = $info[ count( $info ) - 2 ] . " και " . $info[ count( $info ) - 1 ];
                                            unset( $info[ count( $info ) - 1 ] );
                                        }
                                        ?> <?php
                                        echo implode( ', ', $info );
                                        ?></div><?php
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
