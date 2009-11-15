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
		    
		    $page->setTitle( 'Αποτελέσματα S.P.O.T - Social Prediction Optimazation Tool' );
	
		    
		    ?><h2>Αποτελέσματα S.P.O.T - Social Prediction Optimazation Tool</h2><?php


			$libs->Load( 'poll/poll' );
			$libs->Load( 'poll/frontpage' );

	       	$finder = New PollFinder();
		    $polls = false;
		   	if ( $user->Exists() ) {

				$polls = $finder->FindUserRelated( $user );
				if ( $polls === false ) {
					?><b>Spot connection failed (start daemon!).</b><?php
				}
				else {
					foreach ( $polls as $poll ) {
						?><div class="list">
						<h2 class="pheading">Δημοσκοπήσεις ( S.P.O.T ) </h2><?php
						$domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
						$url = $domain . 'polls/' . $poll->Url;
				   	    ?><p>
						  <a href="<?php
							    echo $url;
						?>"> <?php 
						echo $url . "</a> - ";
				 	    echo htmlspecialchars( $poll->Question ) . "</p>";
					}
					?></div><?php
				}
				


				$libs->Load( 'poll/frontpage' );
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
			 	    echo htmlspecialchars( $poll->Question ) . "</p>";
				}
				?></div><?php
			}
		}
	}
?>
