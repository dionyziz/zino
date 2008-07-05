<?php
	/*
		Developer: Dionyziz
	*/
	
	global $libs;
	
	$libs->Load( 'rabbit/helpers/socket' );
	
	class MedulaException extends Exception {
	}
	
	class MedulaQueue {
		private $mName;
		private $mPushClient = false;
		private $mPopClient = false;
		private $mToPush = array();
		private $mToPop = array();
		private $mJobPending = false;
		
		public function Push( MedulaQueueItem $target ) {
			$this->mToPush[] = $target;
		}
		public function Commit() {
			$this->mPushClient = New MedulaPushClient();
			$this->mPushClient->Push( $this->mName, $mToPush );
		}
		public function Retrieve( $amount ) {
			$this->mPopClient = New MedulaPopClient();
			$this->mToPop = $this->mPopClient->Pop( $this->mName, $amount );
		}
		public function Pop() {
			if ( $this->mJobPending !== false ) {
				throw New MedulaException( 'Cannot pop another job from queue, because another job is already being processed. Please complete the current job by marking it as Successful or Failed before continuing.' );
			}
			
			if ( count( $this->mToPop ) ) {
				$job = array_shift( $this->mToPop );
				w_assert( $job instanceof MedulaJob );
				$this->mJobPending = $job;
			}
			return false;
		}
		public function Success() {
			$this->mPopClient->Done( $this->mJobPending );
			$this->mJobPending = false;
		}
		public function Failure() {
			$this->mJobPending = false;
		}
		public function __construct( $name ) {
			$this->mPopClient = New MedulaClient();
			w_assert( is_string( $name ) );
			$this->mName = $name;
		}
	}
	
	class MedulaPopClient extends SocketClient {
		public function Pop( $queuename, $maxjobs ) {
			global $water;
			
			w_assert( is_string( $queuename ) );
			w_assert( preg_match( '#^[a-zA-Z0-9]+$#', $queuename ) );
			w_assert( is_int( $maxjobs ) );
			w_assert( $maxjobs >= 0 );
			
			if ( $maxjobs == 0 ) {
				return array();
			}
			
			$this->WriteLine( "POP $queuename" );
			$this->WriteLine( $maxjobs );
			
			$result = $this->ReadLine();
			$data = explode( ' ', $result );
			if ( $data[ 0 ] != 'POPPED' ) {
				$water->Notice( 'Invalid server response to "POP"; "POPPED" expected, "' . $data[ 0 ] . '" given; aborting pop operation' );
				return array();
			}
			if ( ( string )( int )$data[ 1 ] != $data[ 1 ] ) {
				$water->Notice( 'Invalid server response to "POP"; integer expression expected after "POPPED", "' . $data[ 1 ] . '" given; aborting pop operation' );
				return array();
			}
			
			$n = ( int )$data[ 1 ];
			
			$jobs = array();
			for ( $i = 0; $i < $n; ++$i ) {
				$jobdetails = $this->ReadLine();
				$job = $this->ParseJob( $jobdetails );
				if ( $job !== false ) {
					w_assert( $job instanceof MedulaJob );
					$jobs[] = $job;
				}
			}
			
			$this->WriteLine( 'DONE' );
			
			return $jobs;
		}
		private function ParseJob( $jobdetails ) {
			$data = explode( ' ', $jobdetails );
			if ( ( string )( int )$data[ 0 ] != $data[ 0 ] ) {
				$water->Notice( 'Invalid job details provided when popping job; integer expression expected for id, "' . $data[ 0 ] . '" given; skipping job' );
				return false;
			}
			if ( ( string )( int )$data[ 1 ] != $data[ 1 ] ) {
				$water->Notice( 'Invalid job details provided when popping job; integer expression expected for type, "' . $data[ 1 ] . '" given; skipping job' );
				return false;
			}
			if ( ( string )( int )$data[ 2 ] != $data[ 2 ] ) {
				$water->Notice( 'Invalid job details provided when popping job; integer expression expected for priority, "' . $data[ 2 ] . '" given; skipping job' );
				return false;
			}
			$id = ( int )$data [ 0 ];
			$type = ( int )$data[ 1 ];
			$priority = ( int )$data[ 2 ];
			$job = New MedulaJob( $type, $id, $priority );
			
			return $job;
		}
		public function Done( MedulaJob $job ) {
			$this->WriteLine( $job->Id . ' ' . $job->Type );
		}
	}
	
	class MedulaPushClient extends SocketClient {
		public function Push( $queuename, array $jobs ) {
			global $water;
			
			w_assert( is_string( $queuename ) );
			w_assert( preg_match( '#^[a-zA-Z0-9]+$#', $queuename ) );
			w_assert( is_array( $jobs ) );
			
			if ( empty( $jobs ) ) {
				return;
			}
			
			$this->WriteLine( "PUSH $queuename" );
			$this->WriteLine( count( $jobs ) );
			
			foreach ( $jobs as $job ) {
				w_assert( $job instanceof MedulaJob );
				$this->WriteLine( $job->Id . ' ' . $job->Type . ' ' . $job->Priority );
			}
			$result = $this->ReadLine();
			
			$data = explode( ' ', $result );
			if ( $data[ 0 ] != 'PUSHED' ) {
				$water->Notice( 'Invalid server response to "PUSH"; "PUSHED" expected, "' . $data[ 0 ] . '" given; ignoring' );
				return;
			}
			if ( ( string )( int )$data[ 1 ] != $data[ 1 ] ) {
				$water->Notice( 'Invalid server response to "PUSH"; integer expression expected after "PUSHED", "' . $data[ 1 ] . '" given; ignoring' );
				return;
			}
			if ( $data[ 1 ] != count( $jobs ) ) {
				$water->Notice( 'Count of jobs that were sent to server in PUSH operation (' . count( $jobs ) . ') doesn\'t match the count that were PUSHED (' . $data[ 1 ] . ')' );
				return;
			}
		}
	}
	
	class MedulaJob extends Overloadable {
		private $mType;
		private $mId;
		private $mPriority;
		private $mCreated;
		
		protected function GetType() {
			return $this->mType;
		}
		protected function GetId() {
			return $this->mId;
		}
		protected function GetPriority() {
			return $this->mPriority;
		}
		public function __construct( $type, $id, $priority ) {
			w_assert( is_int( $type ) );
			w_assert( is_int( $id ) );
			w_assert( is_int( $priority ) );
			
			$this->mType = $type;
			$this->mId = $id;
			$this->mPriority = $priority;
		}
	}
	
?>
