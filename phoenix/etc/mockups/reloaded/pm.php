	<div class="comment" style="margin-left:10px">
		<div class="upperline">
			<div class="leftcorner">&nbsp;</div>
			<div class="title"><?php
			if( !$read ) {
				?><b><u>Νέο Μύνημα:</u></b> <?php
			}
			if( $usersender ) {
				?>προς <?php
			}
			else {
				?>από <?php
			}
			?><a href="" class="<?php
			echo $type;
			?>"><b><?php
			echo $nick;
			?></b></a>, <?php
			echo $time;
			?></div>
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
				?>
			<br /><br /></div>
		</div>
		<div class="lowerline">
			<div class="leftcorner">&nbsp;</div>
			<div class="rightcorner">&nbsp;</div>
			<div class="middle">&nbsp;</div>
			<div class="toolbar">
				<ul>
					<li><a href="">Απάντηση</a></li>
					<li><a href="">Διαγραφή</a></li>
				</ul>
			</div>
		</div>
	</div>