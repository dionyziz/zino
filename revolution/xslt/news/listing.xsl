<xsl:template match="/social[@resource='news' and @method='listing']">
    <xsl:call-template name="zoomout" />
</xsl:template>

<xsl:template match="/social[@resource='news' and @method='listing']//feed">
    <div class="feed">
        <ul>
            <xsl:for-each select="entry">
                <li>
                    <xsl:attribute name="class"><xsl:value-of select="@type" /></xsl:attribute>
                    <xsl:apply-templates select="author" />
                    <p class="publishtime">Αναρτήθηκε <xsl:value-of select="published[1]" /></p>
                    <xsl:if test="discussion[1]/@count &gt; 0">
                        <xsl:if test="discussion[1]/@count &lt; 2">
                            <p class="commentcount"><xsl:value-of select="discussion[1]/@count" /> σχόλιο</p>
                        </xsl:if>
                        <xsl:if test="discussion[1]/@count &gt; 1">
                            <p class="commentcount"><xsl:value-of select="discussion[1]/@count" /> σχόλια</p>
                        </xsl:if>
                    </xsl:if>
                    <xsl:if test="@type = 'poll' ">
                        <a class="title">
                            <xsl:attribute name="href">polls/<xsl:value-of select="@id" /></xsl:attribute>
                            <h2>
                                <xsl:value-of select="question[1]" />
                            </h2>
                        </a>
                    </xsl:if>
                    <xsl:if test="@type = 'journal' ">
                        <a class="title">
                            <xsl:attribute name="href">journals/<xsl:value-of select="@id" /></xsl:attribute>
                            <h2>
                                <xsl:value-of select="title[1]" />
                            </h2>
                        </a>
                    </xsl:if>
                    <xsl:if test="@type = 'photo' ">
                        <a class="thumb">
                            <xsl:attribute name="href">
                                photos/<xsl:value-of select="@id" />
                            </xsl:attribute>
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
                    </xsl:if>
                </li>
            </xsl:for-each>
        </ul>
    </div>
</xsl:template>
