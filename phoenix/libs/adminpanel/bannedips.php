<?php
    /*
        Developer:Pagio
    */
    
    class BannedIpFinder extends Finder {
        protected $mModel = 'BannedIp';
        
        public function FindActiveByIp( $ip ) {
            global $db;
            global $libs;
            
            $libs->Load( 'adminpanel/bannedips' );
            
            $sql = $db->Prepare( 
                'SELECT *
                FROM :bannedips
                WHERE `bannedips_expire` > NOW( )
                AND `bannedips_ip` = :ip
                ;'
            );
            $sql->BindTable( 'bannedips' );
            $sql->Bind( 'ip', $ip );
            $res = $sql->Execute();
            
            $ips = array();
            while ( $row = $res->FetchArray() ) {
                $ips[] = new BannedIp( $row );
            }
            
            return $ips;
        }
        
        public function FindByIp( $ip ) {
            $prototype = new BannedIp();
            $prototype->Ip = $ip;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        }
        
        public function FindByUserId( $userid ) {
            $prototype = new BannedIp();
            $prototype->Userid = $userid;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        }
    }
    
    class BannedIp extends Satori {
        protected $mDbTableAlias = 'bannedips';

    }
?>
