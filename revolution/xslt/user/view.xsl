<xsl:template match="/social[@resource='user' and @method='view']">
    <xsl:apply-templates select="user"/>
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user">
    <xsl:if test="@deleted='yes'">
        <script type="text/javascript">
            location.href = 'http://static.zino.gr/phoenix/deleted/';
        </script>
    </xsl:if>
    <xsl:if test="not(@deleted='yes')">
        <div class="userview">
            <xsl:attribute name="id">user_<xsl:value-of select="@id" /></xsl:attribute>
            <ul class="useritems">
                <xsl:if test="photos/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            photos/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="photos/@count" /></span>
                        Φωτογραφίες
                    </a></li>
                </xsl:if>
                <xsl:if test="journals/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            journals/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="journals/@count" /></span>
                        Ημερολόγια
                    </a></li>
                </xsl:if>
                <xsl:if test="polls/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            polls/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="polls/@count" /></span>
                        Δημοσκοπίσεις
                    </a></li>
                </xsl:if>
                <xsl:if test="friends/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            friends/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="friends/@count" /></span>
                        Φίλοι
                    </a></li>
                </xsl:if>
                <xsl:if test="favourites/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            favourites/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="favourites/@count" /></span>
                        Αγαπημένα
                    </a></li>
                </xsl:if>
            </ul>
            <div class="maininfo">
                <div id="useravatar">
                    <xsl:if test="$user = name[1]">
                        <xsl:attribute name="class">useravatar editable</xsl:attribute>
                        <div class="changeavatar">Αλλαγή</div>
                    </xsl:if>
                    <img class="avatar">
                        <xsl:choose>
                            <xsl:when test="avatar[1]/@id = 0">
                                <xsl:attribute name="src">http://static.zino.gr/phoenix/anonymous100.jpg</xsl:attribute>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:attribute name="src"><xsl:value-of select="avatar[1]/media[1]/@url" /></xsl:attribute>
                            </xsl:otherwise>
                        </xsl:choose>
                    </img>
                </div>
                <div class="details">
                    <div><span class="username"><xsl:value-of select="name[1]" /></span>
                        <xsl:if test="$user = name[1]">
                            <ul class="accountmenu">
                                <li><a href="">Λογαριασμός</a></li>
                                <li class="dot"><a href="">Έξοδος</a></li>
                            </ul>
                        </xsl:if>
                    </div>
                    <ul class="asl">
                        <xsl:if test="( gender[1] and gender[1] != '-' ) or $user = name[1]">
                            <li class="gender">
                                <span>
                                    <xsl:call-template name="detailstrings">
                                        <xsl:with-param name="field">gender</xsl:with-param>
                                        <xsl:with-param name="value" select="gender" />
                                        <xsl:with-param name="gender" select="gender" />
                                    </xsl:call-template>
                                </span>
                                <xsl:if test="$user = name[1]">
                                    <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="gender" /></xsl:attribute>
                                        <option><xsl:attribute name="value"><xsl:value-of select="gender" /></xsl:attribute></option>
                                    </select>
                                </xsl:if>
                            </li>
                        </xsl:if>
                        <xsl:if test="age[1] or $user = name[1]">
                            <li class="age">
                                <xsl:if test="( gender[1] and gender[1] != '-' ) or $user = name[1]">
                                    <xsl:attribute name="class">dot</xsl:attribute>
                                </xsl:if>
                                <span id="age"><xsl:value-of select="age[1]" /></span>
                            </li>
                        </xsl:if>
                        <xsl:if test="location[1] or $user = name[1]">
                            <li>
                                <xsl:attribute name="class">
                                    location
                                    <xsl:if test="( gender and gender != '-' ) or age or $user = name[1]">
                                        dot
                                    </xsl:if>
                                </xsl:attribute>
                                <span><xsl:attribute name="id">location_<xsl:value-of select="location[1]/@id" /></xsl:attribute><xsl:value-of select="location[1]" /></span>
                            </li>
                            <xsl:if test="$user = name[1]">
                                <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="gender" /></xsl:attribute>
                                    <option><xsl:attribute name="value"><xsl:value-of select="gender" /></xsl:attribute></option>
                                </select>
                            </xsl:if>
                        </xsl:if>
                    </ul>
                    <xsl:if test="details/slogan">
                        <div class="slogan"><xsl:value-of select="details/slogan" /></div>
                    </xsl:if>
                </div>
            </div>
            <div class="eof"></div>
            <div class="pantherbox tweetbox" style="display: none">
                <div class="arrow">&#9650;</div>
                <span class="tweet">tweetbox</span>
            </div>
            <div class="eof"></div>
            <div class="sidebar">
                <xsl:if test="mood or $user = name[1]">
                    <div class="mood">
                        <xsl:choose>
                            <xsl:when test="not( mood ) and $user = name[1]">
                                <div alt="Δεν έχει οριστεί διάθεση" title="Δεν έχει οριστεί διάθεση" class="moodtile nomood activemood"></div>
                            </xsl:when>
                            <xsl:otherwise>
                                <div>
                                    <xsl:attribute name="class">moodtile<xsl:if test="$user = name[1]"> activemood</xsl:if></xsl:attribute>
                                    <xsl:attribute name="style">background-image:url(<xsl:value-of select="mood/media[1]/@url" />)</xsl:attribute>
                                    <xsl:attribute name="alt"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                                    <xsl:attribute name="title"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                                    <xsl:attribute name="id">activemood_<xsl:value-of select="mood/@id" /></xsl:attribute>
                                </div>
                            </xsl:otherwise>
                        </xsl:choose>
                    </div>
                </xsl:if>
                <xsl:if test="$user and $user != name[1]">
                    <xsl:choose>
                        <xsl:when test="knownBy = $user">
                            <form action="friendship/delete" method="post" class="friendship">
                                <input type="hidden" name="friendid">
                                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                                </input>
                                <a href="" title="Διαγραφή φίλου">
                                    <strong>&#9829;</strong>
                                    <strong class="delete">/</strong>
                                    Φίλος
                                </a>
                            </form>
                        </xsl:when>
                        <xsl:otherwise>
                            <form action="friendship/create" method="post" class="friendship">
                                <input type="hidden" name="friendid">
                                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                                </input>
                                <a class="love linkbutton" href=""><strong>+</strong> Προσθήκη φίλου</a>
                            </form>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:if>
                <div class="eof"></div>
                <xsl:apply-templates select="details" />

                <xsl:apply-templates select="song" />
               
                <ul class="userinterests">
                    <xsl:if test="/social/@for = /social/user/name">
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">hobbies</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='hobbies']" />
                        </xsl:call-template>
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">movies</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='movies']" />
                        </xsl:call-template>
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">shows</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='shows']" />
                        </xsl:call-template>
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">books</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='books']" />
                        </xsl:call-template>
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">games</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='games']" />
                        </xsl:call-template>
                        <xsl:call-template name="interest">
                            <xsl:with-param name="type">artists</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='artists']" />
                        </xsl:call-template>
						<xsl:call-template name="interest">
                            <xsl:with-param name="type">songs</xsl:with-param>
                            <xsl:with-param name="data" select="taglist[@type='songs']" />
                        </xsl:call-template>
                    </xsl:if>
                    <xsl:if test="/social/@for != /social/user/name">
                        <xsl:for-each select="taglist">
                            <xsl:call-template name="interest">
                                <xsl:with-param name="type"><xsl:value-of select="@type" /></xsl:with-param>
                                <xsl:with-param name="data" select="." />
                            </xsl:call-template>
                        </xsl:for-each>
                    </xsl:if>
                </ul>
            </div>
            <div class="rightbar">
                <xsl:apply-templates select="activities" />
            </div>
            <div class="eof"></div>
        </div>
        <xsl:apply-templates select="discussion" />
    </xsl:if>
