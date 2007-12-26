<?php
	function ElementJournalSmall() {
		global $page;
		
		$page->AttachStyleSheet( 'css/journal/small.css' );
		
		?><h3><a href="">The MacGyver sandwich</a></h3>
		<p>
		Εξεταστική και το κάψιμο βαράει κόκκινο. Κάτι έπρεπε λοιπόν να κάνω για να σπάσω τη μονοτονία 
		της καθημερινότητας και του διαβάσματος. Σήμερα είπα να φτιάξω βραδυνό με έναν προτότυπο τρόπο. 
		Έφτιαξα...
		</p>
		<ul>
			<li class="readwhole"><a href="">Προβολή ολόκληρου&raquo;</a></li>
			<li>
				<dl>
					<dt class="addfav"><a href=""><img src="http://static.zino.gr/phoenix/heart_add.png" alt="Προσθήκη στα αγαπημένα" title="Προσθήκη στα αγαπημένα" /></a></dt>
				</dl>
			</li>
			<li>
				<dl>
					<dt class="commentsnum"><a href="">54 σχόλια</a></dt>
				</dl>
			</li>
		</ul>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div><?php
	}
?>