<?php
	function CheckMemoryUsage() {
		global $user;
		global $settings;

		$memorySize = memory_get_usage( TRUE );
		if( $memorySize > 15*1024*1024 ) {
			$mem = new MemoryUsage();
			$mem->Size = $memorySize;
			if( $user->Exists() ) {
				$mem->Userid = $user->Id;
			}
			else {
				$mem->Userid = -1;
			}
			$mem->Url = $_SERVER[ 'REQUEST_URI' ];
			$mem->Save();			
		}
		return;
	}

        class MemoryUsageFinder extends Finder {
                protected $mModel = 'MemoryUsage';
                    
                public function FindAll( $offset, $limit ) {
                    $prototype = New MemoryUsage();
                    $found = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
                    return $found;
                }
        }
                    

	class MemoryUsage extends Satori {
		protected $mDbTableAlias = 'memoryusage';

		protected function LoadDefaults() {
		    $this->Created = NowDate();
		}	
        }
?>
