<xsl:template match="/social[@resource='news' and @method='listing']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='news' and @method='listing']//news" name="news">
    <div class="stream">
        <xsl:if test="$user">
            <ol class="createitem">
                <li><a href="" class="newjournal">Νέο ημερολόγιο</a></li>
                <li><a href="" class="newpoll">Νέα δημοσκόπηση</a></li>
            </ol>
        </xsl:if>
        <ul>
            <xsl:for-each select="journal|photo|poll">
                <li>
                    <xsl:attribute name="class"><xsl:value-of select="name()" /></xsl:attribute>
                    <xsl:attribute name="id"><xsl:value-of select="name()" />_<xsl:value-of select="@id" /></xsl:attribute>
                    <div class="details">
                        <xsl:apply-templates select="author" />
                        <p class="time"><xsl:value-of select="published[1]" /></p>
                        <xsl:if test="discussion[1]/@count &gt; 0">
                            <xsl:if test="discussion[1]/@count &lt; 2">
                                <p class="commentcount"><xsl:value-of select="discussion[1]/@count" /> σχόλιο</p>
                            </xsl:if>
                            <xsl:if test="discussion[1]/@count &gt; 1">
                                <p class="commentcount"><xsl:value-of select="discussion[1]/@count" /> σχόλια</p>
                            </xsl:if>
                        </xsl:if>
                    </div>
                    <xsl:if test="name() = 'poll' ">
                        <a class="title">
                            <xsl:attribute name="href">polls/<xsl:value-of select="@id" /></xsl:attribute>
                            <h2>
                                <xsl:value-of select="question[1]" />
                            </h2>
                        </a>
                    </xsl:if>
                    <xsl:if test="name() = 'journal' ">
                        <a class="title">
                            <xsl:attribute name="href">journals/<xsl:value-of select="@id" /></xsl:attribute>
                            <h2>
                                <xsl:value-of select="title[1]" />
                            </h2>
                        </a>
                    </xsl:if>
                    <xsl:if test="name() = 'photo' ">
                        <xsl:attribute name="id">photo_<xsl:value-of select="@id" /></xsl:attribute>
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
                    <a class="zoomin">
                        <xsl:if test="name() = 'journal'">
                            <xsl:attribute name="href">journals/<xsl:value-of select="@id" /></xsl:attribute>
                        </xsl:if>
                        <xsl:if test="name() = 'poll'">
                            <xsl:attribute name="href">polls/<xsl:value-of select="@id" /></xsl:attribute>
                        </xsl:if>
                        Κάνε κλικ ξανά για μεγιστοποίηση
                    </a>
                </li>
            </xsl:for-each>
        </ul>
    </div>
    <div id="preview">
        <div class="contentshadow">
            <div class="content">
                <span class="infotext">Κάνε κλικ σε ένα Νέο για προεπισκόπηση</span>
            </div>
        </div>
    </div>
</xsl:template>
