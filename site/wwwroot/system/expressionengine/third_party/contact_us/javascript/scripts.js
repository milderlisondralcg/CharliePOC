/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
var options = {
    svgPath: '/system/expressionengine/third_party/simplemaps_updater/css/ui/icons.svg',
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
$(document).ready(function(){
    $("html, body").animate({ scrollTop: 0}, 500);
});
$(window).on('scroll', function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height()-250) {
        $('.formButtons').attr("style", "position:static; margin:0px; float:right;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;");
    }else{
        $('.formButtons').attr("style", "position:fixed;");
    }
});
function selectCountries(){
    var out = "<select class='selectCountry' name='location_countries[]'>";
    out = out + '<option value="">Select Countries</option>'
    for(var i = 0; i < countries.length; i++){
        var addOption = '<option value="' + countries[i] + '">' + countries[i] + '</option>';
        out = out + addOption;
    }
    out = out + "</select>";
    return out;
}
$('#addLocation').click(function(){
    var td0 = '<td align="center" class="location_delete"><input type="checkbox" class="deleteEntry" name="location_delete[]" value="false" onClick="removeThisRow(this)" /><input type="hidden" class="newEntry" name="location_new[]" value="true"/></td>';
    var td1 = '<td align="center" class="location_update"><input type="checkbox" class="updateEntry" name="location_update[]" value="false" disabled="disabled"/></td>';
    var td2 = '<td align="center" class="location_name"><input type="text" name="location_name[]" value="" placeholder="Name"/>';
    var td4 = '<td align="center" class="latlng"><span onclick="initAutocomplete(this)" style="display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;" class="glyphicon glyphicon-map-marker"></span><input type="text" style="display:none" class="googleAddress" name="location_google[]" /><div style="display:inline-block;width:70%;"><input type="text" name="location_lat[]" value="" placeholder="Latitude"/><br/><input type="text" name="location_lng[]" value="" placeholder="Longitude"/></div>';
    var td5 = '<td align="center" class="title"><input type="text" name="location_title[]" value="" placeholder="Title"/></td>';
    var td6 = '<td align="center" class="description"><textarea class="trumbowyg" name="location_description[]"></textarea></td>';
    var td7 = '<td align="center" class="additional"><textarea class="trumbowyg" name="location_additional[]"></textarea></td>';
    var td3 = '<td align="center" class="countries">'+selectCountries()+'</td>';
    var tbody = $('#mapLocations > tbody:last-child').append('<tr>'+td0+td1+td2+td5+td4+td6+td7+td3+'</tr>');

    $('#mapLocations tr').last().children(".countries").find('select').selectize({
        maxItems: 213
    });
    $('#mapLocations tr').last().children(".description").find('.trumbowyg').trumbowyg(options);
    $('#mapLocations tr').last().children(".additional").find('.trumbowyg').trumbowyg(options);
    $('#mapLocations tr').last().children(".description").attr('style', 'background-color:white;padding:0px');
    $('#mapLocations tr').last().children(".additional").attr('style', 'background-color:white;padding:0px');
    $('.trumbowyg-box').attr("style", "margin:0px");
    var scroll_to = $('#points').offset().top + $('#points').height();
    $("html, body").animate({ scrollTop: scroll_to}, "slow");
});
$("input:checkbox[class='updateEntry']").change(function() {
    var tr = $(this).closest('tr');
    var name = tr.children('.location_name');
    var settingsvalue = tr.children('.setting_value');
    var statename = tr.children('.state_name');
    var latlng = tr.children('.latlng');
    var locTitle = tr.children('.title');
    var pntDesc = tr.children('.description');
    var pntAdd = tr.children('.additional');
    var countries = tr.children('.countries');
    var abbr = tr.children('.state_abbr');
    var stateName = tr.children('.state_name');
    var isActive = tr.children('.state_inactive');
    var title = tr.children('.state_title');
    var stateDesc = tr.children('.state_description');
    var additional = tr.children('.state_additional');
    var isZoom = tr.children('.state_zoomable');
    var color = tr.children('.state_color');
    if(this.checked){
        if(name.hasClass("location_name")){
            locationChange(name, latlng, locTitle, pntDesc, pntAdd, countries);
        }else if(settingsvalue.hasClass("setting_value")){
            settingsChange(settingsvalue);
        }else{
            stateChange(abbr,stateName,isActive,title,stateDesc,additional,isZoom,color);
        }
        $(this).attr("value",true);
    }
    else{
        if(name.hasClass("location_name")){
            revertLocation(name, latlng, locTitle, pntDesc, pntAdd, countries);
        }else if(settingsvalue.hasClass("setting_value")){
            revertSettings(settingsvalue);
        }else{
            revertState(abbr,stateName,isActive,title,stateDesc,additional,isZoom,color,tr);
        }
        $(this).attr("value", false);
    }
});
$("input:checkbox[class='deleteEntry']").on('change', function() {
    var tr = $(this).closest('tr');
    var name = tr.children('.location_name');
    var statename = tr.children('.state_name');
    var latlng = tr.children('.latlng');
    var title = tr.children('.title');
    var pntDesc = tr.children('.description');
    var pntAdd = tr.children('.additional');
    var countries = tr.children('.countries');
    var update = tr.children('.location_update').children('.updateEntry');
    if(this.checked) {
        if(update.val() == "true"){
            revertLocation(name, latlng, title, pntDesc, pntAdd, countries);
            update.attr("disabled", "disabled");
            update.attr("value", false);
            update.prop("checked",false);
        }else{
            update.attr("disabled", "disabled");
            update.attr("value", false);
        }
        $(this).attr("value",true);
    }else{
        update.removeAttr("disabled");
        $(this).attr("value",false);
    }
});
function locationChange(name, latlng, title, desc, add, countries){
    name.html('<input type="hidden" name="location_name_hidden[]" value="'+name.find('input:hidden').val()+'"/><input type="text" name="location_name[]" value="'+name.find('input:hidden').val()+'"/>');
    latlng.html('<input type="hidden" name="location_lng_hidden[]" value="'+latlng.find('input:hidden[name="location_lng_hidden[]"]').val()+'"/><input type="hidden" name="location_lat_hidden[]" value="'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+'"/><span onclick="initAutocomplete(this)" style="display:inline-block;width:25%;height:50px;vertical-align:middle;cursor:pointer;" class="glyphicon glyphicon-map-marker"></span><input type="text" style="display:none" class="googleAddress" name="location_google[]" /><div style="display:inline-block;width:70%;"><input type="text" name="location_lat[]" value="'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+'"/><br /><input type="text" name="location_lng[]" value="'+latlng.find('input:hidden[name="location_lng_hidden[]"]').val()+'"/></div>');
    title.html('<input type="hidden" name="location_title_hidden[]" value="'+title.find('input:hidden').val()+'"/><input type="text" name="location_title[]" value="'+title.find('input:hidden').val()+'"/>');
    desc.html('<input type="hidden" name="location_description_hidden[]" value="'+desc.find('input:hidden').val()+'"/><textarea type="text" class="trumbowyg" name="location_description[]">'+desc.find('input:hidden').val()+'</textarea>');
    add.html('<input type="hidden" name="location_additional_hidden[]" value="'+add.find('input:hidden').val()+'"/><textarea type="text" class="trumbowyg" name="location_additional">'+add.find('input:hidden').val()+'</textarea>');
    countries.html('<input type="hidden" name="location_countries_hidden[]" value="'+countries.find('input:hidden').val()+'"/>'+selectCountries());
    
    var $select = countries.find('select').selectize({
        maxItems: 213
    });
    var selectize = $select[0].selectize;
    var newCountries = countries.find('input:hidden').val().split(",");
    for(var i = 0; i < newCountries.length; i++){
        if(newCountries[i].charAt(0) == " "){
            newCountries[i] = newCountries[i].substr(1);
        }
    }
    selectize.setValue(newCountries, false);
    desc.find('.trumbowyg').trumbowyg(options);
    add.find('.trumbowyg').trumbowyg(options);
    desc.find('#editor').trumbowyg('html', desc.find('input:hidden').val());
    add.find('#editor').trumbowyg('html',add.find('input:hidden').val());
    desc.attr('style', 'background-color:white;padding:0px');
    add.attr('style', 'background-color:white;padding:0px');
    latlng.attr('style', 'padding-left:0px;padding-right:0px;')
    $('.trumbowyg-box').attr("style", "margin:0px");
}
function settingsChange(setvalue){
    setvalue.html('<input type="hidden" name="setting_value_hidden[]" value="'+setvalue.find('input:hidden').val()+'"/><input type="text" name="setting_value[]" value="'+setvalue.find('input:hidden').val()+'"/>');
}
function revertLocation(name, latlng, title, desc, add, countries){
    name.html('<input type="hidden" name="location_name_hidden[]" value="'+name.find('input:hidden').val()+'"/>'+name.find('input:hidden').val());
    latlng.html('<input type="hidden" name="location_lng_hidden[]" value="'+latlng.find('input:hidden[name="location_lng_hidden[]"]').val()+'"/><input type="hidden" name="location_lat_hidden[]" value="'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+'"/>'+latlng.find('input:hidden[name="location_lat_hidden[]"]').val()+",<br>"+latlng.find('input:hidden[name="location_lng_hidden[]"]').val());
    title.html('<input type="hidden" name="location_title_hidden[]" value="'+title.find('input:hidden').val()+'"/>'+title.find('input:hidden').val());
    desc.html('<input type="hidden" name="location_description_hidden[]" value="'+desc.find('input:hidden').val()+'"/>'+desc.find('input:hidden').val());
    add.html('<input type="hidden" name="location_additional_hidden[]" value="'+add.find('input:hidden').val()+'"/>'+add.find('input:hidden').val());
    countries.html('<input type="hidden" name="location_countries_hidden[]" value="'+countries.find('input:hidden').val()+'"/>'+countries.find('input:hidden').val());
    
    desc.attr('style', 'background-color:none;');
    add.attr('style', 'background-color:none;');
    latlng.attr('style', '');
}
function revertSettings(setvalue){
    setvalue.html('<input type="hidden" name="location_name_hidden[]" value="'+setvalue.find('input:hidden').val()+'"/>'+setvalue.find('input:hidden').val());
}
function stateChange(abbr,name,isActive,title,desc,additional,isZoom,color){
    abbr.html('<input type="hidden" name="state_abbr_hidden[]" value="'+abbr.find('input:hidden').val()+'"/><input type="text" name="state_abbr[]" value="'+abbr.find('input:hidden').val()+'"/>');
    name.html('<input type="hidden" name="state_name_hidden[]" value="'+name.find('input:hidden').val()+'"/><input type="text" name="state_name[]" value="'+name.find('input:hidden').val()+'"/>');
    isActive.html('<input type="hidden" name="state_inactive_hidden[]" value="'+isActive.find('input:checkbox').val()+'"/><input type="checkbox" name="state_inactive[]" onChange="changeBox(this)" value="'+isActive.find('input:checkbox').val()+'"'+checkBox(isActive.find('input:checkbox').val())+'/>');
    title.html('<input type="hidden" name="state_title_hidden[]" value="'+title.find('input:hidden').val()+'"/><input type="text" name="state_title[]" value="'+title.find('input:hidden').val()+'"/>');
    desc.html('<input type="hidden" name="state_desc_hidden[]" value="'+desc.find('input:hidden').val()+'"/><textarea class="trumbowyg" name="state_desc[]">'+desc.find('input:hidden').val()+'</textarea>');
    additional.html('<input type="hidden" name="state_additional_hidden[]" value="'+additional.find('input:hidden').val()+'"/><textarea class="trumbowyg" name="state_additional[]">'+additional.find('input:hidden').val()+'</textarea>');
    isZoom.html('<input type="hidden" name="state_zoomable_hidden[]" value="'+isZoom.find('input:checkbox').val()+'"/><input type="checkbox" name="state_zoomable[]" onChange="changeBox(this)" value="'+isZoom.find('input:checkbox').val()+'" '+checkBox(isZoom.find('input:checkbox').val())+'/>');
    
    if(color.find('input:hidden').val() == ""){
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input:hidden').val()+'"/><input type="color" name="state_color[]" value="#F2F2F2"/>');
    }else{
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input:hidden').val()+'"/><input type="color" name="state_color[]" value="'+color.find('input:hidden').val()+'"/>');
    }
    
    
    desc.find('.trumbowyg').trumbowyg(options);
    additional.find('.trumbowyg').trumbowyg(options);
    desc.find('#editor').trumbowyg('html', desc.find('input:hidden').val());
    additional.find('#editor').trumbowyg('html',additional.find('input:hidden').val());
    desc.attr('style', 'background-color:white;padding:0px');
    additional.attr('style', 'background-color:white;padding:0px');
    $('.trumbowyg-box').attr("style", "margin:0px");
}
function revertState(abbr,name,isActive,title,desc,additional,isZoom,color,tr){
    abbr.html('<input type="hidden" name="state_abbr_hidden[]" value="'+abbr.find('input:hidden').val()+'"/>'+abbr.find('input:hidden').val());
    name.html('<input type="hidden" name="state_name_hidden[]" value="'+name.find('input:hidden').val()+'"/>'+name.find('input:hidden').val());
    isActive.html('<input type="checkbox" name="state_inactive[]" value="'+isActive.find('input:hidden').val()+'" disabled="disabled" '+checkBox(isActive.find('input:hidden').val())+'/>');
    title.html('<input type="hidden" name="state_title_hidden[]" value="'+title.find('input:hidden').val()+'"/>'+title.find('input:hidden').val());
    if(desc.find('input:hidden').val()){
        desc.html('<input type="hidden" name="state_desc_hidden[]" value="'+desc.find('input:hidden').val()+'"/>'+desc.find('input:hidden').val());
    }else{
        desc.html('<input type="hidden" name="state_desc_hidden[]" value=""/>No Description');
    }
    if(additional.find('input:hidden').val()){
        additional.html('<input type="hidden" name="state_additional_hidden[]" value="'+additional.find('input:hidden').val()+'"/>'+additional.find('input:hidden').val());
    }else{
        additional.html('<input type="hidden" name="state_additional_hidden[]" value=""/>No Additional Information');
    }
    isZoom.html('<input type="checkbox" name="state_zoomable[]" value="'+isZoom.find('input:hidden').val()+'" disabled="disabled" '+checkBox(isZoom.find('input:hidden').val())+'/>');
    if(color.find('input:hidden').val()){
        color.html('<input type="hidden" name="state_color_hidden[]" value="'+color.find('input:hidden').val()+'"/>');
    }else{
        color.html('<input type="hidden" name="state_color_hidden[]" value=""/>No Color Selected');    
    }
    desc.attr('style', 'background-color:none;');
    additional.attr('style', 'background-color:none;');
}
function checkBox(check){
    if(check == "true"){
        return "checked";
    }
    else{
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