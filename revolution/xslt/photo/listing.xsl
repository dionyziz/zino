<xsl:template match="/social[@resource='photo' and @method='listing']">
    <xsl:call-template name="zoomout" />
</xsl:template>

<xsl:template match="/social[@resource='photo' and @method='listing']/user">
    <xsl:for-each select="album">
        <xsl:choose>
            <xsl:when test="@deleted = 'yes'">
                Το album έχει διαγραφεί
            </xsl:when>
            <xsl:otherwise>
                <xsl:for-each select="photos">
                    <xsl:call-template name="photolist">
                        <xsl:with-param name="owner"><xsl:value-of select="../../name" /></xsl:with-param>
                    </xsl:call-template>
                </xsl:for-each>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:for-each>
    <xsl:for-each select="photos">
        <div class="useralbums">
            <xsl:choose>
                <xsl:when test="../gender = 'm'">
                    Άλμπουμ του
                </xsl:when>
                <xsl:when test="../gender = 'f'">
                    Άλμπουμ της
                </xsl:when>
                <xsl:otherwise>
                    Άλμπουμ του/της
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="../name" />
        </div>
        <xsl:call-template name="photolist" />
    </xsl:for-each>
</xsl:template>

<xsl:template match="/social[@resource='photo' and @method='listing']/photos">
    <xsl:call-template name="photolist" />
</xsl:template>

<xsl:template name="photolist">
    <xsl:param name="owner" />
    <div class="photostream">
        <ul>
            <xsl:if test="/social/@for and (($owner=/social/@for) or not($owner))">
                <li>
                    <form method="post" action="photo/create" enctype="multipart/form-data">
                        <div>
                            <label class="cabinet">
                                <input type="file" name="uploadimage" class="file" />
                            </label>
                            <span class="tooltip"><span>&#9650;</span>ανέβασε εικόνα</span>
                        </div>
                    </form>
                </li>
            </xsl:if>
            <xsl:for-each select="photo">
                <li>
                    <a>
                        <xsl:attribute name="href">photos/<xsl:value-of select="@id" /></xsl:attribute>
                        <img>
                            <xsl:attribute name="src">
                                <xsl:value-of select="media[1]/@url" />
                            </xsl:attribute>
                        </img>
                        <xsl:if test="discussion[1]/@count &gt; 0">
                            <span class="countbubble">
                                <xsl:if test="discussion[1]/@count &gt; 99">
                                    &#8734;
                                </xsl:if>
                                <xsl:if test="discussion[1]/@count &lt; 100">
                                    <xsl:value-of select="discussion[1]/@count" />
                                </xsl:if>
                            </span>
                        </xsl:if>
                    </a>
                </li>
            </xsl:for-each>
        </ul>
    </div>
</xsl:template>