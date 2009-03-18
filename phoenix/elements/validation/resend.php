<?php
    class ElementValidationResend extends Element {
        public function Render() {
            global $user;
			
			ob_start();
            $link = $this->Profile->ChangedEmail( '', $this->Name );
            $subject = Element( 'user/email/welcome', $this, $link );
            $text = ob_get_clean();
            Email( $this->Name, $this->Profile->Email, $subject, $text, "Zino", "noreply@zino.gr" );
		}
	}
?>