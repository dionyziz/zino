<xsl:template match="/social[@resource='journal' and @method='listing']" >
    <div class="stream">
        <xsl:apply-templates />
    </div>
    <div id="preview"><div class="contentshadow"><div class="content"><span class="infotext" style="position: absolute; height: 0px; top: 50%; margin-top: 0px;">Κάνε κλικ σε ένα Νέο για προεπισκόπηση</span></div></div></div>
</xsl:template>

<xsl:template match="/social[@resource='journal' and @method='listing']/journals" >
    <ul>
        <xsl:for-each select="journal">
            <li class="journal">
                <xsl:attribute name="id">
                    <xsl:text>journal_</xsl:text>
                    <xsl:value-of select="@id" />
                </xsl:attribute>
                <div class="details">
                    <a class="username">
                        <xsl:attribute name="href">
                            <xsl:text>users/</xsl:text>
                            <xsl:value-of select="author/name" />
                        </xsl:attribute>
                        <img class="avatar">
                            <xsl:attribute name="src">
                                <xsl:value-of select="author/avatar/media/@url" />
                            </xsl:attribute>
                        </img>
                        <xsl:value-of select="author/name" />
                    </a>
                    <p class="time">
                        <xsl:value-of select="published" />
                    </p>
                    <p class="commentcount">
                        <xsl:if test="discussion/@count = 1">
                            <xsl:value-of select="discussion/@count" />
                            <xsl:text> σχόλιο</xsl:text>
                        </xsl:if>
                        <xsl:if test="discussion[1]/@count &gt; 1">
                            <xsl:value-of select="discussion/@count" />
                            <xsl:text> σχόλια</xsl:text>
                        </xsl:if>
                    </p>
                </div>
                <a class="title">
                    <xsl:attribute name="href">
                        <xsl:text>journals/</xsl:text>
                        <xsl:value-of select="@id" />
                    </xsl:attribute>
                    <h2>
                        <xsl:value-of select="title" />
                    </h2>
                </a>
                <a class="zoomin">
                    <xsl:attribute name="href">
                        <xsl:text>journals/</xsl:text>
                        <xsl:value-of select="@id" />
                    </xsl:attribute>
                    <xsl:text>Κάνε κλικ ξανά για μεγιστοποίηση</xsl:text>
                </a>
            </li>
        </xsl:for-each>
    </ul>
</xsl:template>

