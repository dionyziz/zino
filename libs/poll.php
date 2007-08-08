<?php

	function getAllPolls() {
		global $polls;
		global $db;
		
		$sql = "SELECT * FROM `$polls` ORDER BY `poll_id` DESC;";
		
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}
	function NewPoll( $text ) {
		global $polls;
		global $user;
		global $db;

		if( $user->CanModifyStories() ) {
			$storyid = MakeStory( "Ψηφοφορία: $text" , "" , 0 , 0 , false , true );
	
			$text = myescape( $text );
	
			$userid = $user->Id();
			$nowdate = NowDate();
			$sql = "INSERT INTO `$polls` ( `poll_id` , `poll_question` , `poll_userid` , `poll_storyid`, `poll_date` ) VALUES( '' , '$text' , '$userid' , '$storyid', '$nowdate' );";
			$db->Query( $sql );

			return mysql_insert_id();
		}
		else {
			return -1;
		}
	}
	
	function EditPoll( $pollid , $newtext ) {
		global $user;
		global $db;
		
		if( $user->CanModifyStories() ) {
			$thispoll = New Poll( $pollid );
			if( $user->CanModifyCategories() || $thispoll->UserId() == $user->Id() ) {
				$newtext = myescape( $newtext );
		
				$sql = "UPDATE `$polls` SET `poll_question`='$newtext' WHERE `poll_id`='$pollid' LIMIT 1;";
				$db->Query( $sql );
			}
			else {
				mdie( "Insufficient rights to modify this poll" );
			}
		}
	}
	
	function DeletePoll( $pollid ) {
		global $user;
		global $polls;
		global $db;
		
		if( $user->CanModifyStories() ) {
			$thispoll = New Poll( $pollid );
			if( $user->CanModifyCategories() || $thispoll->UserId() == $user->Id() ) {
				$sql = "DELETE FROM `$polls` WHERE `poll_id`='$pollid' LIMIT 1;";
				$db->Query( $sql );
			}
			else {
				mdie( "Insufficient rights to modify this poll" );
			}
		}
	}
	
	function DeleteOption( $optionid ) {
		global $user;
		global $polloptions;
		global $db;
		
		if( $user->CanModifyCategories() ) {
			if( ValidId( $optionid ) ) {
				$sql = "DELETE FROM `$polloptions` WHERE `poption_id`='$optionid'";
				$db->Query( $sql );
				return true;
			}
		}
		return false;
	}
	
	class Option { 
		var $mId;
		var $mText;
		
		function Id() {
			return $this->mId;
		}
		function Text() {
			return $this->mText;
		}
		function Option( $fetched_array ) {
			$this->mId = $fetched_array[ "poption_id" ];
			$this->mText = $fetched_array[ "poption_text" ];
		}
	}
	
	class Poll {
		var $mQuestion;
		var $mId;
		var $mUserId;
		var $mStoryId;
		var $mOptions;
		var $mOptionsRetrieved;
		var $mUserHasVoted;
		var $mUserHasVotedRetrieved;
		
		function UserHasVoted() {
			global $user;
			global $votes;
			global $polls;
			global $polloptions;
			global $db;
			
			if( !$this->mUserHasVotedRetrieved ) {
				$sql = "SELECT COUNT(`$votes`.*) AS votescount
						FROM
							`$votes`, `$polloptions`
						WHERE
							`poption_pollid`='" . $this->mId . "' AND
							`vote_polloption`=`poption_id` AND
							`vote_userid`='" . $user->Id() . "' AND
						LIMIT 1;";
				$res = $db->Query( $sql );
				$fa = $res->FetchArray();
				if( $fa[ "votescount" ] ) {
					$this->mUserHasVoted = true;
				}
				else {
					$this->mUserHasVoted = false;
				}
			}
			return $this->mUserHasVoted;
		}
		function Question() {
			return $this->mQuestion;
		}
		function StoryId() {
			return $this->mStoryId;
		}
		function UserId() {
			return $this->mUserId;
		}
		function Id() {
			return $this->mId;
		}
		function Option() {
			global $polloptions;
			global $db;
			
			if( $this->mOptionsRetrieved == false ) {
				$this->mOptionsRetrieved = true;
				$sql = "SELECT * FROM `$polloptions` WHERE `poption_pollid`='" . $this->mId . "'";
				$options = $db->Query( $sql );
			}
			$fetched = $options->FetchArray();
			if( $fetched ) {
				return New Option( $fetched );
			}
			else {
				return false;
			}
		}
		function NewOption( $text ) {
			global $polloptions;
			global $db;
			
			if( $user->CanModifyCategories() ) {
				$text = myescape( $text );
				$sql = "INSERT INTO `$polloptions` (`poption_id`, `poption_text`, `poption_pollid`) VALUES('', '$text', '" . $this->mId . "');";
				$db->Query( $sql );
				return mysql_insert_id();
			}
			return -1;
		}
		function Poll( $construct ) {
			global $polls;
			global $db;
			
			if( ValidId( $construct ) ) {
				$sql = "SELECT * FROM `$polls` WHERE `poll_id`='$construct' LIMIT 1;";
				$res = $db->Query( $sql );
				if( !$res->NumRows() ) {
					mdie( "Error" );
				}
				$fetched_array = $res->FetchArray();
			}
			else if( is_array( $construct ) ) {
				$fetched_array = $construct;
			}
			else {
				mdie( "Error constructing poll" );
			}
			$this->mQuestion = $fetched_array[ "poll_question" ];
			$this->mId = $fetched_array[ "poll_id" ];
			$this->mUserId = $fetched_array[ "poll_userid" ];
			$this->mStoryId = $fetched_array[ "poll_storyid" ];
			$this->mOptionsRetrieved = false;
		}
	}
?>