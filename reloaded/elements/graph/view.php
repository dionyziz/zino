<?php
	function ElementGraphView() {
		global $page;
		global $xc_settings;
        
		$page->SetTitle( 'Graphs' );
		$page->AttachScript( 'js/graphs.js' );
		
		?><div style="margin-top: 30px; text-align:center;">
			<div style="margin-left: 70px; width: 780px;">
				<div style="float: left; text-align: left;">
					<button type="button" onclick="NewCGraph.adjust( 'newcom' )">Adjust</button><br />
				</div>
				<div id="adjust_newcom" style="display: none; position: absolute; top: 25px; left: 100px; background-color: #f4f4d4; text-align: left; padding: 20px; border: 1px solid black;">
					<b>Graph:</b> Comments<br />
					<b>Size:</b>
					<img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/less.png" onclick="NewCGraph.resize( 'smaller' );" style="cursor: pointer;" />
					<span id="newcom_sz">small</span>
					<img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/more.png" onclick="NewCGraph.resize( 'bigger' );" style="cursor: pointer;" /><br />
					<b>Smoothing:</b>
					<img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/less.png" />
					<span id="newcom_sm">3</span>
					<img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/more.png" /><br />
				</div>
				
				<div style="text-align: right;">
					<button type="button" onclick="NewMGraph.adjust()">Adjust</button><br />
				</div>
				<div id="adjust_newmem" style="display: none; position: absolute; top: 25px; right: 100px; background-color: #f4f4d4; text-align: left; padding: 20px; border: 1px solid black;">
					<b>Graph:</b> New Members<br />
					<b>Size:</b> <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/less.png" /> <span id="newmem_sz">small</span> <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/more.png" /><br />
					<b>Smoothing:</b> <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/less.png" /> <span id="newmem_sm">0</span> <img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/more.png" /><br />
				</div>
			</div>
			
			<img src="etc/mockups/newcomments.png" style="margin-right: 3px;" id="graph_newcom" />
			<img src="etc/mockups/newmembers.png" id="graph_newmem" />
			<br /><br />
			
			<div style="margin-left: 80px; text-align: left;">
				<button type="button" onclick="PagGraph.adjust()">Adjust</button><br />
			</div>
			<div id="adjust_pageviews" style="display: none; position: absolute; left: 100px; background-color: #f4f4d4; text-align: left; padding: 20px; border: 1px solid black; margin-top: 4px; margin-left: 10px;">
				<b>Graph:</b> Pageviews<br />
				<b>Size:</b> <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/less.png" /> <span id="pageviews_sz">big</span> <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/more.png" /><br />
				<b>Smoothing:</b> <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/less.png" /> <span id="pageviews_sm">0</span> <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/more.png" /><br />
			</div>
			<img src="etc/mockups/pageviews.png" id="graph_pageviews" />
		</div><?php
        
        return array( 'tiny' => true );
	}

?>
