<xsl:template name="detailstrings">
    <xsl:param name="type" />
    <xsl:param name="gender" />
    <xsl:param name="value" />
<!-- smoker -->
    <xsl:if test="$type = 'smoker'">
        <xsl:choose>
            <xsl:when test="$value = 'yes'">Ναι</xsl:when>
            <xsl:when test="$value = 'no'">Όχι</xsl:when>
            <xsl:when test="$value = 'socially'">Με παρέα</xsl:when>
        </xsl:choose>
    </xsl:if>
<!-- drinker -->
    <xsl:if test="$type = 'drinker'">
        <xsl:choose>
            <xsl:when test="$value = 'yes'">Ναι</xsl:when>
            <xsl:when test="$value = 'no'">Όχι</xsl:when>
            <xsl:when test="$value = 'socially'">Με παρέα</xsl:when>
        </xsl:choose>
    </xsl:if>
<!-- relationship -->
    <xsl:if test="$type = 'relationship'">
        <xsl:choose>
            <xsl:when test="$gender = 'f'">
                <xsl:choose>
                    <xsl:when test="$value = 'single'">Ελεύθερη</xsl:when>
                    <xsl:when test="$value = 'relationship'">Σε σχέση</xsl:when>
                    <xsl:when test="$value = 'casual'">Ελεύθερη Σχέση</xsl:when>
                    <xsl:when test="$value = 'engaged'">Δεσμευμένη</xsl:when>
                    <xsl:when test="$value = 'married'">Παντρεμένη</xsl:when>
                    <xsl:when test="$value = 'complicated'">Μπέρδεμα</xsl:when>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="$value = 'single'">Ελεύθερος</xsl:when>
                    <xsl:when test="$value = 'relationship'">Σε σχέση</xsl:when>
                    <xsl:when test="$value = 'casual'">Ελεύθερη Σχέση</xsl:when>
                    <xsl:when test="$value = 'engaged'">Δεσμευμένος</xsl:when>
                    <xsl:when test="$value = 'married'">Παντρεμένος</xsl:when>
                    <xsl:when test="$value = 'complicated'">Μπέρδεμα</xsl:when>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:if>
<!-- religion -->
    <xsl:if test="$type = 'religion'">
        <xsl:choose>
            <xsl:when test="$gender ='f'">
				<xsl:choose>
					<xsl:when test="$value = 'christian'">Χριστιανή</xsl:when>
                    <xsl:when test="$value = 'muslim'">Ισλαμίστρια</xsl:when>
                    <xsl:when test="$value = 'atheist'">Άθεη</xsl:when>
                    <xsl:when test="$value = 'agnostic'">Αγνωστικίστρια</xsl:when>
                    <xsl:when test="$value = 'nothing'">Άθρησκη</xsl:when>
                    <xsl:when test="$value = 'pastafarian'">Πασταφαριανή</xsl:when>
                    <xsl:when test="$value = 'pagan'">Παγανίστρια</xsl:when>
                    <xsl:when test="$value = 'budhist'">Βουδίστρια</xsl:when>
                    <xsl:when test="$value = 'greekpolytheism'">Δωδεκαθεΐστρια</xsl:when>
                    <xsl:when test="$value = 'hindu'">Ινδουίστρια</xsl:when>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="$value = 'christian'">Χριστιανός</xsl:when>
                    <xsl:when test="$value = 'muslim'">Ισλαμιστής</xsl:when>
                    <xsl:when test="$value = 'atheist'">Άθεος</xsl:when>
                    <xsl:when test="$value = 'agnostic'">Αγνωστικιστής</xsl:when>
                    <xsl:when test="$value = 'nothing'">Άθρησκος</xsl:when>
                    <xsl:when test="$value = 'pastafarian'">Πασταφαριανός</xsl:when>
                    <xsl:when test="$value = 'pagan'">Παγανιστής</xsl:when>
                    <xsl:when test="$value = 'budhist'">Βουδιστής</xsl:when>
                    <xsl:when test="$value = 'greekpolytheism'">Πολυθεϊστής</xsl:when>
                    <xsl:when test="$value = 'hindu'">Ινδουιστής</xsl:when>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:if>
