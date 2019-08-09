// var product_nav;

// $.ajax({
//     url: "/assets/json/rich_nav/product-nav.json",
//     type: "GET",
//     dataType: "json",
//     cache: false,
//     success: function(data) {
//         var product_nav = data;
        
//         var units_of_measurement_map = {
//             "ActiveArea":"mm",
//             "Aperture Size":"mm",
//             "Beam Diameter":"mm",
//             "Calibration Uncertainty":"%",
//             "Cooling Width":"mm",
//             "Laser Pulse Width":"&#181;m",
//             "Material Thickness":"mm",
//             "Max. Energy":"J",
//             "Max. Power":"mW",
//             "Max. Work Area":"in/mm",
//             "Min. Energy":"J",
//             "Min. Power":"mW",
//             "Pixel Size":"&#181;m",
//             "Power":"mW",
//             "Precision":"&#181;m",
//             "Pulse Width":"ns",
//             "Repetition Rate":"kHz",
//             "Wavelength":"nm"
//         };

//         $(document).ready(function() {
//             $(document).on("click", ".mobile-menu-dd17 .js-mobile-filter", function(e){
//                 e.stopPropagation();
//                 $(this).siblings().children('.mobile-menu-filter').animate({ right: '0'}, 800, function() { $(this).show();});
//             });               
            
//             $(document).on("click", ".mobile-menu-dd17 .sub-js-mobile-filter", function(e){
//                 e.stopPropagation();
//                 document.getElementById("sub-mobile-filter").style.width = "75%";
//             });            
            
//             $(document).on("click", ".mobile-menu-dd17 .mobile-menu-done", function(e){
//                 e.stopPropagation();
//                 $(this).parents().parents('.mobile-menu-filter').animate({right: '-585px'}, 800, function() { $(this).hide();});
//             });            
            
//             $(document).on("click", ".mobile-menu-dd17 .mobile-menu-clear", function(e){
//                 var this_menu_head = $(this).parent().siblings(".mobile-filter");
//                 $(".filter-option", this_menu_head).removeClass("active");

// 				$(".mobile-menu-dd17-data .tbody .tr").addClass("filtering");
                
//                 setTimeout(filter_and_sort_dd17_menu, 1);
//             });
            
//             $(document).on("click", ".mobile-menu-dd17 .filter-options .filter-option", function(e){
//                 $(this).toggleClass("active");
//                 $(".mobile-menu-dd17-data .tbody .tr").addClass("filtering");

//                 if($(".mobile-menu-dd17 .filter-options .filter-option.active").length){
//                     $(this).children(".mobile-menu-clear").removeClass("disabled");
//                 }else{
//                     $(this).children(".mobile-menu-clear").addClass("disabled");
//                 }

//                 // 1 milli second delay to this filter_and_sort_dd17_menu function, to allow the above toggleClass to appear without interferance 
//                 // from this function, and remove the delay the user expereienced when checking a filter option. The check 
//                 // is expected to appear immediately.
//                 setTimeout(filter_and_sort_dd17_menu, 1);
//             });

//             build_dd17_products_mobile_menu();

//         });
        
//         function filter_and_sort_dd17_menu(){
//             var parent = $('.mobile-menu-dd17-data').parent();
//             console.log(parent);
//             var visible_thead = $(".mobile-menu-dd17-data .js-accordion .tr-wrapper .js-accordion-control");
            
//             var category_index = $(".th", visible_thead).attr("data-category-index");
//             var sub_category_index = $(".th", visible_thead).attr("data-sub-category-index");
//             var sort_dir = $(".th", visible_thead).attr("data-sort");
            
//             var colum_name_formatted = format_name($(".th .sort .sort-name", visible_thead).html(), "_");
//             var table_element = visible_thead.parents().parents().parents().siblings(".tbody");
            
//             if(sub_category_index == "none"){
//                 var items = product_nav['categories'][parseInt(category_index)].items;
//                 var column_count = product_nav['categories'][parseInt(category_index)].columns.length;
//             }else{
//                 var items = product_nav['categories'][parseInt(category_index)].sub_categories[parseInt(sub_category_index)].items;
//                 var column_count = product_nav['categories'][parseInt(category_index)].sub_categories[parseInt(sub_category_index)].columns.length;
//             }
            
//             items.sort(function(a, b) {
//                 return compareStrings(a.columns[colum_name_formatted], b.columns[colum_name_formatted]);
//             });
            
//             if(sort_dir == "desc"){
//                 items.reverse();
//             }            
            
