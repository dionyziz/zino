<xsl:template match="/social[@resource='user' and @method='view']">
    <xsl:apply-templates select="user"/>
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user">
    <div class="contentitem">
        <xsl:attribute name="id">user_<xsl:value-of select="@id" /></xsl:attribute>
        <a class="xbutton" href="photos">&#171;</a>
        <div class="userview">
            <ul class="useritems">
                <xsl:if test="stream[@type='photo']/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            photos/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="stream[@type='photo']/@count" /></span>
                        Φωτογραφίες
                    </a></li>
                </xsl:if>
                <xsl:if test="stream[@type='journal']/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            journals/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="stream[@type='journal']/@count" /></span>
                        Ημερολόγια
                    </a></li>
                </xsl:if>
                <xsl:if test="stream[@type='poll']/@count &gt; 0">
                    <li><a>
                        <xsl:attribute name="href">
                            polls/<xsl:value-of select="name[1]" />
                        </xsl:attribute>
                        <span><xsl:value-of select="stream[@type='poll']/@count" /></span>
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
                <img class="avatar">
                    <xsl:choose>
                        <xsl:when test="avatar[1]/@id = 0">
                            <xsl:attribute name="src">http://static.zino.gr/phoenix/anonymous100.jpg</xsl:attribute>asd
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:attribute name="src"><xsl:value-of select="avatar[1]/media[1]/@url" /></xsl:attribute>das
                        </xsl:otherwise>
                    </xsl:choose>
                </img>
                <div class="details">
                    <div class="username"><xsl:value-of select="name[1]" /></div>
                    <ul class="asl">
                        <xsl:if test="gender[1] or $user = name[1]">
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
                        <xsl:if test="age[1]">
                            <li class="age">
                                <xsl:if test="gender[1] or $user = name[1]">
                                    <xsl:attribute name="class">dot</xsl:attribute>
                                </xsl:if>
                                <span class="age"><xsl:value-of select="age[1]" /></span>
                            </li>
                        </xsl:if>
                        <xsl:if test="location[1] or $user = name[1]">
                            <li>
                                <xsl:attribute name="class">
                                    location
                                    <xsl:if test="gender or age or $user = name[1]">
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
                <div class="eof"></div>
                <xsl:if test="$user = name[1]">
                    <div class="pantherbox" id="accountmenu" style="clear:both">
                        <div class="arrow">&#9650;</div>
                        <ul style="float: right">
                            <li style="float: left; padding-left: 5px;" class="dot"><a href="">Λογαριασμός</a></li>
                            <li style="float: left; padding-left: 5px;"><a href="">Έξοδος</a></li>
                        </ul>
                        Εγώ
                    </div>
                </xsl:if>
            </div>
            <div class="eof"></div>
            <div class="sidebar">
                <xsl:if test="$user and $user != name[1]">
                    <xsl:choose>
                        <xsl:when test="knownBy = $user">
                            <form action="friendship/delete" method="post" id="friendship">
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
                            <form action="friendship/create" method="post" id="friendship">
                                <input type="hidden" name="friendid">
                                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                                </input>
                                <a class="love linkbutton" href=""><strong>+</strong> Προσθήκη φίλου</a>
                            </form>
                            <div class="eof"></div>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:if>
                <xsl:apply-templates select="details" />

                <xsl:apply-templates select="song" />
                
                <ul class="userinterests">
                    <xsl:for-each select="taglist">
                        <li>
                            <xsl:attribute name="class"><xsl:value-of select="./@type" /></xsl:attribute>
                            <div>
                                <xsl:choose>
                                    <xsl:when test="./@type = 'hobbies'">Hobbies</xsl:when>
                                    <xsl:when test="./@type = 'movies'">Αγαπημένες ταινίες</xsl:when>
                                    <xsl:when test="./@type = 'shows'">Αγαπημένες σειρές</xsl:when>
                                    <xsl:when test="./@type = 'books'">Αγαπημένα βιβλία</xsl:when>
                                    <xsl:when test="./@type = 'games'">Αγαπημένα παιχνίδια</xsl:when>
                                    <xsl:when test="./@type = 'artists'">Αγαπημένοι καλλιτέχνες</xsl:when>
                                </xsl:choose>
                            </div>
                            <ul class="interestitems">
                                <xsl:for-each select="./tag">
                                    <li>
                                        <xsl:attribute name="id">tag_<xsl:value-of select="./@id" /></xsl:attribute>
                                        <xsl:if test="/social/@for = /social/user/name">
                                            <xsl:attribute name="class">edditable</xsl:attribute>
                                        </xsl:if>
                                        <span class="value">
                                            <xsl:value-of select="." />
                                        </span>
                                        <xsl:if test="./@id != ../tag[last()]/@id"><span class="comma">,</span></xsl:if>
                                    </li>&#160;
                                </xsl:for-each>
                            </ul>
                        </li>
                    </xsl:for-each>
                </ul>

            </div>
            <div class="rightbar">
                <xsl:if test="mood or $user = name[1]">
                    <div class="mood">
                        <xsl:choose>
                            <xsl:when test="not( mood ) and $user = name[1]">
                                <div alt="Δεν έχει οριστεί διάθεση" title="Δεν έχει οριστεί διάθεση" class="moodtile nomood activemood"></div>
                            </xsl:when>
                            <xsl:otherwise>
                                <div class="moodtile activemood">
                                    <xsl:attribute name="style">background-image:url(<xsl:value-of select="mood/media[1]/@url" />)</xsl:attribute>
                                    <xsl:attribute name="alt"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                                    <xsl:attribute name="title"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                                    <xsl:attribute name="id">mood_active_<xsl:value-of select="mood/@id" /></xsl:attribute>
                                </div>
                            </xsl:otherwise>
                        </xsl:choose>
                    </div>
                </xsl:if>
            </div>
        </div>
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user/details">
    <ul class="userdetails">
        <li class="heightweight">
            <xsl:if test="height">
                <span>
                    <xsl:value-of select="height div 100" />
                </span>
                m
            </xsl:if>
            <xsl:if test="weight">
                <span>
                    <xsl:if test="height and weight">
                        <xsl:attribute name="class">dot</xsl:attribute>
                    </xsl:if>
                    <xsl:value-of select="weight" />
                </span>
                kg
            </xsl:if>
        </li>
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
                Χρώμμα ματιών:
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
                Χρώμμα μαλλιών:
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
                <span><xsl:value-of select="aboutme" /></span>
            </li>
        </xsl:if>
    </ul>
</xsl:template>
