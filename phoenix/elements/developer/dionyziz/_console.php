<?php
	class ElementDeveloperDionyzizConsole extends Element {
		public function Render() {
			if ( $rabbit_settings[ 'production' ] !== false ) {
				return;
			}
			
			?>
			Interactive shell<br /><br />
			<div>
				rabbit &gt; <input />
			</div>
			<?php
			
			return array( 'tiny' => true );
		}
	}
?>
