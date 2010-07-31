<xsl:template match="/social[@resource='favourite' and @method='listing']">
    <xsl:for-each select="stream">
        <div class="contentitem favourites">
            <div class="details">
                <ul>
                    <li>
                        <xsl:apply-templates select="author" />
                    </li>
                    <li><div class="time"><xsl:value-of select="published" /></div></li>
                    <xsl:if test="favourites[1]/@count &gt; 0">
                        <li class="stat numfavourites">&#9829; <span><xsl:value-of select="favourites[1]/@count" /></span></li>
                    </xsl:if>
                    <xsl:if test="discussion[1]/@count &gt; 0">
                        <li class="stat numcomments"><span><xsl:value-of select="discussion[1]/@count" /></span></li>
                    </xsl:if>
                </ul>
            </div>
            <h2>
                Αγαπημένα 
                <xsl:choose>
                    <xsl:when test="author/gender = 'f'">της</xsl:when>
                    <xsl:otherwise>του</xsl:otherwise>
                </xsl:choose>
                <xsl:text> </xsl:text>
                <xsl:value-of select="author/name" />
            </h2>
            <div class="itemstream">
                <ul>
                    <xsl:for-each select="entry">
                        <li>
                            <xsl:if test="@type = 'photo'">
                                <img>
                                    <xsl:attribute name="src"><xsl:value-of select="media/@url" /></xsl:attribute>
                                </img>
                            </xsl:if>
                            <h3>
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:if test="@type = 'photo'">photos/</xsl:if>
                                        <xsl:if test="@type = 'journal'">journals/</xsl:if>
                                        <xsl:if test="@type = 'poll'">polls/</xsl:if>
                                        <xsl:value-of select="@id" />
                                    </xsl:attribute>
                                    <xsl:value-of select="title" />
                                </a>
                            </h3>
                            <xsl:if test="@type = 'poll'">
                            </xsl:if>
                            <xsl:if test="@type = 'journal'">
                                <span class="preview"><xsl:value-of select="text" /></span>
                            </xsl:if>
                            <div class="eof"></div>
                        </li>
                    </xsl:for-each>
                </ul>
            </div>
        </div>
    </xsl:for-each>
</xsl:template>
