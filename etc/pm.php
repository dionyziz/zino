<?php
	header( 'Content-type: application/xhtml+xml; charset=utf-8' );
    echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
	<head>
		<title>Προσωπικά μηνύματα / Chit-Chat</title>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />//-->
		<link href="http://www.chit-chat.gr/css/main.css" rel="stylesheet" type="text/css"/>
		<link href="http://www.chit-chat.gr/css/pmnew.css" rel="stylesheet" type="text/css"/>
		<link href="http://www.chit-chat.gr/css/modal.css" rel="stylesheet" type="text/css"/>
		<link href="http://www.chit-chat.gr/css/banner.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<div id="ygddfdiv" style="border: 2px solid rgb(170, 170, 170); position: absolute; visibility: hidden; cursor: move; z-index: 999; height: 25px; width: 25px;">
			<div style="height: 100%; width: 100%; background-color: rgb(204, 204, 204); opacity: 0;"></div>
		</div>
		<div id="globalwarning" onmouseover="Water.HideGlobalWarningClear()" onmouseout="Water.HideGlobalWarningPrepare()" onclick="Water.OpenWindow();Water.HideGlobalWarning()" style="bottom: -18px;"><img src="http://static.chit-chat.gr/images/icons/water_notice_icon.png" alt="Notices"/> This page has notices. To debug, click on this box.</div><div class="axel"><div class="roku"><div class="aku" style="">
        <div style="float: right;"><img src="http://static.chit-chat.gr/images/icons/akuright.jpg" alt=""/></div>
        <div style="float: left;"><img src="http://static.chit-chat.gr/images/icons/akuleft.jpg" alt=""/></div>
        <div class="content">
            <div>
                <a href="https://beta.chit-chat.gr/user/izual" class="developer"><img src="http://static.chit-chat.gr/images/icons/anonymous.jpg" class="avatar" style="width: 50px; height: 50px;" title="izual" alt="izual"/><b>izual</b></a><br/><small>Fuck</small></div>
        </div><ul><li><a class="options" href="https://beta.chit-chat.gr/?p=p">Επιλογές</a></li><li><a class="profile" href="https://beta.chit-chat.gr/user/izual">Προφίλ</a></li><li><a class="chat" href="" onclick="window.open('chat', 'ccchat');return false;">Chat</a></li><li><a href="javascript:Userbox.Animate();" class="arrow" style="visibility: hidden;" title="Προβολή κάρτας χρήστη" id="userboxshow"/></li>
            </ul>        </div></div>
		<div class="spacer"> </div>
        <div class="roxas">
			<div style="float: right;">
				<img src="http://static.chit-chat.gr/images/icons/roxasend.jpg" alt=""/>
			</div>
			<a href="https://beta.chit-chat.gr/"><img src="http://static.chit-chat.gr/images/icons/logo-xc.jpg" alt="Chit-Chat" class="logo"/></a>
			<ul>
                <li>
                    <form action="" method="get">
                        <input type="hidden" name="p" value="search"/>
                        <input type="text" name="q" id="q" value="Αναζήτηση" class="text" onfocus="Search.Focus(this)" onblur="Search.Blur(this);"/><a href="https://beta.chit-chat.gr/?p=search" onclick="this.parentNode.submit();return false;"><img src="http://static.chit-chat.gr/images/icons/magnifier.png" alt="Ψάξε" title="Αναζήτηση"/></a>
                    </form>
                </li>
				<li>
					<a class="messages messagesread" href="https://beta.chit-chat.gr/?p=pms" title="Μηνύματα"><img src="http://static.chit-chat.gr/images/icons/email.png" alt="Μηνύματα" style="width: 16px; height: 16px; vertical-align: bottom;"/></a></li><li><a href="https://beta.chit-chat.gr/?p=faq" style="padding: 2px;" title="Πληροφορίες">
						<img src="http://static.chit-chat.gr/images/icons/help.png" alt="Πληροφορίες" style="width: 16px; height: 16px; vertical-align: bottom;"/>
					</a>
				</li>
				<li>
					<a class="logout" href="https://beta.chit-chat.gr/do/user/logout">Έξοδος</a>
				</li>            
			</ul>
		</div>
		<br/>
		<script type="text/javascript">
			var unreadpms = 0;		
		</script>
		<script type="text/javascript" src="http://static.chit-chat.gr/images/icons/yahoo-dom-event.js"></script>
		<script type="text/javascript" src="http://static.chit-chat.gr/images/icons/dragdrop-min.js"></script>
		<script src="http://static.chit-chat.gr/images/icons/animation-min.js"></script>
		<br/><br/><br/><br/>
		<div class="body">
			<div class="upper">
				<span class="title">Μηνύματα</span>
				<div class="subheading">Εισερχόμενα</div>
			</div>
			<div class="leftbar">
				<div class="folders" id="folders">
					<div class="activefolder" alt="Εισερχόμενα" title="Εισερχόμενα" onload="pms.activefolder = this;return false;" id="firstfolder"><a href="" class="folderlinksactive" onclick="pms.ShowFolderPm( this.parentNode , -1 );return false;">Εισερχόμενα</a></div>
					<div class="folder top" alt="Απεσταλμένα" title="Απεσταλμένα" id="sentfolder"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , -2 );return false;">Απεσταλμένα</a></div><div class="folder top" id="folder_10" alt="izual" title="izual"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '10' );return false;">izual</a></div>
						<script type="text/javascript">var test = new YAHOO.util.DDTarget( "folder_10" );</script>
						<div class="folder top" id="folder_11" alt="koukou" title="koukou"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '11' );return false;">koukou</a></div>
						<script type="text/javascript">var test = new YAHOO.util.DDTarget( "folder_11" );</script>
						<div class="folder top" id="folder_19" alt="neos fakelos" title="neos fakelos"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '19' );return false;">neos fakelos</a></div>
						<script type="text/javascript">var test = new YAHOO.util.DDTarget( "folder_19" );</script>
						<div class="folder top" id="folder_23" alt="testing" title="testing"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '23' );return false;">testing</a></div>
						<script type="text/javascript">var test = new YAHOO.util.DDTarget( "folder_23" );</script>
						<div class="folder top" id="folder_25" alt="koutsou" title="koutsou"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '25' );return false;">koutsou</a></div>
						<script type="text/javascript">var test = new YAHOO.util.DDTarget( "folder_25" );</script>
						<div class="newfolder top" id="newfolderlink" alt="Δημιούργησε έναν νέο φάκελο" title="Δημιούργησε έναν νέο φάκελο" onclick="pms.NewFolder();return false;"><a href="" class="folderlinksnew">Νέος Φάκελος</a></div>
				</div><br/>
				<a href="" class="folder_links" onclick="pms.NewMessage( '' , '' );return false;"><img src="http://static.chit-chat.gr/images/email_open.png" alt="Νέο μήνυμα" title="Νέο μήνυμα"/> Νέο μήνυμα</a><br/>
				<a href="" id="deletefolderlink" class="folder_links" onclick="return false;" style="display: none;"><img src="http://static.chit-chat.gr/images/icons/folder_delete.png" alt="Διαγραφή φακέλου" title="Διαγραφή φακέλου"/> Διαγραφή φακέλου</a>
				<a href="" id="renamefolderlink" class="folder_links" onclick="return false;" style="display: none;"><img src="http://static.chit-chat.gr/images/icons/folder_edit.png" alt="Μετονομασία φακέλου" title="Μετονομασία φακέλου"/> Μετονομασία φακέλου</a>
			</div>
			<div class="rightbar" style="float: left;">
				<div class="messages" id="messages"><div class="message" style="width: 620px;" id="pm_48">
		<div class="infobar"><a href="" style="float: right;" onclick="pms.DeletePm( this.parentNode.parentNode , 48 , true );return false;"><img src="http://static.chit-chat.gr/images/cross.png"/></a><div class="infobar_info" onclick="pms.ExpandPm( this ,  false , 48 );return false;"> από το </div><div style="display: inline;" class="infobar_info"/><div onclick="pms.ExpandPm( this ,  false, 48 );return false;" style="display: inline;" class="infobar_info">, πριν 2 μέρες και 18 ώρες</div>
		</div>

		<div class="text" style="background-color: rgb(248, 248, 246); display: none;">
			<div>
				test<br/><br/><br/><br/>
            </div>
		</div>
		<div class="lowerline" style="background-color: rgb(248, 248, 246); display: none;">
			<div class="leftcorner"> </div>
			<div class="rightcorner"> </div>
			<div class="middle"> </div>
			<div class="toolbar">
				<ul>
					<li><a href="" onclick="pms.NewMessage( null , &quot;test&quot; );return false;">Απάντηση</a></li>
				</ul>
			</div>
		</div>
	</div><div class="message" style="width: 620px;" id="pm_7">
		<div class="infobar"><a href="" style="float: right;" onclick="pms.DeletePm( this.parentNode.parentNode , 7 , false );return false;"><img src="http://static.chit-chat.gr/images/cross.png"/></a><img style="padding: 0px 4px 3px 2px; float: left;" src="http://static.chit-chat.gr/images/email_open_image.png" alt="Νέο μήνημα" title="Νέο μήνυμα"/><div class="infobar_info" onclick="pms.ExpandPm( this ,  true , 7 );return false;"> από τον </div><div style="display: inline;" class="infobar_info"><a href="https://beta.chit-chat.gr/user/izual" class="developer">izual</a></div><div onclick="pms.ExpandPm( this ,  true, 7 );return false;" style="display: inline;" class="infobar_info">, πριν 493 μήνες και 4 μέρες</div>
		</div>

		<div class="text" style="background-color: rgb(248, 248, 246); display: none;">
			<div>
				pls answer me if you can see this<br/><br/><br/><br/>
            </div>
		</div>
		<div class="lowerline" style="background-color: rgb(248, 248, 246); display: none;">
			<div class="leftcorner"> </div>
			<div class="rightcorner"> </div>
			<div class="middle"> </div>
			<div class="toolbar">
				<ul>
					<li><a href="" onclick="pms.NewMessage( &quot;izual&quot; , &quot;pls answer me if you can see this&quot; );return false;">Απάντηση</a></li>
				</ul>
			</div>
		</div>
	</div>				</div>
			</div>
			<div style="clear: left;"/>
			<div class="newfoldermodal" id="newfoldermodal" style="display: none;">
				Δώσε ένα όνομα για τον φάκελό σου<br/><br/>
				<form id="newfolderform" onsubmit="pms.CreateNewFolder( this );return false;" action="" method="">
					<input type="textbox" style="width: 130px;"/> 
					<a href="" onclick="pms.CreateNewFolder( this.parentNode );return false;"><img src="http://static.chit-chat.gr/images/icons/accept.png" alt="Δημιουργία" title="Δημιουργία"/></a>
					<a href="" onclick="pms.CancelNewFolder();return false;"><img src="http://static.chit-chat.gr/images/icons/cancel.png" alt="Ακύρωση" title="Ακύρωση"/></a>
				</form>
			</div>
		</div>
<div class="copy">
		Copyright © Chit-Chat.gr<span>, Excalibur <a href="" onclick="Water.OpenWindow(); return false;">Reloaded</a> 6.4</span>
		</div></div><!--[if lt IE 7]>
<script type="text/javascript" src="js/pngfix.js?1186670859"></script><![endif]-->
<script type="text/javascript" src="http://www.chit-chat.gr/js/main.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/pmsnew.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/coala.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/modal.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/animations.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/drag.htm"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/search.js"></script>
<script type="text/javascript" src="http://www.chit-chat.gr/js/user.js"></script>
</body></html>