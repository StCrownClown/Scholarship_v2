////// get 
visualSearch.searchBox.value();

// returns: 'country: "United States" state: "New York" account: 5-samuel title: "Pentagon Papers"'

visualSearch.searchQuery.facets();

// returns: [{"country":"United States"},{"state":"New York"},{"account":"5-samuel"},{"title":"Pentagon Papers"}]


////// set 
visualSearch.searchBox.setQuery('country: "United States"');

visualSearch.searchBox.addFacet('Krrn','333');

 visualSearch.searchBox.clearSearch();

//////////////////////////////
http://documentcloud.github.io/visualsearch/docs/search_box.html
//////////////////////////////////

function displayDatepicker(callback){
        callback(['asdfasfd']);
        $("ul.VS-interface:visible li.ui-menu-item a:first").click();
        visualSearch.searchBox.setQuery('Krrn: "66694"');
//        alert(visualSearch.searchBox.value());
    }

    var vsName = '';
    function vsSubject(name){
        vsName = name;
        searchSubject();
    }

    function showAuditorSearch(id,name){
        var tmp = visualSearch.searchBox.value();
        tmp += ' '+vsName+': "'+id+'-'+name+'"';
        visualSearch.searchBox.setQuery(tmp);
        $('#showlistAuditorSearch').dialog('close');
        $("ul.VS-interface:visible li.ui-menu-item a:first").click();
    }


//////////////////////



<?php
	$this->widget('ext.VisualSearch.VisualSearch',array(
		'options'=>array(
					'placeholder'=>Yii::t('web', 'search'),
					// 'autosearch'=>false,
					'query'=>'country: "United States" account: 5-samuel "U.S. State": California',
					'callbacks'=>"js:{
							search   : function(query, searchCollection) {
								alert(searchCollection.serialize());
							},
							facetMatches : function(callback) {
								callback([
									'krrn', 
									'account', 
									'filter', 
									'access', 
									'title',
									{ label: 'city',        category: 'location' },
									{ label: 'address',     category: 'location' },
									{ label: 'country',     category: 'location' },
									{ label: 'U.S. State',  category: 'location' },
								  ]);
							},
							valueMatches : function(category, searchTerm, callback) {
								switch (category) {
								  case 'account':
									  callback([
										{ value: '1-amanda', label: 'Amanda' },
										{ value: '2-aron',   label: 'Aron' },
										{ value: '3-eric',   label: 'Eric' },
										{ value: '4-jeremy', label: 'Jeremy' },
										{ value: '5-samuel', label: 'Samuel' },
										{ value: '6-scott',  label: 'Scott' }
									  ]);
									  break;
									case 'filter':
									  callback(['published', 'unpublished', 'draft']);
									  break;
									case 'access':
									  callback(['public', 'private', 'protected']);
									  break;
									case 'title':
									  callback([
										'Pentagon Papers',
										'CoffeeScript Manual',
										'Laboratory for Object Oriented Thinking',
										'A Repository Grows in Brooklyn'
									  ]);
									  break;
									case 'city':
									  callback([
										'Cleveland',
										'New York City',
										'Brooklyn',
										'Manhattan',
										'Queens',
										'The Bronx',
										'Staten Island',
										'San Francisco',
										'Los Angeles',
										'Seattle',
										'London',
										'Portland',
										'Chicago',
										'Boston'
									  ])
									  break;
									case 'U.S. State':
									  callback([
										\"Alabama\", \"Alaska\", \"Arizona\", \"Arkansas\", \"California\",
										\"Colorado\", \"Connecticut\", \"Delaware\", \"District of Columbia\", \"Florida\",
										\"Georgia\", \"Guam\", \"Hawaii\", \"Idaho\", \"Illinois\",
										\"Indiana\", \"Iowa\", \"Kansas\", \"Kentucky\", \"Louisiana\",
										\"Maine\", \"Maryland\", \"Massachusetts\", \"Michigan\", \"Minnesota\",
										\"Mississippi\", \"Missouri\", \"Montana\", \"Nebraska\", \"Nevada\",
										\"New Hampshire\", \"New Jersey\", \"New Mexico\", \"New York\", \"North Carolina\",
										\"North Dakota\", \"Ohio\", \"Oklahoma\", \"Oregon\", \"Pennsylvania\",
										\"Puerto Rico\", \"Rhode Island\", \"South Carolina\", \"South Dakota\", \"Tennessee\",
										\"Texas\", \"Utah\", \"Vermont\", \"Virginia\", \"Virgin Islands\",
										\"Washington\", \"West Virginia\", \"Wisconsin\", \"Wyoming\"
									  ]);
									  break
									case 'country':
									  callback([
										\"China\", \"India\", \"United States\", \"Indonesia\", \"Brazil\",
										\"Pakistan\", \"Bangladesh\", \"Nigeria\", \"Russia\", \"Japan\",
										\"Mexico\", \"Philippines\", \"Vietnam\", \"Ethiopia\", \"Egypt\",
										\"Germany\", \"Turkey\", \"Iran\", \"Thailand\", \"D. R. of Congo\",
										\"France\", \"United Kingdom\", \"Italy\", \"Myanmar\", \"South Africa\",
										\"South Korea\", \"Colombia\", \"Ukraine\", \"Spain\", \"Tanzania\",
										\"Sudan\", \"Kenya\", \"Argentina\", \"Poland\", \"Algeria\",
										\"Canada\", \"Uganda\", \"Morocco\", \"Iraq\", \"Nepal\",
										\"Peru\", \"Afghanistan\", \"Venezuela\", \"Malaysia\", \"Uzbekistan\",
										\"Saudi Arabia\", \"Ghana\", \"Yemen\", \"North Korea\", \"Mozambique\",
										\"Taiwan\", \"Syria\", \"Ivory Coast\", \"Australia\", \"Romania\",
										\"Sri Lanka\", \"Madagascar\", \"Cameroon\", \"Angola\", \"Chile\",
										\"Netherlands\", \"Burkina Faso\", \"Niger\", \"Kazakhstan\", \"Malawi\",
										\"Cambodia\", \"Guatemala\", \"Ecuador\", \"Mali\", \"Zambia\",
										\"Senegal\", \"Zimbabwe\", \"Chad\", \"Cuba\", \"Greece\",
										\"Portugal\", \"Belgium\", \"Czech Republic\", \"Tunisia\", \"Guinea\",
										\"Rwanda\", \"Dominican Republic\", \"Haiti\", \"Bolivia\", \"Hungary\",
										\"Belarus\", \"Somalia\", \"Sweden\", \"Benin\", \"Azerbaijan\",
										\"Burundi\", \"Austria\", \"Honduras\", \"Switzerland\", \"Bulgaria\",
										\"Serbia\", \"Israel\", \"Tajikistan\", \"Hong Kong\", \"Papua New Guinea\",
										\"Togo\", \"Libya\", \"Jordan\", \"Paraguay\", \"Laos\",
										\"El Salvador\", \"Sierra Leone\", \"Nicaragua\", \"Kyrgyzstan\", \"Denmark\",
										\"Slovakia\", \"Finland\", \"Eritrea\", \"Turkmenistan\"
									  ], {
										preserveOrder: true // Otherwise the selected value is brought to the top
									  });
									  break;
								  }
							}
						}",
			),
	));
?>