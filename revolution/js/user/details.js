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
//axslt( false, 'call:detailstrings', function() { alert( this ) }, { 'type': 'religion', 'value': 'atheist', 'gender': 'f' } );