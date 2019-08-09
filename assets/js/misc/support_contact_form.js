$(function() {

	// Get the form.
	var form = $('#ajax-contact');

	// Get the messages div.
	var formMessages = $('#form-messages');
	
	// temporarily disable
	$(formMessages).text('This form is temporarily unavailable for scheduled maintenance')
	$(formMessages).addClass('error');
	$('#ajax-contact input').attr('disabled','disabled');
	$('#ajax-contact select').attr('disabled','disabled');
	$('#ajax-contact textarea').attr('disabled','disabled');
	
	//Build Country List TODO - Create from DB
	var jsonDataCountry = {
	"Table": [{"v": "USA","d": "United States"}
	,{"v": "ALB","d":"Albania"}
	,{"v": "ARG","d":"Argentina"}
	,{"v": "AUS","d":"Australia"}
	,{"v": "AUT","d":"Austria"}
	,{"v": "BEL","d":"Belgium"}
	,{"v": "BRA","d":"Brazil"}
	,{"v": "BGR","d":"Bulgaria"}
	,{"v": "CAN","d":"Canada"}
	,{"v": "CHL","d":"Chile"}
	,{"v": "CHN","d":"China"}
	,{"v": "CYP","d":"Cyprus"}
	,{"v": "CZE","d":"CZECH Republic"}
	,{"v": "DNK","d":"Denmark"}
	,{"v": "EGY","d":"Egypt"}
	,{"v": "FIN","d":"Finland"}
	,{"v": "FRA","d":"France"}
	,{"v": "DEU","d":"Germany"}
	,{"v": "GBR","d":"Great Britan"}
	,{"v": "GRC","d":"Greece"}
	,{"v": "HKG","d":"Hong Kong"}
	,{"v": "HUN","d":"Hungary"}
	,{"v": "IND","d":"India"}
	,{"v": "IRL","d":"Ireland"}
	,{"v": "ISR","d":"Israel"}
	,{"v": "ITA","d":"Italy"}
	,{"v": "JPN","d":"Japan"}
	,{"v": "PRK","d":"Korea"}
	,{"v": "KWT","d":"Kuwait"}
	,{"v": "MEX","d":"Mexico"}
	,{"v": "NLD","d":"Netherlands"}
	,{"v": "NOR","d":"Norway"}
	,{"v": "POL","d":"Poland"}
	,{"v": "PRT","d":"Portugal"}
	,{"v": "PRI","d":"Puerto Rico"}
	,{"v": "QAT","d":"Qatar"}
	,{"v": "ROU","d":"Romania"}
	,{"v": "RUS","d":"Russia"}
	,{"v": "SAU","d":"Saudi Arabia"}
	,{"v": "IRL","d":"Scotland"}
	,{"v": "SGP","d":"Singapore"}
	,{"v": "SVN","d":"Slovenia"}
	,{"v": "ZAF","d":"South Africa"}
	,{"v": "ESP","d":"Spain"}
	,{"v": "LKA","d":"Sri Lanka"}
	,{"v": "SWE","d":"Sweden"}
	,{"v": "CHE","d":"Switzerland"}
	,{"v": "TWN","d":"Taiwan"}
	,{"v": "TUR","d":"Turkey"}
	,{"v": "USA","d":"United States"}
	,{"v": "","d":"Other - Not Listed"}
	]};
	
	//Build Request Type List TODO - Create from DB
	var jsonDataType = {
	"Table": [{"v": "4","d":"Calibration"}
	,{"v": "3","d":"Entitlement Check"}
	,{"v": "7","d":"Field Service"}
	,{"v": "2","d":"RMA / Order / Delivery Status"}	
	,{"v": "5","d":"Sales/Applications"}
	,{"v": "1","d":"Technical Support"}
	,{"v": "6","d":" - Other - "}
	]};
	
	//Build Product List TODO - Create from DB
	var jsonDataProducts={"Table":[
	{"v":"AVIA","d":"AVIA"}
	,{"v":"Azure","d":"Azure"}
	,{"v":"Chameleon","d":"Chameleon"}
	,{"v":"ChameleonOPO","d":"ChameleonOPO"}
	,{"v":"CO2","d":"CO2"}
	,{"v":"Compass","d":"Compass"}
	,{"v":"CUBE","d":"CUBE"}
	,{"v":"Daytona","d":"Daytona"}
	,{"v":"DiodeLasersandModules","d":"DiodeLasersandModules"}
	,{"v":"Evolution","d":"Evolution"}
	,{"v":"Excimer","d":"Excimer"}
	,{"v":"FAPSystems","d":"FAPSystems"}
	,{"v":"Fibers","d":"Fibers"}
	,{"v":"FLARE","d":"FLARE"}
	,{"v":"FlourescentLighting","d":"FlourescentLighting"}
	,{"v":"Genesis","d":"Genesis"}
	,{"v":"HELIOS","d":"HELIOS"}
	,{"v":"Hidra","d":"Hidra"}
	,{"v":"Highlight","d":"Highlight"}
	,{"v":"HYPERRAPID","d":"HYPERRAPID"}
	,{"v":"IonLasers","d":"IonLasers"}
	,{"v":"Legend","d":"Legend"}
	,{"v":"Libra","d":"Libra"}
	,{"v":"MATRIX","d":"MATRIX"}
	,{"v":"MBD","d":"MBD"}
	,{"v":"MBR","d":"MBR"}
	,{"v":"Mephisto","d":"Mephisto"}
	,{"v":"METABEAM","d":"METABEAM"}
	,{"v":"MetersandSensors","d":"MetersandSensors"}
	,{"v":"Micra","d":"Micra"}
	,{"v":"Mira","d":"Mira"}
	,{"v":"MiraOPO","d":"MiraOPO"}
	,{"v":"OBIS","d":"OBIS"}
	,{"v":"OMNIBEAM","d":"OMNIBEAM"}
	,{"v":"OPA","d":"OPA"}
	,{"v":"OPerA","d":"OPerA"}
	,{"v":"Other","d":"Other"}
	,{"v":"Paladin","d":"Paladin"}
	,{"v":"PhaseMask","d":"PhaseMask"}
	,{"v":"Prisma","d":"Prisma"}
	,{"v":"Prometheus","d":"Prometheus"}
	,{"v":"Radius","d":"Radius"}
	,{"v":"RAPID","d":"RAPID"}
	,{"v":"RegA","d":"RegA"}
	,{"v":"Sapphire","d":"Sapphire"}
	,{"v":"Staccato","d":"Staccato"}
	,{"v":"SUPERRAPID","d":"SUPERRAPID"}
	,{"v":"Talisker","d":"Talisker"}
	,{"v":"TOPAS","d":"TOPAS"}
	,{"v":"TracER","d":"TracER"}
	,{"v":"UltrafastAccessories","d":"UltrafastAccessories"}
	,{"v":"VarioLas","d":"VarioLas"}
	,{"v":"Vector","d":"Vector"}
	,{"v":"VerdiG","d":"VerdiG"}
	,{"v":"VerdiV","d":"VerdiV"}
	,{"v":"Vitara","d":"Vitara"}
	,{"v":"Vitesse","d":"Vitesse"}
	]};
			

	//Populate select boxes
	$(document).ready(function () {
		var listItemsCountry = '<option selected="selected" value=""> - - Select your Location  - - </option>';
		for (var i = 0; i < jsonDataCountry.Table.length; i++) {
 			listItemsCountry += "<option value='" + jsonDataCountry.Table[i].v + "'>" + jsonDataCountry.Table[i].d + "</option>";
		}
		
		$("#RequestTypeID").html(listItemsType);
				var listItemsType = '<option selected="selected" value=""> - - Select Request Type  - - </option>';
		for (var i = 0; i < jsonDataType.Table.length; i++) {
 			listItemsType += "<option value='" + jsonDataType.Table[i].v + "'>" + jsonDataType.Table[i].d + "</option>";
		}
		
		$("#ProductName").html(listItemsProducts);
				var listItemsProducts = '<option selected="selected" value=""> - - Select the Product  - - </option>';
		for (var i = 0; i < jsonDataProducts.Table.length; i++) {
 			listItemsProducts += "<option value='" + jsonDataProducts.Table[i].v + "'>" + jsonDataProducts.Table[i].d + "</option>";
		}
		
		//		
		$("#Countryid").html(listItemsCountry);
		$("#RequestTypeID").html(listItemsType);
		$("#ProductName").html(listItemsProducts);	
	});	

	// Set up an event listener for the contact form.
	$(form).submit(function(e) {
		// Stop the browser from submitting the form.
		e.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();

		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		})
		.done(function(response) {
			// Make sure that the formMessages div has the 'success' class.
			$(formMessages).removeClass('error');
			$(formMessages).addClass('success');

			// Set the message text.
			$(formMessages).text(response);

			// Clear the form.
			$('#name').val('');
			$('#email').val('');
			$('#message').val('');
		})
		.fail(function(data) {
			// Make sure that the formMessages div has the 'error' class.
			$(formMessages).removeClass('success');
			$(formMessages).addClass('error');

			// Set the message text.
			if (data.responseText !== '') {
				$(formMessages).text(data.responseText);
			} else {
				$(formMessages).text('Oops! An error occured and your message could not be sent.');
			}
		});

	});

});
