<xsl:template match="/social[@resource='album' and @method='listing']">
    <ol id="albumlist">
        <xsl:apply-templates />
    </ol>
</xsl:template>

<xsl:template match="/social[@resource='album' and @method='listing']/albums">
    <xsl:for-each select="album">
        <li>
            <a>
                <xsl:attribute name="href">
                    <xsl:text>albums/</xsl:text>
                    <xsl:value-of select="@id" />
                </xsl:attribute>
                <xsl:if test="./@egoalbum='yes'">
                    <xsl:attribute name="class">
                        <xsl:text>egoalbum</xsl:text>
                    </xsl:attribute>
                </xsl:if>
                <img>
                    <xsl:attribute name="src">
                        <xsl:if test="photos/photo/@main='yes'">
                            <xsl:value-of select="photos/photo[@main='yes']/media/@url" />
                        </xsl:if>
                        <xsl:if test="not(photos/photo/@main='yes')">
                            <xsl:text>http://static.zino.gr/phoenix/anonymous150.jpg</xsl:text>
                        </xsl:if>
                    </xsl:attribute>
                </img>
            </a>
            <p>
                <xsl:choose>
                    <xsl:when test="./@egoalbum='yes'">
                        <xsl:text>Εγώ</xsl:text>
                    <xsl:when test="not(./@egoalbum='yes'">
                    <xsl:otherwise>
                        <xsl:value-of select="name" />
                    </xsl:otherwise>
                </xsl:choose>
            </p>
        </li>
    </xsl:for-each>
</xsl:template>
