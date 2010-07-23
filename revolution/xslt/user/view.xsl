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
                <xsl:if test="avatar[1]">
                    <img class="avatar">
                        <xsl:attribute name="src">
                            <xsl:value-of select="avatar[1]/media[1]/@url" />
                        </xsl:attribute>
                    </img>
                </xsl:if>
                <div class="details">
                    <div class="username"><xsl:value-of select="name[1]" /></div>
                    <ul class="asl">
                        <xsl:if test="gender[1]">
                            <li>
                                <span class="gender">
                                    <xsl:choose>
                                        <xsl:when test="gender[1] = 'f'">Κορίτσι</xsl:when>
                                        <xsl:otherwise>Αγόρι</xsl:otherwise>
                                    </xsl:choose>
                                </span>
                            </li>
                        </xsl:if>
                        <xsl:if test="age[1]">
                            <li>
                                <xsl:if test="gender[1]">
                                    <xsl:attribute name="class">dot</xsl:attribute>
                                </xsl:if>
                                <span class="age"><xsl:value-of select="age[1]" /></span>
                            </li>
                        </xsl:if>
                        <xsl:if test="location[1]">
                            <li>
                                <xsl:if test="gender or age">
                                    <xsl:attribute name="class">dot</xsl:attribute>
                                </xsl:if>
                                <span class="location"><xsl:value-of select="location[1]" /></span>
                            </li>
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
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:if>
                <xsl:apply-templates select="details" />
            </div>
            <div class="rightbar">
                <xsl:if test="mood">
                    <img>
                        <xsl:attribute name="src"><xsl:value-of select="mood/media[1]/@url" /></xsl:attribute>
                        <xsl:attribute name="alt"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                        <xsl:attribute name="title"><xsl:value-of select="mood/label[1]" /></xsl:attribute>
                    </img>
                </xsl:if>
                <xsl:apply-templates select="song" />
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
        <xsl:if test="smoker">
            <li>
                <span>Καπνίζει:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">smoker</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="smoker" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="drinker">
            <li>
                <span>Πίνει:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">drinker</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="drinker" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="relationship">
            <li>
                <span>Σχέση:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">relationship</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="relationship" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="politics">
            <li>
                <span>Πολιτικές πεποιθήσεις:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">politics</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="politics" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="religion">
            <li>
                <span>Θρήσκευμα:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">religion</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="religion" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="sexualorientation">
            <li>
                <span>Σεξουαλικές προτιμήσεις:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">sexualorientation</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="sexualorientation" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="eyecolor">
            <li>
                <span>Χρώμμα ματιών:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">eyecolor</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="eyecolor" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="haircolor">
            <li>
                <span>Χρώμμα μαλλιών:</span>
                <xsl:call-template name="detailstrings">
                    <xsl:with-param name="type">haircolor</xsl:with-param>
                    <xsl:with-param name="gender" select="../gender" />
                    <xsl:with-param name="value" select="haircolor" />
                </xsl:call-template>
            </li>
        </xsl:if>
        <xsl:if test="aboutme">
            <li>
                <div>Λίγα λόγια για μένα:</div>
                <xsl:value-of select="aboutme" />
            </li>
        </xsl:if>
    </ul>
</xsl:template>