//             var panel_output = "";
            
//             for(var r = 0; r < items.length; r++) {
//                 var show_this_item = 1;
//                 var filter_array = [];

//                 $(".filter-option.active", visible_thead).each(function(){
//                     var filter_column_key = format_name($(this).parent().parent().parent().children(".sort").find(".sort-name").html(), "_");
//                     var filter_column_value = $(this).children("span").html();
                    
//                     if(filter_array.hasOwnProperty(filter_column_key)){
//                         filter_array[filter_column_key] = filter_array[filter_column_key] + "||" + filter_column_value;
//                     }else{
//                         filter_array[filter_column_key] = filter_column_value;
//                     }
//                 });
                
//                 for (var f in filter_array){
//                     var this_col_ok = 0;
//                     if(filter_array[f].indexOf("||") !== -1){
//                         filter_values_array = filter_array[f].split("||");
//                         for(var v = 0; v < filter_values_array.length; v++) {
//                             var temp = $("<div>" + items[r]['columns'][f] + "</div>");
//                             temp.find("a").remove();
//                             var this_value = temp.html();

// 	                        // Create array from comma delimited list.
// 	                        var this_value_array = this_value.split(',');
	                        
// 	                        // Remove leading and trailing spaces from string elements of the array.
// 	                        for (var i = 0; i < this_value_array.length; i++){
// 	                        	this_value_array[i] = this_value_array[i].trim();
// 	                        }

// 	                        // Search for current value in array.
// 	                        if(this_value_array.indexOf($('<textarea />').html(filter_values_array[v]).text()) > -1 ){
// 	                        	this_col_ok = 1;
// 	                        }                            
//                         }
//                     }else{
//                         var temp = $("<div>" + items[r]['columns'][f] + "</div>");
//                         temp.find("a").remove();
//                         var this_value = temp.html();

//                         // Create array from comma delimited list.
//                         var this_value_array = this_value.split(',');
                        
//                         // Remove leading and trailing spaces from string elements of the array.
//                         for (var i = 0; i < this_value_array.length; i++){
//                         	this_value_array[i] = this_value_array[i].trim();
//                         }

//                         // Search for current value in array.
//                         if(this_value_array.indexOf($('<textarea />').html(filter_array[f]).text()) > -1 ){
//                         	this_col_ok = 1;
//                         }
//                     }
                    
//                     if(this_col_ok == 0){
//                         show_this_item = 0;
//                     }
//                 }
                
//                 if(show_this_item == 1){
//                     panel_output += '<div class="tr group filtering" data-url="' + items[r]['url'] + '">';
                    
//                     var first_col = 1;
//                     for (var k in items[r]['columns']){
//                         if (items[r]['columns'][k] !== 'function') {
//                             if(first_col == 1){
//                                 panel_output += '<div class="th col_' + column_count + '">' + items[r]['columns'][k] + '</div>';
//                                 first_col = 0;
//                             }else{
//                                 if(items[r]['columns'][k].indexOf("<compare_checkbox>") !== -1){
//                                     var checkbox_id = format_name(items[r]['columns']["product_name"], "-");
//                                     panel_output += '<div class="td compare-col col_' + items[r]['columns'].length + '">' +
//                                                         '<input type="checkbox" id="' + checkbox_id + '" />' +
//                                                         '<label for="' + checkbox_id + '">Select</label>' +
//                                                     '</div>';
//                                 }else{
//                                     panel_output += '<div class="td col_' + column_count + '">' + items[r]['columns'][k] + '</div>';
//                                 }
//                             }
//                         }
//                     }
                    
//                     panel_output +=     '</div>';
//                 }
//             }
            
//             if(panel_output == ""){
//                 panel_output = '<div class="no-results">There are no results for your filtering options. Please broaden your filters.<br />To clear all filters, <span class="clear-filters-link">click here</span>.</div>';
//             }

//             table_element.html(panel_output);

//             $(".mobile-menu-dd17-data .tbody .tr").hide().fadeIn("slow");

//             $(".mobile-menu-dd17-data .tbody .tr").removeClass("filtering");

//             // size_dd17_menus();
//         }
        
//         function build_column_filter(categories, category_index, key_filter, sub_category_available, sub_category_index = 0){
//             var filter_list = '';

