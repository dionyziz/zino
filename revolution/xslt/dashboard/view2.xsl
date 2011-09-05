<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<html id="photo-listing" class="SI-FILES-STYLIZED" lang="el" xmlns="http://www.w3.org/1999/xhtml" xml:lang="el">
			<head>
				<title>Zino</title>
				<base href="http://zino.gr/"></base>
				<link type="text/css" rel="stylesheet" href="http://static.zino.gr/css/global.css?22"></link>
				<link type="text/css" rel="stylesheet" href="http://beta.zino.gr/themis/css/home.css"></link>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
				<meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1"></meta>
				<script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
			</head>
			<body onload="Comet.OnBodyLoaded()">
				<script type="text/javascript">
				var href = window.location.href.split( '#' );
				if( href.length != 1 ){
				if( href[ 1 ].length ){
				document.body.style.display = 'none';
				}
				}
				</script>
				<div id="world" class="master-photo-listing">
					<div class="bar">
						<h1>
							<a href="" >
								<img src="http://static.zino.gr/phoenix/logo-trans.png"></img>
							</a>
						</h1>
						<ul>
							<li id="chat_icon">
								<a id="chatbutton" href="">Chat</a>
							</li>
							<li id="feedback_icon" >
								<a href="journals/13870">Meeting</a>
							</li>
							<li id="photo_icon">
								<a href="">Εικόνες</a>
							</li>
							<li id="news_icon">
								<a href="news">Νέα</a>
							</li>
							<li id="profile_icon">
								<a id="logoutbutton" href="users/themis5"> Προφίλ </a>
							</li>
						</ul>
					</div>
					<ul id="last_uploaded">
							<li>
								<form action="" >
									<div>
										<label class="cabinet">
											<input class="file" type="file" name="uploadimage" style="top: -1px; left: -188px;"></input>
										</label>
										<span class="tooltip">
											<span>▲</span>
											ανέβασε εικόνα
										</span>
									</div>
								</form>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5181/226090/226090_100.jpg" alt="petrosagg" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5221/260788/260788_100.jpg" alt="themis5" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/1/260127/260127_100.jpg" alt="dionyziz" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/11637/270213/270213_100.jpg" alt="mariosal" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5548/248930/248930_100.jpg" alt="vexlos" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5104/255495/255495_100.jpg" alt="chorvus" ></img></a>
							</li>
							<li>				
								<a href=""><img src="http://images2.zino.gr/media/3710/275398/275398_100.jpg" alt="konmpat" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/17165/265501/265501_100.jpg" alt="isminixXx" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5181/226090/226090_100.jpg" alt="petrosagg" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5221/260788/260788_100.jpg" alt="themis5" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/1/260127/260127_100.jpg" alt="dionyziz" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/11637/270213/270213_100.jpg" alt="mariosal" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5548/248930/248930_100.jpg" alt="vexlos" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/5104/255495/255495_100.jpg" alt="chorvus" ></img></a>
							</li>
							<li>				
								<a href=""><img src="http://images2.zino.gr/media/3710/275398/275398_100.jpg" alt="konmpat" ></img></a>
							</li>
							<li>
								<a href=""><img src="http://images2.zino.gr/media/17165/265501/265501_100.jpg" alt="isminixXx" ></img></a>
							</li>
					</ul>
					<div id="left">
						<div id="home_chat">
							<div id="top_chat" class="top_news" >
								<h2>Συζήτηση<a href="" class="page_open" >(μεγιστοποίηση)</a></h2>
								<div class="clear"></div>
							</div>
							<input type="text" name="message" value="Γράψε στη συζήτηση" ></input>
							<script type="text/javascript">
								var text;
								$( "div#home_chat input[ type=text ]" ).focus( function () {
									if ( this.value == "Γράψε στη συζήτηση" ) {
										$( this ).attr( "value", "" );
										$( this ).css( "color", "#000" );
									}
								} );
								$( "div#home_chat input[ type=text ]" ).focusout( function () {
									text = this.value;
									if ( text == '' ) {
										$( this ).attr( "value", "Γράψε στη συζήτηση" );
										$( this ).css( "color", "#999" );
									}
								} );
							</script>
							<ul>		
								<xsl:for-each select="home/chatchannel/discussion/comment">
									<xsl:sort select="@id" order="descending" />
									<li>
										<img>
											<xsl:attribute name="src"><xsl:value-of select="author/avatar/media/@url" /></xsl:attribute>
											<xsl:attribute name="alt"><xsl:value-of select="author/name" /></xsl:attribute>
										</img>
										<span class="chat_user">
											<a>
												<xsl:attribute name="href">users/<xsl:value-of select="author/name" /></xsl:attribute>
												<xsl:value-of select="author/name"/>
											</a>
										</span>
										<span><xsl:value-of select="text"/></span>
									</li>
								</xsl:for-each>
							</ul>
						</div>
						<div id="online_now">
							<div class="top_news" >
								<h2>Online</h2>
								<div class="clear"></div>
							</div>
							<ul>
								<xsl:for-each select="home/crowd/user">
									<li>
										<a>
											<xsl:attribute name="href">users/<xsl:value-of select="name" /></xsl:attribute>
											<img>
												<xsl:attribute name="src"><xsl:value-of select="avatar/media/@url" /></xsl:attribute>
												<xsl:attribute name="alt"><xsl:value-of select="name" /></xsl:attribute>
											</img>
										</a>
									</li>
								</xsl:for-each>
							</ul>
						</div>
					</div>
					<div id="right">
						<div id="last_posts">
							<div id="top_posts" class="top_news" >
								<h2>Πρόσφατα σχόλια <a href="" class="page_open" >(μεγιστοποίηση)</a></h2>
								<div class="clear"></div>
							</div>
							<ul class="activities" >
								<li>
									<a class="photo" href="" >
										<img src="http://images2.zino.gr/media/3710/275398/275398_100.jpg" alt="konmpat" class="user_avatar" ></img>
										<img src="http://images2.zino.gr/media/3710/275398/275398_100.jpg" alt="konmpat" class="preview" ></img>
										<div style="float:left">
											<span class="head"> σχολίασε στη φωτογραφία του konmpat</span>
											<div class="body novideo">na pas na pe8aneis</div>
										</div>
									</a>
								</li>
								<li>
									<a class="photo" href="" >
										<img src="http://images2.zino.gr/media/13168/275320/275320_100.jpg" alt="LenLen" class="user_avatar" ></img>
										<img src="http://images2.zino.gr/media/5104/255495/255495_100.jpg" alt="chorvus" class="preview" ></img>
										<div style="float:left">
											<span class="head"> σχολίασε στη φωτογραφία του chorvus</span>
											<div class="body novideo">YOU ROCK</div>
										</div>
									</a>
								</li>
								<li>
									<a href="" >
										<img src="http://images2.zino.gr/media/17816/275268/275268_100.jpg" alt="marinablackdyingrose" class="user_avatar" ></img>
										<span class="preview">Οι αγωνίες ενώς νέου</span>
										<div style="float:left">
											<span class="head"> σχολίασε στο ημερολόγιο</span>
											<div class="body novideo">dn.pas.k@la..</div>
										</div>
									</a>
								</li>
								<li>
									<a href="" >
										<img src="http://images2.zino.gr/media/16555/262582/262582_100.jpg" alt="karasas_leme" class="user_avatar" ></img>
										<span class="preview">Γράψε ό,τι σου 'ρθει</span>
										<div style="float:left">
											<span class="head"> σχολίασε στο ημερολόγιο</span>
											<div class="body novideo">Blah blousha lorem ipsum...</div>
										</div>
									</a>
								</li>
								<li>
									<a href="" >
										<img src="http://images2.zino.gr/media/16957/274976/274976_100.jpg" alt="Xtigma" class="user_avatar" ></img>
										<span class="preview">Θα έρθετε στο παρτυ μου;</span>
										<div style="float:left">
											<span class="head"> σχολίασε στη δημοσκόπηση</span>
											<div class="body novideo">Ναι σίγουρα πες μας τι ώρα</div>
										</div>
									</a>
								</li>
								<li>
									<a class="photo" href="" >
										<img src="http://images2.zino.gr/media/17165/265501/265501_100.jpg" alt="isminixXx" class="user_avatar" ></img>
										<img src="http://images2.zino.gr/media/5221/260788/260788_100.jpg" alt="themis5" class="preview" ></img>
										<div style="float:left">
											<span class="head"> σχολίασε στη φωτογραφία</span>
											<div class="body novideo">u so sexy</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div id="last_journals">
							<div id="top_journals" class="top_news" >
								<h2>Πρόσφατα ημερολόγια <a href="" class="page_open" >(μεγιστοποίηση)</a></h2>
								<div style="clear:both;"></div>
							</div>
							<ul>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/11934/267579/267579_100.jpg" alt="TomSuddenDeath" ></img>
										<span>''Μεγάλο'' Zino Meeting 5 Ιουλίου, Σύνταγμα :D yay!</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/13168/275320/275320_100.jpg" alt="LenLen" ></img>
										<span>ΔΝΤ</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/17816/275268/275268_100.jpg" alt="marinablackdyingrose" ></img>
										<span>Final Promise</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/16555/262582/262582_100.jpg" alt="karasas_leme" ></img>
										<span>Το χρονικό του Zino</span>
										<div style="clear: both"></div>
									</a>
								</li>
							</ul>
						</div>
						<div id="last_polls">
							<div id="top_polls" class="top_news" >
								<h2>Πρόσφατες δημοσκοπήσεις <a href="" class="page_open" >(μεγιστοποίηση)</a></h2>
								<div style="clear:both;"></div>
							</div>
							<ul>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/11934/267579/267579_100.jpg" alt="TomSuddenDeath" ></img>
										<span>Mac or PC?</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/13168/275320/275320_100.jpg" alt="LenLen" ></img>
										<span>Zino Reloaded vs Zino Phoenix</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/17816/275268/275268_100.jpg" alt="marinablackdyingrose" ></img>
										<span>Θα έρθεις στο νεο zino-meeting?</span>
										<div style="clear: both"></div>
									</a>
								</li>
								<li>
									<a href="">
										<img src="http://images2.zino.gr/media/16555/262582/262582_100.jpg" alt="karasas_leme" ></img>
										<span>Που θα πας διακοπές;</span>
										<div style="clear: both"></div>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div id="footer">
					© Kamibu 2011
					</div>
				</div>
			</body>
		</html>	
	</xsl:template>
</xsl:stylesheet>