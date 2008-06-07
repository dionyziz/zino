<?php
	
	function ElementFrontpageEventsView( $event ) {
		?><div class="event">
			<div class="toolbox">
				<span class="time">���� <?php
				echo $event->Since;
				?></span>
			</div>
			<div class="who">
				<a href="http://morvena.zino.gr"><?php
				Element( 'user/display' , $event->User );
				?></a> <?php
				switch ( $event->Typeid ) {
					case EVENT_IMAGE_CREATED:
						?>ανέβασε την εικόνα<?php
						break;
					case EVENT_JOURNAL_CREATED:
						?>καταχώρησε στο ημερολόγιο<?php
						break;
					case EVENT_POLL_CREATED:
						?>δημιούργησε τη δημοσκόπηση<?php
						break;
					case EVENT_USERSPACE_UPDATED:
						?>ανανέωσε τον χώρο<?php
						break;
					case EVENT_FRIENDRELATION_CREATED:
						?>πρόσθεσε στους φίλους<?php
						break;
					case EVENT_USERPROFILE_EDUCATION_UPDATED:
						?>όρισε εκπαίδευση<?php
						break;
					case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
						?>όρισε σεξουαλικές προτιμήσεις<?php
						break;
					case EVENT_USERPROFILE_RELIGION_UPDATED:
						?>όρισε τις θρησκευτικές πεποιθήσεις<?php
						break;
					case EVENT_USERPROFILE_POLITICS_UPDATED:
						?>όρισε τις πολιτικές πεποιθήσεις<?php
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
						?>ανανέωσε το προφίλ<?php
						break;
					case EVENT_USER_CREATED:
						?>δημιούργησε λογαριασμό<?php
						break;			
				}
			?></div>
			<div class="subject">
				<a href="#"><img src="images/phlast1.jpg" alt="dragon" /></a>
				<a href="#"><img src="images/phlast2.jpg" alt="big-bang" /></a>
			</div>					
		</div><?php
	}
?>
