<?php

	function ElementFaqSearchBox() {
		?><div class="searchbox">
			<div class="upperline">
				<div class="leftupcorner"></div>
				<div class="rightupcorner"></div>
				<div class="middle"></div>
			</div>
			<div class="registeropts" style="padding-top: 0px; margin-top: 0px; margin-left: 5px;">
				<form action="index.php" method="get">
					<input type="hidden" name="p" value="faqs" />
					<input type="text" size="70" name="q" />
					<input type="submit" value="Αναζήτηση" style="font-weight: bold; display:inline;" />
				</form>
			</div>
			<div class="downline">
				<div class="leftdowncorner"></div>
				<div class="rightdowncorner"></div>
				<div class="middledowncss"></div>
			</div>
		</div><?php
	}
	
?>
