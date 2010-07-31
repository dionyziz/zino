var UserDetails = {
    GenderStrings: {},
    GetString: function( field, value, gender ) {
        if ( value != '-' ) {
            return UserDetails.GetMap( field, gender )[ value ];
        }
        else {
            switch( field ) {
                case 'gender':
                    return 'Όρισε φύλο';
                    break;
                default:
                    return UserDetails.GetMap( field, gender )[ value ];
            }
        }
    },
    GetMap: function( field, gender ) {
        var map;
        if ( field == 'gender' ) {
            map = { m: 'Αγόρι', f: 'Κορίτσι' };
        }
        else {
            map =  UserDetails.GenderStrings[ gender == 'f' ? 'f' : 'm' ][ field ];
        }
        if ( field != 'height' && field != 'weight' ) {
            map[ '-' ] = 'Να μην εμφανίζεται';
        }
        return map;
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
    },
    Init: function () {
        var social = {
            yes: 'Ναι',
            no: 'Όχι',
            socially: 'Με παρέα'
        };
        var hair = {
            black: 'Μαύρο',
            brown: 'Καστανό',
            red: 'Κόκκινο',
            blond: 'Ξανθό',
            highlights: 'Ανταύγες',
            dark: 'Σκούρο',
            grey: 'Γκρι',
            skinhead: 'Skinhead'
        };
        var eyes = {
            black: 'Μαύρο',
            brown: 'Καφέ',
            green: 'Πράσινο',
            blue: 'Μπλε',
            grey: 'Γκρι'
        };
        var height = {};
        for ( var i = 120; i <= 220; ++i ) {
            height[ i ] = Math.floor( i / 100 ) + '.' +
                (
                    ( ( i % 100 ) < 10 ) ? 
                        '0' + ( i % 100 ) :
                        ( i % 100 )
                )
                + 'm';
        }
        height[ -1 ] = 'Πάνω από 2.20m'
        height[ -2 ] = 'Κάτω από 1.20m'
        height[ -3 ] = 'Να μην εμφανίζεται';
        
        var weight = {};
        for ( var i = 40; i <= 120; ++i ) {
            weight[ i ] = i + 'kg';
        }
        weight[ -1 ] = 'Πάνω από 120kg';
        weight[ -2 ] = 'Κάτω από 40kg';
        weight[ -3 ] = 'Να μην εμφανίζεται';
        
        UserDetails.GenderStrings = {
            m: {
                weight: weight,
                height: height,
                smoker: social,
                drinker: social,
                relationship: {
                    single: 'Ελεύθερος',
                    relationship: 'Σε σχέση',
                    casual: 'Ελεύθερη Σχέση',
                    engaged: 'Δεσμευμένος',
                    married: 'Παντρεμένος',
                    complicated: 'Μπέρδεμα'
                },
                religion: {
                    christian: 'Χριστιανός',
                    muslim: 'Ισλαμιστής',
                    atheist: 'Άθεος',
                    agnostic: 'Αγνωστικιστής',
                    nothing: 'Άθρησκος',
                    pastafarian: 'Πασταφαριανός',
                    pagan: 'Παγανιστής',
                    budhist: 'Βουδιστής',
                    greekpolytheism: 'Πολυθεϊστής',
                    hindu: 'Ινδουιστής'
                },
                politics: {
                    right: 'Δεξιός',
                    left: 'Αριστερός',
                    center: 'Κεντρώος',
                    'radical left': 'Ακροαριστερός',
                    'radical right': 'Ακροδεξιός',
                    'center left': 'Κεντροαριστερός',
                    'center right': 'Κεντροδεξιός',
                    nothing: 'Τίποτα',
                    anarchism: 'Αναρχικός',
                    communism: 'Κομμουνιστής',
                    socialism: 'Σοσιαλιστής',
                    liberalism: 'Φιλελεύθερος',
                    green: 'Πράσινος'
                },
                sexualorientation: {
                    straight: 'Straight',
                    bi: 'Bisexual',
                    gay: 'Gay'
                },
                eyecolor: eyes,
                haircolor: hair
            },
            f: {
                weight: weight,
                height: height,
                smoker: social,
                drinker: social,
                relationship: {
                    single: 'Ελεύθερη',
                    relationship: 'Σε σχέση',
                    casual: 'Ελεύθερη Σχέση',
                    engaged: 'Δεσμευμένη',
                    married: 'Παντρεμένη',
                    complicated: 'Μπέρδεμα'
                },
                religion: {
                    christian: 'Χριστιανή',
                    muslim: 'Ισλαμίστρια',
                    atheist: 'Άθεη',
                    agnostic: 'Αγνωστικίστρια',
                    nothing: 'Άθρησκη',
                    pastafarian: 'Πασταφαριανή',
                    pagan: 'Παγανίστρια',
                    budhist: 'Βουδίστρια',
                    greekpolytheism: 'Δωδεκαθεΐστρια',
                    hindu: 'Ινδουίστρια'
                },
                politics: {
                    right: 'Δεξιά',
                    left: 'Αριστερή',
                    center: 'Κεντρώα',
                    'radical left': 'Ακροαριστερή',
                    'radical right': 'Ακροδεξιά',
                    'center left': 'Κεντροαριστερή',
                    'center right': 'Κεντροδεξιά',
                    nothing: 'Τίποτα',
                    anarchism: 'Αναρχική',
                    communism: 'Κομμουνίστρια',
                    socialism: 'Σοσιαλίστρια',
                    liberalism: 'Φιλελεύθερη',
                    green: 'Πράσινη'
                },
                sexualorientation: {
                    straight: 'Straight',
                    bi: 'Bisexual',
                    gay: 'Λεσβία'
                },
                eyecolor: eyes,
                haircolor: hair
            }
        };
    }
};

//axslt( false, 'call:detailstrings', function() { alert( this ) }, { 'type': 'religion', 'value': 'atheist', 'gender': 'f' } );
