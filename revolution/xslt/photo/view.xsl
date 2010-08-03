<xsl:template match="/social[@resource='photo' and @method='view']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='photo' and @method='view']//photo">
    <a class="xbutton" href="photos">&#171;<span class="tooltip"><span>&#9650;</span>πίσω στις εικόνες</span></a>
    <div class="contentitem">
        <xsl:attribute name="id">photo_<xsl:value-of select="/social/photo/@id" /></xsl:attribute>
        <xsl:if test="not( @deleted )">
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
            <div class="image">
                <xsl:attribute name="style">width: <xsl:value-of select="media[1]/@width" />px;</xsl:attribute>
                <img class="maincontent">
                    <xsl:attribute name="src"><xsl:value-of select="media[1]/@url" /></xsl:attribute>
                    <xsl:attribute name="width"><xsl:value-of select="media[1]/@width" /></xsl:attribute>
                    <xsl:attribute name="height"><xsl:value-of select="media[1]/@height" /></xsl:attribute>
                </img>
                <xsl:if test="/social/@for = author[1]/name[1]">
                    <div class="icon" id="deletebutton">&#215;</div>     
                </xsl:if>
            </div>
        </xsl:if>
        <div class="title">
            <xsl:if test="/social/@for = author[1]/name[1]">
                <input type="text">
                    <xsl:attribute name="value"><xsl:value-of select="title[1]" /></xsl:attribute>
                    <xsl:if test="not(title)">
                        <xsl:attribute name="value">Γράψε τίτλο για τη φωτογραφία</xsl:attribute>
                        <xsl:attribute name="class">empty</xsl:attribute>
                    </xsl:if>
                </input>
            </xsl:if>
            <xsl:if test="@deleted">Η φωτογραφία έχει διαγραφεί.</xsl:if>
            <span>
                <xsl:if test="/social/@for = author[1]/name[1] or not(title[1])">
                    <xsl:attribute name="class">hidden</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="title[1]" />
            </span>
        </div>
        <xsl:if test="favourites or not(/social/@for = author[1]/name[1])">
            <div class="note">
                <xsl:for-each select="favourites/user">
                    <div class="love">&#9829; <span class="username"><xsl:value-of select="name[1]" /> </span> </div>
                    <xsl:text> </xsl:text>
                </xsl:for-each>
                <a class="love linkbutton" href="" style="display:none"><strong>&#9829;</strong> Το αγαπώ!</a>
            </div>
        </xsl:if>
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>
