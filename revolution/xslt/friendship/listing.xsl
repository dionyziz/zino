<xsl:template match="/social[@resource='friendship' and @method='listing']">
    <a class="xbutton">
        <xsl:attribute name="href">users/<xsl:value-of select="friends/@of" /></xsl:attribute>&#171;
       <span class="tooltip"><span>&#9650;</span>
        pisw sto profil
       </span>
    </a>
    <ul class="friends">
    <xsl:for-each select="friends/entry">
        <li class="friend">
            <a class="username">
            <xsl:attribute name="href">users/<xsl:value-of select="name" /></xsl:attribute>
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
            <div class="username"><xsl:value-of select="name[1]" /></div>
            </a>
            <div class="details">
                <ul class="asl">
                    <xsl:if test="gender[1]">
                        <li class="gender">
                            <span>
                                <xsl:call-template name="detailstrings">
                                    <xsl:with-param name="field">gender</xsl:with-param>
                                    <xsl:with-param name="value" select="gender" />
                                    <xsl:with-param name="gender" select="gender" />
                                </xsl:call-template>
                            </span>
                        </li>
                    </xsl:if>
                    <xsl:if test="age[1]">
                        <li class="age">
                            <xsl:if test="gender[1]">
                                <xsl:attribute name="class">dot</xsl:attribute>
                            </xsl:if>
                            <span class="age"><xsl:value-of select="age[1]" /></span>
                        </li>
                    </xsl:if>
                    <xsl:if test="location[1]">
                        <li>
                            <xsl:attribute name="class">
                                location
                                <xsl:if test="gender or age">
                                    dot
                                </xsl:if>
                            </xsl:attribute>
                            <span><xsl:attribute name="id">location_<xsl:value-of select="location[1]/@id" /></xsl:attribute><xsl:value-of select="location[1]" /></span>
                        </li>
                    </xsl:if>
                </ul>
            </div>
        </li>
    </xsl:for-each>
    </ul>
</xsl:template>
