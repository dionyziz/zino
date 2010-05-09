<xsl:template match="/social[@resource='user' and @method='view']">
    <xsl:apply-templates select="user"/>
</xsl:template>

<xsl:template match="/social[@resource='user' and @method='view']/user">
    <div class="contentitem">
        <xsl:attribute name="id">
            user_<xsl:value-of select="@id" />
        </xsl:attribute>
        <a class="xbutton" href="photos">&#171;</a>
        <h2><xsl:value-of select="name[1]" /></h2>
        <xsl:if test="slogan[1]">
            <h3><xsl:value-of select="slogan[1]" /></h3>
        </xsl:if>
        <a class="avatar">
            <xsl:attribute name="href">photo/<xsl:value-of select="avatar[1]/@id" /></xsl:attribute>
            <img class="avatar">
                <xsl:attribute name="src">
                    <xsl:value-of select="avatar[1]/media[1]/@url" />
                </xsl:attribute>
            </img>
        </a>
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