<?php 
	function ElementUserProfileSidebarInterests( $theuser ) {
		?><dl>
			<dt><strong>Hobbies</strong></dt>
			<dd>
				<a href="">Web Development</a>, 
				<a href="">PHP</a>, 
				<a href="">HTML</a>, 
				<a href="">Javascript</a>, 
				<a href="">Underage</a>, 
				<a href="">Πλέξιμο</a>, 
				<a href="">Μπαλέτο</a>, 
				<a href="">Maths</a>
			</dd>
			
			<dt><strong>Αγαπημένα τραγούδια</strong></dt>
			<dd>
				<a href="">Papercut</a>, 
				<a href="">Perfection is my direction</a>, 
				<a href="">Over my head</a>, 
				<a href="">Teenagers</a>, 
				<a href="">Black parade</a>
			</dd>
		
			<dt><strong>Αγαπημένες ταινίες</strong></dt>
			<dd>
				<a href="">Amelie</a>, 
				<a href="">Elephant</a>, 
				<a href="">Sommersturm</a>, 
				<a href="">Lord of the Rings</a>
			</dd>
			
			<dt><strong>Αγαπημένες σειρές</strong></dt>
			<dd>
				<a href="">Pixelperfect</a>, 
				<a href="">Friends</a>
			</dd>
			
			<dt><strong>Αγαπημένα βιβλία</strong></dt>
			<dd>
				<a href="">PHP cookbook</a>, 
				<a href="">MySQL cookbook</a>, 
				<a href="">Code complete</a>
			</dd>
			
			<dt><strong>Αγαπημένοι καλλιτέχνες</strong></dt>
			<dd>
				<a href="">Angelina Jolie</a>, 
				<a href="">Rowan Atkinson</a>, 
				<a href="">Harisson Ford</a>
			</dd>
			
			<dt><strong>Αγαπημένα videogames</strong></dt>
			<dd>
				<a href="">WoW</a>, 
				<a href="">Baldrus gate</a>, 
				<a href="">Empire earth</a>, 
				<a href="">Age of empires</a>
			</dd><?php
			if ( $theuser->Profile->Favquote != '' ) {
				?><dt><strong>Αγαπημένα ρητό</strong></dt>
				<dd><?php
				echo htmlspecialchars( $theuser->Profile->Favquote );
				?></dd><?php
			}
		?></dl><?php
	}
?>