//             if (sub_category_available) {
//                 for(var filter_item in categories[category_index].sub_categories[sub_category_index][key_filter]) {
//                     filter_list += '<div class="filter-option"><span>' + categories[category_index].sub_categories[sub_category_index][key_filter][filter_item] + '</span></div>';
//                 }
//             }else{
//                 for(var filter_item in categories[category_index][key_filter]) {
//                     filter_list += '<div class="filter-option"><span>' + categories[category_index][key_filter][filter_item] + '</span></div>';
//                 }
//             }

//             return filter_list;
//         }

//         function build_dd17_products_mobile_menu(){
//             var categories = product_nav['categories'];
            
//             var output =    '<!-- Mobile Menu -->' +
//                             '<div class="mobile-menu-dd17">' +
//                                 '<!-- Mobile Header -->' +
//                                 '<div class="mobile-header group">' +
//                                     '<div class="mobile-header-logo vcenter">' +
//                                         '<a href="/"><img src="/assets/site_images/logo-mobile.png" alt="Coherent" /></a>' +
//                                     '</div>' +
//                                     '<div class="mobile-header-btn vcenter">' +
//                                         '<span id="js-mobile-icon" class="far fa-bars" aria-hidden="true"></span>' +
//                                     '</div>' +
//                                 '</div>' +
//                                 '<div class="mobile-nav-top-level-wrapper js-mobile-top-level">' +
//                                     '<ul class="mobile-nav-top-level">' +
//                                         '<li><a href="javascript:void(0);" data-subnav="products">Products</a></li>' +
//                                         '<li><a href="/applications">Applications</a></li>' +
//                                         '<li><a href="/support">Support</a></li>' +
//                                         '<li><a href="/company">Company</a></li>' +
//                                         '<li><a href="/company/contact">Contact</a></li>' +
//                                     '</ul>' +
//                                     '<form class="mobile-nav-top-level-search" action="//lasers.coherent.com/search" method="get">' +
//                                         '<div class="row">' +
//                                             '<div class="col full">' +
//                                                 '<label for="mobile_search">Search</label>' +
//                                                 '<input type="text" id="mobile_search" name="w" placeholder="Search" />' +
//                                             '</div>' +
//                                             '<div class="col">' +
//                                                 '<button type="submit"><i class="fa fa-search"></i></button>' +
//                                             '</div>' +
//                                         '</div>' +
//                                     '</form>' +
//                                 '</div>' +
//                                 '<div class="mobile-nav-level-2 js-mobile-nav-level-2 products">' +
//                                     '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
//                                     '<ul class="subnav group js-mobile-nav-level-2-subnav">' +
//                                         '{{products_top_level}}' +
//                                     '</ul>' +
//                                 '</div>';
            
            
//             var products_top_level_html = "";
//             for(var c = 0; c < categories.length; c++) {
//                 var category_formatted = format_name(categories[c].name, "-");
                
//                 products_top_level_html += '<li><a class="subnav-' + format_name(categories[c].name, "-") + '" href="javascript:void(0);" data-tab="dd17-cat-' + c + '"><span>' + categories[c].name + '</span></a></li>';
                
//                 if(!categories[c].hasOwnProperty('sub_categories')){
//                     output += '<div class="mobile-nav-level-3 js-tab-content" id="dd17-cat-' + c + '">' +
//                                     '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
//                                     '<a class="mobile-nav-filter js-mobile-filter" href="##">Filter</a>' +
//                                     '<div class="mobile-menu-dd17-data">' +
//                                         '<div class="mobile-menu-filter mobile-menu-filter-' + c + '">' +
//                                             '<p class="mobile-menu-filter-title">Filters</p>' +
//                                             '<div class="mobile-menu-buttons">' + 
//                                                 '<a class="mobile-menu-clear" href="##">Clear</a>' +
//                                                 '<a class="mobile-menu-done" href="##">Done</a>' + 
//                                             '</div>' +
//                                             '<div class="mobile-filter js-accordion">';
                    
//                                 for(var j = 0; j < categories[c].columns.length; j++) {
//                                     var col_name_spaces_removed = categories[c].columns[j].toLowerCase().replace(/ /g, '_');
//                                     var col_filter_list = build_column_filter(categories, c, col_name_spaces_removed + '_key_filter');
                                    
//                                     if(j === 0) {
//                                         var sorted_by = "1";
//                                         var filter_html = "";
//                                         output += "";
//                                     }
//                                     else {
//                                         var sorted_by = "0";
                                        
//                                         var sort_attributes = 'data-sort="asc" data-sorted-by="' + sorted_by + '"';
//                                         var unit_of_meas = typeof units_of_measurement_map[categories[c].columns[j]] === "undefined" ? "" : '&nbsp;&nbsp;(' + units_of_measurement_map[categories[c].columns[j]] + ')';
                                        
