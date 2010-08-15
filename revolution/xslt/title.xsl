<xsl:template name="title">
    <xsl:choose>
        <xsl:when test="/social/@resource = 'user' and /social/@method = 'view'">
            <xsl:value-of select="/social/user/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'photo' and /social/@method = 'view'">
            <xsl:choose>
                <xsl:when test="/social/photo/title">
                    <xsl:value-of select="/social/photo/title" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="/social/photo/author/name" />
                </xsl:otherwise>
            </xsl:choose>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'poll' and /social/@method = 'view'">
            "<xsl:value-of select="/social/poll/title" />"
            <xsl:choose>
                <xsl:when test="/social/poll/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/poll/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'journal' and /social/@method = 'view'">
            "<xsl:value-of select="/social/journal/title" />"
            <xsl:choose>
                <xsl:when test="/social/journal/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/journal/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'news' and /social/@method = 'listing'">
            Νέα στο zino
        </xsl:when>
        <xsl:when test="/social/@resource = 'journal' and /social/@method = 'listing'">
            Ημερολόγια 
            <xsl:if test="/social/journals/author">
                <xsl:choose>
                    <xsl:when test="/social/journals/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/journals/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'poll' and /social/@method = 'listing'">
            Δημοσκοπήσεις
            <xsl:if test="/social/polls/author">
                <xsl:choose>
                    <xsl:when test="/social/polls/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/polls/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'photo' and /social/@method = 'listing'">
            Εικόνες
            <xsl:if test="/social/photos/author">
                <xsl:choose>
                    <xsl:when test="/social/photos/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/photos/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'favourite' and /social/@method = 'listing'">
            Αγαπημένα
            <xsl:choose>
                <xsl:when test="/social/photos/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/photos/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:otherwise>
            zino
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>
