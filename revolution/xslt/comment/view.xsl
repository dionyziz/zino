<xsl:template match="comment">
    <div class="thread">
        <xsl:attribute name="id">thread_<xsl:value-of select="@id" /></xsl:attribute>
        <div>
            <xsl:attribute name="class">message<xsl:if test="/social/@for = author[1]/name[1]"> mine</xsl:if></xsl:attribute>
            <div class="author">
                <a>
                    <xsl:attribute name="href">users/<xsl:value-of select="author[1]/name[1]" /></xsl:attribute>
                    <img class="avatar">
                        <xsl:attribute name="src">
                            <xsl:choose>
                                <xsl:when test="author/avatar">
                                    <xsl:value-of select="author/avatar/media/@url" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>
                    </img>
                </a>
                <div class="details">
                    <span class="username"><xsl:value-of select="author[1]/name[1]" /></span>
                    <xsl:if test="published">
                        <div class="time">
                            <xsl:value-of select="published" />
                        </div>
                    </xsl:if>
                </div>
            </div>
            <xsl:if test="/social/@for = author/name or /social/*/author/name = /social/@for or /social/user/name = /social/@for">
                <div class='delete'>
                    <a href="#" title='Διαγραφή Σχολίου'>
                        <xsl:attribute name="id">delete_<xsl:value-of select="@id" /></xsl:attribute>
                        ×
                    </a>
                </div>
            </xsl:if>
            <div class="text">
                <xsl:copy-of select="text/*|text/text()" />
            </div>
            <div class="eof"></div>
        </div>
        <xsl:apply-templates select="comment" />
    </div>
</xsl:template>

<xsl:template name="comment.modal.smileys">
    <div class="modal">
        <table summary="Υπόμνημα για τα χαμόγελα του Zino" class="smileys">
            <caption>Χαμόγελα</caption>
            <thead>
                <tr>
                    <td>Γράφεις</td><td>Εμφανίζεται</td>
                </tr>
            </thead>
            <tbody>
                <tr><td>:D</td><td><span class="emoticon-teeth"></span></td></tr>
                <tr><td>:)</td><td><span class="emoticon-smile"></span></td></tr>
                <tr><td>:P</td><td><span class="emoticon-tongue"></span></td></tr>
                <tr><td>:-D</td><td><span class="emoticon-teeth"></span></td></tr>
                <tr><td>:S</td><td><span class="emoticon-confused"></span></td></tr>
                <tr><td>:'(</td><td><span class="emoticon-cry"></span></td></tr>
                <tr><td>:angel:</td><td><span class="emoticon-innocent"></span></td></tr>
                <tr><td>:angry:</td><td><span class="emoticon-angry"></span></td></tr>
                <tr><td>:awesome:</td><td><span class="emoticon-awesome"></span></td></tr>
                <tr><td>:bat:</td><td><span class="emoticon-bat"></span></td></tr>
                <tr><td>:beer:</td><td><span class="emoticon-beer"></span></td></tr>
                <tr><td>:cake:</td><td><span class="emoticon-cake"></span></td></tr>
                <tr><td>:photo:</td><td><span class="emoticon-camera"></span></td></tr>
                <tr><td>:cat:</td><td><span class="emoticon-cat"></span></td></tr>
                <tr><td>:clock:</td><td><span class="emoticon-clock"></span></td></tr>
                <tr><td>:drink:</td><td><span class="emoticon-cocktail"></span></td></tr>
                <tr><td>:cafe:</td><td><span class="emoticon-cup"></span></td></tr>
                <tr><td>:666:</td><td><span class="emoticon-devil"></span></td></tr>
                <tr><td>:evil:</td><td><span class="emoticon-devil"></span></td></tr>
                <tr><td>:dog:</td><td><span class="emoticon-dog"></span></td></tr>
                <tr><td>:mail:</td><td><span class="emoticon-email"></span></td></tr>
                <tr><td>^^Uu</td><td><span class="emoticon-embarassed"></span></td></tr>
                <tr><td>:film:</td><td><span class="emoticon-film"></span></td></tr>
                <tr><td>:smooch:</td><td><span class="emoticon-kiss"></span></td></tr>
                <tr><td>:idea:</td><td><span class="emoticon-lightbulb"></span></td></tr>
                <tr><td>LOL</td><td><span class="emoticon-lol"></span></td></tr>
                <tr><td>:phone:</td><td><span class="emoticon-phone"></span></td></tr>
                <tr><td>:cool:</td><td><span class="emoticon-shade"></span></td></tr>
                <tr><td>:no:</td><td><span class="emoticon-thumbs-down"></span></td></tr>
                <tr><td>:yes:</td><td><span class="emoticon-thumbs-up"></span></td></tr>
                <tr><td>:yuck:</td><td><span class="emoticon-tongue"></span></td></tr>
                <tr><td>:hate:</td><td><span class="emoticon-unlove"></span></td></tr>
                <tr><td>:rose:</td><td><span class="emoticon-wilted-rose"></span></td></tr>
                <tr><td>:star:</td><td><span class="emoticon-star"></span></td></tr>
                <tr><td>:X</td><td><span class="emoticon-uptight"></span></td></tr>
                <tr><td>:gift:</td><td><span class="emoticon-present"></span></td></tr>
                <tr><td>:love:</td><td><span class="emoticon-love"></span></td></tr>
                <tr><td>:music:</td><td><span class="emoticon-note"></span></td></tr>
                <tr><td>:note:</td><td><span class="emoticon-note"></span></td></tr>
                <tr><td>:airplane:</td><td><span class="emoticon-airplane"></span></td></tr>
                <tr><td>:boy:</td><td><span class="emoticon-boy"></span></td></tr>
                <tr><td>:car:</td><td><span class="emoticon-car"></span></td></tr>
                <tr><td>:smoke:</td><td><span class="emoticon-cigarette"></span></td></tr>
                <tr><td>:computer:</td><td><span class="emoticon-computer"></span></td></tr>
                <tr><td>:girl:</td><td><span class="emoticon-girl"></span></td></tr>
                <tr><td>:-|</td><td><span class="emoticon-indifferent"></span></td></tr>
                <tr><td>:island:</td><td><span class="emoticon-ip"></span></td></tr>
                <tr><td>:!!:</td><td><span class="emoticon-lightning"></span></td></tr>
                <tr><td>:sms:</td><td><span class="emoticon-mobile-phone"></span></td></tr>
                <tr><td>:wow:</td><td><span class="emoticon-omg"></span></td></tr>
                <tr><td>:(</td><td><span class="emoticon-sad"></span></td></tr>
                <tr><td>:sheep:</td><td><span class="emoticon-sheep"></span></td></tr>
                <tr><td>:@:</td><td><span class="emoticon-snail"></span></td></tr>
                <tr><td>:ball:</td><td><span class="emoticon-soccer"></span></td></tr>
                <tr><td>:kaboom:</td><td><span class="emoticon-storm"></span></td></tr>
                <tr><td>:sun:</td><td><span class="emoticon-sun"></span></td></tr>
                <tr><td>:turtle:</td><td><span class="emoticon-turtle"></span></td></tr>
                <tr><td>:?:</td><td><span class="emoticon-thinking"></span></td></tr>
                <tr><td>:umbrella:</td><td><span class="emoticon-umbrella"></span></td></tr>
                <tr><td>:~:</td><td><span class="emoticon-ugly"></span></td></tr>
                <tr><td>:::</td><td><span class="emoticon-empty"></span></td></tr>
            </tbody>
        </table>
    </div>
</xsl:template>
