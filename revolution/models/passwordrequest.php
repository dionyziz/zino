<?php
	global $settings;
    class PasswordRequest {
		public static function Create( $userid ) {
			clude( 'models/hashstring.php' );
			clude( 'models/agent.php' );
			$userid = ( int )$userid;
			$hash = GenerateRandomHash();
			$ip = (string)UserIp();
			db( 'INSERT INTO `passwordrequests`
                 ( `request_userid`, `request_hash`, `request_used`, `request_host`, `request_created` )
                 VALUES ( :userid, :hash, 0, :ip, NOW() )',
				compact( 'userid', 'hash', 'ip' ) 
			);
            $id = mysql_insert_id();
			
			return array( 
					'userid' => $userid, 
					'hash' => $hash, 
					'used' => 0,
					'host' => $ip,
                    'id' => $id
			);
		}
		public static function Item( $id, $hashvalue ) {

		}
        public function Mail( $requestid, $hash ) {
            ?>Είμαστε έτοιμοι να αλλάξεις τον κωδικό στον λογαριασμό σου στο Zino.

Για να το κάνεις, ακολούθησε τον παρακάτω σύνδεσμο:

<?php
            echo $settings[ 'base' ];
            ?>/forgot/recover/<?php
            echo $requestid;
            ?>?hash=<?php
            echo $hash;
            ?>

Αν ο σύνδεσμός δεν λειτουργεί, δοκίμασε να τον αντιγράψεις στην γραμμή διευθύνσεων.

Αν δεν έχεις ζητήσει να αλλάξεις τον κωδικό σου, μπορείς να αγνοήσεις αυτό το μήνυμα.<?php
            //Element( 'email/footer', false );
            
            return 'Zino: Επαναφορά κωδικού πρόσβασης';
        }
	}
?>
