<xsl:template match="/social[@resource='favourite' and @method='listing']">
    <div class="photostream">
        <ul class="favourites">
            <xsl:for-each select="stream/entry">
                <li>
                    <a href="">
                    <xsl:if test="@type = 'photo'">
                        <img>
                            <xsl:attribute name="src"><xsl:value-of select="media/@url" /></xsl:attribute>
                        </img>
                    </xsl:if>
                    </a>
                    <xsl:if test="@type = 'poll'">
                        <div class="box">
                            <span><xsl:value-of select="title" /></span>
                        </div>
                    </xsl:if>
                    <xsl:if test="@type = 'journal'">
                        <div class="box">
                            <span><xsl:value-of select="title" /></span>
                        </div>
                    </xsl:if>
                </li>
            </xsl:for-each>
        </ul>
    </div>
</xsl:template>
