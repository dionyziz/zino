<?php
   class UserSearch extends UserFinder {
       private $mFoundRows;

       public function FoundRows() {
           return $this->mFoundRows;
       }
       public function FindByDetails(
            $minage, $maxage, Place $location, $gender, $sexual, $name,
            $offset = 0, $limit = 25
       ) {
           $minage = ( int )$minage;
           $maxage = ( int )$maxage;
           $clauses = array( 1 );
           $age = "FLOOR( DATEDIFF( NOW(), `profile_dob` ) / 365.33 )";
           if ( $minage > 0 ) {
               $clauses[] = $age . ' >= :minage';
           }
           if ( $maxage > 0 ) {
               $clauses[] = $age . ' <= :maxage';
           }
           if ( $gender == 'm' || $gender == 'f' ) {
               $clauses[] = '`user_gender` = :gender';
           }
           if ( $location->Exists() ) {
               $clauses[] = '`profile_placeid` = :placeid';
           }
           if ( $sexual !== '' ) {
               $clauses[] = '`profile_sexualorientation` = :sexual';
           }
           if ( !empty( $name ) ) {
               $clauses[] = '( `user_name` LIKE "%'  . $name . '%" 
                             OR `user_email` LIKE "%' . $name . '%" )';
           }
           $where = implode( ' AND ', $clauses );
           $query = $this->mDb->Prepare(
                "SELECT
                    SQL_CALC_FOUND_ROWS
                    *
                FROM
                    :users CROSS JOIN :userprofiles
                        ON `user_id`=`profile_userid`
                WHERE
                    $where
                ORDER BY
                    `user_avatarid` = 0
                LIMIT
                    :offset, :limit;"
           );
           $query->BindTable( 'users', 'userprofiles' );

           $query->Bind( 'offset', $offset );
           $query->Bind( 'limit', $limit );

           $query->Bind( 'minage', $minage );
           $query->Bind( 'maxage', $maxage );
           $query->Bind( 'gender', $gender );
           $query->Bind( 'placeid', $location->Id );
           $query->Bind( 'sexual', $sexual );

           $query->BindLike( 'name', $name );

           $res = $query->Execute(); 

           $this->mFoundRows = ( int )array_shift( $this->mDb->Prepare( 'SELECT FOUND_ROWS();' )->Execute()->FetchArray() );

           return $this->FindBySQLResource( $res );
       }
   }
?>
