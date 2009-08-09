<?php
    function ActionAboutContact(
          tText $email,
          tText $reason,
          tText $bugURL,
          tText $bugDescription,
          tText $bugDevice,
          tText $bugComputerOS,
          tText $bugPalmOS,
          tText $bugConsole,
          tText $bugWinVersion,
          tText $bugLinuxDistro,
          tText $bugBSDDistro,
          tText $bugBrowser,
          tText $bugIEVersion,
          tText $bugFFVersion,
          tText $bugOperaVersion,
          tText $bugChromeVersion,
          tText $bugSafariVersion,
          tText $featureChoice,
          tText $featureDescription,
          tText $abuseType,
          tText $abuseUsername,
          tText $abuseDescription,
          tText $pressFullname,
          tText $pressType,
          tText $pressCompany,
          tText $pressPhone,
          tText $pressDescription,
          tText $bizFullname,
          tText $bizCompany,
          tText $bizPosition,
          tText $bizPhone,
          tText $bizDescription
        ) {
        global $libs;
        global $user;

        $email = $email->Get();
        $reason = $reason->Get();
        $bugURL = $bugURL->Get();
        $bugDescription = $bugDescription->Get();
        $bugDevice = $bugDevice->Get();
        $bugComputerOS = $bugComputerOS->Get();
        $bugPalmOS = $bugPalmOS->Get();
        $bugConsole = $bugConsole->Get();
        $bugWinVersion = $bugWinVersion->Get();
        $bugLinuxDistro = $bugLinuxDistro->Get();
        $bugBSDDistro = $bugBSDDistro->Get();
        $bugBrowser = $bugBrowser->Get();
        $bugIEVersion = $bugIEVersion->Get();
        $bugFFVersion = $bugFFVersion->Get();
        $bugOperaVersion = $bugOperaVersion->Get();
        $bugChromeVersion = $bugChromeVersion->Get();
        $bugSafariVersion = $bugSafariVersion->Get();
        $featureChoice = $featureChoice->Get();
        $featureDescription = $featureDescription->Get();
        $abuseType = $abuseType->Get();
        $abuseUsername = $abuseUsername->Get();
        $abuseDescription = $abuseDescription->Get();
        $pressFullname = $pressFullname->Get();
        $pressType = $pressType->Get();
        $pressCompany = $pressCompany->Get();
        $pressPhone = $pressPhone->Get();
        $pressDescription = $pressDescription->Get();
        $bizFullname = $bizFullname->Get();
        $bizCompany = $bizCompany->Get();
        $bizPosition = $bizPosition->Get();
        $bizPhone = $bizPhone->Get();
        $bizDescription = $bizDescription->Get();

        $text = '';
        switch ( $reason ) {
            case "support":
                $text .= "== Bug Report ==\n\n";
                $text .= "Device: " . $bugDevice;
                switch ( $bugDevice ) {
                    case 'computer':
                        $text .= "running: " . $bugComputerOS . ' ';
                        switch ( $bugComputerOS ) {
                            case 'windows':
                                $text .= $bugWinVersion;
                                break;
                            case 'linux':
                                $text .= $bugLinuxDistro;
                                break;
                            case 'mac':
                                break;
                            case 'bsd':
                                $text .= $bugBSDDistro;
                                break;
                        }
                        $text .= "\n";
                        break;
                    case 'palmtop':
                        $text .= "running: " . $bugPalmOS . "\n";
                        break;
                    case 'console':
                        $text .= " (" . $bugConsole . ")\n";
                }
                $text .= "Browser: " . $bugBrowser . " ";
                switch ( $bugBrowser ) {
                    case 'ie':
                        $text .= $bugIEVersion;
                        break;
                    case 'ff':
                        $text .= $bugFFVersion;
                        break;
                    case 'chrome':
                        $text .= $bugChromeVersion;
                        break;
                    case 'opera':
                        $text .= $bugOperaVersion;
                        break;
                    case 'safari':
                        $text .= $bugSafariVersion;
                        break;
                }
                $text .= "\n";
                $text .= "\n\n" . $bugDescription . "\n";
                break;
            case "feature":
                $title = "Feature Request";
                $text .= "Feature: " . $featureChoice . "\n";
                $text .= "\n\n" . $featureDescription . "\n";
                break;
            case "abuse":
                $title = "Abuse Report";
                $text .= "Type of abuse: " . $abuseType . "\n";
                $text .= "Abuser: " . $abuseUsername . "\n";
                $text .= "\n\n" . $abuseDescription . "\n";
                break;
            case "biz":
                $title = "Business Inquiry";
                $text .= 'Full name: ' . $bizFullname . "\n";
                $text .= 'Company: ' . $bizCompany . "\n";
                $text .= 'Phone: ' . $bizPhone . "\n";
                $text .= "\n\n" . $bizDescription . "\n";
                break;
            case "press":
                $title = "Press Inquiry";
                $text .= 'Full name: ' . $pressFullname . "\n";
                $text .= 'Journalism form: ' . $pressType . "\n";
                $text .= 'Company: ' . $pressCompany . "\n";
                $text .= 'Phone: ' . $pressPhone . "\n";
                $text .= "\n\n" . $pressDescription . "\n";
                break;
        }
        $text = "== $title ==\n\n" . $text;
        
		$libs->Load( 'rabbit/helpers/email' );

        $subject = "Zino: " . $title;
        if ( $user->Exists() ) {
            $text = "This user is logged in: http://" . $user->Subdomain . ".zino.gr/\n\n";
            $from = $user->Subdomain . "@users.zino.gr";
        }
        else {
            $from = $email;
        }
        $oniz = "oniz@kamibu.com";
		$fromname = "";

        Email( '', 'oniz@kamibu.com', $subject, $text, '', $from );

        return Redirect( '?p=about&section=contact&status' );
    }
?>
