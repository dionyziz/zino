<?php
    /*
    $user = UserFinder::GetByName( 'dionyziz' );
    
    class UserFinder extends Finder {
        protected static $mModel = 'User';
        
        public function FindByName( $username ) {
            $prototype = New User();
            $prototype->Name = $username;
            return $this->FindByPrototype( $prototype );
        }
        public function FindRecent( $limit ) {
            w_assert( is_int( $limit ) );
            
            $query = self::mDb->Query(
                'SELECT
                    *
                FROM
                    :users
                ORDER BY
                    ``
                LIMIT :limit'
            );
            $query->BindTable( 'users' );
            $query->Bind( 'limit', $limit );
            
            return $this->FindBySQLResult( $query );
        }
    }
    */
?>
