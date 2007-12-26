<?php
	function ElementUserProfileMain() {
		global $page;
		?><div class="main">
			<div class="photos">
				<ul>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizb.jpg" style="width:100px;height:100px;" alt="dionyzizb" title="dionyzizb" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizc.jpg" style="width:100px;height:100px;" alt="dionyzizc" title="dionyzizc" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizd.jpg" style="width:100px;height:100px;" alt="dionyzizd" title="dionyzizd" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzize.jpg" style="width:100px;height:100px;" alt="dionyzize" title="dionyzize" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizf.jpg" style="width:100px;height:100px;" alt="dionyzizf" title="dionyzizf" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizg.jpg" style="width:100px;height:100px;" alt="dionyzizg" title="dionyzizg" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizh.jpg" style="width:100px;height:100px;" alt="dionyzizh" title="dionyzizh" /></a></li>
					<li><a href=""><img src="http://static.zino.gr/phoenix/mockups/dionyzizi.jpg" style="width:100px;height:100px;" alt="dionyzizi" title="dionyzizi" /></a></li>
					<li><a href="" class="button">&raquo;</a></li>
				</ul>
			</div>
			<div class="friends">
				<h3>Οι φίλοι μου</h3><?php
				Element( 'user/list' );
				?><a href="" class="button">Περισσότεροι φίλοι&raquo;</a>
			</div>
			<div class="lastpoll">
				<h3>Δημοσκοπήσεις</h3><?php
				Element( 'poll/small' );
				?><a href="" class="button">Περισσότερες δημοσκοπήσεις&raquo;</a>
			</div>
			<div class="questions">
				<h3>Ερωτήσεις</h3>
				<ul>
					<li>
						<p class="question">Πόσα κιλά είναι η γιαγιά σου;</p>
						<p class="answer">Είναι πολύ χοντρή</p>
					</li>

					<li>
						<p class="question">Ποιο είναι το αγαπημένο σου φρούτο;</p>
						<p class="answer">Το αχλάδι και το καρπούζι</p>
					</li>
					<li>
						<p class="question">Πώς τη βλέπεις τη ζωή;</p>
						<p class="answer">Δε τη βλέπω</p>
					</li>
					<li>
						<p class="question">Πώς θα ήθελες να πεθάνεις;</p>
						<p class="answer">Από την πείνα</p>
					</li>
					<li style="padding-bottom:20px;">
						<p class="question">Αν εμφανιζόταν ένα τζίνι και σου έλεγε πως μπορεί να πραγματοποιήσει μια ευχή σου, τι θα του ζητούσες;</p>
						<p class="answer">Τίποτα</p>
					</li>
				</ul>
				<a href="" class="button">Περισσότερες ερωτήσεις&raquo;</a>
			</div>
			<div style="clear:right"></div>
			<div class="lastjournal">
				<h3>Ημερολόγιο</h3>
				<h4><a href="">The MacGuyver Sandwich</a></h4>
				<p>		
					Εξεταστική και το κάψιμο βαράει κόκκινο. Κάτι έπρεπε λοιπόν να κάνω για να σπάσω τη μονοτονία της καθημερινότητας
					και του διαβάσματος. Σήμερα είπα να φτιάξω βραδυνό με έναν προτότυπο τρόπο. Έφτιαξα λοιπόν
					ένα sandwich χωρίς να έχω ψηστιέρα. Όπως όμως λέει και η γνωστή παροιμία όποιος δεν έχει ψηστιέρα έχει σίδερο...
				</p>
				<dl>
					<dd><a href="">57 σχόλια</a></dd>
				</dl>
				<a href="" class="button">Περισσότερες καταχωρήσεις&raquo;</a>
			</div>
			<div class="comments">
				<h3>Σχόλια στο προφίλ του dionyziz</h3>
				<div class="comment newcomment">
					<div class="toolbox">
						<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
					</div>
					<div class="who">
						<a href="user/dionyziz">
							<img src="http://static.zino.gr/phoenix/mockups/dionyziz.jpg" class="avatar" alt="Dionyziz" />
							dionyziz
						</a>πρόσθεσε ένα σχόλιο στο προφίλ σου
					</div>
					<div class="text">
						<textarea></textarea>
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
							<img src="http://static.zino.gr/phoenix/mockups/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
							SmilEMagiC
						</a> είπε:
					</div>
					<div class="text">
						ρε μλκ τι είναι αυτά που γράφεις στο προφίλ μου? μωρή μαλακία...
						<img src="images/emoticons/tongue.png" alt=":P" title=":P" /><br />
						άμα σε πιάσω...<br />
						χαχα!! <img src="images/emoticons/teeth.png" alt=":D" title=":D" /><br />
						θα βρεθούμε το ΣΚ!??
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment" style="margin-left: 20px; border-color: #eed;">
					<div class="toolbox" style="margin-right: 20px">
						<span class="time">πριν 10 λεπτά</span>
					</div>
					<div class="who">
						<a href="user/kostis90gr">
							<img src="http://static.zino.gr/phoenix/mockups/kostis90gr.jpg" class="avatar" alt="kostis90gr" />
							kostis90gr
						</a> είπε:
					</div>
					<div class="text">
						αχαχαχαχ έλεος ρε νίκο!!...
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment" style="margin-left: 20px; border-color: #ded">
					<div class="toolbox" style="margin-right: 20px">
						<span class="time">πριν 9 λεπτά</span>
					</div>
					<div class="who">
						<a href="user/izual">
							<img src="http://static.zino.gr/phoenix/mockups/izual.jpg" class="avatar" alt="izual" />
							izual
						</a> είπε:
					</div>
					<div class="text">
						αφού τον ξέρεις μωρέ πώς κάνει..
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment" style="margin-left: 40px; border-color: #dee">
					<div class="toolbox" style="margin-right: 40px">
						<span class="time">πριν 9 λεπτά</span>
					</div>
					<div class="who">
						<a href="user/smilemagic">
							<img src="http://static.zino.gr/phoenix/mockups/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
							SmilEMagiC
						</a> είπε:
					</div>
					<div class="text">
						για πλάκα τα λέω ρε!!
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment">
					<div class="toolbox">
						<span class="time">πριν 12 λεπτά</span>
					</div>
					<div class="who">
						<a href="user/titi">
							<img src="http://static.zino.gr/phoenix/mockups/titi.jpg" class="avatar" alt="Titi" />
							Titi
						</a> είπε:
					</div>
					<div class="text">
						αδερφούλη το πάρτυ θα είναι γαμάτο, έχω ήδη μαγειρέψει αίμα!!!
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment" style="margin-left: 20px">
					<div class="toolbox" style="margin-right: 20px">
						<span class="time">πριν 12 λεπτά</span>
						<a href="" onclick="return false"><img src="http://static.zino.gr/phoenix/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a>
					</div>
					<div class="who">
						<a href="user/dionyziz">
							<img src="http://static.zino.gr/phoenix/mockups/dionyziz.jpg" class="avatar" alt="Dionyziz" />
							dionyziz
						</a> είπε:
					</div>
					<div class="text">
						Τέλεια! Πήρες black light?
					</div>
					<div class="bottom">
						<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
					</div>
				</div>
				<div class="comment oldcomment">
					<div class="toolbox">
						<a href="" onclick="return false" class="rss">
							<img src="images/feed.png" alt="rss" title="RSS Feed" class="rss" />
						</a>
					</div>
					<div class="who">
						<a href="user/dionyziz">
							412 παλιότερα σχόλια
						</a>
					</div>
					<div class="text">
					</div>
					<div class="bottom">
					</div>
				</div>
			</div>
		</div><?php
	}
?>