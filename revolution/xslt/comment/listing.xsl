<xsl:template match="discussion">
    <div class="discussion">
        <xsl:if test="/social/@for">
            <div class="note">
                <a href="" class="talk"><span class="s1_0027">&#160;</span> Άρχισε μία νέα συζήτηση</a>
                <div class="thread new">
                    <div class="message mine new">
                        <div class="author">
                            <div class="details" />
                        </div>
                        <div class="text"><textarea></textarea></div>
                        <ul class="tips"><li>Enter = <strong>Αποθήκευση</strong></li><li>Escape = <strong>Ακύρωση</strong></li><li>Shift + Enter = <strong>Νέα γραμμή</strong></li><li><a href="">Φάτσες</a></li></ul>
                        <div class="eof"></div>
                    </div>
                </div>
            </div>
        </xsl:if>
        <xsl:apply-templates select="comment" />
    </div>
</xsl:template>

<xsl:template match="comment">
    <div class="thread">
        <xsl:attribute name="id">thread_<xsl:value-of select="@id" /></xsl:attribute>
        <div>
            <xsl:attribute name="class">message<xsl:if test="/social/@for = author[1]/name[1]"> mine</xsl:if></xsl:attribute>
            <div class="author">
                <xsl:if test="author[1]/avatar[1]">
                    <a>
                        <xsl:attribute name="href">users/<xsl:value-of select="author[1]/name[1]" /></xsl:attribute>
                        <img class="avatar">
                            <xsl:attribute name="src">
                                <xsl:value-of select="author[1]/avatar[1]/media[1]/@url" />
                            </xsl:attribute>
                        </img>
                    </a>
                </xsl:if>
                <div class="details">
                    <span class="username"><xsl:value-of select="author[1]/name[1]" /></span>
                    <div class="time">
                        <xsl:value-of select="published" />
                    </div>
                </div>
            </div>
            <div class="text">
                <xsl:copy-of select="text/*|text/text()" />
            </div>
            <div class="eof"></div>
        </div>
        <xsl:apply-templates select="comment" />
    </div>
</xsl:template>
