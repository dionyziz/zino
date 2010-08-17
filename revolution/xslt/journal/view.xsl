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
            <span class="icon" id="deletebutton" title="Διαγραφή ημερολογίου">&#215;</span>
        </xsl:if>
        <h2><xsl:value-of select="title[1]" /></h2>
        <div class="document">
            <xsl:copy-of select="text/*|text/text()" />
        </div>
        <xsl:if test="false() and $user = author/name">
            <ul class="journaleditmenu">
                <li><a class="edit linkbutton" href="">Επεξεργασία</a></li>
                <li><a class="save linkbutton" href="">Αποθήκευση</a></li>
                <li><a class="cancel linkbutton" href="">Ακύρωση</a></li>
            </ul>
        </xsl:if>
        <xsl:call-template name="favourite.list" />
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>
