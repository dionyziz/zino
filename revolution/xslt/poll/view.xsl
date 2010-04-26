<xsl:template match="/social[@resource='poll' and @method='view']">
    <xsl:call-template name="zoomin" />
</xsl:template>

<xsl:template match="/social[@resource='poll' and @method='view']//entry">
    <a class="xbutton" href="news">&#171;<span><span>&#9650;</span>πίσω στα νέα</span></a>
    <div class="contentitem">
        <xsl:attribute name="id">poll_<xsl:value-of select="/social/entry[1]/@id" /></xsl:attribute>
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
        <h2><xsl:value-of select="title[1]" /></h2>
        <xsl:choose>
            <xsl:when test="not( options[1]/*/@voted ) and /social/@for">
                <ul class="options">
                    <xsl:for-each select="options[1]/option">
                        <li>
                            <strong>
                                <input type="radio" name="polloption">
                                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                                </input>
                                <label for=""><xsl:value-of select="title" /></label>
                            </strong>
                        </li>
                    </xsl:for-each>
                </ul>
            </xsl:when>
            <xsl:otherwise>
                <ul class="options">
                    <xsl:for-each select="options[1]/option">
                        <li>
                            <strong><xsl:value-of select="ceiling( 100 * @votes div ../@totalvotes )" />%</strong>
                             - 
                            <xsl:value-of select="title" />
                            <div class="progressbar">
                                <div class="progress">
                                    <xsl:attribute name="style">
                                        width:<xsl:value-of select="25 + ceiling(275 * @votes div ../@totalvotes)" />px
                                    </xsl:attribute>
                                </div>
                            </div>
                        </li>
                    </xsl:for-each>
                </ul>
            </xsl:otherwise>
        </xsl:choose>
        <div class="note">
            <xsl:for-each select="favourites/user">
                <div class="love">&#9829; <span class="username"><xsl:value-of select="name[1]" /> </span> </div>
            </xsl:for-each>
            <a class="love button" href="" style="display:none"><strong>&#9829;</strong> Το αγαπώ!</a>
        </div>
    </div>
    <xsl:apply-templates select="discussion" />
    <script type="text/javascript">
    Startup( function () {
        ItemView.Init( 1 );
    } );
    </script>
</xsl:template>
