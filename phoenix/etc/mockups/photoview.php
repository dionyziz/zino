<?php
	include "usersections.php";
?>
<script type="text/javascript">
function Hover( node ) {
	Animations.SetAttribute( node, 'opacity', 1 );
}
function Unhover( node ) {
	Animations.SetAttribute( node, 'opacity', 0.8 );
}

</script>
<div id="photoview">
	<h2>Στη Θεσαλλονίκη ξημερώματα</h2>
	<span>στο album</span> <a href="">Θεσαλλονίκη</a>
	<dl>
		<dd class="commentsnum">20 σχόλια</dd>
		<dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd>
	</dl>
	<div class="eof"></div>
	<div class="thephoto">
		<img src="images/photoview.jpg" alt="photoview" title="photoview" />
	</div>
	<div class="photothumbs">
        <div class="left arrow">
            <a href="" class="nav" style="vertical-align:middle"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" onmouseover="Hover( this );"  onmouseout="Unhover( this );" style="vertical-align:middle" /></a>
        </div>
        <div class="right arrow">
            <a href="" class="nav" style="vertical-align:middle"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" onmouseover="Hover( this );" onmouseout="Unhover( this );" style="vertical-align:middle" /></a>
        </div>
        <ol>
            <li class="left">
                <bdo dir="rtl"><!-- thumbs here should be listed in REVERSE order, i.e. the one that is "closest" to the photo that is being viewed goes first, the second closest should be second etc. -->
                    <span><a href=""><img src="images/photo6.jpg" alt="photo6" title="photo6" /></a></span>
                    <span><a href=""><img src="images/photo1.jpg" alt="photo1" title="photo1" /></a></span>
                    <span><a href=""><img src="images/photo2.jpg" alt="photo2" title="photo2" /></a></span>
                </bdo>
            </li>
            <li class="selected" style="width:150px">
                <a href=""><img src="images/photoview_small.jpg" alt="photoview_small" title="photoview_small" /></a>
            </li>
            <li class="right">
                <bdo dir="ltr"><!-- right order here, but same idea. the one tha tis "closest" to the photo that is being viewed goes first. -->
                    <span><a href=""><img src="images/photo3.jpg" alt="photo3" title="photo3" /></a></span>
                    <span><a href=""><img src="images/photo4.jpg" alt="photo4" title="photo4" /></a></span>
                    <span><a href=""><img src="images/photo7.jpg" alt="photo7" title="photo7" /></a></span>
                </bdo>
            </li>
        </ol>
	</div>
	<div class="comments">
		<div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					<img src="images/avatars/dionyziz.jpg" class="avatar" alt="Dionyziz" />
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
					<img src="images/avatars/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
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
					<img src="images/avatars/kostis90gr.jpg" class="avatar" />
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
					<img src="images/avatars/izual.jpg" class="avatar" alt="izual" />
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
					<img src="images/avatars/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
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
					<img src="images/avatars/titi.jpg" class="avatar" alt="Titi" />
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
				<a href="" onclick="return false"><img src="images/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					<img src="images/avatars/dionyziz.jpg" class="avatar" alt="Dionyziz" />
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
	<div class="eof"></div>
</div>
