<?php
	
	class ElementSchoolView extends Element {
		public function Render( tInteger $id ) {
            $id = $id->Get();
			
            $school = New School( $id );
			$students = $userfinder->FindBySchool( $school , 0 , 12 );
            if ( !$school->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν υπάρχει.' );
                return Element( '404' );
            }

            $institution = $school->Institution;
            if ( !$institution->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν εντάσσεται σε κάποιο ίδρυμα.' );
                return Element( '404' );
            }

			?><div id="schview">
				<div class="gname"><?php
                    if ( $institution->Avatar->Exists() ) {
                        ?><img src="" alt="" title="" /><?php
                    }
                    ?>
					<h2><?php
                    echo htmlspecialchars( $school->Name );
                    ?></h2>
					<h3><?php
                    echo htmlspecialchars( $institution->Name );
                    ?></h3>
				</div>
				<div class="eof"></div><?php
					Element( 'school/members/members' );
				?><div class="photos">
					<h4>Φωτογραφίες</h4>
					<div class="plist">
						<ul>
							<li>
								<a href="?p=photo&amp;id=125909"><span class="imageview"><img src="http://images.zino.gr/media/4531/125909/125909_100.jpg" style="width:100px;height:100px;" title="eirinougen" alt="eirinougen" /><span class="info"><span class="commentsnumber">&nbsp;</span>16</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125907"><span class="imageview"><img src="http://images.zino.gr/media/4725/125907/125907_100.jpg" style="width:100px;height:100px;" title="Peach" alt="Peach" /><span class="info"><span class="commentsnumber">&nbsp;</span>46</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125903"><span class="imageview"><img src="http://images.zino.gr/media/2887/125903/125903_100.jpg" style="width:100px;height:100px;" title="Black_Sign_Of_Death" alt="Black_Sign_Of_Death" /><span class="info"><span class="commentsnumber">&nbsp;</span>1</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125902"><span class="imageview"><img src="http://images.zino.gr/media/4557/125902/125902_100.jpg" style="width:100px;height:100px;" title="halloween" alt="halloween" /><span class="info"><span class="commentsnumber">&nbsp;</span>11</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125901"><span class="imageview"><img src="http://images.zino.gr/media/3049/125901/125901_100.jpg" style="width:100px;height:100px;" title="cRazY_SoFy" alt="cRazY_SoFy" /></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125900"><span class="imageview"><img src="http://images.zino.gr/media/5253/125900/125900_100.jpg" style="width:100px;height:100px;" title="veteran" alt="veteran" /></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125899"><span class="imageview"><img src="http://images.zino.gr/media/2128/125899/125899_100.jpg" style="width:100px;height:100px;" title="natalie" alt="natalie" /><span class="info"><span class="commentsnumber">&nbsp;</span>3</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125897"><span class="imageview"><img src="http://images.zino.gr/media/1884/125897/125897_100.jpg" style="width:100px;height:100px;" title="xaliasas" alt="xaliasas" /></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125896"><span class="imageview"><img src="http://images.zino.gr/media/4195/125896/125896_100.jpg" style="width:100px;height:100px;" title="ErotOxtiPimeN0" alt="ErotOxtiPimeN0" /><span class="info"><span class="commentsnumber">&nbsp;</span>10</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125895"><span class="imageview"><img src="http://images.zino.gr/media/4765/125895/125895_100.jpg" style="width:100px;height:100px;" title="realrealthanos" alt="realrealthanos" /><span class="info"><span class="commentsnumber">&nbsp;</span>37</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125894"><span class="imageview"><img src="http://images.zino.gr/media/1/125894/125894_100.jpg" style="width:100px;height:100px;" title="dionyziz" alt="dionyziz" /><span class="info"><span class="commentsnumber">&nbsp;</span>10</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125893"><span class="imageview"><img src="http://images.zino.gr/media/5052/125893/125893_100.jpg" style="width:100px;height:100px;" title="manarakia_xD" alt="manarakia_xD" /><span class="info"><span class="commentsnumber">&nbsp;</span>8</span></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125891"><span class="imageview"><img src="http://images.zino.gr/media/2172/125891/125891_100.jpg" style="width:100px;height:100px;" title="Teddy" alt="Teddy" /></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125890"><span class="imageview"><img src="http://images.zino.gr/media/4750/125890/125890_100.jpg" style="width:100px;height:100px;" title="spyr0c" alt="spyr0c" /></span></a>
							</li>
							<li>
								<a href="?p=photo&amp;id=125889"><span class="imageview"><img src="http://images.zino.gr/media/5260/125889/125889_100.jpg" style="width:100px;height:100px;" title="funeral" alt="funeral" /><span class="info"><span class="commentsnumber">&nbsp;</span>28</span></span></a>
							</li>                
						</ul>
					</div>
				</div>
				<div class="eof"></div>
				<div class="comments">
					<h4>Σχόλια σχετικά με Σχολή Ηλεκτρολόγων Μηχανικών &amp; Μηχανικών Υπολογιστών</h4>
                    <div class="comment newcomment"> 
						<div class="toolbox"><span class="time">τα σχόλια είναι επεξεργάσιμα για ένα τέταρτο</span></div> 
						<div class="who">
							<a href="http://izual.zino.gr/"><span class="imageview"><img src="http://images.zino.gr/media/58/117694/117694_100.jpg" class="avatar" style="width:50px;height:50px;" title="izual" alt="izual" /></span>izual</a> πρόσθεσε ένα σχόλιο
						</div> 
		                <div class="text"> 
		                    <textarea rows="" cols=""></textarea> 
		                </div> 
		                <div class="bottom"> 
		                    <form onsubmit="return false" action=""><input type="submit" value="Σχολίασε!" onclick="Comments.Create(0);" /></form> 
		                </div> 
		                <div style="display:none" id="item">58</div> 
		                <div style="display:none" id="type">3</div> 
					</div>
					<div id="comment_1638730" class="comment" style="">
						<div class="toolbox"><span class="time invisible">2008-10-31 16:45:49</span><a href="" class="invisible" style="margin-right:0px;" title="Διαγραφή">&nbsp;</a></div>
						<div class="who">
							<a href="http://realrealthanos.zino.gr/"><span class="imageview"><img src="http://images.zino.gr/media/4765/125895/125895_100.jpg" class="avatar" style="width:50px;height:50px;" title="realrealthanos" alt="realrealthanos" /></span>realrealthanos</a> είπε:
						</div>
						<div class="text">treat or SAE?<img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" class="emoticon" width="22" height="22" />
						</div> 
						<div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div> 
					</div>	
					<div id="comment_1638882" class="comment" style="padding-left:20px;">
						<div class="toolbox"><span class="time invisible">2008-10-31 17:05:00</span><a href="" class="invisible" style="margin-right:20px;" title="Διαγραφή">&nbsp;</a></div>
						<div class="who">
							<a href="http://izual.zino.gr/"><span class="imageview"><img src="http://images.zino.gr/media/58/117694/117694_100.jpg" class="avatar" style="width:50px;height:50px;" title="izual" alt="izual" /></span>izual</a> είπε:
						</div>
						<div class="text">treat?</div> 
						<div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div> 
					</div>
					<div id="comment_1638892" class="comment" style="padding-left:40px;">
						<div class="toolbox"><span class="time invisible">2008-10-31 17:07:08</span><a href="" class="invisible" style="margin-right:40px;" title="Διαγραφή">&nbsp;</a></div>
						<div class="who">
							<a href="http://realrealthanos.zino.gr/"><span class="imageview"><img src="http://images.zino.gr/media/4765/125895/125895_100.jpg" class="avatar" style="width:50px;height:50px;" title="realrealthanos" alt="realrealthanos" /></span>realrealthanos</a> είπε:
						</div>
						<div class="text"><img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" class="emoticon" width="22" height="22" />more candy<img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" class="emoticon" width="22" height="22" /><img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" class="emoticon" width="22" height="22" /><img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" class="emoticon" width="22" height="22" /></div> 
						<div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div> 
					</div>
				</div>
				<div class="eof"></div>
			</div><?php
		}
	}
?>
