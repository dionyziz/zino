<?php
    function ActionAboutContact(
          tText $email,
          tText $reason,
          tText $bugurl,
          tText $bugdescription,
          tText $bugdevice,
          tText $bugcomputeros,
          tText $bugpalmos,
          tText $bugconsole,
          tText $bugwinversion,
          tText $buglinuxdistro,
          tText $bugbsddistro,
          tText $bugbrowser,
          tText $bugieversion,
          tText $bugffversion,
          tText $bugoperaversion,
          tText $bugchromeversion,
          tText $bugsafariversion,
          tText $featurechoice,
          tText $featuredescription,
          tText $abusetype,
          tText $abuseusername,
          tText $abusedescription,
          tText $pressfullname,
          tText $presstype,
          tText $presscompany,
          tText $pressphone,
          tText $pressdescription,
          tText $bizfullname,
          tText $bizcompany,
          tText $bizposition,
          tText $bizphone,
          tText $bizdescription
        ) {
        global $libs;
        global $user;

        $email = $email->get();
        $reason = $reason->get();
        $bugurl = $bugurl->get();
        $bugdescription = $bugdescription->get();
        $bugdevice = $bugdevice->get();
        $bugcomputeros = $bugcomputeros->get();
        $bugpalmos = $bugpalmos->get();
        $bugconsole = $bugconsole->get();
        $bugwinversion = $bugwinversion->get();
        $buglinuxdistro = $buglinuxdistro->get();
        $bugbsddistro = $bugbsddistro->get();
        $bugbrowser = $bugbrowser->get();
        $bugieversion = $bugieversion->get();
        $bugffversion = $bugffversion->get();
        $bugoperaversion = $bugoperaversion->get();
        $bugchromeversion = $bugchromeversion->get();
        $bugsafariversion = $bugsafariversion->get();
        $featurechoice = $featurechoice->get();
        $featuredescription = $featuredescription->get();
        $abusetype = $abusetype->get();
        $abuseusername = $abuseusername->get();
        $abusedescription = $abusedescription->get();
        $pressfullname = $pressfullname->get();
        $presstype = $presstype->get();
        $presscompany = $presscompany->get();
        $pressphone = $pressphone->get();
        $pressdescription = $pressdescription->get();
        $bizfullname = $bizfullname->get();
        $bizcompany = $bizcompany->get();
        $bizposition = $bizposition->get();
        $bizphone = $bizphone->get();
        $bizdescription = $bizdescription->get();

        $text = '';
        switch ( $reason ) {
            case "support":
                $title = 'Bug Report';
                $text .= "Device: " . ucfirst( $bugdevice );
                switch ( $bugdevice ) {
                    case 'computer':
                        $text .= " running " . ucfirst( $bugcomputeros ) . ' ';
                        switch ( $bugcomputeros ) {
                            case 'windows':
                                $text .= ucfirst( $bugwinversion );
                                break;
                            case 'linux':
                                $text .= ucfirst( $buglinuxdistro );
                                break;
                            case 'mac':
                                break;
                            case 'bsd':
                                $text .= ucfirst( $bugbsddistro );
                                break;
                        }
                        $text .= "\n";
                        break;
                    case 'palmtop':
                        $text .= "running: " . $bugpalmos . "\n";
                        break;
                    case 'console':
                        $text .= " (" . $bugconsole . ")\n";
                }
                $text .= "Browser: " . ucfirst( $bugbrowser ) . " ";
                switch ( $bugbrowser ) {
                    case 'ie':
                        $text .= $bugieversion;
                        break;
                    case 'ff':
                        $text .= $bugffversion;
                        break;
                    case 'chrome':
                        $text .= $bugchromeversion;
                        break;
                    case 'opera':
                        $text .= $bugoperaversion;
                        break;
                    case 'safari':
                        $text .= $bugsafariversion;
                        break;
                }
                $text .= "\n";
                $text .= "\n\n" . $bugdescription . "\n";
                break;
            case "feature":
                $title = "Feature Request";
                $text .= "Feature: " . $featurechoice . "\n";
                $text .= "\n\n" . $featuredescription . "\n";
                break;
            case "abuse":
                $title = "Abuse Report";
                $text .= "Type of abuse: " . $abusetype . "\n";
                $text .= "Abuser: " . $abuseusername . "\n";
                $text .= "\n\n" . $abusedescription . "\n";
                break;
            case "biz":
                $title = "Business Inquiry";
                $text .= 'Full name: ' . $bizfullname . "\n";
                $text .= 'Company: ' . $bizcompany . "\n";
                $text .= 'Phone: ' . $bizphone . "\n";
                $text .= "\n\n" . $bizdescription . "\n";
                break;
            case "press":
                $title = "Press Inquiry";
                $text .= 'Full name: ' . $pressfullname . "\n";
                $text .= 'Journalism form: ' . $presstype . "\n";
                $text .= 'Company: ' . $presscompany . "\n";
                $text .= 'Phone: ' . $pressphone . "\n";
                $text .= "\n\n" . $pressdescription . "\n";
                break;
        }
        $text = "== $title ==\n\n" . $text;
        
		$libs->load( 'rabbit/helpers/email' );

        $subject = "[Zino-contact] " . $title;
        if ( $user->Exists() ) {
            $text = "This user is logged in: http://" . $user->Subdomain . ".zino.gr/\n\n" . $text;
            $from = $user->Subdomain . "@users.zino.gr";
        }
        else {
            $from = $email;
        }
        $oniz = "oniz@kamibu.com";
		$fromname = "";

        Email( '', 'oniz@kamibu.com', $subject, $text, '', $from );

        return Redirect( '?p=about&section=contact&status=1' );
    }
?>
