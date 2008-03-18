<?php
	$newuser = isset( $_GET[ 'newuser' ] ) ? true : false;
?>
<script type="text/javascript" src="js/frontpage.js"></script>
<div class="frontpage"><?php
	if ( $newuser ) {
		?><div class="ybubble">
			<a href="" onclick="Frontpage.Closenewuser( this );this.style.display='none';return false;"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
			<form style="margin:0;padding:0">
				<p style="margin:0">Αν είσαι φοιτητής επέλεξε τη σχολή σου:</p>
				<div>
					<span>Πόλη:</span><select onchange="Frontpage.Showunis( this.parentNode.parentNode );">
						<option value="0" selected="selected">-</option>
						<option value="117">Άρτα</option><option value="46">Άργος</option><option value="109">Άγιος Νικόλαος</option><option value="98">Άμφισσα</option><option value="146">Έδεσσα</option><option value="149">Αργοστόλι</option><option value="160">Ασπρόπυργος</option><option value="35">Αγρίνιο</option><option value="2">Αθήνα</option><option value="113">Αλεξανδρούπολη</option><option value="159">Αμφιλοχία</option><option value="137">Βόλος</option><option value="102">Βέροια</option><option value="143">Γρεβενά</option><option value="112">Δράμα</option><option value="37">Ερέτρια</option><option value="133">Ερμούπολη</option><option value="157">Ελευσίνα</option><option value="124">Ζάκυνθος</option><option value="110">Ηράκλειο</option><option value="120">Ηγουμενίτσα</option><option value="154">Θήβα</option><option value="107">Θεσσαλονίκη</option><option value="1">Ιωάννινα</option><option value="155">Ιεράπετρα</option><option value="130">Κόρινθος</option><option value="26">Κύπρος</option><option value="121">Κέρκυρα</option><option value="97">Καρπενήσι</option><option value="135">Καρδίτσα</option><option value="144">Καστοριά</option><option value="105">Κατερίνη</option><option value="114">Καβάλα</option><option value="132">Καλαμάτα</option><option value="122">Κεφαλλονιά</option><option value="161">Κιάτο</option><option value="103">Κιλκίς</option><option value="145">Κοζάνη</option><option value="115">Κομοτηνή</option><option value="136">Λάρισα</option><option value="126">Λέσβος</option><option value="99">Λαμία</option><option value="123">Λευκάδα</option><option value="153">Ληξούρι</option><option value="100">Λιβαδειά</option><option value="147">Μυτιλήνη</option><option value="158">Μέτσοβο</option><option value="140">Μεσολόγγι</option><option value="152">Νάουσα</option><option value="129">Ναύπλιο</option><option value="116">Ξάνθη</option><option value="151">Ορεστιάδα</option><option value="119">Πρέβεζα</option><option value="141">Πύργος</option><option value="139">Πάτρα</option><option value="148">Πειραιάς</option><option value="101">Πολύγυρος</option><option value="134">Ρόδος</option><option value="111">Ρέθυμνο</option><option value="131">Σπάρτη</option><option value="156">Σπέτσες</option><option value="150">Σύρος</option><option value="127">Σάμος</option><option value="106">Σέρρες</option><option value="44">Σκύρος</option><option value="128">Τρίπολη</option><option value="138">Τρίκαλα</option><option value="142">Φλώρινα</option><option value="125">Χίος</option><option value="96">Χαλκίδα</option><option value="11">Χανιά</option>
					</select>
				</div>
				<p>Μπορείς να το κάνεις και αργότερα από το προφίλ σου.</p>
			</form>
			<i class="bl"></i>
	        <i class="br"></i>
		</div><?php
	}
	?>	
	<div class="latestimages">
		<ul>
			<li><a href="" onclick=""><img src="images/avatars/seraphim.jpg" alt="seraphim" title="seraphim" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/elsa.jpg" alt="elsa" title="elsa" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/elenh.jpg" alt="elenh" title="elenh" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/izual.jpg" alt="izual" title="izual" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/morvena.jpg" alt="morvena" title="morvena" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/ulee.jpg" alt="ulee" title="ulee" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/teddy.jpg" alt="teddy" title="teddy" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/klio.jpg" alt="klio" title="klio" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/cafrillio.jpg" alt="cafrillio" title="cafrillio" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/dionyziz.jpg" alt="dionyziz" title="dionyziz" /></a></li><?php /*
			<li><a href="" onclick=""><img src="images/avatars/irini_th_bill.jpg" alt="irini_th_bill" title="irini_th_bill" /></a></li>
			*/
			?>
		</ul>
	</div>
	<div class="meetfriends">
		<h2>Γνώρισε νέους φίλους</h2>
		<div class="people">
			<ul>
				<li><a href=""><img src="images/avatars/2400.jpg" style="width:100px;height:67px" alt="dionyziz" title="dionyziz" /><strong>dionyziz</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/14041.jpg" style="width:100px;height:75px" alt="klio" title="klio" /><strong>klio</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/20128.jpg" style="width:100px;height:88px" alt="finlandos" title="finlandos" /><strong>finlandos</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/28155.jpg" style="width:100px;height:75px" alt="elsa_kaulitz" title="elsa_kaulitz" /><strong>elsa_kaulitz</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/29203.jpg" style="width:100px;height:75px" alt="izual" title="izual" /><strong>izual</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/29230.jpg" style="width:100px;height:80px" alt="metalo92" title="metalo92" /><strong>metalo92</strong><span>προβολή προφίλ &raquo;</span></a></li>
			</ul>
			<div class="eof"></div>
		</div>
	</div>
	<div class="bubble">
	    <i class="tl"></i><i class="tr"></i>
	    <form class="memberform">
            <a name="login"></a>
	    	<h2>Είσαι ήδη μέλος?</h2>
	        <div>
	            <label for="login_uname">Όνομα:</label><input id="login_uname" type="text" value="" class="text" />
	        </div>
	        <div>
	            <label for="login_password">Κωδικός:</label><input id="login_password" type="text" value="" class="text" />
	        </div>
	        <input type="submit" value="Είσοδος &raquo;" class="button" />
	    </form>

	    <form class="joinform">
	    	<h2>Είσαι μέσα? Γίνε μέλος!</h2>
	        Γράψε το ψευδώνυμό σου για να ξεκινήσεις:
            <div>
	            <input type="text" value="" class="text" />
                <input type="submit" value="Γίνε μέλος &raquo;" class="button" />
	        </div>
	    </form>
	    
        <div class="eof"></div>
        
	    <i class="qleft"></i><i class="qright"></i>
	    <i class="qbottom"></i>
	    <i class="bl"></i><i class="br"></i>
	</div>
	<div class="recentevents">
		<h2>Πρόσφατα γεγονότα</h2>
		<ul class="events">
			<li class="poll">
				<div><a href="">Πόσες φορές τη βδομάδα βαράς μαλακία;</a> από <a href="">dionyziz</a></div>
			</li>
			<li class="journal">
				<div>Ο <a href="">Izual</a> έγραψε <a href="">σχόλιο στο MacGuyver sandwich</a></div>
			</li>
			<li class="journal">
				<div><a href="">MacGuyver sandwich</a> από <a href="">Izual</a></div>
			</li>
			<li class="photo">
				<div>
					<a href="">Γαμάτος ουρανοξύστης από Izual<br />
						<img src="images/ph3.jpg" alt="Γαμάτος ουρανοξύστης" title="Γαμάτος ουρανοξύστης" />
					</a>
				</div>
			</li>
			<li class="journal">
				<div><a href="">Tokio Hotel</a> από <a href="">Skater</a></div>
			</li>
			<li class="poll">
				<div><a href="">Τι να γίνει με τις μαθητικές παρελάσεις;</a> από <a href="">loliza</a></div>
			</li>
			<li class="photo">
				<div>
					<a href="">klio ψώνιο από klio<br />
						<img src="images/kliosexy.jpg" alt="klio ψώνιο" title="klio ψώνιο" />
					</a>
				</div>
			</li>
			<li class="journal">
				<div><a href="">Scooter</a> από <a href="">Izual</a></div>
			</li>
			<li class="journal last">
				<div><a href="">Parkour</a> από <a href="">dionyziz</a></div>
			</li>
		</ul>
    </div>
</div>