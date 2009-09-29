<?php
        /*Developer : Pagio 
          Parts used from code of : Abresas */

	function CheckMemoryUsage() {
		global $user;
		global $settings;
        global $water;
        
		$memorySize = memory_get_peak_usage( true );
        $water->Trace( 'Memory peak size: ' . number_format( $memorySize ) . ' bytes' );
        $water->Trace( 'Memory peak size: ' . number_format( memory_get_usage( true ) ) . ' bytes' );
		if( $memorySize > 25*1024*1024 && MemoryUsage_LogVisitorOrNot() ) {
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

        function MemoryUsage_LogVisitorOrNot() {
                return rand( 0, 99 ) == 0;      
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
