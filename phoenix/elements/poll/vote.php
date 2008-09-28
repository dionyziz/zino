<?php
   class ElementPollVote extends Element {
	   protected $mPersistent = array();

	   public function Render() {
			global $rabbit_settings;

			?><div class="voting"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>ajax-loader.gif" alt="Παρακαλώ περιμένετε..." title="Παρακαλώ περιμένετε..." /> Παρακαλώ περιμένετε...
			</div><?php
		}
	}
?>
