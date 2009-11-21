<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			if( $theuser->Profile->Songwidgetid != -1 ){	
				?><div class="player">
					<object>
						<param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param> 
						<param name="wmode" value="window"></param> 
						<param name="allowScriptAccess" value="always"></param> 
						<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;widgetID=<?php
							echo $theuser->Profile->Songwidgetid;
						?>&amp;style=metal&amp;p=0"></param> 
						<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" width="300" height="40" flashvars="hostname=cowbell.grooveshark.com&amp;widgetID=<?php
							echo $theuser->Profile->Songwidgetid;
						?>&amp;style=metal&amp;p=0" allowScriptAccess="always" wmode="window">
						</embed>
					</object>
					<div class="toolbox">
						<span class="s1_0024 search">&nbsp;</span>
						<span class="s1_0007 delete">&nbsp;</span>
					</div>
				</div><?php
			}
			else{
				?><div class="addsong">
					<a href="">Πρόσθεσε κάποιο τραγούδι στο προφίλ σου.</a>
				</div>
				<?php
			}
			?>
			<div id="mplayersearchmodal">
				<h3 class="modaltitle">Αναζήτηση τραγουδιών...</h3>
				<form>
					<div class="input">
						<input type="text" />
						<input type="submit" style="display: none" />
					</div>
					<table>
						<th>
							<td>Name</td>
							<td>Artist</td>
							<td>Album</td>
						</th>
					</table>
				</form>
			</div><?php
		}
    }
?>
