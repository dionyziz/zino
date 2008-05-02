<?php

	function InterestTag_Valid( $text ) {
		return !( strlen( trim( $text ) ) == 0 || strpos( $text, ',' ) !== false || strpos( $text, ' ' ) !== false );
	}
    
    function InterestTag_List( $usern, $offset = 0 , $length = 20 ) {
        global $db;
        global $interesttags;

        w_assert( $usern instanceof User || is_string( $usern ), 'InterestTag_List() accepts either a user instance or a string parameter' );
        
		$ret = array();
        if ( $usern instanceof User ) {
            
			// Prepared query
			$query = $db->Prepare("
				SELECT
                    *
                FROM
                    `$interesttags`
                WHERE
                    `interesttag_userid` = :UserId
                ;
			");
			
			// Assign query values
			$query->Bind( 'UserId', $usern->Id() );

            /*
            The following code was comment out because:
            	1)Ordering is not implemented in 6.5 phase
            	2)Needs fixing, since it selects only a subset of a user's InterestTags.
            
            
            $tags = array();
			$prevs = array();
			$first = false;
			while ( $row = $res->FetchArray() ) {
				$tag = new InterestTag( $row );

				$tags[ $tag->Id ] = $tag;
				$prevs[ $tag->NextId ] = $tag->Id;
				if ( !isset( $prevs[ $tag->Id ] ) ) {
					$first = $tag->Id;
				}
			}

			if ( !isset( $tags[ $first ] ) ) {
				return $ret;
			}
			$cur = $tags[ $first ];
			while ( true ) {
				$ret[] = $cur;

				if ( !isset( $tags[ $cur->NextId ] ) ) {
					break;
				}

				$cur = $tags[ $cur->NextId ];
			} */
        }
        else if ( is_string( $usern ) ) {
        	global $users;
			global $images;
			global $relations;
			global $friendrel;
			global $user;
			
            $tagtext = $usern;
			if ( $offset != 0 ) {
				$offset = $offset * $length - $length;
			}
			
			// Prepared query
			$query = $db->Prepare("
				SELECT
				SQL_CALC_FOUND_ROWS `interesttag_id`, `interesttag_text`, `user_id`,`user_name`,`frel_type`,`image_id`
				FROM `$interesttags`
					RIGHT JOIN `$users` ON `user_id` = `interesttag_userid`
					LEFT JOIN `$images` ON `user_icon` = `image_id`
					LEFT JOIN `$relations` ON `relation_friendid` = `user_id` 
											AND `relation_userid` = :RelationUserId
					LEFT JOIN `$friendrel` ON `frel_id` = `relation_type`
				WHERE `interesttag_text` = :InterestTagText
				LIMIT :Offset , :Length
				;
			");
			
			// Assign query values
			$query->Bind( 'RelationUserId'  , $user->Id() );
			$query->Bind( 'InterestTagText' , $tagtext );
			$query->Bind( 'Offset' , $offset );
			$query->Bind( 'Lenght' , $length );
        }
		// Execute query
        $res = $query->Execute();
		while ( $row = $res->FetchArray() ) {
				$ret[] = new InterestTag( $row );
			}
		return $ret;
    }

    function InterestTag_Count() {
        global $db;
		
		// Prepared query
        $query = $db->Prepare("SELECT FOUND_ROWS() AS fr");
		$fetched = $db->Execute()->FetchArray();

        return $fetched[ 'fr' ];
    }

    function InterestTag_Clear( $user ) {
        global $db;
        global $interesttags;
		
		// Prepared query
		$query = $db->Prepare("
			DELETE 
            FROM 
                `$interesttags` 
            WHERE 
                `interesttag_userid` = :InterestTagUserId
            ;
		");
		
		// Assign query values
		$query->Bind( 'InterestTagUserId', $user->Id() );

        return $query->Execute();
    }
    
    /* // TODO: Convert to new Satori
    class InterestTag extends Satori {
        protected   $mId;
        protected   $mUserId;
        protected   $mText;
        protected   $mNextId;
        private     $mUser;
        private     $mNext;

        public function GetUser() {
            if ( $this->mUser === false ) {
                $this->mUser = new User( $this->mUserId );
            }
            return $this->mUser;
        }
        public function SetUser( $value ) {
            $this->mUser = $value;
            $this->mUserId = $this->mUser->Id();
        }
        public function GetNext() {
            if ( $this->mNext === false ) {
                $this->mNext = new InterestTag( $this->NextId != "-1" ? $this->NextId : false );
            }
            return $this->mNext;
        }
        public function GetPrevious() {
			// Prepared query
			$query = $this->mDb->Prepare("
				SELECT
                    *
                FROM
                    `" . $this->mDbTable . "`
                WHERE
                    `interesttag_next` = :InterestTagNext
                LIMIT :Limit
				;
			");
			
			// Assign query values
			$query->Bind( 'InterestTagNext', $this->Id );
			$query->Bind( 'Limit', 1 );
			
            return new InterestTag( $query->Execute()->FetchArray() );
        }
        public function MoveAfter( $target ) {
            $prev = $this->GetPrevious();
            if ( $prev->Exists() ) {
                $prev->NextId = $this->NextId;
                $prev->Save();
            }
            
            $this->NextId = $target->NextId;
            $this->Save();

            $target->NextId = $this->Id;
            $target->Save();
        }
        public function MoveBefore( $target ) {
            $prev = $this->GetPrevious();
            if ( $prev->Exists() ) {
                $prev->NextId = $this->NextId;
                w_assert( $prev->NextId == $this->NextId );
                $prev->Save();
            }

            if ( $target->Previous->Exists() ) {
                $target->Previous->NextId = $this->Id;
                $target->Previous->Save();
            }

            $this->NextId = $target->Id;
            $this->Save();

        }
        public function Save() {
            $existed    = $this->Exists();
            $change     = Satori::Save();

            if ( !$existed && $change->Impact() ) {
				// Prepared query
				
				$query = $this->mDb->Prepare("
					UPDATE
                        `" . $this->mDbTable . "`
                    SET
                        `interesttag_next` = :InterestTagNext
                    WHERE
                        `interesttag_userid` = :InterestTagUserId
                        `interesttag_next`  =  :InterestTag AND
                        `interesttag_id` !=  :InterestTagId
                    LIMIT
                        :Limit
                    ;
				");
				
				// Assign query values
				$query->Bind( 'InterestTagNext'  , $this->Id );
				$query->Bind( 'InterestTagUserId', $this->UserId );
				$query->Bind( 'InterestTag'		 , '-1' );
				$query->Bind( 'InterestTagId'    , $this->Id );
				$query->Bind( 'Limit', 1 );
				
				// Execute query
                $change = $query->Execute();
            }

            return $change;
        }
        public function LoadDefaults() {
            $this->NextId = -1;
        }
        public function InterestTag( $construct = false, $user = false ) { // construct = text
            global $db;
            global $interesttags;

            $this->mDb      = $db;
            $this->mDbTable = $interesttags;

            $this->SetFields( array(
                'interesttag_id'        => 'Id',
                'interesttag_userid'    => 'UserId',
                'interesttag_text'      => 'Text',
                'interesttag_next'      => 'NextId'
            ) );

            if ( is_string( $construct ) && $user instanceof User ) {
                // Prepared query
				$query = $db->Prepare("
					SELECT
                        *
                    FROM
                        `" . $this->mDbTable . "`
                    WHERE
                        `interesttag_text`   = :InterestTagText AND
                        `interesttag_userid` = :InterestTagUserId
                    LIMIT 
						:Limit
					;
				");
				
				// Assign query values
				$query->Bind( 'InterestTagText', $construct );
				$query->Bind( 'InterestTagUserId', $user->Id() );
				$query->Bind( 'Limit', 1 );
				
                $construct = $query->Execute()->FetchArray();
            }

            $this->Satori( $construct );

            $this->mNext        = false;
//          $this->mPrevious    = false;
            
            if ( $user !== false ) {
                $this->mUser        = $user;
            }
            else if ( isset( $construct[ 'user_id' ] ) ) {
                $this->mUser        = new User( $construct );
            }
        }
    }
    */

?>
