<?php
   
   function ElementFrontpageView( tBoolean $newuser ) {
        global $user;
		global $water;
		
    	$newuser = $newuser->Get(); // TODO
		$finder = New ImageFinder();
		$images = $finder->FindFrontpageLatest( 0 , 15 );
        ?><div class="frontpage"><?php
		if ( $newuser && $user->Exists() ) {
			?><div class="ybubble">
				<a href="" onclick="Frontpage.Closenewuser();return false;"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
				<form style="margin:0;padding:0">
					<p style="margin:0">Αν είσαι φοιτητής επέλεξε τη σχολή σου:</p>
					<div>
						<span>Πόλη:</span><?php
						if ( $user->Profile->Placeid != 0 ) {
							echo htmlspecialchars( $user->Profile->Location->Name );
						}
						else { 
							Element( 'user/settings/personal/place' , $user );
						}
					?></div>
					<p>Μπορείς να το κάνεις και αργότερα από τις ρυθμίσεις.</p>
				</form>
				<i class="bl"></i>
		        <i class="br"></i>
			</div><?php
		}
		if ( count( $images ) > 0 ) {
			?><div class="latestimages">
			<ul><?php
				foreach ( $images as $image ) {
					?><li><a href="?p=photo&amp;id=<?php
					echo $image->Id;
					?>"><?php
					Element( 'image' , $image , 100 , 100 , '' , $image->User->Name , $image->User->Name , '' , false , 0 , 0 );
					?></a></li><?php
				}
				?>
			</ul>
			</div><?php
		}
		if ( !$user->Exists() ) {
			?><div class="members">
				<div class="join">
					<form action="" method="get">
						<h2>Δημιούργησε το προφίλ σου!</h2>
						<div>
							<input type="hidden" name="p" value="join" />
							<label>Όνομα:</label><input type="text" name="username" />
						</div>
						<div>
							<input value="Δημιουργία &raquo;" type="submit" /> 
						</div>
					</form>
				</div>
				<div class="login">
					<form action="do/user/login" method="post">
						<h2>Είσοδος στο zino</h2>
						<div>
							<label>Όνομα:</label> <input type="text" name="username" />
						</div>
						<div>
							<label>Κωδικός:</label> <input type="password" name="password" />
						</div>
						<div>
							<input type="submit" value="Είσοδος &raquo;" />
						</div>
					</form>
				</div>
			</div>
			<div class="eof"></div>
			<div class="outshoutbox"><?php
			Element( 'frontpage/shoutbox/list' );
			?></div><?php
		} 
		else {
			?><div class="inshoutbox"><?php
				Element( 'frontpage/shoutbox/list' );
				?><div class="inlatestcomments"><?php
				Element( 'frontpage/comment/list' );
				?></div>
			</div>
			<div class="inevents"><?php
			Element( 'frontpage/events' );
			?></div><?php
		}
		?><div class="eof"></div>
		<div class="nowonline"><?php
			$finder = New UserFinder();
			$users = $finder->FindOnline( 0 , 50 );
			$water->Trace( 'onlineusers are: ' . count( $users ) );
			if ( count( $users ) > 0 ) {
				?><h2>Είναι online τώρα</h2>
				<div class="list"><?php
					foreach( $users as $onuser ) {
						?><a href="?p=user&amp;subdomain=<?php
						echo $onuser->Subdomain;
						?>"><?php
						Element( 'user/avatar' , $onuser , 150 , '' , '' );
						?></a><?php
					}	
				?></div><?php
			}
		?></div>
		<div class="eof"></div>
	</div><?php
	}
?>
