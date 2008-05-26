<?php
   
   function ElementFrontpageView( tBoolean $newuser ) {
        global $user;
		global $water;
		
    	$newuser = $newuser->Get(); // TODO
        ?><div class="frontpage"><?php
		if ( $newuser ) {
			?><div class="ybubble">
				<a href="" onclick="Frontpage.Closenewuser();return false;"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
				<form style="margin:0;padding:0">
					<p style="margin:0">Αν είσαι φοιτητής επέλεξε τη σχολή σου:</p>
					<div>
						<span>Πόλη:</span><select onchange="Frontpage.Showunis( this.parentNode.parentNode );">
							<option value="0" selected="selected">-</option>
							<option value="117">Άρτα</option><option value="46">Άργος</option><option value="109">Άγιος Νικόλαος</option><option value="98">Άμφισσα</option><option value="146">Έδεσσα</option><option value="149">Αργοστόλι</option><option value="160">Ασπρόπυργος</option><option value="35">Αγρίνιο</option><option value="2">Αθήνα</option><option value="113">Αλεξανδρούπολη</option><option value="159">Αμφιλοχία</option><option value="137">Βόλος</option><option value="102">Βέροια</option><option value="143">Γρεβενά</option><option value="112">Δράμα</option><option value="37">Ερέτρια</option><option value="133">Ερμούπολη</option><option value="157">Ελευσίνα</option><option value="124">Ζάκυνθος</option><option value="110">Ηράκλειο</option><option value="120">Ηγουμενίτσα</option><option value="154">Θήβα</option><option value="107">Θεσσαλονίκη</option><option value="1">Ιωάννινα</option><option value="155">Ιεράπετρα</option><option value="130">Κόρινθος</option><option value="26">Κύπρος</option><option value="121">Κέρκυρα</option><option value="97">Καρπενήσι</option><option value="135">Καρδίτσα</option><option value="144">Καστοριά</option><option value="105">Κατερίνη</option><option value="114">Καβάλα</option><option value="132">Καλαμάτα</option><option value="122">Κεφαλλονιά</option><option value="161">Κιάτο</option><option value="103">Κιλκίς</option><option value="145">Κοζάνη</option><option value="115">Κομοτηνή</option><option value="136">Λάρισα</option><option value="126">Λέσβος</option><option value="99">Λαμία</option><option value="123">Λευκάδα</option><option value="153">Ληξούρι</option><option value="100">Λιβαδειά</option><option value="147">Μυτιλήνη</option><option value="158">Μέτσοβο</option><option value="140">Μεσολόγγι</option><option value="152">Νάουσα</option><option value="129">Ναύπλιο</option><option value="116">Ξάνθη</option><option value="151">Ορεστιάδα</option><option value="119">Πρέβεζα</option><option value="141">Πύργος</option><option value="139">Πάτρα</option><option value="148">Πειραιάς</option><option value="101">Πολύγυρος</option><option value="134">Ρόδος</option><option value="111">Ρέθυμνο</option><option value="131">Σπάρτη</option><option value="156">Σπέτσες</option><option value="150">Σύρος</option><option value="127">Σάμος</option><option value="106">Σέρρες</option><option value="44">Σκύρος</option><option value="128">Τρίπολη</option><option value="138">Τρίκαλα</option><option value="142">Φλώρινα</option><option value="125">Χίος</option><option value="96">Χαλκίδα</option><option value="11">Χανιά</option>
						</select>
					</div>
					<p>Μπορείς να το κάνεις και αργότερα από τις ρυθμίσεις.</p>
				</form>
				<i class="bl"></i>
		        <i class="br"></i>
			</div><?php
		}
		?>	
		<div class="latestimages">
			<ul>
				<li><a href="" onclick=""><img src="images/seraphim2.jpg" alt="seraphim" title="seraphim" /></a></li>
				<li><a href="" onclick=""><img src="images/elsa2.jpg" alt="elsa" title="elsa" /></a></li>
				<li><a href="" onclick=""><img src="images/elenh2.jpg" alt="elenh" title="elenh" /></a></li>
				<li><a href="" onclick=""><img src="images/izual2.jpg" alt="izual" title="izual" /></a></li>
				<li><a href="" onclick=""><img src="images/morvena2.jpg" alt="morvena" title="morvena" /></a></li>
				<li><a href="" onclick=""><img src="images/ulee2.jpg" alt="ulee" title="ulee" /></a></li>
				<li><a href="" onclick=""><img src="images/avatars/teddy.jpg" alt="teddy" title="teddy" /></a></li>
				<li><a href="" onclick=""><img src="images/avatars/klio.jpg" alt="klio" title="klio" /></a></li>
				<li><a href="" onclick=""><img src="images/avatars/cafrillio.jpg" alt="cafrillio" title="cafrillio" /></a></li>
				<li><a href="" onclick=""><img src="images/dionyziz2.jpg" alt="dionyziz" title="dionyziz" /></a></li>
				<li><a href="" onclick=""><img src="images/avatars/argiro-18.jpg" alt="argiro_18" title="argiro_18" /></a></li>
			</ul>
		</div><?php
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
			Element( 'frontpage/shoutbox' );
			?></div><?php
		} 
		else {
			?><div class="inshoutbox"><?php
				Element( 'frontpage/shoutbox' );
				?><div class="inlatestcomments"><?php
				Element( 'frontpage/comments' );
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
