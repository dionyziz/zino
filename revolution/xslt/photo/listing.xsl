<xsl:template match="/social[@resource='photo' and @method='listing']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="photos">
    <xsl:for-each select="album">
        <xsl:choose>
            <xsl:when test="@deleted = 'yes'">
                Το album έχει διαγραφεί
            </xsl:when>
            <xsl:otherwise>
                <xsl:for-each select="photos">
                    <xsl:call-template name="photolist" />
                </xsl:for-each>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:for-each>
    <xsl:if test="author">
        <div class="useralbums">
            <xsl:choose>
                <xsl:when test="author/gender = 'm'">
                    Άλμπουμ του
                </xsl:when>
                <xsl:when test="author/gender = 'f'">
                    Άλμπουμ της
                </xsl:when>
                <xsl:otherwise>
                    Άλμπουμ του/της
                </xsl:otherwise>
            </xsl:choose>
            <span class="user">
                <xsl:value-of select="author/name" />
            </span>
            <span class="minimize">▼</span>
        </div>
    </xsl:if>
    <div class="photostream">
        <ul>
            <xsl:if test="/social/@for and (/social/@for=author/name or not(author/name))">
                <li>
                    <form method="post" action="photo/create" enctype="multipart/form-data">
                        <div>
                            <label class="cabinet">
                                <input type="file" name="uploadimage" class="file" />
                            </label>
                            <input type="hidden" name="albumid" value="0" />
                            <span class="tooltip"><span>&#9650;</span>ανέβασε εικόνα</span>
                        </div>
                    </form>
                </li>
            </xsl:if>
            <xsl:apply-templates select="photo"/>
        </ul>
    </div>
</xsl:template>

<xsl:template name="photolist" match="photos/photo">
    <li>
        <a>
            <xsl:attribute name="href">photos/<xsl:value-of select="@id" /></xsl:attribute>
            <img>
                <xsl:attribute name="src">
                    <xsl:value-of select="media[1]/@url" />
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select="author/name" />
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:value-of select="author/name" />
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
</xsl:template>