//                                         output +=   '<div class="tr-wrapper">' +
//                                                         '<div class="tr header group js-accordion-control">' +
//                                                             '<div class="th" data-category="' + category_formatted + '" data-category-index="' + c + '" data-sub-category-index="none" ' + sort_attributes + '>' + 
//                                                                 '<span id="sort" class="sort">'+ 
//                                                                     '<span class="sort-name">' + categories[c].columns[j] + '</span>' + 
//                                                                 '</span>'+ unit_of_meas +
//                                                             '</div>' + 
//                                                         '</div>' +
//                                                         '<div class="js-accordion-panel">' +
//                                                             '<div class="filter-by-box">' +
//                                                                 '<div class="filter-options">' + col_filter_list + '</div>' +
//                                                             '</div>' +
//                                                         '</div>' +
//                                                     '</div>'; 
//                                     }  
//                                 }

//                             output += '</div>' +
//                                         '</div>' +
//                                         '<div class="thead">' +
//                                             '<div class="tr group">';
                                            
//                     if(categories[c].columns[categories[c].columns.length-1].toLowerCase() == "compare"){
//                         output += '<div class="th"><a href="javascript:void(0)" data="' + format_name(categories[c].columns[0], "_") + '" data-sort="asc">' + categories[c].columns[0] + '</a></div>' +
//                                   '<div class="th">Compare</div>' +
//                                   '<div class="th">Go</div>';
//                         var has_compare = 1;
//                     }else{
//                         output += '<div class="th no-compare"><a href="javascript:void(0)" data="' + format_name(categories[c].columns[0], "_") + '" data-sort="asc" data-category-index="' + c + '" data-sub-category-index="none">' + categories[c].columns[0] + '</a></div>' +
//                                   '<div class="th">Go</div>';
//                         var has_compare = 0;
//                     }
                    
//                     output +=           '</div>' +
//                                     '</div>' +
//                                     '<div class="tbody js-accordion">';
                                    
//                     for(var i = 0; i < categories[c].items.length; i++) {
                        
//                         output +=   '<div class="tr-wrapper">' +
//                                             '<div class="tr header group js-accordion-control">' +
//                                                 '<div class="th"><span>' + categories[c].items[i].columns[format_name(categories[c].columns[0], "_")] + '</span></div>';
                                                
//                         if(has_compare == 1){
//                             output +=          '<div class="td">' +
//                                                     '<span>' +
//                                                         '<input type="checkbox" id="mobile-' + format_name(categories[c].items[i].columns[format_name(categories[c].columns[0], "_")], "-") + '" />' +
//                                                         '<label for="mobile-' + format_name(categories[c].items[i].columns[format_name(categories[c].columns[0], "_")], "-") + '"><span>Select</span></label>' +
//                                                     '</span>' +
//                                                 '</div>';
//                         }
                        
//                         output +=               '<div class="td arrow-link">' +
//                                                     '<a href="' + categories[c].items[i].url + '">' +
//                                                         '<i class="fa fa-arrow-right vcenter"></i>' +
//                                                     '</a>' +
//                                                 '</div>' +
//                                             '</div>' +
//                                             '<div class="js-accordion-panel">';
                                            
//                         var column_count = 0;
//                         for (var k in categories[c].items[i]['columns']){
//                             if(column_count != 0){
//                                 if(k != "compare"){
//                                     output += '<div class="tr group">' +
//                                                     '<div class="th">' + categories[c].columns[column_count] + '</div>' +
//                                                     '<div class="td">' + categories[c].items[i]['columns'][k] + '</div>' +
//                                               '</div>';
//                                 }
//                             }
//                             column_count++;
//                         }
                        
//                         output +=           '</div>' +
//                                     '</div>';
                        
//                     }
                    
//                     output +=       '</div>' +
//                                 '</div>'; // close mobile-menu-dd17-data
                                
//                     if(has_compare == 1){
//                         output += '<div class="menu-footer group">' +
//                                     '<div class="compare-buttons">' +
//                                         '<a href="javascript:void(0);" class="compare-selected-button disabled">Compare Selected</a>' +
//                                         '<a href="javascript:void(0);" class="compare-reset-button disabled">Reset</a>' +
//                                     '</div>' +
//                                 '</div>';
//                     }
                    
//                     output += '</div>';
                    
//                 }else{ //sub-categories start
                    
