<xsl:template match="/social[@resource='notification' and @method='listing']">
    <div id="notifications" class="panel bottom novideo">
        <div class="background"></div>
        <div class="xbutton"></div>
        <h3>
            <xsl:text>Ενημερώσεις (</xsl:text>
            <xsl:value-of select="stream/@count" />
            <xsl:text>)</xsl:text>
        </h3>
        <xsl:apply-templates select="stream/entry" />
    </div>
</xsl:template>

<xsl:template match="/social[@resource='notification' and @method='listing']/stream/entry">
    <div class="box">
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:value-of select=".//media/@url" />
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select=".//name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4>
                <xsl:value-of select=".//name" />
            </h4>
            <div class="background"></div>
            <div class="text">
                <xsl:choose>
                    <xsl:when test="discussion/comment/comment">
                        <xsl:value-of select="discussion/comment/comment/text" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="discussion/comment/text" />
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </div>
    </div>
</xsl:template>
