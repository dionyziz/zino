<xsl:template match="/social[@resource='journal' and @method='view']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='journal' and @method='view']//journal">
    <div class="contentitem">
        <xsl:attribute name="id">journal_<xsl:value-of select="/social/journal/@id" /></xsl:attribute>
        <xsl:if test="author">
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
        </xsl:if>
        <xsl:if test="$user = author/name[1]">
            <span class="icon" id="deletebutton">&#215;</span>
        </xsl:if>
        <h2><xsl:value-of select="title[1]" /></h2>
        <div class="document">
            <xsl:copy-of select="text/*|text/text()" />
        </div>
        <div class="note">
            <xsl:for-each select="favourites/user">
                <div class="love">
                &#9829; <span class="username"><xsl:value-of select="name[1]" /> </span> </div>
            </xsl:for-each>
            <a class="love linkbutton" href="" style="display:none">
                <xsl:attribute name="id">love_<xsl:value-of select="/social/journal/@id" /></xsl:attribute>
                <strong>&#9829;</strong> Το αγαπώ!
            </a>
        </div>
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>