<!-- politics -->
    <xsl:if test="$type = 'politics'">
        <xsl:choose>
            <xsl:when test="$gender ='f'">
				<xsl:choose>
					<xsl:when test="$value = 'right'">Δεξιά</xsl:when>
                    <xsl:when test="$value = 'left'">Αριστερή</xsl:when>
                    <xsl:when test="$value = 'center'">Κεντρώα</xsl:when>
                    <xsl:when test="$value = 'radical left'">Ακροαριστερή</xsl:when>
                    <xsl:when test="$value = 'radical right'">Ακροδεξιά</xsl:when>
                    <xsl:when test="$value = 'center left'">Κεντροαριστερή</xsl:when>
                    <xsl:when test="$value = 'center right'">Κεντροδεξιά</xsl:when>
                    <xsl:when test="$value = 'nothing'">Τίποτα</xsl:when>
                    <xsl:when test="$value = 'anarchism'">Αναρχική</xsl:when>
                    <xsl:when test="$value = 'communism'">Κομμουνίστρια</xsl:when>
                    <xsl:when test="$value = 'socialism'">Σοσιαλίστρια</xsl:when>
                    <xsl:when test="$value = 'liberalism'">Φιλελεύθερη</xsl:when>
                    <xsl:when test="$value = 'green'">Πράσινη</xsl:when>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="$value = 'right'">Δεξιός</xsl:when>
                    <xsl:when test="$value = 'left'">Αριστερός</xsl:when>
                    <xsl:when test="$value = 'center'">Κεντρώος</xsl:when>
                    <xsl:when test="$value = 'radical left'">Ακροαριστερός</xsl:when>
                    <xsl:when test="$value = 'radical right'">Ακροδεξιός</xsl:when>
                    <xsl:when test="$value = 'center left'">Κεντροαριστερός</xsl:when>
                    <xsl:when test="$value = 'center right'">Κεντροδεξιός</xsl:when>
                    <xsl:when test="$value = 'nothing'">Τίποτα</xsl:when>
                    <xsl:when test="$value = 'anarchism'">Αναρχικός</xsl:when>
                    <xsl:when test="$value = 'communism'">Κομμουνιστής</xsl:when>
                    <xsl:when test="$value = 'socialism'">Σοσιαλιστής</xsl:when>
                    <xsl:when test="$value = 'liberalism'">Φιλελεύθερος</xsl:when>
                    <xsl:when test="$value = 'green'">Πράσινος</xsl:when>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:if>
<!-- sexualorientation -->
    <xsl:if test="$type = 'sexualorientation'">
        <xsl:choose>
            <xsl:when test="$gender ='f'">
				<xsl:choose>
					<xsl:when test="$value = 'straight'">Straight</xsl:when>
                    <xsl:when test="$value = 'bi'">Bisexual</xsl:when>
                    <xsl:when test="$value = 'gay'">Λεσβία</xsl:when>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="$value = 'straight'">Straight</xsl:when>
                    <xsl:when test="$value = 'bi'">Bisexual</xsl:when>
                    <xsl:when test="$value = 'gay'">Gay</xsl:when>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:if>
<!-- eyecolor -->
    <xsl:if test="$type = 'eyecolor'">
        <xsl:choose>
            <xsl:when test="$value = 'black'">Μαύρο</xsl:when>
            <xsl:when test="$value = 'brown'">Καφέ</xsl:when>
            <xsl:when test="$value = 'green'">Πράσινο</xsl:when>
            <xsl:when test="$value = 'blue'">Μπλε</xsl:when>
            <xsl:when test="$value = 'grey'">Γκρι</xsl:when>
        </xsl:choose>
    </xsl:if>
<!-- haircolr -->
    <xsl:if test="$type = 'haircolor'">
        <xsl:choose>
            <xsl:when test="$value = 'black'">Μαύρο</xsl:when>
            <xsl:when test="$value = 'brown'">Καστανό</xsl:when>
            <xsl:when test="$value = 'red'">Κόκκινο</xsl:when>
            <xsl:when test="$value = 'blond'">Ξανθό</xsl:when>
            <xsl:when test="$value = 'highlights'">Ανταύγες</xsl:when>
            <xsl:when test="$value = 'dark'">Σκούρο</xsl:when>
            <xsl:when test="$value = 'grey'">Γκρι</xsl:when>
            <xsl:when test="$value = 'skinhead'">Skinhead</xsl:when>
        </xsl:choose>
    </xsl:if>
</xsl:template>
