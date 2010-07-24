var Details = {
    GetString: function( field, value, gender, callback ) {
        axslt( false, 'call:detailstrings', callback, { 'field': field, 'value': value, 'gender': gender } );
    },
    GetMap: function( field ) {
        
    },
    AcceptableValues: {
        'gender': [ '-', 'm', 'f' ],
        'sexualorientation': [ '-', 'straight', 'bi', 'gay' ],
        'relationship': [ '-', 'single', 'relationship', 'casual', 'engaged', 'married', 'complicated' ],
        'religion': [ '-', 'christian', 'muslim', 'atheist', 'agnostic', 'nothing', 'pastafarian', 'pagan', 'budhist', 'greekpolytheism', 'hindu' ],
        'politics': [ '-', 'right', 'left', 'center', 'radical left', 'radical right', 'center left', 'center right', 'nothing', 'anarchism', 'communism', 'socialism', 'liberalism', 'green' ],
        'eyecolor': ['-', 'black', 'brown', 'green', 'blue', 'grey'],
        'haircolor': [ '-','black','brown','red','blond','highlights','dark','grey','skinhead' ],
        'smoker': [ '-', 'yes', 'no', 'socially' ],
        'drinker': [ '-', 'yes', 'no', 'socially' ]
    }
}

function () {
    var social = {
        'yes': 'Ναι',
        'no': 'Όχι',
        'socially': 'Με παρέα'
    };
    var GenderStrings = {
        m: {
            smoker: social,
            drinker: social,
            relationship: {
                single: Ελεύθερος,
                relationship: Σε σχέση,
                casual: Ελεύθερη Σχέση,
                engaged: Δεσμευμένος,
                married: Παντρεμένος,
                complicated: Μπέρδεμα
            },
        },
        f: {
            smoker: social,
            drinker: social,
            relationship: {
                single: Ελεύθερη,
                relationship: Σε σχέση,
                casual: Ελεύθερη Σχέση,
                engaged: Δεσμευμένη,
                married: Παντρεμένη,
                complicated: Μπέρδεμα
            },
            religion: {
            }
                        christian: Χριστιανή
                        muslim: Ισλαμίστρια
                        atheist: Άθεη
                        agnostic: Αγνωστικίστρια
                        nothing: Άθρησκη
                        pastafarian: Πασταφαριανή
                        pagan: Παγανίστρια
                        budhist: Βουδίστρια
                        greekpolytheism: Δωδεκαθεΐστρια
                        hindu: Ινδουίστρια
                <xsl:otherwise>
                        christian: Χριστιανός
                        muslim: Ισλαμιστής
                        atheist: Άθεος
                        agnostic: Αγνωστικιστής
                        nothing: Άθρησκος
                        pastafarian: Πασταφαριανός
                        pagan: Παγανιστής
                        budhist: Βουδιστής
                        greekpolytheism: Πολυθεϊστής
                        hindu: Ινδουιστής
                </xsl:otherwise>
        politics: {
                <xsl:when test="$gender = 'f'">
                        right: Δεξιά
                        left: Αριστερή
                        center: Κεντρώα
                        <xsl:when test="$value = 'radical left'">
                            Ακροαριστερή
                        <xsl:when test="$value = 'radical right'">
                            Ακροδεξιά
                        <xsl:when test="$value = 'center left'">
                            Κεντροαριστερή
                        <xsl:when test="$value = 'center right'">
                            Κεντροδεξιά
                        nothing: Τίποτα
                        anarchism: Αναρχική
                        communism: Κομμουνίστρια
                        socialism: Σοσιαλίστρια
                        liberalism: Φιλελεύθερη
                        green: Πράσινη
                <xsl:otherwise>
                        right: Δεξιός
                        left: Αριστερός
                        center: Κεντρώος
                        <xsl:when test="$value = 'radical left'">
                            Ακροαριστερός
                        <xsl:when test="$value = 'radical right'">
                            Ακροδεξιός
                        <xsl:when test="$value = 'center left'">
                            Κεντροαριστερός
                        <xsl:when test="$value = 'center right'">
                            Κεντροδεξιός
                        nothing: Τίποτα
                        anarchism: Αναρχικός
                        communism: Κομμουνιστής
                        socialism: Σοσιαλιστής
                        liberalism: Φιλελεύθερος
                        green: Πράσινος
                </xsl:otherwise>
        sexualorientation: {
                <xsl:when test="$gender = 'f'">
                        straight: Straight
                        bi: Bisexual
                        gay: Λεσβία
                <xsl:otherwise>
                        straight: Straight
                        bi: Bisexual
                        gay: Gay
                </xsl:otherwise>
        eyecolor: {
                black: Μαύρο
                brown: Καφέ
                green: Πράσινο
                blue: Μπλε
                grey: Γκρι
        haircolor: {
                black: Μαύρο
                brown: Καστανό
                red: Κόκκινο
                blond: Ξανθό
                highlights: Ανταύγες
                dark: Σκούρο
                grey: Γκρι
                skinhead: Skinhead
        },
        'f': {
        }
    };

//axslt( false, 'call:detailstrings', function() { alert( this ) }, { 'type': 'religion', 'value': 'atheist', 'gender': 'f' } );
