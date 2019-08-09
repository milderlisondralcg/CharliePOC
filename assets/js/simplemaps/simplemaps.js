$(document).ready(function(){
	// Function to reset all the "state" colors after receiving blue, selected, treatment.
	// All the states are targeted because it is uncertain, without keeping record, which
	// "states" were selected before resetting the map.
	function resetColor() {
        if (typeof stateColor !== "undefined"){
            for(var state in simplemaps_worldmap_mapdata.state_specific){
            	stateLoc = simplemaps_worldmap_mapdata.state_specific[state];
 
            	if (stateLoc.color !== undefined){
		            simplemaps_worldmap_mapdata.state_specific[state].color = stateColor;
		        }
	        }
	    }
	}

    // Create drop-down list for SimpleMaps
    var state_list = $("#state_list");

    for(var stateISOCode in simplemaps_worldmap_mapdata.state_specific){
        StateInactivity = simplemaps_worldmap_mapdata.state_specific[stateISOCode].inactive;

        if(StateInactivity != "yes"){
            var stateName = simplemaps_worldmap_mapdata.state_specific[stateISOCode].name;
            state_list.append($("<option></option>").attr("value",stateISOCode).text(stateName));
        } 
    }     

    // Drop-down change event that zooms into location when option is chosen from drop-down list.
    state_list.change(function(){
        var stateId    = $(this).val();

        // Global variables used in resetColor function to retrieve original state color.
        stateColor = simplemaps_worldmap_mapdata.state_specific[stateId].color;

        resetColor();

        simplemaps_worldmap_mapdata.state_specific[stateId].color = "#0076BF";

        simplemaps_worldmap.state_zoom(stateId, function() {
        	simplemaps_worldmap.popup("state", stateId); 
        });

        simplemaps_worldmap.refresh();
    });

    simplemaps_worldmap.hooks.back = function(){
    	// When you zoom out, reset the select and color.
        $("#state_list").val("");

        resetColor();

        simplemaps_worldmap.refresh();
    };
    
    $(".group_toggle").click(function(event){						
        var group_id=parseInt(this.id.substring(5));  
        working = true;
        for (var location in simplemaps_worldmap_mapdata.locations){				
            loc=simplemaps_worldmap_mapdata.locations[location];
            if (loc.group==group_id){						
                if (loc.hide!='yes'){loc.hide='yes'}						
                else{loc.hide='no'}
            }
        }
        simplemaps_worldmap.refresh();
    });	

    $("#group0").click(function(event) {
        var elem = document.getElementById("group0");
		if(elem.innerHTML == "Click Here to Show Representatives"){
			elem.innerHTML = "Click Here to Hide Representatives";
		}else{
			elem.innerHTML = "Click Here to Show Representatives";
		}
    });

    $("#group1").click(function(event) {
        var elem = document.getElementById("group1");
		if(elem.innerHTML == "Click Here to Show Coherent Locations"){
			elem.innerHTML = "Click Here to Hide Coherent Locations";
		}else {
			elem.innerHTML = "Click Here to Show Coherent Locations";
		}
    });
});