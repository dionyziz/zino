	<div class="comment" style="margin-left:<?php
		echo 10 * $indent;
		?>px">
		<div class="upperline">
			<div class="leftcorner">&nbsp;</div>
			<div class="title"><a href="" class="<?php
			echo $type;
			?>"><b><?php
			echo $nick;
			?></b></a>, <?php
			echo $time;
			?>&nbsp;&nbsp;<span style="opacity: 0.7">145.52.12.54</span></div>
			<div class="fade">&nbsp;</div>
			<div class="rightcorner">&nbsp;</div>
			<div class="filler">&nbsp;</div>
		</div>
		<div class="avatar">
			<img src="images/<?php
			echo strtolower( $nick );
			?>.jpg" alt="<?php
			echo $nick;
			?>" title="<?php
			echo $nick;
			?>" />
		</div>
		<div class="text">
			<div>
				<?php
					echo $text;
				?><br /><br /><br />
                <div class="sig">
					If you think you can, you can. And if you think you can't, you 're right.<br />
                </div>
            </div>
		</div>
		<div class="lowerline">
			<div class="leftcorner">&nbsp;</div>
			<div class="rightcorner">&nbsp;</div>
			<div class="middle">&nbsp;</div>
			<div class="toolbar">
				<ul>
					<li><a href="">Απάντηση</a></li>
					<li><a href="">Επεξεργασία</a></li>
					<li><a href="">Διαγραφή</a></li>
				</ul>
			</div>
		</div>
	</div>