</xsl:template>

<xsl:template match="activities">
    <xsl:if test="activity">
        <h2>
            <xsl:choose>
                <xsl:when test="/social/user/gender = 'f'">Η&#160;</xsl:when>
                <xsl:otherwise>Ο&#160;</xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/user/name" /> πρόσφατα:
        </h2>
        <ul class="activities">
            <xsl:apply-templates select="activity" />
        </ul>
    </xsl:if>
</xsl:template>

<xsl:template match="activity">
    <li>
        <a>
            <xsl:choose>
                <xsl:when test="type = 'comment'">
                    <xsl:choose>
                        <xsl:when test="comment/journal">
                            <xsl:attribute name="href">journals/<xsl:value-of select="comment/journal/@id" /></xsl:attribute>
                            <span class="head">
                                σχολίασε στο ημερολόγιο <xsl:value-of select="comment/journal/title" />:
                            </span>
                        </xsl:when>
                        <xsl:when test="comment/poll">
                            <xsl:attribute name="href">polls/<xsl:value-of select="comment/poll/@id" /></xsl:attribute>
                            <span class="head">
                                σχολίασε στη δημοσκόπηση <xsl:value-of select="comment/poll/question" />:
                            </span>
                        </xsl:when>
                        <xsl:when test="comment/profile">
                            <xsl:attribute name="href">users/<xsl:value-of select="comment/profile/name" /></xsl:attribute>
                            <span class="head">
                                σχολίασε στο προφίλ
                                <xsl:choose>
                                    <xsl:when test="comment/profile/gender = 'f'"> της </xsl:when>
                                    <xsl:otherwise> του </xsl:otherwise>
                                </xsl:choose>
                                <xsl:if test="/social/user/@id != comment/profile/@id">
                                    <xsl:value-of select="comment/profile/name" />
                                </xsl:if>
                            </span>
                        </xsl:when>
                        <xsl:when test="comment/photo">
                            <xsl:attribute name="href">photos/<xsl:value-of select="comment/photo/@id" /></xsl:attribute>
                            <xsl:attribute name="class">photo</xsl:attribute>
                            <img>
                                <xsl:if test="comment/photo/title">
                                    <xsl:attribute name="alt"><xsl:value-of select="comment/photo/title" /></xsl:attribute>
                                    <xsl:attribute name="title"><xsl:value-of select="comment/photo/title" /></xsl:attribute>
                                </xsl:if>
                                <xsl:attribute name="src">http://images2.zino.gr/media/<xsl:value-of select="comment/photo/author/@id" />/<xsl:value-of select="comment/photo/@id" />/<xsl:value-of select="comment/photo/@id" />_100.jpg</xsl:attribute>
                            </img>
                            <span class="head">
                                σχολίασε στη φωτογραφία 
                                <xsl:choose>
                                    <xsl:when test="comment/photo/author/gender = 'f'">της </xsl:when>
                                    <xsl:otherwise>του </xsl:otherwise>
                                </xsl:choose>
                                <xsl:if test="/social/user/@id != comment/photo/author/@id">
                                    <xsl:value-of select="comment/photo/author/name" />
                                </xsl:if>
                            </span>
                        </xsl:when>
                    </xsl:choose>
                    <div class="body novideo"><xsl:copy-of select="comment/text/*|comment/text/text()" /></div>
                </xsl:when>
                <xsl:when test="type = 'favourite'">
                    <xsl:attribute name="href">
                        <xsl:choose>
                            <xsl:when test="photo">photos/<xsl:value-of select="photo/@id" /></xsl:when>
                            <xsl:when test="journal">journals/<xsl:value-of select="journal/@id" /></xsl:when>
                            <xsl:when test="poll">poll/<xsl:value-of select="poll/@id" /></xsl:when>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:if test="photo">
                        <xsl:attribute name="class">photo</xsl:attribute>
                        <img>
                            <xsl:if test="photo/title">
                                <xsl:attribute name="alt"><xsl:value-of select="photo/title" /></xsl:attribute>
                                <xsl:attribute name="title"><xsl:value-of select="photo/title" /></xsl:attribute>
                            </xsl:if>
                            <xsl:attribute name="src">http://images2.zino.gr/media/<xsl:value-of select="photo/author/@id" />/<xsl:value-of select="photo/@id" />/<xsl:value-of select="photo/@id" />_100.jpg</xsl:attribute>
                        </img>
                    </xsl:if>
                    <span class="head">
                        πρόσθεσε στα αγαπημένα <xsl:choose>
                            <xsl:when test="/social/user/gender = 'f'">της</xsl:when>
                            <xsl:otherwise>του</xsl:otherwise>
                        </xsl:choose>
                        <xsl:choose>
                            <xsl:when test="photo"> μια φωτογραφία <xsl:choose>
                                <xsl:when test="photo/author/gender = 'f'"> της </xsl:when>
                                <xsl:otherwise> του </xsl:otherwise>
                            </xsl:choose>
                            <xsl:value-of select="photo/author/name" />.</xsl:when>
                            <xsl:when test="journal"> το ημερολόγιο <xsl:value-of select="journal/title" />.</xsl:when>
                            <xsl:when test="poll"> τη δημοσκόπηση <xsl:value-of select="poll/question" />.</xsl:when>
                        </xsl:choose>
                    </span>
                </xsl:when>
                <xsl:when test="type = 'friend'">
                    <xsl:attribute name="href">users/<xsl:value-of select="friend/subdomain" /></xsl:attribute>
                    <span class="head">
                    έκανε 
                        <xsl:choose>
                            <xsl:when test="friend/gender = 'f'">φίλη την&#160;</xsl:when>
                            <xsl:otherwise>φίλο τον&#160;</xsl:otherwise>
                        </xsl:choose>
                        <xsl:value-of select="friend/name" /> 
                    </span>
                </xsl:when>
                <xsl:when test="type = 'fan'">
                    <xsl:attribute name="href">users/<xsl:value-of select="friend/subdomain" /></xsl:attribute>
                    <span class="head">έγινε 
                    <xsl:choose>
                        <xsl:when test="/social/user/gender = 'f'"> φίλη </xsl:when>
                        <xsl:otherwise> φίλος </xsl:otherwise>
                    </xsl:choose>
                    <xsl:choose>
                        <xsl:when test="friend/gender = 'f'"> της </xsl:when>
                        <xsl:otherwise> του </xsl:otherwise>
                    </xsl:choose>
                    <xsl:value-of select="friend/name" />
                    </span>
                </xsl:when>
                <xsl:when test="type = 'song'">
                    <span class="head">έβαλε στο προφίλ 
                        <xsl:choose>
                            <xsl:when test="/social/user/gender = 'f'"> της </xsl:when>
                            <xsl:otherwise> του </xsl:otherwise>
                        </xsl:choose>
                        το τραγούδι
                    </span>
                    <div class="body"><xsl:value-of select="song/title" /></div>
                </xsl:when>
                <xsl:when test="type = 'status'">
                    <span class="head">άλλαξε το status <xsl:choose>
                        <xsl:when test="/social/user/gender = 'f'">της</xsl:when>
                        <xsl:otherwise>του</xsl:otherwise>
                    </xsl:choose>
                    </span>
                    <div class="body">
                        <xsl:value-of select="status/message" />
                    </div>
                </xsl:when>
                <xsl:when test="type = 'item'">
                    <xsl:choose>
                        <xsl:when test="photo">
                            <xsl:attribute name="class">photo</xsl:attribute>
                            <xsl:attribute name="href">photos/<xsl:value-of select="photo/@id" /></xsl:attribute>
                            <img>
                                <xsl:if test="photo/title">
                                    <xsl:attribute name="alt"><xsl:value-of select="photo/title" /></xsl:attribute>
                                    <xsl:attribute name="title"><xsl:value-of select="photo/title" /></xsl:attribute>
                                </xsl:if>
                                <xsl:attribute name="src">http://images2.zino.gr/media/<xsl:value-of select="/social/user/@id" />/<xsl:value-of select="photo/@id" />/<xsl:value-of select="photo/@id" />_100.jpg</xsl:attribute>
                            </img>
                            <span class="head">ανέβασε μια φωτογραφία</span>
                        </xsl:when>
                        <xsl:when test="album">
                            <xsl:attribute name="href">photos/<xsl:value-of select="/social/user/name" /></xsl:attribute>
                            <span class="head">
                                δημιούργησε το άλμπουμ
                                <xsl:value-of select="album/name" />
                            </span>
                        </xsl:when>
                        <xsl:when test="poll">
                            <xsl:attribute name="href">polls/<xsl:value-of select="poll/@id" /></xsl:attribute>
                            <span class="head">
                                δημιούργησε τη δημοσκόπηση 
                                <xsl:value-of select="poll/question" />
                            </span>
                        </xsl:when>
                        <xsl:when test="journal">
                            <xsl:attribute name="href">journals/<xsl:value-of select="journal/@id" /></xsl:attribute>
                            <span class="head">
                                δημιούργησε το ημερολόγιο 
                                <xsl:value-of select="journal/title" />
                            </span>
                        </xsl:when>
                    </xsl:choose>
                 </xsl:when>
            </xsl:choose>
            <span class="time"><xsl:value-of select="date" /></span>
        </a>
    </li>
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user/details">
    <ul class="userdetails">
        <xsl:if test="height or weight or $user = ../name[1]">
            <li class="heightweight">
                <ul>
                    <xsl:if test="height or $user = ../name[1]">
                        <li class="height">Ύψος:
                            <span>
                                <span><xsl:choose>
                                    <xsl:when test="height &gt; 0">
                                        <xsl:value-of select="height div 100" />m
                                    </xsl:when>
                                    <xsl:when test="height = -2">
                                        Κάτω από 1.20m
                                    </xsl:when>
                                    <xsl:when test="height = -1">
                                        Πάνω από 2.20m
                                    </xsl:when>
                                </xsl:choose></span>
                                <xsl:if test="$user = ../name[1]">
                                    <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="height" /></xsl:attribute>
                                        <option><xsl:attribute name="value"><xsl:value-of select="height" /></xsl:attribute></option>
                                    </select>
                                </xsl:if>
                            </span>
                        </li>
                    </xsl:if>
                    <xsl:if test="weight or $user = ../name[1]">
                        <li>
                            <xsl:attribute name="class">weight <xsl:if test="( height and weight ) or $user = ../name[1]">dot</xsl:if></xsl:attribute>
                            Βάρος:
                            <span>
                                <span><xsl:choose>
                                    <xsl:when test="weight &gt; 0">
                                        <xsl:value-of select="weight" />kg
                                    </xsl:when>
                                </xsl:choose></span>
                                <xsl:if test="$user = ../name[1]">
                                    <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="weight" /></xsl:attribute>
                                        <option><xsl:attribute name="value"><xsl:value-of select="weight" /></xsl:attribute></option>
                                    </select>
                                </xsl:if>
                            </span>
                        </li>
                    </xsl:if>
                </ul>
            </li>
        </xsl:if>
        <xsl:if test="smoker or $user = ../name[1]">
            <li class="smoker">
                Καπνίζει:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">smoker</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="smoker" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="smoker" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="smoker" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="drinker or $user = ../name[1]">
            <li class="drinker">
                Πίνει:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">drinker</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="drinker" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="drinker" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="drinker" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="relationship or $user = ../name[1]">
            <li class="relationship">
                Σχέση:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">relationship</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="relationship" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="relationship" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="relationship" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="politics or $user = ../name[1]">
            <li class="politics">
                Πολιτικές πεποιθήσεις:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">politics</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="politics" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="politics" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="politics" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="religion or $user = ../name[1]">
            <li class="religion">
                Θρήσκευμα:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">religion</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="religion" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="religion" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="religion" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="sexualorientation or $user = ../name[1]">
            <li class="sexualorientation">
                Σεξουαλικές προτιμήσεις:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">sexualorientation</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="sexualorientation" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="sexualorientation" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="sexualorientation" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="eyecolor or $user = ../name[1]">
            <li class="eyecolor">
                Χρώμα ματιών:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">eyecolor</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="eyecolor" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="eyecolor" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="eyecolor" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="haircolor or $user = ../name[1]">
            <li class="haircolor">
                Χρώμα μαλλιών:
                <span>
                    <span><xsl:call-template name="detailstrings">
                        <xsl:with-param name="field">haircolor</xsl:with-param>
                        <xsl:with-param name="gender" select="../gender" />
                        <xsl:with-param name="value" select="haircolor" />
                    </xsl:call-template></span>
                    <xsl:if test="$user = ../name[1]">
                        <select class="dropdown"><xsl:attribute name="value"><xsl:value-of select="haircolor" /></xsl:attribute>
                            <option><xsl:attribute name="value"><xsl:value-of select="haircolor" /></xsl:attribute></option>
                        </select>
                    </xsl:if>
                </span>
            </li>
        </xsl:if>
        <xsl:if test="aboutme or $user = ../name[1]">
            <li class="aboutme">
                <div>Λίγα λόγια για μένα:</div>
                <span>
                    <xsl:choose>
                        <xsl:when test="not( aboutme ) and $user = ../name[1]">
                            <xsl:attribute name="class">aboutme notshown</xsl:attribute>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="aboutme" />
                        </xsl:otherwise>
                    </xsl:choose>
                </span>
            </li>
        </xsl:if>
    </ul>
