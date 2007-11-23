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
			<li><a href="" onclick=""><img src="images/avatars/rapper.jpg" alt="rapper" title="rapper" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/dionyziz2.jpg" alt="dionyziz" title="dionyziz" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/klio.jpg" alt="klio" title="klio" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/izual2.jpg" alt="izual" title="izual" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/arianti.jpg" alt="arianti" title="arianti" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/cafrillio.jpg" alt="cafrillio" title="cafrillio" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/emo4life.jpg" alt="emo4life" title="emo4life" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/ax_tom_ax.jpg" alt="ax_tom_ax" title="ax_tom_ax" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/rakimu.jpg" alt="rakimu" title="rakimu" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/paaa.jpg" alt="paaa" title="paaa" /></a></li>
			<li><a href="" onclick=""><img src="images/avatars/irini_th_bill.jpg" alt="irini_th_bill" title="irini_th_bill" /></a></li>
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
				<li><a href=""><img src="images/avatars/11400.jpg" style="width:100px;height:75px" alt="effie" title="effie" /><strong>effie</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/29229.jpg" style="width:75px;height:100px" alt="punkerfly" title="punkerfly" /><strong>punkerfly</strong><span>προβολή προφίλ &raquo;</span></a></li>
				<li><a href=""><img src="images/avatars/29228.jpg" style="width:83px;height:100px" alt="alinangel" title="alinangel" /><strong>alinangel</strong><span>προβολή προφίλ &raquo;</span></a></li>
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
	<div>
		<h2>Πρόσφατα γεγονότα</h2>
		<ul>
		</ul>
	</div>
</div>