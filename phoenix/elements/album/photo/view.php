<?php
	function ElementAlbumPhotoView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/view.css' );
		
		Element( 'user/sections', 'album' );
		?><div id="photoview">
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
		            <a href="" class="nav"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" onmouseover="Hover( this );"  onmouseout="Unhover( this );" /></a>
		        </div>
		        <div class="right arrow">
		            <a href="" class="nav"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" onmouseover="Hover( this );" onmouseout="Unhover( this );" /></a>
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
			<div class="comments"><?php
				Element( 'comment/list' );
			?></div>
			<div class="eof"></div>
		</div><?php
	}
?>