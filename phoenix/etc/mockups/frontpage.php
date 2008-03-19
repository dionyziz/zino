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
	<div class="members">
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
	<div class="shoutbox">
		<h2>Συζήτηση</h2>
		<div class="comments">
			<div class="comment newcomment">
				<div class="who">
					<a href="user/dionyziz">
						<img src="images/avatars/dionyziz.jpg" class="avatar" alt="Dionyziz" />
						dionyziz
					</a>πρόσθεσε ένα σχόλιο στη συζήτηση
				</div>
				<div class="text">
					<textarea rows="2" cols="50"></textarea>
				</div>
				<div class="bottom">
					<input type="submit" value="Σχολίασε!" />
				</div>
			</div>
			
			<div class="comment" style="border-color: #dee;">
				<div class="toolbox">
					<span class="time">πριν 12 λεπτά</span>
				</div>
				<div class="who">
					<a href="user/smilemagic">
						<img src="images/avatars/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
						SmilEMagiC
					</a> είπε:
				</div>
				<div class="text">
					pao na pai3o WoW... geia sas malakies!!!
				</div>
			</div>
			
			<div class="comment" style="border-color: #dee;">
				<div class="toolbox">
					<span class="time">πριν 12 λεπτά</span>
				</div>
				<div class="who">
					<a href="user/smilemagic">
						<img src="images/avatars/izual.jpg" class="avatar" alt="SmilEMagiC" />
						Izual
					</a> είπε:
				</div>
				<div class="text">
					Βαριέμαι ρε ψόλες...
				</div>
			</div>
			
			<div class="comment" style="border-color: #dee;">
				<div class="toolbox">
					<span class="time">πριν 13 λεπτά</span>
				</div>
				<div class="who">
					<a href="user/smilemagic">
						<img src="images/avatars/ulee.jpg" class="avatar" alt="SmilEMagiC" />
						uLee
					</a> είπε:
				</div>
				<div class="text">
					Φοράω τα φτερά μου και το παίζω πεταλούδος... yeah zouzounitsa mou
				</div>
			</div>
			
			<div class="comment" style="border-color: #dee;">
				<div class="toolbox">
					<span class="time">πριν 16 λεπτά</span>
				</div>
				<div class="who">
					<a href="user/smilemagic">
						<img src="images/avatars/elsa.jpg" class="avatar" alt="SmilEMagiC" />
						elsa
					</a> είπε:
				</div>
				<div class="text">
					Re georgia ama se piaso... na deis ti 8a ginei
				</div>
			</div>
			
			<div class="comment" style="border-color: #dee;">
				<div class="toolbox">
					<span class="time">πριν 17 λεπτά</span>
				</div>
				<div class="who">
					<a href="user/smilemagic">
						<img src="images/avatars/elenh.jpg" class="avatar" alt="_El3nh_" />
						_El3nh_
					</a> είπε:
				</div>
				<div class="text">
					Re zouzounitsa se agapao pragmatika kai eisai to pan gia mena...<br />
					O fterotos petaloudos sou
				</div>
			</div>	
		</div>
	</div>
	<div class="latestcomments">
		<h2>Νεότερα σχόλια</h2>
		<div class="lastcomment">
			<div class="toolbox">
				<span class="time">πριν λίγο</span>
			</div>
			<div class="who">
				<a href="http://morvena.zino.gr">
					<img src="images/avatars/morvena.jpg" class="avatar" alt="morvena" />
					Morvena
				</a> σχολίασε στο ημερολόγιο
			</div>
			<div class="subject">
				<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
			</div>
		</div>
		<div class="lastcomment">
			<div class="toolbox">
				<span class="time">πριν λίγο</span>
			</div>
			<div class="who">
				<a href="http://teddy.zino.gr">
					<img src="images/avatars/teddy.jpg" class="avatar" alt="morvena" />
					Teddy
				</a> σχολίασε στη φωτογραφία
			</div>
			<div class="subject">
				
			</div>
		</div>
		<div class="lastcomment">
			<div class="toolbox">
				<span class="time">πριν 1 λεπτό</span>
			</div>
			<div class="who">
				<a href="http://elenh.zino.gr">
					<img src="images/avatars/elenh.jpg" class="avatar" alt="morvena" />
					_El3nh_
				</a> σχολίασε στη δημοσκόπηση
			</div>
			<div class="subject">
				<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
			</div>
		</div>
		<div class="lastcomment">
			<div class="toolbox">
				<span class="time">πριν 1 λεπτό</span>
			</div>
			<div class="who">
				<a href="http://dionyziz.zino.gr">
					<img src="images/avatars/dionyziz.jpg" class="avatar" alt="morvena" />
					dionyziz
				</a> σχολίασε στην εικόνα
			</div>
			<div class="subject">
				<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
			</div>
		</div>
		<div class="lastcomment">
			<div class="toolbox">
				<span class="time">πριν 3 λεπτά</span>
			</div>
			<div class="who">
				<a href="http://izual.zino.gr">
					<img src="images/avatars/izual.jpg" class="avatar" alt="morvena" />
					izual
				</a> σχολίασε στo προφίλ της 
			</div>
			<div class="subject">
				<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
			</div>
		</div>
	</div>
	<div class="eof"></div>
</div>