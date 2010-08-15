<xsl:template match="/social[@resource='ban' and (@method='create' or @method='delete')]">
</xsl:template>

<xsl:template match="/social[@resource='ban' and @method='listing']">
    <xsl:apply-templates select="bans" />
</xsl:template>

<xsl:template match="bans">
    <form action="ban/create" method="post">
        <table summary="Λίστα των χρηστών που έχουν αποκλειστεί από το Zino" class="bans">
            <caption>Αποκλεισμένοι χρήστες</caption>
            <thead>
                <tr>
                    <td>Θύτης</td>
                    <td>Παράπτωμα</td>
                    <td>Διαχειριστής</td>
                    <td>Από</td>
                    <td>Έως</td>
                    <td/>
                </tr>
            </thead>
            <tbody>
                <td>
                    <input type="text" value="" name="username" />
                </td>
                <td>
                    <select name="reason">
                        <option value="spam">Spam</option>
                        <option value="fake">Fake</option>
                        <option value="porn">Πορνογραφία</option>
                        <option value="bot">Λογαριασμός-ρομπότ</option>
                        <option value="ads">Διαφήμιση</option>
                        <option value="drugs">Ναρκωτικές ουσίες</option>
                        <option value="profanity">Λογαριασμός για βρίσιμο</option>
                        <option value="trolling">Λογαριασμός για trolling</option>
                        <option value="ban evasion">Αποφυγή παλιού αποκλεισμού</option>
                        <option value="phishing">Ηλεκτρονικό "ψάρεμα"</option>
                        <option value="sex abuse">Σεξουαλική παρενόχληση</option>
                        <option value="10minutemail">10minutemail</option>
                        <option value="triggering">Ενθάρυνση αυτοκτονιών</option>
                        <option value="illegal content">Αλλο παράνομο περιεχόμενο</option>
                    </select>
                </td>
                <td><xsl:value-of select="/social/@for" /></td>
                <td>Σήμερα</td>
                <td>
                    <select name="daysbanned">
                        <option value="1">1 μέρα</option>
                        <option value="2">2 μέρες</option>
                        <option value="3">3 μέρες</option>
                        <option value="5">5 μέρες</option>
                        <option value="7">1 εβδομάδα</option>
                        <option value="14">2 εβδομάδες</option>
                        <option value="30">1 μήνας</option>
                        <option value="0">Διαγραφή λογαριασμού</option>
                    </select>
                </td>
                <td>
                    <input type="submit" value="Αποκλεισμός" />
                </td>
                <xsl:apply-templates select="ban">
                    <xsl:sort select="started" order="descending" />
                </xsl:apply-templates>
            </tbody>
        </table>
    </form>
</xsl:template>

<xsl:template match="ban">
    <tr>
        <td>
            <xsl:value-of select="user/name" />
        </td>
        <td>
            <xsl:value-of select="reason" />
        </td>
        <td>
            <xsl:value-of select="bannedBy/user/name" />
        </td>
        <td class="time">
            <xsl:value-of select="started" />
        </td>
        <td>
            <xsl:value-of select="expire" />
        </td>
        <td>
            <a href="">
                <xsl:attribute name="id">revoke_<xsl:value-of select="user/@id" /></xsl:attribute>
                Άρση
            </a>
        </td>
    </tr>
</xsl:template>
