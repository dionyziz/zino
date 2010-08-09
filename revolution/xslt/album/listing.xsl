<xsl:template match="/social[@resource='album' and @method='listing']">
    <ol id="albumlist">
        <xsl:apply-templates />
    </ol>
</xsl:template>

<xsl:template match="/social[@resource='album' and @method='listing']/albums">
    <xsl:for-each select="album">
        <li>
            <xsl:if test="./@egoalbum='yes'">
                <xsl:attribute name="class">
                    <xsl:text>egoalbum</xsl:text>
                </xsl:attribute>
            </xsl:if>
            <a>
                <xsl:attribute name="href">
                    <xsl:text>albums/</xsl:text>
                    <xsl:value-of select="@id" />
                </xsl:attribute>
                <img>
                    <xsl:attribute name="src">
                        <xsl:choose>
                            <xsl:when test="photos/photo/@main='yes'">
                                <xsl:value-of select="photos/photo[@main='yes']/media/@url" />
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>http://static.zino.gr/phoenix/anonymous150.jpg</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                </img>
            </a>
            <p>
                <xsl:choose>
                    <xsl:when test="./@egoalbum='yes'">
                        <xsl:text>Εγώ</xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select=".//name" />
                    </xsl:otherwise>
                </xsl:choose>
            </p>
        </li>
    </xsl:for-each>
</xsl:template>
