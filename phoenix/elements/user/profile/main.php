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
				Element( 'poll/small' , true );
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
				<h3>Ημερολόγιο</h3><?php
				Element( 'journal/small' );
				?><dl>
					<dd><a href="">57 σχόλια</a></dd>
				</dl>
				<a href="" class="button">Περισσότερες καταχωρήσεις&raquo;</a>
			</div>
			<div class="comments">
				<h3>Σχόλια στο προφίλ του dionyziz</h3><?php
				Element( 'comment/list' );
			?></div>
		</div><?php
	}
?>