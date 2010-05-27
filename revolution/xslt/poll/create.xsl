<xsl:template name="poll.new">
    <div class="newpoll"><form>
        <div class="info">Γράψε μία ερώτηση:</div>
        <input class="question" />
        <div class="info options">Γράψε τις επιλογές:</div>
        <ul class="options">
            <li><input class="option" id="newoption_1" /></li>
            <li><input class="option" id="newoption_2" /></li>
        </ul>
        <ul class="toolbox">
            <li><a class="button big" href="">Δημιουργία</a></li>
            <li><a class="linkbutton" href="">Ακύρωση και πίσω</a></li>
        </ul>
    </form></div>
</xsl:template>
