<?php

    function AdminAction_GetByAdmin( $admin ) {
        global $db;
        global $adminactions;
        global $water;

        if ( $admin instanceof User ) {
            $adminid = $admin->Id();
        }
        else if ( (int)$admin === $admin ) {
            $adminid = $admin;
        }
        else {
            $water->Notice( 'AdminAction_GetByAdmin parameter must be instance of User class or an integer' );

            return false;
        }
		
		// Prepared query
		$db->Prepare("
			SELECT
				*
			FROM
				`$adminactions`
			WHERE
				`$adminaction_adminid` = :AdminId
			;
		");
		
		// Assign values to query
		$db->Bind( 'AdminId', $adminid );
		
		// Execute query
		$res = $db->Execute();
		
		$actions    = array();
        while ( $row = $res->FetchArray() ) {
            $actions[] = new AdminAction( $row );
        }

        return $actions;
    }

    class AdminAction extends Satori {
        protected $mId;
        protected $mAdminId;
        protected $mAdmin;
        protected $mDate;
        protected $mTypeId;
        protected $mItemId;

        public function GetAdmin() {
            return $this->mAdmin;
        }
        public function AdminAction( $construct ) {
            global $db;
            global $adminactions;

            $this->mDb      = $db;
            $this->mDbTable = $adminactions;

            if ( !is_array( $construct ) ) {
				
				// Prepared query
				$this->mDb->Prepare("
					SELECT
	                	*
	                FROM
	                	`$adminactions` RIGHT JOIN
	                    `$users` ON `adminaction_adminid` = `user_id`
	                WHERE
	                	`adminaction_id` = :Construct
	                LIMIT 1
					;
				");
				
				// Assign values to query
				$this->mDb->Bind('Construct', $construct );
             	
				// Execute query
                $construct = $this->mDb->Execute()->FetchArray();
            }

            $this->SetFields( array(
                'adminaction_id'        => 'Id',
                'adminaction_adminid'   => 'AdminId',
                'adminaction_date'      => 'Date',
                'adminaction_typeid'    => 'TypeId',
                'adminaction_itemid'    => 'ItemId'
            ) );

            $this->Satori( $construct );

            $this->mAdmin = new User( $construct );
        }
    }

?>
