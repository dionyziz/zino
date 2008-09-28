<?php
	function ElementAdminMemcache( tString $key ) {
		global $mc;
		global $user;
		
		if ( !$user->IsSysOp() ) {
			return;
		}
		
		$key = $key->Get();
		if ( !empty( $key ) ) {
			?><strong><?php
			echo $key;
			?></strong>:<br /><br /><?php
			
			$result = $mc->get( $key );
			if ( empty( $result ) ) {
				?>[ not found ]<?php
			}
			else {
				ob_start();
				print_r( $result );
				echo nl2br( htmlspecialchars( ob_get_clean() ) );
			}
		}
		
		?><hr />
		Check a memcache key:<br />
		<form action="">
			<input type="hidden" name="p" value="memcache" />
			Key: <input type="text" name="key" value="" />
			<input type="submit" value="check" />
		</form>
		<?php
	}
?>
