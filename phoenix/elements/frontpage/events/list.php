<?php
	
	function ElementFrontpageEventsList() {
		global $libs;
		
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
					Element( 'frontpage/events/view' , $event );
				}
			?></div>
		</div><?php
	}
?>
