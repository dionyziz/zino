<xsl:template match="/social[@resource='dashboard' and @method='view']">
    <div style="width: 400px;">
        <h3>Τελευταία Σχόλια</h3>
        <ul class="activities">
            <xsl:apply-templates select="activities/activity" />
        </ul>
    </div>
</xsl:template>

<xsl:template match="/social[@resource='dashboard']//activity">
    <li>
        <a class="itemlink">
            <xsl:choose>
                <xsl:when test="type = 'comment'">
                    <xsl:choose>
                        <xsl:when test="comment/journal">
                            <xsl:attribute name="href">journals/<xsl:value-of select="comment/journal/@id" /></xsl:attribute>
                            <span class="head">
                                <xsl:call-template name="dashboard.commentauthor" />
                                σχολίασε στο ημερολόγιο <xsl:value-of select="comment/journal/title" />:
                            </span>
                        </xsl:when>
                        <xsl:when test="comment/poll">
                            <xsl:attribute name="href">polls/<xsl:value-of select="comment/poll/@id" /></xsl:attribute>
                            <span class="head">
                                <xsl:call-template name="dashboard.commentauthor" />
                                σχολίασε στη δημοσκόπηση <xsl:value-of select="comment/poll/question" />:
                            </span>
                        </xsl:when>
                        <xsl:when test="comment/profile">
                            <xsl:attribute name="href">users/<xsl:value-of select="comment/profile/name" /></xsl:attribute>
                            <span class="head">
                                <xsl:call-template name="dashboard.commentauthor" />
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
                            <xsl:attribute name="class">itemlink photo</xsl:attribute>
                            <img>
                                <xsl:if test="comment/photo/title">
                                    <xsl:attribute name="alt"><xsl:value-of select="comment/photo/title" /></xsl:attribute>
                                    <xsl:attribute name="title"><xsl:value-of select="comment/photo/title" /></xsl:attribute>
                                </xsl:if>
                                <xsl:attribute name="src">http://images2.zino.gr/media/<xsl:value-of select="comment/photo/author/@id" />/<xsl:value-of select="comment/photo/@id" />/<xsl:value-of select="comment/photo/@id" />_100.jpg</xsl:attribute>
                            </img>
                            <span class="head">
                                <xsl:call-template name="dashboard.commentauthor" />
                                σχολίασε στη φωτογραφία 
                                <xsl:choose>
                                    <xsl:when test="comment/photo/author/gender = 'f'">της </xsl:when>
                                    <xsl:otherwise>του </xsl:otherwise>
                                </xsl:choose>
                                <xsl:if test="user/@id != comment/photo/author/@id">
                                    <xsl:value-of select="comment/photo/author/name" />
                                </xsl:if>
                            </span>
                        </xsl:when>
                    </xsl:choose>
                    <div class="body novideo"><xsl:copy-of select="comment/text/*|comment/text/text()" /></div>
                </xsl:when>
            </xsl:choose>
            <span class="time"><xsl:value-of select="date" /></span>
        </a>
    </li>
</xsl:template>

<xsl:template name="dashboard.commentauthor">
    <xsl:choose>
        <xsl:when test="user/gender = 'f'">Η&#160;</xsl:when>
        <xsl:otherwise>Ο&#160;</xsl:otherwise>
    </xsl:choose>
    <xsl:value-of select="user/name" />
</xsl:template>