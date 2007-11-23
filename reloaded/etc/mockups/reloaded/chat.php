<?php
	$loggedin = true;
	$collapse = true;
	$allowtoggle = false;
	include 'banner.php';
?>
<div class="chat">
	<div class="userlist">
		<h3>Συνομιλούν Τώρα</h3>
		<div>
			<a href="" class="operator" onclick="return false">
				<img src="images/blink.jpg" class="avatar" />Blink
			</a>
			Συνδεδεμένος: 5 λεπτά<br />
			Ανενεργός
		</div>
		<div>
			<a href="" class="developer" onclick="return false">
				<img src="images/dionyziz.jpg" class="avatar" />Dionyziz
			</a>
			Συνδεδεμένος: 1 λεπτό<br />
		</div>
		<div>
			<a href="" class="developer" onclick="return false">
				<img src="images/finlandos.jpg" class="avatar" />Abresas
			</a>
			Συνδεδεμένος: 39 λεπτά<br />
		</div>
	</div>
	<div class="history" id="chathistory">
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Γεια!</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> Γεια.</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Καλά;</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> Μια χαρά...</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> Εσύ;</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Ε καλά κι εγώ...</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Μία από τα ίδια.</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> ...</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> ?</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Τι είναι αυτό μέσα στο οποίο μιλάμε;</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> Η συνομιλία του reloaded.</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Α...</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Μαλακία φαίνεται.</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/dionyziz.jpg" class="avatar" />Dionyziz</a> λέει:</span> Ναι ε; :/</div>
		<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Ε πώς το έχεις κάνει έτσι..</div>
	</div>
	<div>
		<input type="text" id="chatmessage" class="msg" value="" style="background-image:url('images/blink.jpg');" />
		<input type="submit" class="send" value="Αποστολή" />
	</div>
</div>
