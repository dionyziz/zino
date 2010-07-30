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
                    <xsl:if test="@type = 'poll'">
                        <span class="box">
                            <span><xsl:value-of select="title" /></span>
                        </span>
                    </xsl:if>
                    <xsl:if test="@type = 'journal'">
                        <span class="box">
                            <span><xsl:value-of select="title" /></span>
                        </span>
                    </xsl:if>
                    </a>
                </li>
            </xsl:for-each>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
            <li style="height:2px;visibility:hidden"></li>
        </ul>
    </div>
</xsl:template>