//                     output += '<div class="mobile-nav-level-4 js-tab-content" id="dd17-cat-' + c + '">' +
//                                     '<a class="mobile-nav-back js-mobile-back" href="javascript:void(0);">Back</a>' +
//                                     '<ul class="mobile-nav-level-4-sub-nav js-level-4-sub-nav">';
                                    
//                     for(var s = 0; s < categories[c].sub_categories.length; s++) {
//                         output += '<li><a href="javascript:void(0);" data-tab="dd17-cat-' + c + '-sub-' + s + '"><img src="' + categories[c].sub_categories[s].image + '" alt="' + categories[c].sub_categories[s].name + '" /><span>' + categories[c].sub_categories[s].name + '</span></a></li>';
//                     }
                    
//                     output +=          '</ul>' +
//                               '</div>';
                    
//                     for(var s = 0; s < categories[c].sub_categories.length; s++) {
                    
//                         output += '<div class="mobile-nav-level-5 js-tab-sub-content" id="dd17-cat-' + c + '-sub-' + s + '">' +
//                                         '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
//                                         '<a class="mobile-nav-filter js-mobile-filter" href="##">Filter</a>' +
//                                         '<div class="mobile-menu-dd17-data">' +
//                                             '<div class="mobile-menu-filter sub-mobile-menu-filter-' + s + '">' +
//                                                 '<p class="mobile-menu-filter-title">Filters</p>' +
//                                                 '<div class="mobile-menu-buttons">' + 
//                                                     '<a class="mobile-menu-clear" href="##">Clear</a>' +
//                                                     '<a class="mobile-menu-done" href="##">Done</a>' + 
//                                                 '</div>' +
//                                                 '<div class="mobile-filter js-accordion">';
                        
//                                 for(var j = 0; j < categories[c].sub_categories[s].columns.length; j++) {
//                                     var col_name_spaces_removed = categories[c].sub_categories[s].columns[j].toLowerCase().replace(/ /g, '_');
//                                     var col_filter_list = build_column_filter(categories, c, col_name_spaces_removed + '_key_filter', s);
//                                     var unit_of_meas = typeof units_of_measurement_map[categories[c].sub_categories[s].columns[j]] === "undefined" ? "" : '&nbsp;&nbsp;(' + units_of_measurement_map[categories[c].sub_categories[s].columns[j]] + ')';
                                    
//                                     if(j === 0) {
//                                         output += "";
//                                     }
//                                     else {
//                                         output +=   '<div class="tr-wrapper">' +
//                                                         '<div class="tr header group js-accordion-control">' +
//                                                             '<div class="th"><span>' + categories[c].sub_categories[s].columns[j] + '</span>'+ unit_of_meas +'</div>' + 
//                                                         '</div>' +
//                                                         '<div class="js-accordion-panel">' +
//                                                             '<div class="filter-by-box">' +
//                                                                 '<div class="filter-options">' + col_filter_list + '</div>' +
//                                                             '</div>' +
//                                                         '</div>' +
//                                                     '</div>';   
//                                     }
//                                 }
                        
//                                 output +=       '</div>' +
//                                                 '</div>' +
//                                                 '<div class="thead">' +
//                                                 '<div class="tr group">';
                                                
//                         if(categories[c].sub_categories[s].columns[categories[c].sub_categories[s].columns.length-1].toLowerCase() == "compare"){
//                             output += '<div class="th"><a href="javascript:void(0)" data="' + format_name(categories[c].sub_categories[s].columns[0], "_") + '" data-sort="asc">' + categories[c].sub_categories[s].columns[0] + '</a></div>' +
//                                       '<div class="th">Compare</div>' +
//                                       '<div class="th">Go</div>';
//                             var has_compare = 1;
//                         }else{
//                             output += '<div class="th no-compare"><a href="javascript:void(0)" data="' + format_name(categories[c].sub_categories[s].columns[0], "_") + '" data-sort="asc">' + categories[c].sub_categories[s].columns[0] + '</a></div>' +
//                                       '<div class="th">Go</div>';
//                             var has_compare = 0;
//                         }
                        
//                         output +=           '</div>' +
//                                         '</div>' +
//                                         '<div class="tbody js-accordion">';
                                        
//                         for(var i = 0; i < categories[c].sub_categories[s].items.length; i++) {
                            
//                             output +=   '<div class="tr-wrapper">' +
//                                                 '<div class="tr header group js-accordion-control">' +
//                                                     '<div class="th"><span>' + categories[c].sub_categories[s].items[i].columns[format_name(categories[c].sub_categories[s].columns[0], "_")] + '</span></div>';
                                                    
