<?php
	$newuser = isset( $_GET[ 'newuser' ] ) ? true : false;
	$loggedin = isset( $_GET[ 'loggedin' ] ) ? true : false;
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
	</div><?php
	if ( !$loggedin ) {
	?><div class="members">
		<div class="join">
			<form>
				<h2>Δημιούργησε το προφίλ σου!</h2>
				<div>
					<label>Όνομα:</label><input type="text" name="username" />
				</div>
				<div>
					<input value="Δημιουργία &raquo;" type="submit" /> 
				</div>
			</form>
		</div>
		<div class="login">
			<form>
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
	<div style="float:left;width:60%;"><?php
	include 'frontpage_shoutbox.php';
	?></div>
	<div style="float:left;width:40%;"><?php
	include 'frontpage_latestcomments.php';
	?></div><?php
	} 
	?><div class="eof"></div>
	<div class="nowonline">
		<h2>Είναι online τώρα</h2>
		<div class="list">
			<a href="http://klio.zino.gr"><img src="images/avatars/klio.jpg" alt="klio" /></a>
			<a href="http://teddy.zino.gr"><img src="images/avatars/teddy.jpg" alt="teddy" /></a>
			<a href="http://elenh.zino.gr"><img src="images/avatars/elenh.jpg" alt="_El3nh_" /></a>
			<a href="http://dionyziz.zino.gr"><img src="images/avatars/dionyziz.jpg" alt="dionyziz" /></a>
			<a href="http://seraphim.zino.gr"><img src="images/avatars/seraphim.jpg" alt="seraphim" /></a>
			<a href="http://morvena.zino.gr"><img src="images/avatars/morvena.jpg" alt="morvena" /></a>
			<a href="http://kostis90gr.zino.gr"><img src="images/avatars/kostis90gr.jpg" alt="kostis90gr" /></a>
			<a href="http://ctef.zino.gr"><img src="images/avatars/ctef.jpg" alt="ctef" /></a>
			<a href="http://izual.zino.gr"><img src="images/avatars/izual.jpg" alt="izual" /></a>
			<a href="http://cafrillio.zino.gr"><img src="images/avatars/cafrillio.jpg" alt="cafrillio" /></a>
			<a href="http://ulee.zino.gr"><img src="images/avatars/ulee.jpg" alt="uLee" /></a>
			<a href="http://scorpion.zino.gr"><img src="images/avatars/scorpion.jpg" alt="scorpion" /></a>
			<a href="http://porcelain-barbie.zino.gr"><img src="images/avatars/porcelain_barbie.jpg" alt="porcelain_barbie" /></a>
			<a href="http://rhapsody.zino.gr"><img src="images/avatars/rhapsody.jpg" alt="rhapsody" /></a>
			<a href="http://katrine.zino.gr"><img src="images/avatars/katrine.jpg" alt="katrine" /></a>
			<a href="http://gatoni.zino.gr"><img src="images/avatars/gatoni.jpg" alt="gatoni" /></a>
			<a href="http://georgia.zino.gr"><img src="images/avatars/georgia.jpg" alt="georgia" /></a>
			<a href="http://drag.zino.gr"><img src="images/avatars/drag.jpg" alt="drag" /></a>
			<a href="http://eva483.zino.gr"><img src="images/avatars/eva483.jpg" alt="eva483" /></a>
			<a href="http://mpoompoo.zino.gr"><img src="images/avatars/mpoompoo.jpg" alt="mpoompoo" /></a>
			<a href="http://hara16.zino.gr"><img src="images/avatars/hara16.jpg" alt="hara16" /></a>
			<a href="http://olia_kaulitz.zino.gr"><img src="images/avatars/olia_kaulitz.jpg" alt="olia_kaulitz" /></a>
			<a href="http://nenoooz.zino.gr"><img src="images/avatars/nenoooz.jpg" alt="nenoooz" /></a>
			<a href="http://vivika.zino.gr"><img src="images/avatars/vivika.jpg" alt="vivika" /></a>
			<a href="http://tnt.zino.gr"><img src="images/avatars/tnt.jpg" alt="tnt" /></a>
			<a href="http://titi.zino.gr"><img src="images/avatars/titi.jpg" alt="titi" /></a>
			<a href="http://zoulou.zino.gr"><img src="images/avatars/zoulou.jpg" alt="zoulou" /></a>
			<a href="http://vacilina.zino.gr"><img src="images/avatars/vacilina.jpg" alt="vacilina" /></a>
			<a href="http://aserhcp.zino.gr"><img src="images/avatars/aserhcp.jpg" alt="aserhcp" /></a>
			<a href="http://argiro-18.zino.gr"><img src="images/avatars/argiro-18.jpg" alt="argiro_18" /></a>
			<a href="http://arloucoz.zino.gr"><img src="images/avatars/arloucoz.jpg" alt="arloucoz" /></a>
			<a href="http://aztokiohotel09.zino.gr"><img src="images/avatars/aztokiohotel09.jpg" alt="aztokiohotel09" /></a>			
		</div>
	</div>
	<div class="eof"></div>
</div>