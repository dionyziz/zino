<xsl:template match="/social[@resource='user' and @method='view']">
    <xsl:apply-templates select="user"/>
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user">
    <div class="contentitem">
        <xsl:attribute name="id">
            user_<xsl:value-of select="@id" />
        </xsl:attribute>
        <a class="xbutton" href="photos">&#171;</a>
        <div class="maininfo">
            <xsl:if test="avatar[1]">
                <img class="avatar">
                    <xsl:attribute name="src">
                        <xsl:value-of select="avatar[1]/media[1]/@url" />
                    </xsl:attribute>
                </img>
            </xsl:if>
            <span class="username"><xsl:value-of select="name[1]" /></span>
            <ul>
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
            <xsl:if test="slogan[1]">
                <span class="slogan"><xsl:value-of select="slogan[1]" /></span>
            </xsl:if>
        </div>
        <xsl:if test="/social/@for and /social/@for!=name[1]">
            <form action="friendship/create" method="post">
                <input type="hidden" name="friendid">
                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                </input>
                <input type="submit" value="Προσθήκη φίλου" />
            </form>
            <form action="friendship/delete" method="post">
                <input type="hidden" name="friendid">
                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                </input>
                <input type="submit" value="Διαγραφή φίλου" />
            </form>
        </xsl:if>
        <xsl:apply-templates select="details" />
        <ul class="useritems">
            <xsl:if test="stream[@type='photo']/@count &gt; 0">
                <li><a>
                    <xsl:attribute name="href">
                        photos/<xsl:value-of select="name[1]" />
                    </xsl:attribute>
                    Φωτογραφίες (<xsl:value-of select="stream[@type='photo']/@count" />)
                </a></li>
            </xsl:if>
            <xsl:if test="stream[@type='journal']/@count &gt; 0">
                <li><a>
                    <xsl:attribute name="href">
                        journals/<xsl:value-of select="name[1]" />
                    </xsl:attribute>
                    Ημερολόγια (<xsl:value-of select="stream[@type='journal']/@count" />)
                </a></li>
            </xsl:if>
            <xsl:if test="stream[@type='poll']/@count &gt; 0">
                <li><a>
                    <xsl:attribute name="href">
                        polls/<xsl:value-of select="name[1]" />
                    </xsl:attribute>
                    Δημοσκοπίσεις (<xsl:value-of select="stream[@type='poll']/@count" />)
                </a></li>
            </xsl:if>
            <xsl:if test="friends/@count &gt; 0">
                <li><a>
                    <xsl:attribute name="href">
                        friends/<xsl:value-of select="name[1]" />
                    </xsl:attribute>
                    Φίλοι (<xsl:value-of select="friends/@count" />)
                </a></li>
            </xsl:if>
            <xsl:if test="favourites/@count &gt; 0">
                <li><a>
                    <xsl:attribute name="href">
                        favourites/<xsl:value-of select="name[1]" />
                    </xsl:attribute>
                    Αγαπημένα (<xsl:value-of select="favourites/@count" />)
                </a></li>
            </xsl:if>
        </ul>
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user/details">
    <ul class="userdetails">
        <xsl:if test="height">
            <li>
                <span>Ύψος:</span>
                <xsl:value-of select="height" />
            </li>
        </xsl:if>
        <xsl:if test="weight">
            <li>
                <span>Βάρος:</span>
                <xsl:value-of select="weight" />
            </li>
        </xsl:if>
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
        <xsl:if test="slogan">
            <li>
                <span>Σλόγκαν:</span>
                <xsl:value-of select="slogan" />
            </li>
        </xsl:if>
        <xsl:if test="aboutme">
            <li>
                <span>Λίγα λόγια για μένα:</span>
                <xsl:value-of select="aboutme" />
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
    </ul>
</xsl:template>
