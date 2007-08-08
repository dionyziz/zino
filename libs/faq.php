<?php

	/* **********************
		Excalibur FAQ System 
		
		Developer: abresas
	   ********************** */
	   
	global $libs;
	   
	$libs->Load( 'search' );
	
	function FAQ_UploadIcon( $icon ) {
		global $libs;
		
		$libs->Load( 'image/image' );
		$libs->Load( 'albums' );
		
		if ( empty( $icon[ 'name' ] ) ) {
	        return -1;
		}
	    
		$imagename = mystrtolower( basename( $icon['name'] ) );
		$extension = getextension( $imagename );
		
		if ( $extension != "jpg" && $extension != "jpeg" && $extension != "gif" && $extension != "png" ) {
			return -2;
		}
		
		if ( substr( $imagename , 0 , strlen( "usericon_" ) ) == "usericon_" ) {
			die( "libs/faq.php: FAQ_UploadIcon(): Prefix found \"usericon_\"!" );
		}
		
		$tempfile = $icon['tmp_name'];
		
		if ( filesize( $tempfile ) > 1024*1024 ) {
			return -3;
		}
		
		$handle = fopen( $tempfile, "rb" );
		$contents = fread( $handle, filesize( $tempfile ) );
		
		fclose( $handle );
		
		$extension = getextension( $imagename );
		$noextname = NoExtensionName( $imagename );
		
		if ( $noextname == '' ) {
			$imagename = 'noname' . rand( 1 , 20 ) . $extension;
		}
		
		return submit_photo( $imagename ,$tempfile ,0 , '' );
	}
	function FAQ_CanModify( $theuser ) {
		return $theuser->CanModifyCategories();
	}
	function FAQ_CategoryExists( $categoryid ) {
		global $db;
		global $faqcategories;
		
		if ( $categoryid == 0 ) {
			return true;
		}
		
		$categoryid = myescape( $categoryid );
		$sql = "SELECT `faqcategory_id` FROM `$faqcategories` WHERE `faqcategory_id` = '$categoryid' LIMIT 1;";
		$res = $db->Query( $sql );
		
		if ( $res->Results() ) {
			return true;
		}
		else {
			return false;
		}
	}
	function FAQ_KeywordUsed( $keyword, $faqqid = -1 ) {
		global $db;
		global $faqquestions;
		
		$keyword = myescape( $keyword );
		$sql = "SELECT * FROM `$faqquestions` WHERE `faqquestion_keyword` = '$keyword';";
		
		$fetched = $db->Query( $sql )->FetchArray();
		
		$used = false;
		foreach ( $fetched as $row ) {
			if ( $row[ 'faqquestion_id' ] != $faqqid && $row[ 'faqquestion_keyword' ] == $keyword ) {
				$used = true;
			}
		}
		
		return $used;
	}
	function FAQ_MakeQuestion( $question, $answer, $keyword, $categoryid = 0, $creatorid = '' ) {
		// $question: The question text (e.g. "What does MakeQuestion() do?")
		// $answer: The answer to the question (e.g. "It creates a question! :O" )
		// $keyword: The question's keyword (for Pretty URLs)
		// $categoryid: The category of the question ( if no category is given, the root category will be used)
		// $userid: The creator of the question ( if this is not numeric, the id of the user viewing the page will be used)
		
		global $db;
		global $faqquestions;
		global $user;
		
		if ( !FAQ_CategoryExists( $categoryid ) ) {
			return -1;
		}
		
		if ( !preg_match( "/^[A-Za-z0-9]+$/", $keyword ) ) {
			return -3;
		}
		
		if ( FAQ_KeywordUsed( $keyword ) ) {
			return -2;
		}
		
		if ( !is_numeric( $creatorid ) ) { // if userid is not a number
			$creatorid = $user->Id();		// consider the creator is the user currently viewing the page
		}
		
		$sqlarray = array(
			'faqquestion_id'			=> '',
			'faqquestion_categoryid'	=> $categoryid,
			'faqquestion_created'		=> NowDate(),
			'faqquestion_creatorid'		=> $creatorid,
			'faqquestion_creatorip'		=> UserIp(),
			'faqquestion_question'		=> $question,
			'faqquestion_answer'		=> $answer,
			'faqquestion_delid'			=> 0,
			'faqquestion_keyword'		=> $keyword
		);
		
		$change = $db->Insert( $sqlarray, $faqquestions );
		
		if ( $change->Impact() ) {
			return $change->InsertId(); // run the query and return a boolean value indicating if the query was impact
		}
		else {
			return false;
		}
	}
	function FAQ_MakeCategory( $name, $description, $iconid = 0, $creatorid = '' ) {
		global $db;
		global $faqcategories;
		global $user;
		
		if ( !is_numeric( $creatorid ) ) {
			$creatorid = $user->Id();
		}
		
		$sqlarray = array(
			'faqcategory_id'			=> '',
			'faqcategory_creatorid'		=> $creatorid,
			'faqcategory_creatorip'		=> UserIp(),
			'faqcategory_name'			=> $name,
			'faqcategory_description'	=> $description,
			'faqcategory_created'		=> NowDate(),
			'faqcategory_delid'			=> 0,
			'faqcategory_iconid'		=> $iconid
		);
		
		return $db->Insert( $sqlarray, $faqcategories )->InsertId(); // run the query and return a boolean value indicating if the query was impact
	}
	function FAQ_AllQuestions( $hidedeleted = true ) {
		global $db;
		global $faqquestions;
		
		if ( $hidedeleted ) {					 // if we do not need deleted questions
			$where = "WHERE `faqquestion_delid` = '0'"; // create the where clause 
		}			
		else {									// else
			$where = "";						// leave the where clause empty
		}
		
		$sql = "SELECT * FROM `$faqquestions` $where";
		$res = $db->Query( $sql );
		
		$ret = array();
		while ( $row = $res->FetchArray() ) { // create an array with instances of the class FAQ_Question
			$ret[] = New FAQ_Question( $row );
		}
		
		return $ret;
	}
	function FAQ_AllCategories( $hidedeleted = true ) {
		global $db;
		global $faqcategories;
		
		if ( $hidedeleted ) {					 		// if we do not need deleted categories
			$where = "WHERE `faqcategory_delid` = '0'"; // create the where clause 
		}			
		else {											// else
			$where = "";								// leave the where clause empty
		}
		
		$sql = "SELECT * FROM `$faqcategories` $where";
		$res = $db->Query( $sql );
		
		$ret = array();
		while ( $row = $res->FetchArray() ) { // create an array with instances of the class FAQ_Category
			$ret[] = New FAQ_Category( $row );
		}
		
		return $ret;
	}
	
	final class FAQ_Question {
		private $mId;
		private $mCategory;
		private $mCategoryId;
		private $mCreated;
		private $mCreator;
		private $mCreatorId;
		private $mCreatorIp;
		private $mQuestion;
		private $mAnswer;
		private $mAnswerFormatted;
		private $mDelid;
		private $mPageviews;
		
		public function Id() {
			return $this->mId;
		}
		public function Category() { 
			if ( empty( $this->mCategory ) ) { 								// if category object is not yet created
				$this->mCategory = New FAQ_Category( $this->CategoryId() ); // instantiate category class
			}
			return $this->mCategory;
		}
		public function CategoryId() {
			return $this->mCategoryId;
		}
		public function Created() {
			return $this->mCreated;
		}
		public function Creator() {
			if ( empty( $this->mCreator ) ) { 					 // if creator object is not yet created
				$this->mCreator = New User( $this->CreatorId() ); // instantiate user class
			}
			return $this->mCreator;
		}
		public function CreatorId() {
			return $this->mCreatorId;
		}
		public function CreatorIp() {
			return $this->mCreatorIp;
		}
		public function Question() {
			return $this->mQuestion;
		}
		public function Answer() {
			return $this->mAnswer;
		}
		public function AnswerFormatted() {
			return $this->mAnswerFormatted;
		}
		public function Keyword() {
			return $this->mKeyword;
		}
		public function Pageviews() {
			return $this->mPageviews;
		}
		public function IsDeleted() {
			return $this->mDelid > 0;
		}
		public function Exists() {
			return $this->Id() > 0;
		}
		public function AddPageview() {
			global $db;
			global $faqquestions;
		
			$sql = "UPDATE `$faqquestions` SET `faqquestion_pageviews` = `faqquestion_pageviews` + 1 WHERE `faqquestion_id` = '" . $this->Id() . "';";
			$update = $db->Query( $sql )->Impact();
			
			$this->mPageviews = $this->mPageviews + 1;
		}
		public function Kill() {
			global $db;
			global $faqquestions;
			global $user;
			
			if ( !FAQ_CanModify( $user ) ) {
				return -2; // user doesn't have rights to edit FAQ
			}
			
			$sql = "UPDATE `$faqquestions` SET `faqquestion_delid` = '1' WHERE `faqquestion_id` = '" . $this->Id() . "' LIMIT 1;";
			return $db->Query( $sql )->Impact();
		}
		public function Update( $question, $answer, $keyword, $categoryid = '' ) {
			global $db;
			global $faqquestions;
			global $user;
			
			if ( !FAQ_CanModify( $user ) ) {
				return -2; // user doesn't have rights to edit FAQ
			}
			if ( FAQ_KeywordUsed( $keyword, $this->Id() ) ) {
				return -3;
			}
			if ( empty( $categoryid ) ) {
				$categoryid = $this->CategoryId();
			}
			else if ( !FAQ_CategoryExists( $categoryid ) ) { // this is not executed if $categoryid was empty
				return -1; // category doesn't exist
			}
			
			$question 	= myescape( $question );
			$answer		= myescape( $answer );
			$formatted  = mformatstories( array( $answer ) );
			$answerformatted = myescape( $formatted[ 0 ] );
			$categoryid	= myescape( $categoryid );
			$keyword	= myescape( $keyword );
			
			$sql = "UPDATE 
						`$faqquestions` 
					SET 
						`faqquestion_question` = '$question',
						`faqquestion_answer` = '$answer',
						`faqquestion_categoryid` = '$categoryid'
					";
			
			if ( $this->Keyword() != $keyword ) {
				$sql .= ",
						`faqquestion_keyword` = '$keyword'";
			}
			
			$sql .=	"WHERE 
						`faqquestion_id` = '" . $this->Id() . "'
					LIMIT 1;";
			
			$update = $db->Query( $sql );
			
			return $update->Impact();
		}
		public function PrevQuestion() {
			global $db;
			global $faqquestions;
			
			$sql = "SELECT 
						* 
					FROM 
						`$faqquestions` 
					WHERE 
						`faqquestion_id` < '" . $this->Id() . "' AND
						`faqquestion_delid` = '0'
					ORDER BY 
						`faqquestion_id` DESC
					LIMIT 1;";
			
			$fetched = $db->Query( $sql )->FetchArray();
			
			return New FAQ_Question( $fetched );
		}
		public function NextQuestion() {
			global $db;
			global $faqquestions;
			
			$sql = "SELECT 
						* 
					FROM 
						`$faqquestions` 
					WHERE 
						`faqquestion_id` > '" . $this->Id() . "' AND
						`faqquestion_delid` = '0'
					ORDER BY 
						`faqquestion_id` ASC
					LIMIT 1;";
			
			$fetched = $db->Query( $sql )->FetchArray();
			
			return New FAQ_Question( $fetched );
		}
		public function FAQ_Question( $construct ) {
			// constructor
			global $db;
			global $faqquestions;
			
			if ( is_array( $construct ) ) {	// we already have the data
				$fetched_array = $construct;
			}
			else { // get data from database
				if ( is_numeric( $construct ) ) {
					$qid = myescape( $construct );
					$sql = "SELECT * FROM `$faqquestions` WHERE `faqquestion_id` = '$qid' LIMIT 1;";
				}
				else {
					$qkeyword = myescape( $construct );
					$sql = "SELECT * FROM `$faqquestions` WHERE `faqquestion_keyword` = '$qkeyword' LIMIT 1;";
				}
				$res = $db->Query( $sql );
				if ( $res->Results() ) {
					$fetched_array =$res->FetchArray();
				}
				else {
					$fetched_array = array();
				}
			}
			
			$this->mId			    = isset( $fetched_array[ 'faqquestion_id' ] 			 ) ? $fetched_array[ 'faqquestion_id' ] 			    : 0;
			$this->mCategoryId 	    = isset( $fetched_array[ 'faqquestion_categoryid' ] 	 ) ? $fetched_array[ 'faqquestion_categoryid' ] 	    : 0;
			$this->mCreated 	    = isset( $fetched_array[ 'faqquestion_created' ] 		 ) ? $fetched_array[ 'faqquestion_created' ] 	    : '0000-00-00 00:00:00';
			$this->mCreatorId 	    = isset( $fetched_array[ 'faqquestion_creatorid' ] 		 ) ? $fetched_array[ 'faqquestion_creatorid' ] 	    : 0;
			$this->mCreatorIp 	    = isset( $fetched_array[ 'faqquestion_creatorip' ] 		 ) ? $fetched_array[ 'faqquestion_creatorip' ]    	: "";
			$this->mQuestion 	    = isset( $fetched_array[ 'faqquestion_question' ] 		 ) ? $fetched_array[ 'faqquestion_question' ] 	    : "";
			$this->mAnswer 		    = isset( $fetched_array[ 'faqquestion_answer' ] 		 ) ? $fetched_array[ 'faqquestion_answer' ] 		    : "";
			$this->mAnswerFormatted = isset( $fetched_array[ 'faqquestion_answerformatted' ] ) ? $fetched_array[ 'faqquestion_answerformatted' ] : "";
			$this->mDelid 		    = isset( $fetched_array[ 'faqquestion_delid' ]			 ) ? $fetched_array[ 'faqquestion_delid' ]		    : 0;
			$this->mKeyword		    = isset( $fetched_array[ 'faqquestion_keyword' ]		 ) ? $fetched_array[ 'faqquestion_keyword' ]		    : "";
			$this->mPageviews	    = isset( $fetched_array[ 'faqquestion_pageviews' ] 		 ) ? $fetched_array[ 'faqquestion_pageviews' ]	    : 0;
			
			// If we have taken the data for category or creator of the question, create the objects
			// Else, leave the variables empty, and let the the proper functions instantiate the classes.
			$this->mCategory	= isset( $fetched_array[ 'faqcategory_id' ] 	) ? New FAQ_Category( $fetched_array ) 	: "";
			$this->mCreator		= isset( $fetched_array[ 'user_id' ] 			) ? New User( $fetched_array ) 			: "";
		}
	}
	
	final class FAQ_Category {
		private $mId;
		private $mCreator;
		private $mCreatorId;
		private $mCreatorIp;
		private $mName;
		private $mDescription;
		private $mCreated;
		private $mParent;
		private $mParentId;
		private $mDelid;
		private $mIcon;
		private $mIconId;
		
		public function Id() {
			return $this->mId;
		}
		public function Creator() {
			if ( empty( $this->mCreator ) ) { 					 	// if creator object is not yet created
				$this->mCreator = New User( $this->CreatorId() ); 	// instantiate user class
			}
			return $this->mCreator;
		}
		public function CreatorId() {
			return $this->mCreatorId;
		}
		public function Name() {
			return $this->mName;
		}
		public function Description() {
			return $this->mDescription;
		}
		public function Icon() {
			return $this->mIcon;
		}
		public function IconId() {
			return $this->mIconId;
		}
		public function Created() {
			return $this->mCreated;
		}
		public function ParentCategory() {
			if ( empty( $this->mParent ) ) { 					 			// if parent category object is not yet created
				$this->mParent = New FAQ_Category( $this->ParentId() ); 	// instantiate FAQ_Category class
			}
			return $this->mParent;
		}
		public function ParentId() {
			return $this->mParentId;
		}
		public function IsDeleted() {
			return $this->mDelid > 0;
		}
		public function Exists() {
			return $this->Id() > 0;
		}
		public function Update( $name = '', $description = '', $iconid = '' ) {
			global $db;
			global $faqcategories;
			global $user;
			
			if ( !FAQ_CanModify( $user ) ) {
				return -1;
			}
			
			if ( empty( $name ) ) {
				$name = $this->Name();
			}
			if ( empty( $description ) ) {
				$description = $this->Description();
			}
			if ( empty( $iconid ) ) {
				$iconid = $this->IconId();
			}
			
			$name 			= myescape( $name );
			$description 	= myescape( $description );
			$iconid 		= myescape( $iconid );
			
			$sql = "UPDATE 
						`$faqcategories` 
					SET 
						`faqcategory_name` = '$name',
						`faqcategory_description` = '$description',
						`faqcategory_iconid` = '$iconid'
					WHERE 
						`faqcategory_id` = '" . $this->Id() . "'
					LIMIT 1;";
					
			$update = $db->Query( $sql );
			
			return $update->Impact();
		}
		public function Kill() {
			global $db;
			global $faqcategories;
			global $user;
			
			if ( !FAQ_CanModify( $user ) ) {
				return -2; // user doesn't have rights to edit FAQ
			}
			
			$sql = "UPDATE `$faqcategories` SET `faqcategory_delid` = '1' WHERE `faqcategory_id` = '" . $this->Id() . "' LIMIT 1;";
			return $db->Query( $sql )->Impact();
		}
		public function FAQ_Category( $construct ) {
			// constructor
			global $db;
			global $faqcategories;
			
			if ( is_array( $construct ) ) {	// we already have the data
				$fetched_array = $construct;
			}
			else { // get data from database
				$cid = myescape( $construct );
				$sql = "SELECT * FROM `$faqcategories` WHERE `faqcategory_id` = '$cid' AND `faqcategory_delid` = '0' LIMIT 1;";
				$res = $db->Query( $sql );
				if ( $res->Results() ) {
					$fetched_array = $res->FetchArray();
				}
				else {
					$fetched_array = array();
				}
			}
			
			$this->mId			= isset( $fetched_array[ 'faqcategory_id' ] 			) ? $fetched_array[ 'faqcategory_id' ] 			: 0;
			$this->mCreatorId 	= isset( $fetched_array[ 'faqcategory_creatorid' ] 		) ? $fetched_array[ 'faqcategory_creatorid' ]	: 0;
			$this->mCreated 	= isset( $fetched_array[ 'faqcategory_created' ] 		) ? $fetched_array[ 'faqcategory_created' ] 	: '0000-00-00 00:00:00';
			$this->mName 		= isset( $fetched_array[ 'faqcategory_name' ] 			) ? $fetched_array[ 'faqcategory_name' ] 		: "";
			$this->mDescription = isset( $fetched_array[ 'faqcategory_description' ]	) ? $fetched_array[ 'faqcategory_description' ]	: "";
			$this->mParentId 	= isset( $fetched_array[ 'faqcategory_parentid' ] 		) ? $fetched_array[ 'faqcategory_parentid' ] 	: "";
			$this->mDelid 		= isset( $fetched_array[ 'faqcategory_delid' ]			) ? $fetched_array[ 'faqcategory_delid' ]		: 0;
			$this->mIconId		= isset( $fetched_array[ 'faqcategory_iconid' ]			) ? $fetched_array[ 'faqcategory_iconid' ]		: "";	
			$this->mIcon		= new Image( $this->mIconId );
			
			// If we have taken the data for category or creator of the question, create the objects
			// Else, leave the variables empty, and let the the proper functions instantiate the classes.
			$this->mCreator		= isset( $fetched_array[ 'user_id' ] 					) ? New User( $fetched_array ) 					: "";
		}
	}
	
	class Search_FAQQuestions extends Search {
		public function SetSortMethod( $field, $order ) {
			static $fieldsmap = array(
				'date'			=> '`faqquestion_id`',
				'popularity' 	=> '`faqquestion_pageviews`'
			);
			
			w_assert( isset( $fieldsmap[ $field ] ) );
			$this->mSortField = $fieldsmap[ $field ];
			$this->SetSortOrder( $order );
		}
		public function SetFilter( $key, $value ) {
			// 0 -> equal, 1 -> LIKE
			static $keymap = array(
				'user' 		=> array( '`faqquestion_creatorid`'	, 0 ),
				'question' 	=> array( '`faqquestion_question`'		, 1 ),
				'answer'	=> array( '`faqquestion_answer`'		, 1 ),
				'delid'		=> array( '`faqquestion_delid`'		, 0 ),
				'category'	=> array( '`faqquestion_categoryid`'	, 0 ),
				'content' => array( array( '`faqquestion_question`', '`faqquestion_answer`' ), 1 )
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			
			$this->mFilters[] = array( $keymap[ $key ][ 0 ] , $keymap[ $key ][ 1 ] , $value );
		}
		public function Instantiate( $res ) {
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$ret[] = New FAQ_Question( $row );
			}
			
			return $ret;
		}
		private function SetQueryFields() {
			$this->mFields = array(
				'`faqquestion_id`'			=> 'faqquestion_id',
				'`faqquestion_categoryid`'	=> 'faqquestion_categoryid',
				'`faqquestion_creatorid`'	=> 'faqquestion_creatorid',
				'`faqquestion_creatorip`'	=> 'faqquestion_creatorip',
				'`faqquestion_question`'	=> 'faqquestion_question',
				'`faqquestion_answer`'		=> 'faqquestion_answer',
				'`faqquestion_answerformatted`' => 'faqquestion_answerformatted',
				'`faqquestion_delid`'		=> 'faqquestion_delid',
				'`faqquestion_keyword`'		=> 'faqquestion_keyword',
				'`faqquestion_pageviews`'	=> 'faqquestion_pageviews'
			);
		}
		public function Search_FAQQuestions( ) {
			global $faqquestions;
			global $users;
			
			$this->mRelations = array();
			$this->mIndex = 'faqq';
			$this->mTables = array(
				'faqq' => array( 'name' => $faqquestions ),
				'users' => array( 'name' => $users , 'jointype' => 'LEFT JOIN' , 'on' => '`user_id` = `faqquestion_creatorid`' )
			);
			
			$this->SetGroupByField( "faqquestion_id" );
			$this->SetQueryFields();
			$this->Search(); // parent constructor
		}
	}

?>
