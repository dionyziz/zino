<?php
	function CheckMemoryUsage() {
		global $user;
		global $settings;

		$memorySize = memory_get_usage( TRUE );
		if( $memorySize > 3*1024*1024 ) {
			$mem = new MemoryUsage();
			$mem->Size = $memorySize;
			if( $user->Exists() ) {
				$mem->Userid = $user->Id;
			}
			else {
				$mem->Userid = -1;
			}
			$mem->Url = $HTTP_SERVER_VARS[ 'REQUEST_URI' ];
			$mem->Save();			
		}
		return;
	}

	class MemoryUsage extends Satori {
		protected $mDbTableAlias = 'memoryusage';

		protected function LoadDefaults() {
		    $this->Created = NowDate();
		}	
    }
?>
