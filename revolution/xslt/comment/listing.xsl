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
                        <ul class="tips"><li>Enter = <strong><a href="">Αποθήκευση</a></strong></li><li>Escape = <strong><a href="">Ακύρωση</a></strong></li><li>Shift + Enter = <strong><a href="">Νέα γραμμή</a></strong></li><li><a href="">☺</a></li></ul>
                        <div class="eof"></div>
                    </div>
                </div>
            </div>
        </xsl:if>
        <xsl:apply-templates select="comment" />
    </div>
</xsl:template>
