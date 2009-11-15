<?php 
    class ElementSpot extends Element {
        public function Render( tText $username ) {
	        global $page;
	        global $user;
	        global $libs;
			global $xc_settings;
			    
		    if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
		        ?> Permission Denied <?php
		        return;
		    }
		    
		    $page->setTitle( 'S.P.O.T - Social Prediction Optimazation Tool' );
	
		    
		    ?><h2>Αποτελέσματα S.P.O.T - Social Prediction Optimazation Tool</h2><?php


			$libs->Load( 'poll/poll' );
			$libs->Load( 'poll/frontpage' );
			$libs->Load( 'journal/journal' );
			$libs->Load( 'journal/frontpage' );

	       	$finder = New PollFinder();
		    $polls = false;
		   	if ( $user->Exists() ) {

				$polls = $finder->FindUserRelated( $user );
				if ( $polls === false ) {
					?><b>Spot connection failed (start daemon!).</b><?php
				}
				else {
					?><div class="list">
					<h2 class="pheading">Δημοσκοπήσεις ( προτεινόμενες ) </h2><?php
					foreach ( $polls as $poll ) {
						$domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
						$url = $domain . 'polls/' . $poll->Url;
				   	    ?><p>
						  <a href="<?php
							    echo $url;
						?>"> <?php 
						echo $url . "</a> - ";
				 	    echo htmlspecialchars( $poll->Question );
					}
					?></div><?php					
				}

				$polls = $finder->FindFrontpageLatest( 0 , 4 );
		  	    ?><div class="list">
				<h2 class="pheading">Δημοσκοπήσεις ( πρόσφατες )</h2><?php
				foreach ( $polls as $poll ) {
					$domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
					$url = $domain . 'polls/' . $poll->Url;
			   	    ?><p>
					  <a href="<?php
					        echo $url;
					?>"> <?php 
					echo $url . "</a> - ";
			 	    echo htmlspecialchars( $poll->Question );
				}
				?></div><?php



				$finder = New JournalFinder();
		    	$journals = false;
				$journas = $finder->FindUserRelated( $user );
				if ( $journals === false ) {
					?><b>Spot connection failed (start daemon!).</b><?php
				}
				else {
					?><div class="list">
					<h2 class="pheading">Ημερολόγια ( προτεινόμενα ) </h2><?php
					foreach ( $journals as $journal ) {
				   	    ?><p>
						  <a href="<?php
							    echo Element( 'url', $journal );
						?>"> <?php 
						echo Element( 'url', $journal ) . "</a> - ";
				 	    echo htmlspecialchars( $journal->Title ) . "</p>";
					}
					?></div><?php					
				}

				$journals = $finder->FindFrontpageLatest( 0 , 4 );
		  	    ?><div class="list">
				<h2 class="pheading">Ημερολόγια ( πρόσφατα )</h2><?php
				foreach ( $journals as $journal ) {
				   	    ?><p>
						  <a href="<?php
							    echo Element( 'url', $journal );
						?>"> <?php 
						echo Element( 'url', $journal ) . "</a> - ";
				 	    echo htmlspecialchars( $journal->Title ) . "</p>";
				}
				?></div><?php
			}


		}
	}
?>