</xsl:template>

<xsl:template name="interest">
    <xsl:param name="type" />
    <xsl:param name="data" />
    <li>
        <xsl:attribute name="class"><xsl:value-of select="$type" /></xsl:attribute>
        <div>
            <xsl:choose>
                <xsl:when test="$type = 'hobbies'">Hobbies</xsl:when>
                <xsl:when test="$type = 'movies'">Αγαπημένες ταινίες</xsl:when>
                <xsl:when test="$type = 'shows'">Αγαπημένες σειρές</xsl:when>
                <xsl:when test="$type = 'books'">Αγαπημένα βιβλία</xsl:when>
                <xsl:when test="$type = 'games'">Αγαπημένα παιχνίδια</xsl:when>
                <xsl:when test="$type = 'artists'">Αγαπημένοι καλλιτέχνες</xsl:when>
				<xsl:when test="$type = 'songs'">Αγαπημένα τραγούδια</xsl:when>
            </xsl:choose>
            <xsl:if test="/social/@for = /social/user/name">
                <span class="add">&#43;</span>
            </xsl:if>
        </div>
        <ul class="interestitems">
            <xsl:for-each select="$data/tag">
                <li>
                    <xsl:attribute name="id">tag_<xsl:value-of select="./@id" /></xsl:attribute>
                    <xsl:if test="./@id = ../tag[last()]/@id">
                        <xsl:attribute name="class">last</xsl:attribute>
                    </xsl:if>
                    <xsl:value-of select="." />
                    <xsl:if test="/social/@for = /social/user/name">
                        <span class="delete">&#215;</span>
                    </xsl:if>
                </li>&#160;
            </xsl:for-each>
        </ul>
        <div class="eof"></div>
    </li>
</xsl:template>
