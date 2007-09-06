<?php
    // TODO: Satorify
    
	// fetches all children (direct or not) of a given category (but not the category itself)
	// returns an array of category class instances
	function Subcategories() {
		global $db;
		global $categories;
		global $images;
			
		$sql = "SELECT
					`category_id`, `category_creatorid`, `category_name`, `category_description`,
					`category_created`, `category_parentid`, `category_delid`, `category_icon`,
					`image_id`, `image_userid`, `image_created`,
					`image_userip`, `image_name`
				FROM
					`$categories` LEFT JOIN `$images`
						ON `image_id` = `category_icon`
				WHERE
					`category_delid`='0'
				;";
				
		$res = $db->Query( $sql );
		
		$parented = array();
		while ( $row = $res->FetchArray() ) {
			$category = New Category( $row );
			$parented[ $category->ParentId() ][] = $category;
		}
		
		return $parented;
	}
	
	function MakeCategory( $name , $description , $parentCategoryId = 0 , $icon = 0 ) {
		global $categories;
		global $user;
		global $db;
        
		if ( $user->CanModifyCategories() ) {
			/*if ( $id == $parentCategoryId ) { <--Removed it for category creation in General Categories
				return 6;
			}*/
			$name = addslashes( $name );
			$sql = "SELECT `category_id` FROM `$categories` WHERE `category_name`='$name' LIMIT 1;";
            $res = $db->Query( $sql );
			if ( $res->Results() ) {
				// category exists
				return 3;
			}
			$parentCategoryId = addslashes( $parentCategoryId );
			if ( $parentCategoryId > 0 ) {
				$sql = "SELECT `category_id` FROM `$categories` WHERE `category_id`='$parentCategoryId' LIMIT 1;";
                $res = $db->Query( $sql );
				if ( $res->NumRows() == 0 ) {
					// invalid parent category
					return 4;
				}
			}
			$description = addslashes( $description );
			
			$nowdate = NowDate();
			$cui = $user->Id();
			$sql = "INSERT INTO `$categories` ( `category_id` , `category_creatorid` , `category_name` , `category_description` , `category_created` , `category_parentid` , `category_icon` ) 
										VALUES( ''   , '$cui'          , '$name', '$description', '$nowdate', '$parentCategoryId' , '$icon' );";
			// echo $sql;
			$db->Query( $sql );
			return 1;
		}
		else {
			// no priviledges
			return 2;
		}
	}

	function UpdateCategory( $id , $name , $description , $parentCategoryId = 0 , $icon = 0 ) {
		global $categories;
		global $user;
		global $db;
        
		if ( $user->CanModifyCategories() ) {
			if ( $id == $parentCategoryId ) {
				return 6;
			}
			$sql = "SELECT `category_id` FROM `$categories` WHERE `category_id`='$id' LIMIT 1;";
            $res = $db->Query( $sql );
			if ( !$res->Results() ) {
				// category doesn't exist
				return 5;
			}
			$name = addslashes( $name );
			$sql = "SELECT `category_id` FROM `$categories` WHERE `category_name`='$name' AND `category_id`!='$id' LIMIT 1;";
            $res = $db->Query( $sql );
			if ( $res->Results() ) {
				// category name exists
				return 3;
			}
			$parentCategoryId = addslashes( $parentCategoryId );
			if ( $parentCategoryId > 0 ) {
				$sql = "SELECT `category_id` FROM `$categories` WHERE `category_id`='$id' LIMIT 1;";
                $res = $db->Query( $sql );
				if ( !$res->Results() ) {
					// invalid parent category
					return 4;
				}
			}
			
			/* 
				Transfering the category to a child category of itself should not be allowed.
				It is also not allowed to transfer a catecory to a child's child and so on category
				Checking is done beneath
			*/
			$query = "SELECT `category_id` FROM `$categories` WHERE `category_parentid` = '$id';";
			$res = $db->Query( $sql );
			$num = $res->NumRows();
			while ( $num != 0 ) {
				$ids = array();
				while ( $row = $res->FetchArray() ) {
					$ids[] = $row[ 'category_id' ];
				}
				$sql = "SELECT `category_id` FROM `$categories` WHERE `category_parentid` IN ( " . implode( ',' , $ids ) . " );";
				$res = $db->Query( $sql );
				$num = $res->NumRows();
			}
			if ( in_array( $parentCategoryId , $ids ) ) {
				return 7;
			}
			$description = addslashes( $description );
			
			$nowdate = NowDate();
			$cui = $user->Id();
			$sql = "UPDATE 
						`$categories` 
					
					SET 
						`category_name` = '$name',
						`category_description` = '$description',
						`category_parentid` = '$parentCategoryId',
						`category_icon` = '$icon'
						
					WHERE 
						`category_id`='$id';";
			$db->Query( $sql );
			return 1;
		}
		else {
			// no priviledges
			return 2;
		}
	}
	
	function KillCategory( $id ) {
		global $user;
		global $categories;
		global $db;
        
		if ( $user->CanModifyCategories() ) {
			$sql = "SELECT `category_id` FROM `$categories` WHERE `category_id`='$id' LIMIT 1;";
			$sqlresult = $db->Query( $sql );
			if ( !$sqlresult->Results() ) {
				return 3;
			}
			$sql = "UPDATE `$categories` SET `category_delid`='-1' WHERE `category_id`='$id' LIMIT 1;";
			$db->Query( $sql );
			return 1;
		}
		else {
			return 2;
		}
	}
	
	function CountCategories( $parentid ) {
		global $categories;
		global $db;
		
		$sql = "SELECT 
                    COUNT(*) AS numcats 
                FROM 
                    `$categories` 
                WHERE 
                    `category_parentid`='$parentid';";
		
		$res = $db->Query( $sql );
		$row = $res->FetchArray();
        
		return $row[ 'numcats' ];
	}
	
	final class Category {
		private $mId;
		private $mCreatorUserId;
		private $mName;
		private $mDescription;
		private $mDateCreated;
		private $mParentCategoryId;
		private $mCreatedYear, $mCreatedMonth, $mCreatedDay;
		private $mCreatedHour, $mCreatedMinute, $mCreatedSecond;
		private $mDelid;
		private $mIconId;
		private $mIcon;
	 	
		public function Id() {
			return $this->mId;
		}
		public function Name() {
			return $this->mName;
		}
		public function Description() {
			return $this->mDescription;
		}
		public function ParentId() { 
			return $this->mParentCategoryId;
		}
		public function Icon() {
			return $this->mIcon;
		}
		public function Children() {
			global $db;
			global $categories;
			global $images;
				
			$sql = "SELECT
						`category_id`, `category_creatorid`, `category_name`, `category_description`,
						`category_created`, `category_parentid`, `category_delid`, `category_icon`,
						`image_id`, `image_userid`, `image_created`,
						`image_userip`, `image_name`
					FROM
						`$categories` LEFT JOIN `$images`
							ON `image_id` = `category_icon`
					WHERE
						`category_parentid` ='".$this->Id()."' AND `category_delid`='0'
					;";
					
			$res = $db->Query( $sql );
			
			$allchildren = array();
			while ( $row = $res->FetchArray() ) {
				$thicat = New Category( $row );
				$allchildren[] = $thicat;
			}
			return $allchildren;
		}
		public function CountChildren() {
			global $db;
			global $categories;
			
			$sql = "SELECT 
						COUNT(*) as category_childrenum
					FROM `$categories`
					WHERE `category_parentid` = '" . $this->Id() . "' AND `category_delid`='0'
					;";
			$res = $db->Query( $sql );
			$row = $res->FetchArray();
			return $row[ 'category_childrenum' ];
		}
		public function CountArticles() {	
			global $db;
			global $categories;
			global $articles;
			global $revisions;
			
			$sql = "SELECT
						`revision_categoryid`, COUNT(*) AS numarticles
					FROM
						(`$categories` INNER JOIN `$articles`
					ON `category_id` = `revision_categoryid`) 
					INNER JOIN `$revisions` ON
						(
						`article_id` = `revision_articleid` 
						AND `article_headrevision` = `revision_id`
						)
					WHERE
				         `article_delid` = 0 AND
				         `category_parentid` = '" . $this->Id() . "'
				     GROUP BY
						`revision_categoryid`;";
			$res = $db->Query( $sql );
			$numarticles = array();
			$subcategories = $this->Children();
			while ( $row = $res->FetchArray() ) {
				$numarticles[ $row[ 'revision_categoryid' ] ] = $row[ 'numarticles' ];
			}
			foreach ( $subcategories as $category ) {
				if ( !isset( $numarticles[ $category->Id() ] ) ) {
					$numarticles[ $category->Id() ] = 0;
				}
			}
			return $numarticles[ $this->Id() ];
		}
		private function Construct( $id ) {
			global $db;
			global $categories;
			global $images;
            
			$sql = "SELECT 
                        `category_id`, `category_creatorid`, `category_name`,
                        `category_description`, `category_created`, `category_parentid`,
                        `category_icon`, `category_delid`
                        `image_id`, `image_userid`, `image_created`
                    FROM 
                        `$categories` LEFT JOIN `$images`
                            ON `category_icon` = `image_id`
                    WHERE 
                        `category_id` = '$id' 
                    LIMIT 1;";
			$res = $db->Query( $sql );
			
			return $res->FetchArray();
		}
		public function Category( $construct ) {
			global $water;
			
			if ( !is_array( $construct ) ) {
				$fetched_array = $this->Construct( ( integer )$construct );
				if( !is_array( $fetched_array ) ) {
					return false;
				}
			}
			else {
				$fetched_array = $construct;
			}

			$this->mDelid				= $fetched_array[ "category_delid" ];

			if ( $this->mDelid != 0 ) {
				$water->Warning( "Attempt to access deleted category!" );
				return;
			}
			
            w_assert( isset( $fetched_array[ "category_id" ] ) );
            w_assert( isset( $fetched_array[ "category_name" ] ) );

			$this->mId 					= $fetched_array[ "category_id" ];
			$this->mName 				= $fetched_array[ "category_name" ];
			$this->mCreatorUserId		= isset( $fetched_array[ "category_creatorid" ]      ) ? $fetched_array[ "category_creatorid" ]      : "";
			$this->mDescription			= isset( $fetched_array[ "category_description" ]    ) ? $fetched_array[ "category_description" ]    : "";
			$this->mDateCreated			= isset( $fetched_array[ "category_created" ]        ) ? $fetched_array[ "category_created" ]        : '0000-00-00 00:00:00';
			$this->mParentCategoryId	= isset( $fetched_array[ "category_parentid" ]       ) ? $fetched_array[ "category_parentid" ]       : 0;
			$this->mIconId   			= isset( $fetched_array[ "category_icon" ]           ) ? $fetched_array[ "category_icon" ]           : 0;
			
			if ( isset( $fetched_array[ 'image_id' ] ) ) {
				$this->mIcon = New Image( $fetched_array );
			}
			
			ParseDate( $this->mDateCreated , 
						$this->mCreateYear , $this->mCreateMonth , $this->mCreateDay ,
						$this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond );
		}
	}
?>
