<?php
	$loggedin = true;
	$collapse = true;
	$allowtoggle = false;
	include 'banner.php';
?><br /><br /><br /><br />
<div class="body">
	<div class="upper">
		<span class="title">Μηνύματα</span>
		<div class="subheading">Εισερχόμενα</div>
	</div>
	<div class="leftbar">
		<div class="folders">
			<div class="activefolder" alt="Εισερχόμενα" title="Εισερχόμενα"><a href="" class="folderlinksactive">Εισερχόμενα (2)</a></div>
			<div class="folder" alt="Απεσταλμένα" title="Απεσταλμένα"><a href="" class="folderlinks">Απεσταλμένα</a></div>
			<div class="folder" alt="Φίλοι" title="Φίλοι"><a href="" class="folderlinks">Φίλοι</a></div>
			<div class="folder" alt="Crap" title="Crap"><a href="" class="folderlinks">Crap</a></div>
			<div class="newfolder" alt="Δημιούργησε έναν νέο φάκελο" title="Δημιούργησε έναν νέο φάκελο"><a href="" class="folderlinksnew">Νέος Φάκελος</a></div>
		</div><br />
		<a href="" class="folder_links"><img src="http://static.chit-chat.gr/images/email_open.png" alt="Νέο μήνυμα" title="Νέο μήνυμα" /> Νέο μήνυμα</a><br />
		<a href="" class="folder_links"><img src="http://static.chit-chat.gr/images/folder_delete.png" alt="Διαγραφή φακέλου" title="Διαγραφή φακέλου" /> Διαγραφή φακέλου</a>
	</div>
	<div class="rightbar" style="float:left;">
		<div class="messages">
			<div class="message" style="width:620px;">
				<div class="infobar">
					<img style="float:left;padding: 2px 7px 1px 2px;" src="http://static.chit-chat.gr/images/email_open_image.png" alt="Νέο μήνημα" title="Νέο μήνυμα" />
					<a href="" style="float:right;padding: 3px 2px 1px 5px;"><img src="http://static.chit-chat.gr/images/cross.png" alt="Διαγραφή μηνύματος" title="Διαγραφή μηνύματος" /></a>
					<div class="infobar_info" style="padding: 3px;height:21px;">από την <a href="" class="user"><b>SORAL</b></a>, πριν 5 λεπτά</div>
				</div>
			</div>
			
			<div class="message" style="width:620px;">
				<div class="infobar">
					<img style="float:left;padding: 2px 7px 1px 2px;" src="http://static.chit-chat.gr/images/email_open_image.png" alt="Νέο μήνημα" title="Νέο μήνυμα" />
					<a href="" style="float:right;padding: 3px 2px 1px 5px;"><img src="http://static.chit-chat.gr/images/cross.png" alt="Διαγραφή μηνύματος" title="Διαγραφή μηνύματος" /></a>
					<div class="infobar_info" style="padding: 3px;height:21px;">από την <a href="" class="operator"><b>Titi</b></a>, πριν ένα τέταρτο</div>
				</div>
			</div>
			
			<?php
				include 'msgnew.php';
			?>
			
			<div class="message" style="width:620px;">
				<div class="infobar">
					<a href="" style="float:right;padding: 3px 2px 1px 5px;"><img src="http://static.chit-chat.gr/images/cross.png" alt="Διαγραφή μηνύματος" title="Διαγραφή μηνύματος" /></a>
					<div class="infobar_info" style="padding: 3px;height:21px;cursor:pointer;">από τον <a href="" class="operator"><b>dionyziz</b></a>, πριν ένα μήνα</div>
				</div>
			</div>
			
			<div class="message" style="width:620px;">
				<div class="infobar">
					<a href="" style="float:right;padding: 3px 2px 1px 5px;"><img src="http://static.chit-chat.gr/images/cross.png" alt="Διαγραφή μηνύματος" title="Διαγραφή μηνύματος" /></a>
					<div class="infobar_info" style="padding: 3px;height:21px;cursor:pointer;">από τον <a href="" class="user"><b>Blink</b></a>, πριν 2 μήνες</div>
				</div>
			</div>
			
			<div class="message" style="width:620px;">
				<div class="infobar">
					<a href="" style="float:right;padding: 3px 2px 1px 5px;"><img src="http://static.chit-chat.gr/images/cross.png" alt="Διαγραφή μηνύματος" title="Διαγραφή μηνύματος" /></a>
					<div class="infobar_info" style="padding: 3px;height:21px;cursor:pointer;">από τον <a href="" class="user"><b>noel</b></a>, πριν 3 μήνες</div>
				</div>
			</div>
		</div>
	</div>

	<div style="clear:left;"></div>
</div>