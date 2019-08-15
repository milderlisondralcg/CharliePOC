/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
var options = {
    svgPath: '/system/expressionengine/third_party/maps_updater/css/ui/icons.svg',
    btnsDef: {
        // Customizables dropdowns
        formattingLight: {
            dropdown: ['h1'],
            ico: 'p' // Apply formatting icon
        }
    },
    btns: [
    ['viewHTML'],
    ['formattingLight'],
    ['strong', 'em'],
    ['link'],
    ['horizontalRule'],
    ['fullscreen']
    ]
};
$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }    
});
$(document).ready(function(){
    $("html, body").animate({ scrollTop: 0}, 500);
	
});

	$(".group_select").change(function(){
		group_name = $(this).attr("name");
		selected_group = $("input[name='"+group_name+"']:checked").val();
		location_id_temp = $(this).closest('tr').attr("id");
		location_id = location_id_temp.substring(6);
		save_group(selected_group,location_id);
		
	});
	
function save_group(group,id){
	$.post( pointsPath, { "update_group": true, group: group, id: id } );
}
	
function checkArray(array, check){
    for (var i = 0; i < array.length; i++) {
        if (check == array[i])
            return true;
    }
    return false;
}
function buildSelect(type, page, value){
    var out;
    if(type == "Countries"){
        out = "<select multiple class='selectCountry createSelectize' name='"+page+"_countries[]'>";
        out = out + '<option value="">Select Countries</option>';
        var str = value.split(',!%%!,');
        for(var i = 0; i < countries.length; i++){
            if(checkArray(str, countries[i])){
                var addOption = '<option value="' + countries[i] + '" selected>' + countries[i] + '</option>';
            }else{
                var addOption = '<option value="' + countries[i] + '">' + countries[i] + '</option>';
            }
            out = out + addOption;
        }
        out = out + "</select>";
    }else if(type == "Products"){
        out = "<select multiple class='selectProduct createSelectize' name='"+page+"_products[]'>";
        out = out + '<option value="">Select Products</option>';
        var str = value.split(',!%%!,');
        for(var i = 0; i < products.length; i++){
            if(checkArray(str,products[i])){
                var addOption = '<option value="' + products[i] + '" selected>' + products[i] + '</option>';
            }else{
                var addOption = '<option value="' + products[i] + '">' + products[i] + '</option>';
            }
            out = out + addOption;
        }
        out = out + "</select>";
    }else if(type == "Categories"){
        out = "<select multiple class='selectCategory createSelectize' name='"+page+"_categories[]'>";
        out = out + '<option value="">Select Categories</option>';
        var str = value.split(',!%%!,');
        for(var i = 0; i < categories.length; i++){
            if(checkArray(str,categories[i])){
                var addOption = '<option value="' + categories[i] + '" selected>' + categories[i] + '</option>';
            }else{
                var addOption = '<option value="' + categories[i] + '">' + categories[i] + '</option>';
            }
            out = out + addOption;
        }
        if(checkArray(str, "Training")){
            var addOption = '<option value="Training" selected>Training</option>';
            out = out + addOption;
        }
        if(checkArray(str, "Sales")){
            var addOption = '<option value="Sales" selected>Sales</option>';
            out = out + addOption;
        }
        if(checkArray(str, "Service")){
            var addOption = '<option value="Service" selected>Service</option>';
            out = out + addOption;
        }
        out = out + "</select>";
    }else if(type == "Trainings"){
        out = "<select multiple class='selectTrainings createSelectize' name='"+page+"_trainings[]' onchange='updateCategoriesForTrainings(this)'>";
        out = out + '<option value="">Select Trainings</option>';
        var str = value.split(',!%%!,');
        for(var i = 0; i < products.length; i++){
            if(checkArray(str,products[i])){
                var addOption = '<option value="' + products[i] + '" selected>' + products[i] + '</option>';
            }else{
                var addOption = '<option value="' + products[i] + '">' + products[i] + '</option>';
            }
            out = out + addOption;
        }
        out = out + "</select>";
    }else if(type == "Content"){
        out = "<select multiple class='selectContent createSelectize' name='"+page+"_content[]'>";
        out = out + '<option value="">Select Content</option>';
        var str;
        str = value.split(',!%%!,');
        for(var i = 0; i < providers.length; i++){
            if(checkArray(str,providers[i])){
                var addOption = '<option value="' + providers[i] + '" selected>' + providers[i] + '</option>';
            }else{
                var addOption = '<option value="' + providers[i] + '">' + providers[i] + '</option>';
            }
            out = out + addOption;
        }
        out = out + "</select>";
    }else{
        out = "<select class='selectPriority' name='"+page+"_priorities[]'>";
        for(var i = 1; i < 11; i++){
            if(Number(value) == i){
                var addOption = '<option value="'+i+'" selected> Priority Level ' + i + '</option>';
            }else{
                var addOption = '<option value="'+i+'"> Priority Level ' + i + '</option>';
            }
            out = out + addOption;
        }
        out = out + "</select>";
    }
    return out;
}
function addLocation(){
    $('#mapLocations tr').last().remove();
    var td0 = '<td align="center" class="location_delete"><button type="button" class="btn btn-default btn-sm"  onClick="removeThisRow(this)" class="deleteEntry" ><span class="glyphicon glyphicon-trash"></span> Trash</button><input type="hidden" name="location_delete[]" value="false" /><input type="hidden" class="newEntry" name="location_new[]" value="true"/></td>';
    var td1 = '<td align="center" class="location_update"><input type="hidden" class="updateEntry" name="location_update_hidden[]" value="false" /><button type="button" class="btn btn-default btn-sm" id="updateLocation" onClick="insertUpdatePoint(this)"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button></td>';
    var td2 = '<td align="center" class="location_name"><input type="text" name="location_name[]" value="" placeholder="Name"/>';
    var td3 = '<td align="center" class="latlng"><span onclick="initAutocomplete(this)" style="display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;" class="glyphicon glyphicon-map-marker"></span><input type="text" style="display:none" class="googleAddress" name="location_google[]" /><div style="display:inline-block;width:70%;"><input type="text" name="location_lat[]" value="" placeholder="Latitude"/><br/><input type="text" name="location_lng[]" value="" placeholder="Longitude"/></div>';
    var td4 = '<td align="center" class="location_content">'+buildSelect('Content', 'location', '')+'</td>';
	var td5 = '<td align="center" class="border_color"><input type="color" name="border_color" onChange="update_location_color(this, 0)" value="#808080"/></td>';
	var td6 = '<td align="center" class="location_color"><input type="color" name="location_color" onChange="update_location_color(this, 0)" value="#2A5DA2"/></td>';
	var td7 = '<td align="center" class="border"><input type="text" name="border" value="1"/></td>';
	var td8 = '<td align="center" class="hover_color"><input type="color" name="hover_color" onChange="update_location_color(this, 0)" value="#0076BF"/></td>';
	var td9 = '<td><input type="radio" value="0" name="group_1043" class="group_select" checked>Representatives<br><input type="radio" value="1" name="group_1043" class="group_select">Training Offices <br><input type="radio" value="2" name="group_1043" class="group_select"> Sales/Service Offices<br><input type="radio" value="3" name="group_1043" class="group_select"> Service/Training Offices <br><input type="radio" value="4" name="group_1043" class="group_select"> Sales/Service/Training Offices <br></td>';
    var tbody = $('#mapLocations > tbody:last-child').append('<tr>'+td0+td1+td2+td3+td4+td5+td6+td7+td8+td9+'</tr>');

    var tr =  $('#mapLocations tr').last();
    var $select1 = tr.find('.createSelectize').selectize({
        maxItems: 200
    });
    var selectize1 = $select1[0].selectize;
    var scroll_to = $('#points').offset().top + $('#points').height();
    $("html, body").animate({ scrollTop: scroll_to}, "slow");
    $('#mapLocations > tbody:last-child').append('<tr><td></td><td align="center"><button type="button" onClick="addLocation()" class="btn btn-default btn-sm" class="addEntry" ><span class="glyphicon glyphicon-plus"></span> Add</button></td><td></td><td></td><td></td></tr>');
}
function editClick(item) {
    var tr = $(item).closest('tr');
    var table = $(item).closest('table');
    var name = tr.children('.location_name');
    var settingsvalue = tr.children('.setting_value');
    var statename = tr.children('.state_name');
    var latlng = tr.children('.latlng');
    var locContent = tr.children('.location_content');
    var content = tr.children('.state_content');
    var abbr = tr.children('.state_abbr');
    var stateName = tr.children('.state_name');
    var isActive = tr.children('.state_inactive');
    var title = tr.children('.state_title');
    var isZoom = tr.children('.state_zoomable');
    var color = tr.children('.state_color');
	var border_color = tr.children('.border_color');	
	var location_color = tr.children('.location_color');	
	var border = tr.children('.border');
	var hover_color = tr.children('.hover_color');
	var group = tr.children('.group');
    if(name.hasClass("location_name")){
        locationChange(name, latlng, locContent, border_color, location_color, border, hover_color, group);
    }else if(table.attr('id') == "mapProviders"){
        if(table.find('table').length<=0){
            providersChange(table,tr);
        }
    }else if(settingsvalue.hasClass("setting_value")){
        settingsChange(settingsvalue);
    }else{
        stateChange(abbr,stateName,isActive,content,isZoom,border_color, color, border);
    }
    $(this).attr("value",true);
};
function pointsChange(item){
    var tr = $(item).closest('tr');
    var name = tr.children('.location_name');
    var latlng = tr.children('.latlng');
    var locContent = tr.children('.location_content');
    var update = tr.children('td:nth-child(2)').children('.updateEntry');
    if(item.checked){
        locationChange(name, latlng, locContent);
        tr.children('td:nth-child(2)').html('<input type="checkbox" onchange="pointsChange(this)" class="updateEntry" name="location_update[]" value="false" checked/>');
    }else{
        revertLocation(name, latlng, locContent);
        tr.children('td:nth-child(2)').html('<input type="checkbox" onchange="pointsChange(this)" class="updateEntry" name="location_update[]" value="false"/>');
    }
}
function stateChangeMM(item){
    var tr = $(item).closest('tr');
    var abbr = tr.children('.state_abbr');
    var name = tr.children('.state_name');
    var isActive = tr.children('.state_inactive');
    var isZoom = tr.children('.state_zoomable');
    var content = tr.children('.state_content');
    var color = tr.children('.state_color');
    var update = tr.children('td:nth-child(1)').children('.updateEntry');
	var border = tr.children('.border');
    if(item.checked){
        stateChange(abbr,name,isActive,content,isZoom,color,border);
        $(this).attr("value",true);
    }else{
        revertState(abbr,name,isActive,content,isZoom,color,border);
        $(this).attr("value",false);
        tr.children('td:nth-child(1)').html('<input type="checkbox" onchange="stateChangeMM(this)" class="updateEntry" name="state_update[]" value="false"/>');
    }
}
function addSaveAttr(item){
    $(item).width(($(item).width() - 65));
    $(item).closest('td').append('<button type="button" class="btn btn-default btn-sm saveAttr"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>')
}
function addAttributeInput(btn){
    var td = $(btn).closest('td');
    $(btn).remove();
    var sName = td.attr('class') + '[]';
    var hName = td.attr('class') + '_hidden[]';
    td.html('<input type="hidden" name="'+hName+'" value=""><input type="text" onFocus="addSaveAttr(this)" onBlur="submitAttribute(this)" name="'+sName+'" value=""/>');
    td.find("input:text").focus();
}
function addProvider(){
    $('#mapProviders tr').last().remove();
    var td0 = '<td align="center" class="delete"><button type="button" class="btn btn-default btn-sm" onclick="deleteProvider(this)" class="deleteEntry" ><span class="glyphicon glyphicon-trash"></span> Trash</button><input type="hidden" name="provider_delete[]" value="false" /><input type="hidden" class="newEntry" name="provider_new[]" value="true"/></td>';
    var td1 = '<td align="center" class="update"><button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button></td>';
    var td2 = '<td style="margin:0;"><table class="table table-bordered updateProvider" style="margin:0;">'+
    '<tr><th>Title</th><td><input type="text" name="provider_title[]" value=""/></td></tr>'+
    '<tr><th>Address</th><td><input type="text" name="provider_address[]" value=""/></td></tr>'+
    '<tr><th>Phone</th><td><input type="text" name="provider_phone[]" value=""/></td></tr>'+
    '<tr><th>Email</th><td><input type="text" name="provider_email[]" value=""/></td></tr>'+
    '<tr><th>Website</th><td><input type="text" name="provider_website[]" value=""/></td></tr>'+
    '<tr><th>Countries</th><td>'+buildSelect("Countries", "provider", "")+'</td></tr>'+
    '<tr><th>Additional</th><td><textarea class="trubowyg" name="provider_additional[]" value=""></textarea></td></tr>'+
    '<tr><th>Categories</th><td>'+buildSelect("Categories", "provider", "")+'</td></tr>'+
    '<tr><th>Products</th><td>'+buildSelect("Products", "provider", "")+'</td></tr>'+
    '<tr><th>Trainings</th><td>'+buildSelect("Trainings", "provider", "")+'</td></tr>'+
    '<tr><th>Sales</th><td><input type="checkbox" onchange="checkChange(this)" name="provider_sales[]" value="0" /></td></tr>'+
	'<tr><th>Sales Phone</th><td><input type="text" name="provider_sales_phone[]" value="" /></td></tr>'+
	'<tr><th>Sales Email</th><td><input type="text" name="provider_sales_email[]" value="" /></td></tr>'+
    '<tr><th>Service</th><td><input type="checkbox" onchange="checkChange(this)" name="provider_service[]" value="0"/></td></tr>'+
	'<tr><th>Service Phone</th><td><input type="text" name="provider_service_phone[]" value=""/></td></tr>'+
	'<tr><th>Service Email</th><td><input type="text" name="provider_service_email[]" value=""/></td></tr>'+
	'<tr><th>Training Phone</th><td><input type="text" name="provider_training_phone[]" value=""/></td></tr>'+
	'<tr><th>Training Email</th><td><input type="text" name="provider_training_email[]" value=""/></td></tr>'+
    '<tr><th>Priority</th><td>'+buildSelect("Priority", "provider", "5")+'</td></tr>'+
    '<tr><th>Visible</th><td><input type="checkbox" onchange="checkChange(this)" name="provider_visible[]" value="0"</td></tr>'+
    '<tr><td style="border-right:0px;"></td><td align="right" style="border-left:0px;"><span class="providersWarning"></span><button type="button" class="btn btn-default btn-sm" onClick="updateProvider(this)" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button></td></tr>'+
    '</table>'+
    '</td>';
    
    var trID = $('#mapProviders tbody').children('tr').length;
    var tbody = $('#mapProviders > tbody:last-child').append('<tr id="'+(trID+1)+'">'+td0+td1+td2+'</tr>');
    var tr = $('.updateProvider').last();
    var scroll_to = $('.updateProvider').last().offset().top;
    $("html, body").animate({ scrollTop: scroll_to}, "slow");
    var $select1 = tr.find('.createSelectize').selectize({
        maxItems: 200
    });
    var selectize1 = $select1[0].selectize;
    var $select2 = tr.find('.selectPriority').selectize();
    var selectize2 = $select2[0].selectize;
    var input = tr.find('input:text[name="provider_address[]"]');
    initProviderAddress(input);
    tr.closest('td').attr('style', 'padding:0');
    tr.closest('table').closest('td').closest('tr').children('td:nth-child(2)').html('<button type="button" class="btn btn-default btn-sm" onClick="updateProvider2(this)" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
    tr.find('textarea[name="provider_additional[]"]').trumbowyg(options);
    $('.trumbowyg-box').attr("style", "margin:0;border:none;");
    $('.trumbowyg-box').closest('td').attr('style', "padding:0");
    $('#mapProviders > tbody:last-child').append('<tr><td></td><td align="center"><button type="button" onClick="addProvider()" class="btn btn-default btn-sm" class="addEntry" ><span class="glyphicon glyphicon-plus"></span> Add</button></td><td></td></tr>')
}
function checkChange(box){
    var providerTable = $(box).closest("table");
    var $select = providerTable.find('select[name="provider_categories[]"]');
    var selectize4 = $select[0].selectize;
    if($(box).val()=="0"){
        $(box).val('1');
    }else{
        $(box).val('0');
    }
    if($(box).attr('name') == "provider_sales[]" && $(box).val() == "1"){
        selectize4.addOption({value:"Sales",text:"Sales"});
        selectize4.refreshOptions(false);
        selectize4.addItem("Sales");
    }else if($(box).attr('name') == "provider_sales[]" && $(box).val() == "0"){
        selectize4.removeItem("Sales");
        selectize4.removeOption("Sales");
        selectize4.refreshOptions(false);
    }
    if($(box).attr('name') == "provider_service[]" && $(box).val() == "1"){
        selectize4.addOption({value:"Service",text:"Service"});
        selectize4.refreshOptions(false);
        selectize4.addItem("Service");
    }else if($(box).attr('name') == "provider_service[]" && $(box).val() == "0"){
        selectize4.removeItem("Service");
        selectize4.removeOption("Service");
        selectize4.refreshOptions(false);
    }
}
function providersChange(table, tr){
    var inside = "<table class='table table-bordered updateProvider' style='margin:0;'>";
    tr.children('td:nth-child(3)').children("input:hidden").each(function(i){
        var hName = $(this).attr('name');
        var sName = hName.replace("hidden_provider_", "").replace(/^\w/,function(chr){return chr.toUpperCase();}).replace("[]", "");
        var iName = $(this).attr('name').replace("hidden_", "");
        if(sName == "Sales" || sName == "Service" || sName == "Visible"){
            inside = inside + '<tr><th class="col-xs-2">'+sName+'</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="checkbox" onchange="checkChange(this)" name="'+iName+'" value="'+$(this).val()+'"'+checkBox($(this).val())+'/></td></tr>';    
        }else if(sName == "Additional"){
            inside = inside + '<tr><th class="col-xs-2">'+sName+'</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><textarea class="trubowyg" name="'+iName+'" value="'+$(this).val()+'"></textarea></td></tr>';
        }else if(sName == "Products" || sName == "Categories" || sName == "Countries" || sName == "Priority" || sName == "Trainings"){
            inside = inside + '<tr><th class="col-xs-2">'+sName+'</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/>'+buildSelect(sName, "provider", $(this).val())+'</td></tr>';
        }else if(sName == "Sales_phone"){
			inside = inside + '<tr><th class="col-xs-2">Sales Phone</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else if(sName == "Sales_email"){
			inside = inside + '<tr><th class="col-xs-2">Sales Email</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else if(sName == "Service_phone"){
			inside = inside + '<tr><th class="col-xs-2">Service Phone</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else if(sName == "Service_email"){
			inside = inside + '<tr><th class="col-xs-2">Service Email</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else if(sName == "Training_phone"){
			inside = inside + '<tr><th class="col-xs-2">Training Phone</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else if(sName == "Training_email"){
			inside = inside + '<tr><th class="col-xs-2">Training Email</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
		}else{
            inside = inside + '<tr><th class="col-xs-2">'+sName+'</th><td class="col-xs-10"><input type="hidden" name="'+hName+'" value="'+$(this).val()+'"/><input type="text" name="'+iName+'" value="'+$(this).val()+'"/></td></tr>';
        }
    });
    inside = inside + '<tr><td style="border-right:0px;"></td><td align="right" style="border-left:0px;"><span class="providersWarning"></span><button type="button" class="btn btn-default btn-sm" onClick="updateProvider(this)" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button></td></tr>';
    inside = inside + "</table>";
    tr.children('td:nth-child(3)').html(inside);
    var scroll_to = $('#mapProviders').find('tbody').find(tr).offset().top;
    $("html, body").animate({ scrollTop: scroll_to}, "slow");
    var $select1 = tr.children('td:nth-child(3)').find('.createSelectize').selectize({
        maxItems: 200
    });
    var selectize1 = $select1[0].selectize;
    var $select2 = tr.children('td:nth-child(3)').find('.selectPriority').selectize();
    var selectize2 = $select2[0].selectize;

    var input = tr.children('td:nth-child(3)').find('input:text[name="provider_address[]"]');
    initProviderAddress(input);
    tr.children('td:nth-child(3)').attr('style', 'padding:0');
    tr.children('td:nth-child(3)').find('textarea[name="provider_additional[]"]').trumbowyg(options);
    tr.children('td:nth-child(2)').html('<button type="button" class="btn btn-default btn-sm" onClick="updateProvider2(this)" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
    $('.trumbowyg-editor').html(tr.children('td:nth-child(3)').find('input:hidden[name="hidden_provider_additional[]"]').val())
    $('.trumbowyg-box').attr("style", "margin:0;border:none;");
    $('.trumbowyg-box').closest('td').attr('style', "padding:0");
}
function providersRevert(table, tr){
    var inside = "";
    var ending = "";
    tr.children('td:nth-child(3)').removeAttr('style');
    tr.children('td:nth-child(3)').find('input:hidden').each(function(i){
        inside = inside + '<input type="hidden" name="'+$(this).attr('name')+'" value="'+$(this).val()+'"/>';
        if($(this).attr('name')=="hidden_provider_title[]"){
            ending = ending + $(this).val();
        }else if($(this).attr('name')=="hidden_provider_address[]"){
            ending = ending + " : " + $(this).val();
        }
    });
    inside = inside + ending;
    tr.children('td:nth-child(3)').html(inside);
    tr.children('td:nth-child(2)').html('<button type="button" name="provider_update[]" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button>')
}

function locationChange(name, latlng, content, border_color, location_color, border, hover_color){ 
    var tr = name.closest('tr');
    var sbtn = tr.find('.location_update');
    sbtn.html('<input type="hidden" class="updateEntry" name="location_update_hidden[]" value="true" /><button type="button" class="btn btn-default btn-sm" id="updateLocation" onClick="insertUpdatePoint(this)"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
    name.html('<input type="hidden" name="location_name_hidden[]" value="'+name.find('input:hidden').val()+'"/><input type="text" name="location_name[]" value="'+name.find('input:hidden').val()+'"/>');
    latlng.html('<input type="hidden" name="location_lng_hidden[]" value="'+latlng.find('input:hidden[name="location_lng_hidden[]"]').val()+'"/><input type="hidden" name="location_lat_hidden[]" value="'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+'"/><span onclick="initAutocomplete(this)" style="display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;" class="glyphicon glyphicon-map-marker"></span><input type="text" style="display:none" class="googleAddress" name="location_google[]" /><div style="display:inline-block;width:70%;"><input type="text" name="location_lat[]" value="'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+'"/><br /><input type="text" name="location_lng[]" value="'+latlng.find('input:hidden[name="location_lng_hidden[]"]').val()+'"/></div>');
    content.html('<input type="hidden" name="location_content_hidden[]" value="'+content.find('input:hidden').val()+'"/>' + buildSelect("Content", "location", content.find('input:hidden').val()));
    var $select = content.find('.createSelectize').selectize({
        maxItems: 25
    });
    var selectize = $select[0].selectize;
    latlng.attr('style', 'padding-left:0px;padding-right:0px;');
	
	// border_color
	if(border_color.find('input:hidden').val() == ""){
        border_color.html('<input type="hidden" name="border_color_hidden" value="'+border_color.find('input:hidden').val()+'"/><input type="color" name="border_color" onChange="update_location_color(this, 0)" value="#808080"/>');
    }else{
        border_color.html('<input type="hidden" name="border_color_hidden" value="'+border_color.find('input:hidden').val()+'"/><input type="color" name="border_color" onChange="update_location_color(this, 0)" value="'+border_color.find('input:hidden').val()+'"/>');
    }
	
	// location_color - this is the color of the actual dot on the map
	if(location_color.find('input:hidden').val() == ""){
        location_color.html('<input type="hidden" name="location_color_hidden" value="'+location_color.find('input:hidden').val()+'"/><input type="color" name="location_color" onChange="update_location_color(this, 0)" value="#808080"/>');
    }else{
        location_color.html('<input type="hidden" name="location_color_hidden" value="'+location_color.find('input:hidden').val()+'"/><input type="color" name="location_color" onChange="update_location_color(this, 0)" value="'+location_color.find('input:hidden').val()+'"/>');
    }
	
	// border size
	border.html('<input type="hidden" name="border_hidden" value="'+border.find('input:hidden').val()+'"/><input type="text" name="border" value="'+border.find('input:hidden').val()+'"/>');
	
	// hover_color
	if(hover_color.find('input:hidden').val() == ""){
        hover_color.html('<input type="hidden" name="hover_color_hidden" value="'+hover_color.find('input:hidden').val()+'"/><input type="color" name="hover_color" onChange="update_location_color(this, 0)" value="#0076BF"/>');
    }else{
        hover_color.html('<input type="hidden" name="hover_color_hidden" value="'+hover_color.find('input:hidden').val()+'"/><input type="color" name="hover_color" onChange="update_location_color(this, 0)" value="'+hover_color.find('input:hidden').val()+'"/>');
    }
}
function settingsChange(setvalue){
    var sbtn = setvalue.closest('tr').find('.setting_update');
    sbtn.html('<input type="hidden" class="updateEntry" name="setting_update_hidden[]" value="true" /><button type="button" class="btn btn-default btn-sm" id="updateSetting" onClick="fnUpdateSetting(this)"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
    setvalue.html('<input type="hidden" name="setting_value_hidden[]" value="'+setvalue.find('input:hidden').val()+'"/><input type="text" onBlur="fnUpdateSetting(this)" name="setting_value[]" value="'+setvalue.find('input:hidden').val()+'"/>');
}
function revertLocation(name, latlng, content, border){
    var sbtn = name.closest('tr').find('.location_update');
    var _c={};
    sbtn.html('<button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button>');
    name.html('<input type="hidden" name="location_name_hidden[]" value="'+name.find('input:text').val()+'"/>'+name.find('input:text').val());
    name.removeAttr('align');
    latlng.html('<input type="hidden" name="location_lng_hidden[]" value="'+latlng.find('input:text[name="location_lng[]"]').val()+'"/><input type="hidden" name="location_lat_hidden[]" value="'+latlng.find('input:text[name="location_lat[]"]').val()+'"/>'+latlng.find('input:text[name="location_lat[]"]').val()+",<br>"+latlng.find('input:text[name="location_lng[]"]').val());
    if(content.find('select[name="location_content[]"]').val()){
        if(content.find('select[name="location_content[]"]').val().constructor === Array){
            _c = content.find('select[name="location_content[]"]').val().join(",!%%!,");
            
        }else{
            _c = content.find('select[name="location_content[]"]').val();
        }
    }else{
        _c = content.find('input:hidden').val();
    }
    content.html('<input type="hidden" name="location_content_hidden[]" value="'+_c+'"/>'+replaceAll(_c, ",!%%!,", ", "));
    content.removeAttr('align');
    latlng.attr('style', '');
	
	border.html('<input type="hidden" name="border_hidden[]" value="'+border.find('input:text').val()+'"/>'+border.find('input:text').val());
}
function revertSettings(setvalue){
    var sbtn = setvalue.closest('tr').find('.setting_update');
    sbtn.html('<button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button>');
    setvalue.html('<input type="hidden" name="location_name_hidden[]" value="'+setvalue.find('input:hidden').val()+'"/>'+setvalue.find('input:hidden').val());
}
function stateChange(abbr,name,isActive,content,isZoom,border_color, color,border){
    var tr = name.closest('tr');
    var sbtn = tr.find('.state_update');
    sbtn.html('<input type="hidden" class="updateEntry" name="state_update_hidden[]" value="true" /><button type="button" class="btn btn-default btn-sm" id="updateState" onClick="fnUpdateState(this, 1)"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
    abbr.html('<input type="hidden" name="state_abbr_hidden[]" value="'+abbr.find('input:hidden').val()+'"/><input type="text" name="state_abbr[]" onBlur="fnUpdateState(this, 0)" value="'+abbr.find('input:hidden').val()+'"/>');
    name.html('<input type="hidden" name="state_name_hidden[]" value="'+name.find('input:hidden').val()+'"/><input type="text" name="state_name[]" onBlur="fnUpdateState(this, 0)" value="'+name.find('input:hidden').val()+'"/>');
    isActive.html('<input type="hidden" name="state_inactive_hidden[]" value="'+isActive.find('input:checkbox').val()+'"/><input type="checkbox" name="state_inactive[]" onChange="changeBox(this)" onBlur="fnUpdateState(this, 0)" value="'+isActive.find('input:checkbox').val()+'"'+checkBox(isActive.find('input:checkbox').val())+'/>');
    
    content.html('<input type="hidden" name="state_content_hidden[]" value="'+content.find('input:hidden').val()+'"/>' + buildSelect("Content", "state", content.find('input:hidden').val()));
    isZoom.html('<input type="hidden" name="state_zoomable_hidden[]" value="'+isZoom.find('input:checkbox').val()+'"/><input type="checkbox" name="state_zoomable[]" onChange="changeBox(this)" onBlur="fnUpdateState(this, 0)" value="'+isZoom.find('input:checkbox').val()+'" '+checkBox(isZoom.find('input:checkbox').val())+'/>');
    var $select = content.find('.createSelectize').selectize({
        maxItems: 213
    });
    var selectize = $select[0].selectize;
    content.find('.selectize-input').find('input:text').attr('onBlur','fnUpdateState(this, 0)');
    
	border_color.html('<input type="hidden" name="border_color_hidden[]" value="'+border_color.find('input:hidden').val()+'"/><input type="text" name="border_color[]" onBlur="fnUpdateState(this, 0)" value="'+border_color.find('input:hidden').val()+'"/>');
	
	if(color.find('input:hidden').val() == ""){
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input:hidden').val()+'"/><input type="color" name="state_color[]" value="#F2F2F2"/>');
    }else{
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input:hidden').val()+'"/><input type="color" name="state_color[]" onChange="fnUpdateState(this, 0)" value="'+color.find('input:hidden').val()+'"/>');
    }
	border.html('<input type="hidden" name="border_hidden[]" value="'+border.find('input:hidden').val()+'"/><input type="text" name="border[]" onBlur="fnUpdateState(this, 0)" value="'+border.find('input:hidden').val()+'"/>');
}
function replaceAll(str, find, replace) {
    return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}
function revertState(abbr,name,isActive,content,isZoom,color,border){
    var sbtn = name.closest('tr').find('.state_update');
    var _c={};
    sbtn.html('<button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button>');
    abbr.html('<input type="hidden" name="state_abbr_hidden[]" value="'+abbr.find('input:text').val()+'"/>'+abbr.find('input:text').val());
    name.html('<input type="hidden" name="state_name_hidden[]" value="'+name.find('input:text').val()+'"/>'+name.find('input:text').val());
    isActive.html('<input type="checkbox" name="state_inactive[]" value="'+isActive.find('input:checkbox').val()+'" disabled="disabled" '+checkBox(isActive.find('input:checkbox').val())+'/>');
    if(content.find('select[name="state_content[]"]').val()){
        if(content.find('select[name="state_content[]"]').val().constructor === Array){
            _c = content.find('select[name="state_content[]"]').val().join(",!%%!,");
            
        }else{
            _c = content.find('select[name="state_content[]"]').val();
        }
    }else{
        _c = content.find('input:hidden').val();
    }
    content.html('<input type="hidden" name="state_content_hidden[]" value="'+_c+'"/>'+replaceAll(_c, ",!%%!,", ", "));
    isZoom.html('<input type="checkbox" name="state_zoomable[]" value="'+isZoom.find('input:checkbox').val()+'" disabled="disabled" '+checkBox(isZoom.find('input:checkbox').val())+'/>');
    if(color.find('input[name="state_color[]"]').val()){
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input[name="state_color[]"]').val()+'"/>');
    }else{
        color.html('<input type="hidden" name="state_color_hidden[]" value=""/>No Color');    
    }
	border.html('<input type="hidden" name="border_hidden[]" value="'+border.find('input:text').val()+'"/>'+border.find('input:text').val());
}
function checkBox(check){
    if(check == "true" || check=="1"){
        return "checked";
    }else{
        return "";
    }
}
function removeThisRow(item){
    $(item).closest('tr').remove();
}
function changeBox(item){
    if($(item).val()=="true"){
        $(item).attr("value",false);
    }else{
        $(item).attr("value",true);
    }
}
function initProviderAddress(item){
    var autocomplete = new google.maps.places.Autocomplete(item[0]);
}
function initAutocomplete(item){
    var input = $(item).closest('td').children('input:text[name="location_google[]"]');
    input.attr("style", "display:block");
    $(item).closest("td").find('span').attr("style", "display:none");
    $(item).closest("td").find('input:text[name="location_lat[]"]').attr("style", "display:none");
    $(item).closest("td").find('input:text[name="location_lng[]"]').attr("style", "display:none");
    var autocomplete = new google.maps.places.Autocomplete(input[0]);
    input.focus();
    autocomplete.addListener('place_changed', function(){
        var place = autocomplete.getPlace();
        $(item).closest("td").find('input:text[name="location_lat[]"]').val(place.geometry.location.lat());
        $(item).closest("td").find('input:text[name="location_lng[]"]').val(place.geometry.location.lng());
        input.attr("style", "display:none");
        $(item).closest("td").find('span').attr("style", "display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;");
        $(item).closest("td").find('input:text[name="location_lat[]"]').attr("style", "");
        $(item).closest("td").find('input:text[name="location_lng[]"]').attr("style", "");
    });
    input[0].addEventListener('blur', function(){
        input.attr("style", "display:none");
        $(item).closest("td").find('span').attr("style", "display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;");
        $(item).closest("td").find('input:text[name="location_lat[]"]').attr("style", "");
        $(item).closest("td").find('input:text[name="location_lng[]"]').attr("style", "");
    });
}
function deletePoint(btn){
    var tr = $(btn).closest('tr');
    var n = tr.children('.location_name');
    var ll = tr.children('.latlng');
    var c = tr.children('.location_content');
    var u = tr.children('td:nth-child(2)').children('.updateEntry');
    u.attr("value", false);
    var id = tr.attr('id').replace('point-', '');
    var sendData = {};
    sendData["delete"] = true;
    sendData["index"] = Number(id);
    $.ajax({
        url: pointsPath,
        type: 'POST',
        data: sendData,
        success: function(msg) {
            tr.remove();
        }
    });
}
function insertUpdatePoint(item){
    var tr = $(item).closest('tr');
    var td = $(item).closest('td');
    var n = tr.find('td.location_name');
    var _new = tr.find('input.newEntry');
    var l = tr.find('td.latlng');
    var c = tr.find('td.location_content');
    var u = tr.find('input.updateEntry');
	var border_color = tr.find('td.border_color'); 
	var location_color = tr.find('td.location_color'); 
	var border = tr.find('td.border'); // border size
	var hover_color = tr.find('td.hover_color');
	var location_group = tr.find('td.location_group');
	

	for (var i = 0, length = location_group.length; i < length; i++) {
		if (location_group[i].checked) {
			// do whatever you want with the checked radio
			alert(location_group[i].value);

			// only one radio can be logically checked, don't check the rest
			break;
		}
	}
	
	//location_group
    var sendData = {};
    if(_new.val() == "true"){
        sendData["insert"] = true;
        sendData["name"] = n.find('input').val();
        sendData["lat"] = l.find('input[name="location_lat[]"]').val();
        sendData["lng"] = l.find('input[name="location_lng[]"]').val();
        var _c = "";
        if(c.find('select[name="location_content[]"]').val().constructor === Array){
            sendData["content"] = c.find('select[name="location_content[]"]').val().join(",!%%!,");
            _c = sendData["content"];
        }else{
            sendData["content"] = c.find('select[name="location_content[]"]').val();
            _c = sendData["content"];
        }
        $.ajax({
            url: pointsPath,
            type: 'POST',
            data: sendData,
            success: function(msg) {
                n.append('<input type="hidden" name="location_name_hidden[]" value="'+sendData["name"]+'" />');
                l.append('<input type="hidden" name="location_lat_hidden[]" value="'+sendData["lat"]+'" />');
                l.append('<input type="hidden" name="location_lng_hidden[]" value="'+sendData["lng"]+'" />');
                c.append('<input type="hidden" name="location_content_hidden[] value="'+_c+'" />');
				border.append('<input type="hidden" name="border_hidden[]" value="'+sendData["border"]+'" />');
                u.closest('td').html('<input type="checkbox" class="updateEntry" name="location_update[]" value="true" checked/>');
                tr.children('td:nth-child(1)').html('<button type="button" class="btn btn-default btn-sm" onclick="deletePopup(this, \'point\')" class="deleteEntry" ><span class="glyphicon glyphicon-trash"></span> Trash</button><input type="hidden" name="location_delete[]" value="false" /><input type="hidden" class="newEntry" name="location_new[]" value="false"/>');
                tr.attr('id', 'point-' + Number(msg));
            }
        });
        revertLocation(n,l,c);
    }else if(u.val() == "true"){ 
	console.log("u val is true");
        var id = tr.attr('id').replace('point-', '');
        sendData["index"] = Number(id);
        sendData["update"] = u.val();
        sendData["name"] = n.find('input:text').val();
        sendData["lat"] = l.find('input[name="location_lat[]"]').val();
        sendData["lng"] = l.find('input[name="location_lng[]"]').val();
		sendData["border_color"] = border_color.find('input[name="border_color"]').val();
		sendData["location_color"] = location_color.find('input[name="location_color"]').val();
		sendData["border"] = border.find('input[name="border"]').val();
		sendData["hover_color"] = hover_color.find('input[name="hover_color"]').val();
        var _c = "";
        if(c.find('select[name="location_content[]"]').val().constructor === Array){
            sendData["content"] = c.find('select[name="location_content[]"]').val().join(",!%%!,");
            _c = sendData["content"];
            
        }else{
            sendData["content"] = c.find('select[name="location_content[]"]').val();
            _c = sendData["content"];
        }
		console.log(sendData);
        $.ajax({
            url: pointsPath,
            type: 'POST',
            data: sendData,
            success: function(msg) {
                n.find('input:hidden').val(sendData["name"]);
                l.find('input:hidden[name="location_lat_hidden[]"]').val(sendData["lat"]);
                l.find('input:hidden[name="location_lng_hidden[]"]').val(sendData["lng"]);
				border_color.find('input:hidden[name="border_color_hidden"]').val(sendData["border_color"]);
				location_color.find('input:hidden[name="location_color_hidden"]').val(sendData["location_color"]);
				border.find('input:hidden[name="border_hidden"]').val(sendData["border"]);
				hover_color.find('input:hidden[name="hover_color_hidden"]').val(sendData["hover_color"]);
                c.find('input:hidden').val(_c);
                u.closest('tr').children('td:nth-child(1)').attr('onClick', 'deletePopup(this, \'point\')');
            }
        });
        revertLocation(n,l,c, border);
    }
}
function update_location_color(item,close){
	console.log(item);
	var selected_color = "";
	tr = $(item).closest('tr'); 
	input_name = item.getAttribute("name");
	console.log(input_name);
	if(input_name == "hover_color"){
		var hover_color = tr.find('td.hover_color');
		selected_color = hover_color.find('input[name="hover_color"]').val();
		hover_color.css('background-color',selected_color);			
	}else if( input_name == "border_color" ){		
		var border_color = tr.find('td.border_color');
		selected_color = border_color.find('input[name="border_color"]').val();
		console.log(selected_color);
		border_color.css('background-color',selected_color);	
	}else{
		var location_color = tr.find('td.location_color');
		selected_color = location_color.find('input[name="location_color"]').val();
		console.log(selected_color);
		location_color.css('background-color',selected_color);			
	}
	
}

function fnUpdateState(item, close){
    var tr = $(item).closest('tr');
    var a = tr.find('td.state_abbr');
    var n = tr.find('td.state_name');
    var i = tr.find('td.state_inactive');
    var c = tr.find('td.state_content');
    var z = tr.find('td.state_zoomable');
	var hover_color = tr.find('td.hover_color');
    var color = tr.find('td.state_color');
	var border = tr.find('td.border');
    var u = tr.find('td:nth-child(1)').find('input.updateEntry');
    var sendData = {};
    sendData["update"] = true;
    sendData["abbr"] = a.find('input:text').val();
    sendData["name"] = n.find("input:text").val();
    sendData["inactive"] = i.find("input:checkbox").val();
	try{
		if(c.find('select[name="state_content[]"]').val().constructor === Array){
			sendData["content"] = c.find('select[name="state_content[]"]').val().join(",!%%!,");
		}else{
			sendData["content"] = c.find('select[name="state_content[]"]').val();
		}
	}catch(err){
		console.log(err);
	}
    sendData["zoomable"] = z.find("input:checkbox").val();
    sendData["color"] = color.find('input[name="state_color[]"]').val();
	sendData["border"] = border.find('input:text').val();
    $.ajax({
        url: locationPath,
        type: "POST",
        data: sendData,
        success: function(msg){
            a.find("input:hidden").val(sendData["abbr"]);
            n.find("input:hidden").val(sendData["name"]);
            i.find("input:hidden").val(sendData["inactive"]);
            c.find("input:hidden").val(sendData["content"]);
            z.find("input:hidden").val(sendData["zoomable"]);
            color.find("input:hidden").val(sendData["color"]);
            color.css('background-color',color.find('input:hidden').val());
			border.find("input:hidden").val(sendData["border"]);
			hover_color.find("input:hidden").val(sendData["hover_color"]);
            hover_color.css('background-color',color.find('input:hidden').val());
        }

    });
    if(close == "1"){
        revertState(a,n,i,c,z,color,border);
    }
}

function fnUpdateSetting(item){
    var tr = $(item).closest('tr');
    var k = tr.find('td.setting_name');
    var v = tr.find('td.setting_value');
    var sendData = {};
    sendData["update"] = true;
    sendData["name"] = k.find('input:hidden').val();
    sendData["value"] = v.find('input:text').val();
    $.ajax({
        url: settingsPath,
        type: "POST",
        data: sendData,
        success: function(msg){
            v.find('input:hidden').val(sendData["value"]);
            revertSettings(v);
        }
    });
}
function updateProvider2(item){
    newItem = $(item).closest('tr').children('td:nth-child(3)').find('table.updateProvider').find('button.btn');
    updateProvider(newItem);
}
function updateProvider(item){
    var table = $(item).closest('table').closest('table');
    var tr = $(item).closest('table').closest('tr');
    var tb = $(item).closest('tbody');
    var _id = tr.attr('id');
    var wrng = tb.find('span.providersWarning');
    var _new = tr.find('input[name="provider_new[]"]');
    var t = tb.find('input[name="provider_title[]"]');
    var a = tb.find('input[name="provider_address[]"]');
    var p = tb.find('input[name="provider_phone[]"]');
    var e = tb.find('input[name="provider_email[]"]');
    var w = tb.find('input[name="provider_website[]"]');
    var cou = tb.find('select[name="provider_countries[]"]');
    var _cou;
    var add = tb.find('.trumbowyg-editor');
    var cat = tb.find('select[name="provider_categories[]"]');
    var _cat;
    var pro = tb.find('select[name="provider_products[]"]');
    var _pro;
    var tra = tb.find('select[name="provider_trainings[]"]');
    var _tra;
    var s = tb.find('input:checkbox[name="provider_sales[]"]');
	
	var sales_phone = tb.find('input[name="provider_sales_phone[]"]'); 
	var sales_email = tb.find('input[name="provider_sales_email[]"]'); 
	var service_phone = tb.find('input[name="provider_service_phone[]"]'); 
	var service_email = tb.find('input[name="provider_service_email[]"]'); 
	var training_phone = tb.find('input[name="provider_training_phone[]"]'); 
	var training_email = tb.find('input[name="provider_training_email[]"]'); 

	
    var ser = tb.find('input:checkbox[name="provider_service[]"]');
    var pri = tb.find('select[name="provider_priorities[]"]');
    var _pri;
    var v = tb.find('input:checkbox[name="provider_visible[]"]');
    var sendData = {};
    if(_new.val()=="true"){
        sendData["insert"] = true;
    }else{
        sendData["update"] = true;
        sendData["id"] = _id;
    }
    sendData["title"] = t.val();
    sendData["address"] = a.val();
    sendData["phone"] = p.val();
    sendData["email"] = e.val();
    sendData["website"] = w.val();
    if(cou.val().constructor === Array){
        _cou = cou.val().join(",!%%!,");
    }else{
        _cou = cou.val();
    };
    sendData["countries"] = _cou;
    sendData["additional"] = add.html().replace(/"/g, "'");
    if(cat.val().constructor === Array){
        _cat = cat.val().join(",!%%!,");
    }else{
        _cat = cat.val();
    };
    sendData["categories"] = _cat;
    if(pro.val().constructor === Array){
        _pro = pro.val().join(",!%%!,");
    }else{
        _pro = pro.val();
    };
    sendData["products"] = _pro;

    if(tra.val().constructor === Array){
        _tra = tra.val().join(",!%%!,");
    }else{
        _tra = tra.val();
    };
    sendData["trainings"] = _tra;
    sendData["sales"] = s.val();
	
	sendData["sales_phone"] = sales_phone.val();
	sendData["sales_email"] = sales_email.val();
	sendData["service_phone"] = service_phone.val();
	sendData["service_email"] = service_email.val();
	sendData["training_phone"] = training_phone.val();
	sendData["training_email"] = training_email.val();
	
    sendData["service"] = ser.val();

    if(pri.val().constructor === Array){
        _pri = pri.val().join(",!%%!,");
    }else{
        _pri = pri.val();
    };
    sendData["priority"] = _pri;
    sendData["visible"] = v.val();
    if(_tra != "" && _cat.indexOf("Training") === -1){
        _cat = _cat + ",!%%!,Training";
        sendData["categories"] = _cat;
    }
    if(s.val() === "1" && _cat.indexOf("Sales") === -1){
        _cat = _cat + ",!%%!,Sales";
        sendData["categories"] = _cat;
    }
    if(ser.val() === "1" && _cat.indexOf("Service") === -1){
        _cat = _cat + ",!%%!,Service";
        sendData["categories"] = _cat;
    }
    if(sendData["title"] == ""){
        wrng.text("Please enter a title.");
        wrng.show();
        setTimeout(function(){wrng.fadeOut("slow");}, 1000);
    }else{
        $.ajax({
            url: providersPath,
            type: "POST",
            data: sendData,
            success: function(msg){
                if(msg == "Please choose a different title."){
                    wrng.text(msg);
                    wrng.show();
                    setTimeout(function(){wrng.fadeOut("slow");}, 1000);
                }else{
                    wrng.hide();
                    wrng.text("");
                    var beginning ='<input type="hidden" name="';
                    var middle = '" value="';
                    var ending = '"/>';					
                    if(_new.val()!="true"){
                        if(v.val() == "1" && v.closest('td').find('input:hidden').val() == "1"){
                            var proNum = providers.indexOf(t.closest('td').find('input:hidden').val());
                            if(proNum > -1){
                                providers[proNum] = t.val();
                                providers.sort();
                            }
                        }else if(v.val() == "1" && v.closest('td').find('input:hidden').val() == "0"){
                            providers.push(t.val());
                            providers.sort();
                        }else if(v.val() == "0"){
                            var proID = providers.indexOf(t.closest('td').find('input:hidden').val());
                            if(proID > -1){
                                providers.splice(proID, 1);
                            }
                        }
                        t.closest('td').find('input:hidden').val(t.val());
                        a.closest('td').find('input:hidden').val(a.val());
                        p.closest('td').find('input:hidden').val(p.val());
                        e.closest('td').find('input:hidden').val(e.val());
                        w.closest('td').find('input:hidden').val(w.val());
                        cou.closest('td').find('input:hidden').val(_cou);
                        add.closest('td').find('input:hidden').val(add.html().replace(/"/g, "'"));
                        cat.closest('td').find('input:hidden').val(_cat);
                        pro.closest('td').find('input:hidden').val(_pro);
                        tra.closest('td').find('input:hidden').val(_tra);
                        s.closest('td').find('input:hidden').val(s.val());
                        ser.closest('td').find('input:hidden').val(ser.val());
                        pri.closest('td').find('input:hidden').val(_pri);
                        v.closest('td').find('input:hidden').val(v.val());
						sales_phone.closest('td').find('input:hidden').val(sales_phone.val());
						sales_email.closest('td').find('input:hidden').val(sales_email.val());
						service_phone.closest('td').find('input:hidden').val(service_phone.val());
						service_email.closest('td').find('input:hidden').val(service_email.val());
						training_phone.closest('td').find('input:hidden').val(training_phone.val());
						training_email.closest('td').find('input:hidden').val(training_email.val());						
						
                    }else{
                        if(v.val() == "1" && t.val() != ""){
                            providers.push(t.val());
                            providers.sort();
                        }
                        tr.children('td:nth-child(3)').append((beginning+"hidden_provider_title[]"+middle+t.val()+ending));
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_address[]"+middle+a.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_phone[]"+middle+p.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_email[]"+middle+e.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_website[]"+middle+w.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_countries[]"+middle+_cou+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_additional[]"+middle+add.html()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_categories[]"+middle+_cat+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_products[]"+middle+_pro+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_trainings[]"+middle+_tra+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_sales[]"+middle+s.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_service[]"+middle+ser.val()+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_priority[]"+middle+_pri+ending);
                        tr.children('td:nth-child(3)').append(beginning+"hidden_provider_visible[]"+middle+v.val()+ending);
                        tr.children('td:nth-child(1)').find('button').attr('onClick', 'deletePopup(this, \'provider\')');
						console.log(beginning+"hidden_provider_sales_phone[]"+middle+sales_phone.val()+ending);
						tr.children('td:nth-child(3)').append((beginning+"hidden_provider_sales_phone[]"+middle+sales_phone.val()+ending));
						
                        _new.val('false');
                        tr.attr('id', Number(msg));
                    }
                    providersRevert(table,tr);
                    var scroll_to = $('#mapProviders').find('tbody').find(tr).offset().top;
                    $("html, body").animate({ scrollTop: scroll_to}, "slow");
                }
                
            }
        });
    }
}
function deleteProvider(btn){
    var tr = $(btn).closest('tr');
    var _id = tr.attr('id');
    var t = $(btn).closest('tr').children('td:nth-child(3)').find('input:hidden[name="hidden_provider_title[]"]');
    var _new = tr.find('input[name="provider_new[]"]');
    if(_new.val()!="true"){
        var sendData = {};
        sendData["delete"] = true;
        sendData["id"] = _id;
        $.ajax({
            url: providersPath,
            type: "POST",
            data: sendData,
            success: function(msg){
                var proID = providers.indexOf(t.closest('td').find('input:hidden').val());
                if(proID > -1){
                    providers.splice(proID, 1);
                }
                tr.remove();
            }
        });
    }else{
        tr.remove();
    }
}
function submitAttribute(item){
    var sendData = {};
    var temp = {};
    $(item).closest('td').find('button.saveAttr').remove();
    $(item).css("width", "100%");
    //update values in category, product, and countries databases
    if($(item).val()!='' && $(item).closest('td').find('input:hidden').val()!='' && $(item).val() != $(item).closest('td').find('input:hidden').val()){
        if($(item).attr('name')=='categories[]'){
            temp["category"] = $(item).val();
            temp["hidden"] = $(item).closest('td').find('input:hidden').val();
            var catID = categories.indexOf($(item).closest('td').find('input:hidden').val());
            if(catID > -1){
                categories[catID] = $(item).val();
                categories.sort();
            }
        }else if($(item).attr('name')=='products[]'){
            temp["product"] = $(item).val();
            temp["hidden"] = $(item).closest('td').find('input:hidden').val();
            var proID = products.indexOf($(item).closest('td').find('input:hidden').val());
            if(proID > -1){
                products[proID] = $(item).val();
                products.sort();
            }
        }
        sendData["attributes"] = temp;
        sendAttr(sendData);
        $(item).closest('td').find('input:hidden').val($(item).val());

    //delete values in category, product, and countries databases
    }else if($(item).val()=='' && $(item).closest('td').find('input:hidden').val()!=''){
        temp["delete"] = $(item).closest('td').find('input:hidden').val();
        temp["hidden"] = $(item).attr('name');
        if($(item).attr('name') == "products[]"){
            var proID = products.indexOf($(item).closest('td').find('input:hidden').val());
            if(proID > -1){
                products.splice(proID, 1);
                products.sort();
            }
        }else if($(item).attr('name') == "categories[]"){
            var catID = categories.indexOf($(item).closest('td').find('input:hidden').val());
            if(catID > -1){
                categories.splice(catID, 1);
                categories.sort();
            }
        }
        sendData["attributes"] = temp;
        sendAttr(sendData);
        $(item).closest('tr').remove();

    //insert values in category, product, and countries databases
    }else if($(item).val()!='' && $(item).closest('td').find('input:hidden').val()==''){
        temp["insert"] = $(item).val();
        temp["hidden"] = $(item).attr('name');
        if($(item).attr('name') == "products[]"){
            products.push($(item).val());
            products.sort();
        }else if($(item).attr('name') == "categories[]"){
            categories.push($(item).val())
            categories.sort();
        }
        sendData["attributes"] = temp;
        sendAttr(sendData);
        $(item).closest('td').find('input:hidden').val($(item).val());

        //add button to end of table
        if($(item).closest("tr").is(":last-child")){
            var catBtn = '<td class="categories"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td>';
            var proBtn = '<td class="products"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td>';
            var str = "<tr>";
            if($('#mapAttributesCategories').find('td.categories > button').length == 0){
                str = str + catBtn;
                str = str + "</tr>";
                $('#mapAttributesCategories > tbody:last-child').append(str);
            }

            if($('#mapAttributesProducts').find('td.products > button').length == 0){
                str = str + proBtn;
                str = str + "</tr>";
                $('#mapAttributesProducts > tbody:last-child').append(str);
            }
        }else{
            var catBtn = '<button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button>';
            var proBtn = '<button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button>';
            if($('#mapAttributes').find('td.categories > button').length == 0){
                $(item).closest("tr").next('tr').children('.categories').html(catBtn);
            }
            if($('#mapAttributes').find('td.products > button').length == 0){
                $(item).closest("tr").next('tr').children('.products').html(proBtn);
            }
        }
    }else if($(item).val()=="" && $(item).closest('td').find('input:hidden').val()==""){
        $(item).closest('td').html('<button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button>');        
    }
    
}
function sendAttr(sendData){
        $.ajax({
            url: attributesPath,
            type: 'POST',
            data: sendData,
            success: function(msg) {

            }               
        });
}
$(document).mouseup(function(e) 
{
    var container = $("#deletePop");

    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        closePopup();
    }
});
function deletePopup(btn, type){
    var t = "";
    if(type == "provider"){
        t = $(btn).closest('tr').children('td:nth-child(3)').find('input[name="hidden_provider_title[]"]');
    }else if (type == "point"){
        t = $(btn).closest('tr').children('td:nth-child(3)').find('input[name="location_name_hidden[]"]');
    }
    $(btn).closest('td').append('<div id="deletePopContainer"><div id="deletePop">Are you sure you would like to delete the '+type+': '+t.val()+'<br><br><button type="button" class="btn btn-default btn-sm" onClick="closePopup()">Cancel</button>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onClick="confirmDelete(this,\''+type+'\')">Delete</button></div></div>');
}
function closePopup(){
    $('#deletePopContainer').closest('td').css("position", "");
    $('#deletePopContainer').remove();
}
function confirmDelete(btn, type){
    if(type=="point"){
        deletePoint(btn);
    }else if(type = "provider"){
        deleteProvider(btn);
    }
}
function updateCategoriesForTrainings(item){
    var providerTable = $(item).closest("table");
    var $select = providerTable.find('select[name="provider_categories[]"]');
    var selectize4 = $select[0].selectize;
    if($(item).val() != ""){
        selectize4.addOption({value:"Training",text:"Training"});
        selectize4.refreshOptions(false);
        selectize4.addItem("Training");
    }else if($select.val().indexOf("Training") !== -1){
        selectize4.removeItem("Training");
        selectize4.removeOption("Training");
        selectize4.refreshOptions(false);
    }
}
function updateAllProducts(){
    $.ajax({
        url: attributesPath,
        type: 'POST',
        data: {
            "allproducts": true
        },
        success: function(msg) {
            var newproducts = JSON.parse(msg);
            $("#mapAttributesStaticProducts").empty();
            var insertrows = "<tbody>";
            for(var i = 0; i < newproducts.length; i++){
                var add = '<tr><td class="staticProducts">'+newproducts[i]+'</td></tr>'
                insertrows += add;
            }
            insertrows += "</tbody>"
            $("#mapAttributesStaticProducts").append(insertrows);
        }
    });
}