//                             if(has_compare == 1){
//                                 output +=          '<div class="td">' +
//                                                         '<span>' +
//                                                             '<input type="checkbox" id="mobile-' + format_name(categories[c].sub_categories[s].columns[format_name(categories[c].sub_categories[s].columns[0], "_")], "-") + '" />' +
//                                                             '<label for="mobile-' + format_name(categories[c].sub_categories[s].columns[format_name(categories[c].sub_categories[s].columns[0], "_")], "-") + '"><span>Select</span></label>' +
//                                                         '</span>' +
//                                                     '</div>';
//                             }
                            
//                             output +=               '<div class="td arrow-link">' +
//                                                         '<a href="' + categories[c].sub_categories[s].items[i].url + '">' +
//                                                             '<i class="fa fa-arrow-right vcenter"></i>' +
//                                                         '</a>' +
//                                                     '</div>' +
//                                                 '</div>' +
//                                                 '<div class="js-accordion-panel">';
                                                
//                             var column_count = 0;
//                             for (var k in categories[c].sub_categories[s].items[i]['columns']){
//                                 if(column_count != 0){
//                                     if(k != "compare"){
//                                         output += '<div class="tr group">' +
//                                                         '<div class="th">' + categories[c].sub_categories[s].columns[column_count] + '</div>' +
//                                                         '<div class="td">' + categories[c].sub_categories[s].items[i]['columns'][k] + '</div>' +
//                                                   '</div>';
//                                     }
//                                 }
//                                 column_count++;
//                             }
                            
//                             output +=           '</div>' +
//                                         '</div>';
                            
//                         }
                        
//                         output +=       '</div>' +
//                                     '</div>'; // close mobile-menu-dd17-data
                                    
//                         if(has_compare == 1){
//                             output += '<div class="menu-footer group">' +
//                                         '<div class="compare-buttons">' +
//                                             '<a href="javascript:void(0);" class="compare-selected-button disabled">Compare Selected</a>' +
//                                             '<a href="javascript:void(0);" class="compare-reset-button disabled">Reset</a>' +
//                                         '</div>' +
//                                     '</div>';
//                         }
                        
//                         output += '</div>';
                    
//                     }
//                 }
//             }
            
//             output += '</div>';
            
//             output = output.replace("{{products_top_level}}", products_top_level_html);
            
//             $(".index-menu-fade-alt").append(output);
            
            
//             // Headroom
//             var hrHeader = document.querySelector(".mobile-header");

//             // construct an instance of Headroom, passing the element
//             headroom = new Headroom(hrHeader, {
//                 "offset": 50,
//                 "tolerance": {
//                         up : 0,
//                         down : 0
//                     },
//                 "classes": {
//                     "initial": "headroom",
//                     "pinned": "headroom--pinned",
//                     "unpinned": "headroom--unpinned",
//                     "top" : "headroom--top"
//                     }
//             });
//             // initialise
//             headroom.init();
//         }

//         function format_name(name, seperator){
//             var formatted_name = name.split('&amp;').join('');
//             switch(seperator){
//                 case "-":
//                     formatted_name = formatted_name.toLowerCase();
//                     formatted_name = formatted_name.split(' ').join('_');
//                     formatted_name = formatted_name.replace(/\W/g, '')
//                     formatted_name = formatted_name.split('_').join('-');
//                     formatted_name = formatted_name.split('--').join('-');
//                     break;
//                 case "_":
//                     formatted_name = formatted_name.toLowerCase();
//                     formatted_name = formatted_name.split(' ').join('_');
//                     formatted_name = formatted_name.replace(/\W/g, '')
//                     formatted_name = formatted_name.split('__').join('_');
//                     break;
//             }
            
//             return formatted_name;
//         }
        
//         function compareStrings(a, b) {
//           // Assuming you want case-insensitive comparison
//           var a_temp = $("<div>" + a + "</div>");
//           a_temp.find("a").remove();
//           var a_value = a_temp.html();
//           var b_temp = $("<div>" + b + "</div>");
//           b_temp.find("a").remove();
//           var b_value = b_temp.html();
//           a_final = a_value.toLowerCase();
//           b_final = b_value.toLowerCase();

//           return (a_final < b_final) ? -1 : (a_final > b_final) ? 1 : 0;
//         }
//     },
//     error: function(data) {
//         console.log("Product Nav Filter Error:")
//         console.log(data);
//     }
// });