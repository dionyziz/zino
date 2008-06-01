<?php
	
	function ElementFrontpageCommentView( $comment ) {
		?><div class="event">
			<div class="toolbox">
				<span class="time">πριν λίγο</span>
			</div>
			<div class="who">
				<a href="http://morvena.zino.gr">
					<img src="images/avatars/morvena.jpg" class="avatar" alt="morvena" />
					Morvena
				</a> έγραψε:
			</div>
			<div class="subject">
				<p>
					<span class="text">"eleos mori skatoulitsa"</span>
					, στο ημερολόγιο
					<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
				</p>
				<a href="#" class="viewcom">Προβολή σχολίου&raquo;</a>
			</div>
		</div><?php
	}
?>
