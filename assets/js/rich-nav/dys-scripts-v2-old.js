var product_nav;

$.ajax({
    url: "/assets/json/rich_nav/product-nav.json",
    type: "GET",
    dataType: "json",
    cache: false,
    success: function(data) {
        product_nav = data;

        $(document).ready(function() {
            
            if($(".search-container form input[name='w']").length){
                $(".search-container form input[name='w']").val("");
            }
            
            $(document).on("click", ".search-container form .search-submit", function(e){
                e.preventDefault();
                if($(".search-container form input[name='w']").val() == ""){
                    $(".search-container form input[name='w']").focus();
                }else{
                    $(".search-container form").submit();
                }
            });
            
            $(document).on("click", ".main-nav li[data-menu]", function(e){
                if(!$(this).hasClass("active")){
                    //console.log("here");
                    var li_class = $(this).attr("data-menu");
                    $(".main-nav li").removeClass("active");
                    $(this).addClass("active");
                    $(".main-nav-dropdown:visible").slideToggle(400);
                    switch(li_class){
                        case 'products':
                           // size_dd17_menus(0);
                           $(".nav-lasers").slideToggle(400);
                           break;
                        case 'applications':
                           $(".nav-applications").slideToggle(400);
                           break;
                        case 'support':
                           $(".nav-support").slideToggle(400);
                           break;
                        case 'company':
                           $(".nav-company").slideToggle(400);
                           break;
                    }
                }else{
                    $(".main-nav li").removeClass("active");
                    if($(".nav-lasers").is(":visible")){
                    
                        $(".nav-lasers").slideToggle(400, function(){
                            $(".menu-dd17 .subnav li").removeClass("dd-active");
                            $(".menu-dd17 .subnav li:first-child").addClass("dd-active");
                            
                            $(".menu-dd17 .panels-container .panel").removeClass("dd-active");
                            $(".menu-dd17 .panels-container .panel:first-child").addClass("dd-active");
                        });
                    }else{
                        $(".main-nav-dropdown:visible").slideToggle(400);
                    }
                }
            });
            
            $(document).on("click", ".menu-dd17 .subnav a.subnav-link", function(e) {
                e.preventDefault();
                if(!$(this).parent().hasClass("dd-active")){
                    var category = $(this).attr("data-category");
                    
                    $(".menu-dd17 .subnav li").removeClass("dd-active");
                    $(this).parent().addClass("dd-active");
                    
                    $(".menu-dd17 .panels-container .panel").removeClass("dd-active");
                    $("#panel-" + category).addClass("dd-active");
                }        
            });
            
            $(document).on("click", ".menu-dd17 .subnav-lvl3 a", function(e){
                e.preventDefault();
                if(!$(this).hasClass("dd-active")){
                    var category = $(this).attr("data-category");
                    var category_index = $(this).attr("data-category-index");
                    var sub_category_index = $(this).attr("data-sub-category-index");
                    
                    $(".menu-dd17 .subnav-lvl3 a").removeClass("dd-active");
                    $(this).addClass("dd-active");
                    
                    switch_subcategory_dd17_menu(category, category_index, sub_category_index, $(this).parent().parent().siblings(".menu-dd17-data"));
                } 
            });
            
            $(document).on("click", ".menu-dd17 .panels-container .panel .thead .tr .th #sort", function(e) {
                var category = $(this).parent().attr("data-category");
                var category_index = $(this).parent().attr("data-category-index");
                var sub_category_index = $(this).parent().attr("data-sub-category-index");
                var sort_dir = $(this).parent().attr("data-sort");
                var sorted_by = $(this).parent().attr("data-sorted-by");
                var colum_name_formatted = format_name($(this).html(), "_");
                //console.log(colum_name_formatted);
                if(colum_name_formatted != "compare"){
                    //update the sort arrows in the header
                    if(sorted_by == 1){ // the table is already sorted by this column
                        if(sort_dir == "asc"){
                            $(this).parent().attr("data-sort", "desc");
                            var new_sort_dir = "desc";
                        }else{
                            $(this).parent().attr("data-sort", "asc");
                            var new_sort_dir = "asc";
                        }
                        
                    }else{ // the table is not currently sorted by this column
                        $(this).parent().siblings().attr("data-sorted-by", "0");
                        $(this).parent().siblings().attr("data-sort", "asc");
                        $(this).parent().attr("data-sorted-by", "1");
                        var new_sort_dir = "asc";
                    }
                    
                    //sort_products_menu_by_col(category_index, sub_category_index, new_sort_dir, colum_name_formatted, $(this).parent().parent().siblings(".tbody"));
                    filter_and_sort_dd17_menu();
                }
            });
            
            $(document).on("click", ".menu-dd17 .panels-container .panel .tbody .tr", function(e) {
                var url = $(this).attr("data-url");
                window.location = url;
            });
            
            $(document).on("click", ".menu-dd17 .menu-footer-close", function(e){
                e.preventDefault();
                $(".nav-lasers").slideToggle(400, function(){
                    $(".menu-dd17 .subnav li").removeClass("dd-active");
                    $(".menu-dd17 .subnav li:first-child").addClass("dd-active");
                    
                    $(".menu-dd17 .panels-container .panel").removeClass("dd-active");
                    $(".menu-dd17 .panels-container .panel:first-child").addClass("dd-active");
                });
                $(".main-menu-wrapper li").removeClass("active");
            });
            
            $(document).on("click", ".menu-dd17 .tbody .tr .td input, .menu-dd17 .tbody .tr .td label", function(e){
               e.stopPropagation();
            });
            
            $(document).on("click", ".menu-dd17 .compare-reset-button", function(e){
                e.preventDefault();
                $(this).parent().siblings(".tbody").find("input[type='checkbox']").attr("checked", false);
            });
            
            $(document).on("click", ".menu-dd17-data .filter-by-button", function(e){
                e.stopPropagation();
                if(!$(this).siblings(".filter-by-box").is(":visible")){
                    $(this).addClass("active");
                    $(".menu-dd17-data .filter-by-box:visible").slideToggle(200);
                    $(this).siblings().slideToggle(200);
                }else{
                    $(this).removeClass("active");
                    $(this).siblings().slideToggle(200);
                }
            });
            
            $(document).on("click", ".menu-dd17 .filter-by-box", function(e){
                e.stopPropagation();
            });
            
            $(document).on("click", ".menu-dd17 .filter-options .filter-option", function(e){
                $(this).toggleClass("active");

                if(!$(this).parent().parent().siblings(".filter-by-button").hasClass("on")){
                    $(this).parent().parent().siblings(".filter-by-button").addClass("on");
                }
                if(!$(this).parent().children(".active").length){
                    $(this).parent().parent().siblings(".filter-by-button").removeClass("on");
                }
                if($(".menu-dd17 .filter-options .filter-option.active").length){
                    $(this).closest(".thead").siblings(".compare-buttons").children(".clear-filters-button").removeClass("disabled");
                }else{
                    $(this).closest(".thead").siblings(".compare-buttons").children(".clear-filters-button").addClass("disabled");
                }
                
                filter_and_sort_dd17_menu();
            });
            
            $(document).on("click", ".menu-dd17 .clear-filters-button", function(e){
                var this_menu_head = $(this).parent().siblings(".thead");
                $(".filter-option", this_menu_head).removeClass("active");
                $(".filter-by-button", this_menu_head).removeClass("on");
                $(this).addClass("disabled");
                filter_and_sort_dd17_menu();
            });
            
            $(document).on("click", ".menu-dd17 .clear-filters-link", function(e){
                var this_menu_head = $(this).parent().parent().siblings(".thead");
                $(".filter-option", this_menu_head).removeClass("active");
                $(".filter-by-button", this_menu_head).removeClass("on");
                filter_and_sort_dd17_menu();
            });
            
            $(document).on("click", ".menu-dd17 .clear-this-filter", function(e){
                var this_menu = $(this).siblings(".filter-options");
                $(".filter-option", this_menu).removeClass("active");
                this_menu.parent().siblings(".filter-by-button").removeClass("on");
                if($(".menu-dd17 .filter-options .filter-option.active").length){
                    $(this).closest(".thead").siblings(".compare-buttons").children(".clear-filters-button").removeClass("disabled");
                }else{
                    $(this).closest(".thead").siblings(".compare-buttons").children(".clear-filters-button").addClass("disabled");
                }
                filter_and_sort_dd17_menu();
            });
            
            //hide the filter menu if you click outside
            $(document).mouseup(function(e){
                var container = $(".filter-by-box");
                var button = $(".filter-by-button");
                
                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0 && !button.is(e.target) && button.has(e.target).length === 0) 
                {
                    $(".menu-dd17 .filter-by-box:visible").slideToggle(200);
                }
            });
            
            $(document).on("click", ".menu-dd17-data .tbody .td a", function(e){
               e.stopPropagation(); 
            });
            
            /* Mobile Menu Bindings */
           
            $(document).on("click", ".mobile-menu-dd17 #js-mobile-icon", function() {

                if ( $(this).hasClass('fa-bars') ) {
                    $(this).toggleClass("fa-bars fa-times");
                    
                    setTimeout(function(){
                        size_dd17_mobile_menu();
                    }, 50);
                    
                    $(".js-mobile-top-level").slideToggle();

                    $(".mobile-header").addClass("headroom-open");
                    if($(window).scrollTop() > 0){
                        $("html, body").animate({scrollTop : 0},300, function(){
                            headroom.destroy();
                            $("body").addClass("mobile-open");
                        });
                    }else{
                        headroom.destroy();
                        $("body").addClass("mobile-open");
                    }
                } else {
                    $(this).toggleClass("fa-bars fa-times");
                    $("body").removeClass("mobile-open");
                    $(window).scrollTop(0);
                    if($(".mobile-menu-dd17 .open").length){
                    
                        $(".mobile-menu-dd17 .open").removeClass("open");
                        
                        setTimeout(function(){
                            $(".js-mobile-top-level").slideToggle();
                            $(".mobile-menu-dd17-data .th.open").removeClass("open");
                            $(".mobile-menu-dd17-data .js-accordion-panel").hide();
                            $(".mobile-header").removeClass("headroom-open");
                            headroom.init();
                        }, 200);
                    }else{
                        $(".js-mobile-top-level").slideToggle();
                        $(".mobile-menu-dd17-data .th.open").removeClass("open");
                        $(".mobile-menu-dd17-data .js-accordion-panel").hide();
                        $(".mobile-header").removeClass("headroom-open");
                        headroom.init();
                    }
                }

            });

            // Mobile Back
            $(document).on("click", ".mobile-menu-dd17 .js-mobile-back", function(e) {
                e.preventDefault();
                $(this).parent().removeClass("open");
            });

            // Mobile Nav Top Level
            $(document).on("click", ".mobile-menu-dd17 .mobile-nav-top-level li a[data-subnav]", function(e) {
                e.preventDefault();
                var subnav = $(this).attr("data-subnav");
                $(".js-mobile-nav-level-2." + subnav).addClass("open");
            });

            // Mobile Nav Level 2
            $(document).on("click", ".js-mobile-nav-level-2-subnav li a", function(e){
                e.preventDefault();
                var tab_id = $(this).attr('data-tab');

                $('.js-mobile-nav-level-2-subnav li a').removeClass('open');
                $('.js-tab-content').removeClass('open');

                $(this).addClass('open');
                $("#"+tab_id).addClass('open');
            })

            // Mobile Nav Level 4
            $(document).on("click", ".js-level-4-sub-nav li a", function(e){
                e.preventDefault();
                var tab_id = $(this).attr('data-tab');

                $('.js-level-4-sub-nav li a').removeClass('open');
                $('.js-tab-sub-content').removeClass('open');

                $(this).addClass('open');
                $("#"+tab_id).addClass('open');
            })

            // Accordion
            $(document).on("click", ".mobile-menu-dd17 .mobile-menu-dd17-data .tbody .tr.header .td span, .mobile-menu-dd17 .mobile-menu-dd17-data .tbody .td.arrow-link", function(e) {
                e.stopPropagation();
            });

            $(document).on("click", ".js-accordion .js-accordion-control", function() {
                $(this).find(":first-child").toggleClass("open");
                $(this).next(".js-accordion-panel").not(":animated").slideToggle();
            });
            
            $(document).on("click", ".mobile-menu-dd17 .tbody .tr .td input, .mobile-menu-dd17 .tbody .tr .td label", function(e){
               e.stopPropagation();
               var this_panel = $(this).closest(".js-tab-content");
               if($("input[type='checkbox']:checked", this_panel).length){
                   $(".menu-footer .compare-buttons a", this_panel).removeClass("disabled");
               }else{
                   $(".menu-footer .compare-buttons a", this_panel).addClass("disabled");
               }
            });
            
            $(document).on("click", ".mobile-menu-dd17 .compare-reset-button", function(e){
                e.preventDefault();
                $(this).parent().parent().siblings(".mobile-menu-dd17-data").find("input[type='checkbox']").attr("checked", false);
                $(this).addClass("disabled");
                $(this).siblings(".compare-selected-button").addClass("disabled");
            });
            
            
            build_dd17_products_menu();
            build_dd17_products_mobile_menu()

        });

        // $(window).resize(function(){
        //     if($(".menu-dd17").is(":visible")){
        //         size_dd17_menus(1);
        //     }
        // });

        function size_dd17_menus(leave_open){
            var window_height = $(window).height();
            var header_height = $("header").outerHeight();
            
            if(leave_open == 1){
                var current_panel = $(".menu-dd17 .panels-container .panel:visible");
            }
            
            $(".menu-dd17 .panels-container .panel").each(function(index){
                if(leave_open == 0){
                    $(".nav-lasers").show();
                }
                $(this).show();
                
                $(".menu-dd17-data .tbody .tr .th", this).each(function(index){
                    $(this).attr("style", "");
                    var row_height = $(this).parent().outerHeight();
                    $(this).css("height", row_height + "px"); 
                });
                
                
                
                var subnav_height = $(".menu-dd17 ul.subnav").outerHeight();
                var menu_footer = $(".menu-dd17 .menu-footer").outerHeight();
                var table_header_height = $(".thead", this).outerHeight();
                if($(".subnav-lvl3", this).length){
                    var table_height = window_height - header_height - subnav_height - menu_footer - table_header_height - $(".subnav-lvl3", this).outerHeight();
                }else{
                    var table_height = window_height - header_height - subnav_height - menu_footer - table_header_height;
                }
                if(leave_open == 0){
                    $(".nav-lasers").hide();
                }
                $(this).hide();
                $(".tbody", this).css("height", table_height + "px");
                $(this).attr("style", "");
            });
            
            if(leave_open == 1){
                current_panel.addClass("dd-active");
            }
        }

        function size_dd17_mobile_menu(){
            var window_height = $(window).outerHeight();
            var header_height = $(".mobile-menu-dd17 .mobile-header").outerHeight();
            $(".mobile-menu-dd17 > div").show();

            var level1_height = $(".mobile-nav-top-level-wrapper").outerHeight();
            var level1_search_height = $(".mobile-nav-top-level-wrapper form").outerHeight();

            var level1_search_height = window_height - (header_height + level1_search_height);

            $(".mobile-menu-dd17 .mobile-nav-top-level-wrapper ul, .mobile-menu-dd17 .mobile-nav-level-2, .mobile-menu-dd17 .mobile-nav-level-3, .mobile-menu-dd17 .mobile-nav-level-4, .mobile-menu-dd17 .mobile-nav-level-5").css("height", level1_search_height + "px");

            var accordian_height = level1_search_height - 110;

            $(".mobile-menu-dd17 .js-accordion").css("height", accordian_height + "px");
            $(".mobile-menu-dd17 > div").show();

        }

        function build_dd17_products_menu(){
            var categories = product_nav['categories'];
            
            
            var subnav_output = ""; // this will contain the subnav html
            var panel_output = ""; // this will contain the html for the panels that are displayed when you switch between categories.
            
            //filer array will hold the filter html until it can be injected into the output
            var filters_array = [];
                
            // loop through the product categories
            for(var i = 0; i < categories.length; i++) {
                if(i == 0){
                    var active_class = "dd-active";
                }else{
                    var active_class = "";
                }
                
                var category_formatted = format_name(categories[i].name, "-");
                
                // add the category to the sub nav html
                subnav_output += '<li class="' + active_class + '"><a class="subnav-link subnav-' + category_formatted + '" href="javascript:void(0);" data-category="' + category_formatted + '">' + categories[i].name + '</a></li>';
                
                //start the panel html
                panel_output += '<div class="panel ' + active_class + '" id="panel-' + category_formatted + '">';
                
                //if the category has an html row
                if(categories[i].hasOwnProperty('html_row') && categories[i].html_row.length){
                    panel_output += '<div class="nav-html-row">' +
                                        '<div class="container">';
                    for(var r = 0; r < 2; r++) {
                        
                        panel_output += '<div class="nav-html-row-item">' +
                                            '<div class="left"><img src="' + categories[i].html_row[r].image + '"></div>' +
                                            '<div class="right">' +
                                                '<a class="title" href="' + categories[i].html_row[r].link_url + '">' + categories[i].html_row[r].title + '</a>' +
                                                '<p>' + categories[i].html_row[r].text + '</p>' +
                                                '<a class="link" href="' + categories[i].html_row[r].link_url + '">' + categories[i].html_row[r].link_text + '</a>' +
                                            '</div>' +
                                        '</div>'; 
                        
                    }
                    panel_output += '</div>' +
                                 '</div>';
                }
                
                
                // if the category has sub categories loop through them and create the sub category nav html
                if(categories[i].hasOwnProperty('sub_categories')){
                    panel_output += '<ul class="subnav-lvl3 group">';
                    
                    for(var s = 0; s < categories[i].sub_categories.length; s++) {
                        if(s == 0){
                            var active_class = 'class="dd-active"';
                        }else{
                            var active_class = "";
                        }
                        panel_output += '<li><a ' + active_class + ' href="javascript:void(0);" data-category="' + categories[i].name + '" data-category-index="' + i + '" data-sub-category-index="' + s + '">' + categories[i].sub_categories[s].name + '</a></li>';
                    }
                    
                    panel_output += '</ul>';
                    
                    
                }
                
                //start the table of products for this category
                panel_output +=     '<div class="menu-dd17-data">' +
                                        '<div class="thead">' +
                                            '<div class="tr group">';
                
                var has_compare = 0;
                
                
                // generate the header of the table
                //if there are not sub categories then get the columns for the category.
                if(!categories[i].hasOwnProperty('sub_categories')){
                    for(var c = 0; c < categories[i].columns.length; c++) {
                        
                        if(categories[i].columns[c] != "Compare"){
                            if(c == 0){
                                var sorted_by = "1";
                                var filter_html = "";
                            }else{
                                var sorted_by = "0";
                                var filter_html = '<div class="filter-by">' +
                                                        '<div class="filter-by-button"><span class="far fa-bars" aria-hidden="true"></span></div>' +
                                                        '<div class="filter-by-box">' +
                                                            '<div class="clear-this-filter">CLEAR</div>' +
                                                            '<div class="filter-title">Show only...</div>' +
                                                            '<div class="filter-options">{{cat_' + i + '_col_' + c + '_filters}}</div>' +
                                                        '</div>' +
                                                  '</div>';
                                filters_array['cat_' + i + '_col_' + c + '_filters'] = "";
                            }
                            
                            var sort_attributes = 'data-sort="asc" data-sorted-by="' + sorted_by + '"';
                        }else{
                            var sort_attributes = "";
                            var filter_html = "";
                        }
                        panel_output +=         '<div class="th col_' + categories[i].columns.length + '" data-category="' + category_formatted + '" data-category-index="' + i + '" data-sub-category-index="none" ' + sort_attributes + '>' + filter_html + '<span id="sort" class="sort">' + categories[i].columns[c] + '</span></div>';
                        
                        if(categories[i].columns[c] == "Compare"){
                            has_compare = 1;
                        }
                    }
                // if there are sub categories get the columns for the first sub category
                }else{
                    for(var c = 0; c < categories[i].sub_categories[0].columns.length; c++) {
                        if(categories[i].sub_categories[0].columns[c] != "Compare"){
                            if(c == 0){
                                var sorted_by = "1";
                                var filter_html = "";
                            }else{
                                var sorted_by = "0";
                                var filter_html = '<div class="filter-by">' +
                                                        '<div class="filter-by-button"><span class="far fa-bars" aria-hidden="true"></span></div>' +
                                                        '<div class="filter-by-box">' +
                                                            '<div class="clear-this-filter">CLEAR</div>' +
                                                            '<div class="filter-title">Show only...</div>' +
                                                            '<div class="filter-options">{{cat_' + i + '_sub_0_col_' + c + '_filters}}</div>' +
                                                        '</div>' +
                                                  '</div>';
                                filters_array['cat_' + i + '_sub_0_col_' + c + '_filters'] = "";
                            }
                        }else{
                            var sorted_by = "0";
                            var filter_html = "";
                        }
                        panel_output +=         '<div class="th col_' + categories[i].sub_categories[0].columns.length + '" data-category="' + category_formatted + '" data-category-index="' + i + '" data-sub-category-index="0" data-sort="asc" data-sorted-by="' + sorted_by + '"><span id="sort" class="sort">' + categories[i].sub_categories[0].columns[c] + '</span>' + filter_html + '</div>';
                        
                        if(categories[i].sub_categories[0].columns[c] == "Compare"){
                            has_compare = 1;
                        }
                    }
                }
                
                // close the table header and start the table body
                panel_output +=             '</div>' +
                                        '</div>' +
                                        '<div class="tbody">';
                // loop through the items and create the row html
                // if there are no sub categories get the items from the category
                if(!categories[i].hasOwnProperty('sub_categories')){
                    for(var r = 0; r < categories[i].items.length; r++) {
                        panel_output +=     '<div class="tr group" data-url="' + categories[i].items[r]['url'] + '">';
                        
                        var first_col = 1;
                        var col_counter = 0;
                        for (var k in categories[i].items[r]['columns']){
                            if (categories[i].items[r]['columns'][k] !== 'function') {
                                if(first_col == 1){
                                    panel_output += '<div class="th col_' + categories[i].columns.length + '">' + categories[i].items[r]['columns'][k] + '</div>';
                                    first_col = 0;
                                }else{
                                    if(categories[i].items[r]['columns'][k].indexOf("<compare_checkbox>") !== -1){
                                        var first_col_key = format_name(categories[i].columns[0], "_");
                                        var checkbox_id = format_name(categories[i].items[r]['columns'][first_col_key], "-");
                                        
                                        panel_output += '<div class="td compare-col col_' + categories[i].columns.length + '">' +
                                                            '<input type="checkbox" id="' + checkbox_id + '" />' +
                                                            '<label for="' + checkbox_id + '">Select</label>' +
                                                        '</div>';
                                    }else{
                                        panel_output += '<div class="td col_' + categories[i].columns.length + '">' + categories[i].items[r]['columns'][k] + '</div>';
                                        
                                        //check if this value is not in the filters array for this column, if it isn't then add it.
                                        // get the current string and format it so that we can check for the full text between two pipe characters
                                        var current_value_formatted = "|| " + filters_array['cat_' + i + '_col_' + col_counter + '_filters'] + " ||";
                                        if(current_value_formatted.indexOf("|| " + categories[i].items[r]['columns'][k] + " ||") === -1){
                                            if(filters_array['cat_' + i + '_col_' + col_counter + '_filters'] == ""){
                                                filters_array['cat_' + i + '_col_' + col_counter + '_filters'] = categories[i].items[r]['columns'][k];
                                            }else{
                                                filters_array['cat_' + i + '_col_' + col_counter + '_filters'] = filters_array['cat_' + i + '_col_' + col_counter + '_filters'] + " || " + categories[i].items[r]['columns'][k];
                                            } 
                                        }
                                        
                                    }
                                }
                            }
                            col_counter++;
                        }
                        
                        panel_output +=     '</div>';
                    }
                // if there are sub categories get the items from the first sub category.
                }else{
                    for(var r = 0; r < categories[i].sub_categories[0].items.length; r++) {
                        panel_output +=     '<div class="tr group" data-url="' + categories[i].sub_categories[0].items[r]['url'] + '">';
                        
                        var first_col = 1;
                        var col_counter = 0;
                        for (var k in categories[i].sub_categories[0].items[r]['columns']){
                            if (categories[i].sub_categories[0].items[r]['columns'][k] !== 'function') {
                                if(first_col == 1){
                                    panel_output += '<div class="th col_' + categories[i].sub_categories[0].columns.length + '">' + categories[i].sub_categories[0].items[r]['columns'][k] + '</div>';
                                    first_col = 0;
                                }else{
                                    if(categories[i].sub_categories[0].items[r]['columns'][k].indexOf("<compare_checkbox>") !== -1){
                                        var first_col_key = format_name(categories[i].sub_categories[0].columns[0], "_");
                                        var checkbox_id = format_name(categories[i].sub_categories[0].items[r]['columns'][first_col_key], "-");
                                        
                                        panel_output += '<div class="td compare-col col_' + categories[i].sub_categories[0].columns.length + '">' +
                                                            '<input type="checkbox" id="' + checkbox_id + '" />' +
                                                            '<label for="' + checkbox_id + '">Select</label>' +
                                                        '</div>';
                                    }else{
                                        panel_output += '<div class="td col_' + categories[i].sub_categories[0].columns.length + '">' + categories[i].sub_categories[0].items[r]['columns'][k] + '</div>';
                                        
                                        //check if this value is not in the filters array for this column, if it isn't then add it.
                                        if(filters_array['cat_' + i + '_sub_0_col_' + col_counter + '_filters'].indexOf(categories[i].sub_categories[0].items[r]['columns'][k]) === -1){
                                            if(filters_array['cat_' + i + '_sub_0_col_' + col_counter + '_filters'] == ""){
                                                filters_array['cat_' + i + '_sub_0_col_' + col_counter + '_filters'] = categories[i].sub_categories[0].items[r]['columns'][k];
                                            }else{
                                                filters_array['cat_' + i + '_sub_0_col_' + col_counter + '_filters'] = filters_array['cat_' + i + '_sub_0_col_' + col_counter + '_filters'] + " || " + categories[i].sub_categories[0].items[r]['columns'][k];
                                            } 
                                        }
                                    }
                                }
                            }
                            col_counter++;
                        }
                        
                        panel_output +=     '</div>';
                    }
                }
                
                panel_output +=         '</div>';
                
                // if the category has a compare column, add the compare and reset buttons to the bottom
                if(has_compare == 1){
                    panel_output +=     '<div class="compare-buttons">' +
                                            '<a class="clear-filters-button disabled" href="javascript:void(0);">Clear Filters</a>' +
                                            '<a class="compare-selected-button disabled" href="javascript:void(0);">Compare Selected</a>' +
                                            '<a class="compare-reset-button disabled" href="javascript:void(0);">Reset</a>' +
                                        '</div>';
                }else{
                    panel_output +=     '<div class="compare-buttons">' +
                                            '<a class="clear-filters-button disabled" href="javascript:void(0);">Clear Filters</a>' +
                                        '</div>';
                }
                
                // close the table and the panel
                panel_output +=     '</div>' +
                                '</div>';
            }
            
            for (var k in filters_array){
                var this_filter_array = filters_array[k].split(' || ');
                this_filter_array.sort();
                var this_filter_html = "";
                for (i = 0; i < this_filter_array.length; i++) {
                    var filter_temp = $("<div>" + this_filter_array[i] + "</div>");
                    filter_temp.find("a").remove();
                    var filter_value = filter_temp.html();
                    this_filter_html += '<div class="filter-option"><span>' + filter_value + '</span></div>'
                }
                
                var filter_placeholder = "{{" + k + "}}";
                panel_output = panel_output.replace(filter_placeholder, this_filter_html);
            }
            
            $(".menu-dd17 ul.subnav").html(subnav_output);
            $(".menu-dd17 .panels-container").html(panel_output);
            
        }

        function filter_and_sort_dd17_menu(){
            
            var visible_thead = $(".menu-dd17-data .thead:visible");
            
            var category_index = $(".tr .th[data-sorted-by='1']", visible_thead).attr("data-category-index");
            var sub_category_index = $(".tr .th[data-sorted-by='1']", visible_thead).attr("data-sub-category-index");
            var sort_dir = $(".tr .th[data-sorted-by='1']", visible_thead).attr("data-sort");
            var colum_name_formatted = format_name($(".tr .th[data-sorted-by='1'] > span", visible_thead).html(), "_");
            var table_element = visible_thead.siblings(".tbody");
            
            
            
            if(sub_category_index == "none"){
                var items = product_nav['categories'][parseInt(category_index)].items;
                var column_count = product_nav['categories'][parseInt(category_index)].columns.length;
            }else{
                var items = product_nav['categories'][parseInt(category_index)].sub_categories[parseInt(sub_category_index)].items;
                var column_count = product_nav['categories'][parseInt(category_index)].sub_categories[parseInt(sub_category_index)].columns.length;
            }
            
            items.sort(function(a, b) {
                return compareStrings(a.columns[colum_name_formatted], b.columns[colum_name_formatted]);
            });
            
            if(sort_dir == "desc"){
                items.reverse();
            }
            
            //console.log(items);
            
            
            var panel_output = "";
            
            for(var r = 0; r < items.length; r++) {
                var show_this_item = 1;
                
                var filter_array = [];
                $(".filter-option.active", visible_thead).each(function(){
                    var filter_column_key = format_name($(this).parent().parent().parent().parent().children("span").html(), "_");
                    var filter_column_value = $(this).children("span").html();
                    
                    if(filter_array.hasOwnProperty(filter_column_key)){
                        filter_array[filter_column_key] = filter_array[filter_column_key] + "||" + filter_column_value;
                    }else{
                        filter_array[filter_column_key] = filter_column_value;
                    }
                    
                });
                
                //console.log(filter_array);
                for (var f in filter_array){
                    var this_col_ok = 0;
                    if(filter_array[f].indexOf("||") !== -1){
                        filter_values_array = filter_array[f].split("||");
                        for(var v = 0; v < filter_values_array.length; v++) {
                            var temp = $("<div>" + items[r]['columns'][f] + "</div>");
                            temp.find("a").remove();
                            var this_value = temp.html();
                            if(this_value == $('<textarea />').html(filter_values_array[v]).text()){
                                this_col_ok = 1;
                            }
                        }
                    }else{
                        var temp = $("<div>" + items[r]['columns'][f] + "</div>");
                        temp.find("a").remove();
                        var this_value = temp.html();
                        if(this_value == $('<textarea />').html(filter_array[f]).text()){
                            this_col_ok = 1;
                        }
                    }
                    
                    if(this_col_ok == 0){
                        show_this_item = 0;
                    }
                }
                
                if(show_this_item == 1){
                
                    panel_output +=     '<div class="tr group" data-url="' + items[r]['url'] + '">';
                    
                    var first_col = 1;
                    for (var k in items[r]['columns']){
                        if (items[r]['columns'][k] !== 'function') {
                            if(first_col == 1){
                                panel_output += '<div class="th col_' + column_count + '">' + items[r]['columns'][k] + '</div>';
                                first_col = 0;
                            }else{
                                if(items[r]['columns'][k].indexOf("<compare_checkbox>") !== -1){
                                    var checkbox_id = format_name(items[r]['columns']["product_name"], "-");
                                    panel_output += '<div class="td compare-col col_' + items[r]['columns'].length + '">' +
                                                        '<input type="checkbox" id="' + checkbox_id + '" />' +
                                                        '<label for="' + checkbox_id + '">Select</label>' +
                                                    '</div>';
                                }else{
                                    panel_output += '<div class="td col_' + column_count + '">' + items[r]['columns'][k] + '</div>';
                                }
                            }
                        }
                    }
                    
                    panel_output +=     '</div>';
                }
            }
            
            
            if(panel_output == ""){
                panel_output = '<div class="no-results">There are no results for your filtering options. Please broaden your filters.<br />To clear all filters, <span class="clear-filters-link">click here</span>.</div>';
            }
            
            
            table_element.html(panel_output);
            
            // size_dd17_menus();
        }

        function switch_subcategory_dd17_menu(category_name, category_index, sub_category_index, target_element){
            var categories = product_nav['categories'];
            
            var table_height = $(".tbody", target_element).css("height");
            
            var output = '<div class="thead">' +
                            '<div class="tr group">';
                            
            var filters_array = [];
            var has_compare = 0;
            for(var c = 0; c < categories[category_index].sub_categories[sub_category_index].columns.length; c++) {
                
                if(categories[category_index].sub_categories[sub_category_index].columns[c] != "Compare"){
                    if(c == 0){
                        var sorted_by = "1";
                        var filter_html = "";
                    }else{
                        var sorted_by = "0";
                        var filter_html = '<div class="filter-by">' +
                                                '<div class="filter-by-button"><span class="far fa-bars" aria-hidden="true"></span></div>' +
                                                '<div class="filter-by-box">' +
                                                    '<div class="clear-this-filter">CLEAR</div>' +
                                                    '<div class="filter-title">Show only...</div>' +
                                                    '<div class="filter-options">{{cat_' + category_index + '_sub_' + sub_category_index + '_col_' + c + '_filters}}</div>' +
                                                '</div>' +
                                          '</div>';
                                                      
                        filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + c + '_filters'] = "";
                    }
                    var sort_attributes = 'data-sort="asc" data-sorted-by="' + sorted_by + '"';
                }else{
                    var sort_attributes = "";
                    var filter_html = "";
                    has_compare = 1;
                }
                output +=         '<div class="th col_' + categories[category_index].sub_categories[sub_category_index].columns.length + '" data-category="' + category_name + '" data-category-index="' + category_index + '" data-sub-category-index="' + sub_category_index + '" ' + sort_attributes + '><span id="sort" class="sort">' + categories[category_index].sub_categories[sub_category_index].columns[c] + '</span>' + filter_html + '</div>';

            }
            
            output +=    '</div>' +
                         '</div>' +
                         '<div class="tbody" style="height: ' + table_height + ';">';
                         
            for(var r = 0; r < categories[category_index].sub_categories[sub_category_index].items.length; r++) {
                output +=     '<div class="tr group" data-url="' + categories[category_index].sub_categories[sub_category_index].items[r]['url'] + '">';
                
                var first_col = 1;
                var col_counter = 0;
                for (var k in categories[category_index].sub_categories[sub_category_index].items[r]['columns']){
                    if (categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k] !== 'function') {
                        if(first_col == 1){
                            output += '<div class="th col_' + categories[category_index].sub_categories[sub_category_index].columns.length + '">' + categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k] + '</div>';
                            first_col = 0;
                        }else{
                            
                            
                            if(categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k].indexOf("<compare_checkbox>") !== -1){
                                var first_col_key = format_name(categories[category_index].sub_categories[sub_category_index].columns[0], "_");
                                var checkbox_id = format_name(categories[category_index].sub_categories[sub_category_index].items[r]['columns'][first_col_key], "-");
                                
                                output += '<div class="td compare-col col_' + categories[category_index].sub_categories[sub_category_index].columns.length + '">' +
                                                    '<input type="checkbox" id="' + checkbox_id + '" />' +
                                                    '<label for="' + checkbox_id + '">Select</label>' +
                                                '</div>';
                            }else{
                                output += '<div class="td col_' + categories[category_index].sub_categories[sub_category_index].columns.length + '">' + categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k] + '</div>';
                                
                                //check if this value is not in the filters array for this column, if it isn't then add it.
                                // get the current string and format it so that we can check for the full text between two pipe characters
                                var current_value_formatted = "|| " + filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + col_counter + '_filters'] + " ||";
                                if(current_value_formatted.indexOf("|| " + categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k] + " ||") === -1){
                                    if(filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + col_counter + '_filters'] == ""){
                                        filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + col_counter + '_filters'] = categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k];
                                    }else{
                                        filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + col_counter + '_filters'] = filters_array['cat_' + category_index + '_sub_' + sub_category_index + '_col_' + col_counter + '_filters'] + " || " + categories[category_index].sub_categories[sub_category_index].items[r]['columns'][k];
                                    } 
                                }
                            }
                        }
                    }
                    col_counter++;
                }
                
                output +=     '</div>';
            }
            
            output += '</div>';
            
            // if the category has a compare column, add the compare and reset buttons to the bottom
            if(has_compare == 1){
                output +=     '<div class="compare-buttons">' +
                                        '<a class="clear-filters-button disabled" href="javascript:void(0);">Clear Filters</a>' +
                                        '<a class="compare-selected-button disabled" href="javascript:void(0);">Compare Selected</a>' +
                                        '<a class="compare-reset-button disabled" href="javascript:void(0);">Reset</a>' +
                                    '</div>';
            }else{
                output +=     '<div class="compare-buttons">' +
                                        '<a class="clear-filters-button disabled" href="javascript:void(0);">Clear Filters</a>' +
                                    '</div>';
            }
            
            for (var k in filters_array){
                var this_filter_array = filters_array[k].split(' || ');
                this_filter_array.sort();
                var this_filter_html = "";
                for (i = 0; i < this_filter_array.length; i++) {
                    this_filter_html += '<div class="filter-option"><span>' + this_filter_array[i] + '</span></div>'
                }
                
                var filter_placeholder = "{{" + k + "}}";
                output = output.replace(filter_placeholder, this_filter_html);
            }
            
            target_element.html(output);
            
            $(".menu-dd17-data .tbody .tr .th", $(".panel.dd-active")).each(function(index){
                var row_height = $(this).parent().outerHeight();
                $(this).css("height", row_height + "px"); 
            });
            
        }

        function build_dd17_products_mobile_menu(){
            var categories = product_nav['categories'];
            
            var output =    '<!-- Mobile Menu -->' +
                            '<div class="mobile-menu-dd17">' +
                                '<!-- Mobile Header -->' +
                                '<div class="mobile-header group">' +
                                    '<div class="mobile-header-logo vcenter">' +
                                        '<a href="/"><img src="/assets/site_images/logo-mobile.png" alt="Coherent" /></a>' +
                                    '</div>' +
                                    '<div class="mobile-header-btn vcenter">' +
                                        '<span id="js-mobile-icon" class="far fa-bars" aria-hidden="true"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="mobile-nav-top-level-wrapper js-mobile-top-level">' +
                                    '<ul class="mobile-nav-top-level">' +
                                        '<li><a href="javascript:void(0);" data-subnav="products">Products</a></li>' +
                                        '<li><a href="/applications">Applications</a></li>' +
                                        '<li><a href="/support">Support</a></li>' +
                                        '<li><a href="/company">Company</a></li>' +
                                        '<li><a href="/company/contact">Contact</a></li>' +
                                    '</ul>' +
                                    '<form class="mobile-nav-top-level-search" action="//lasers.coherent.com/search" method="get">' +
                                        '<div class="row">' +
                                            '<div class="col full">' +
                                                '<label for="mobile_search">Search</label>' +
                                                '<input type="text" id="mobile_search" name="w" placeholder="Search" />' +
                                            '</div>' +
                                            '<div class="col">' +
                                                '<button type="submit"><i class="fa fa-search"></i></button>' +
                                            '</div>' +
                                        '</div>' +
                                    '</form>' +
                                '</div>' +
                                '<div class="mobile-nav-level-2 js-mobile-nav-level-2 products">' +
                                    '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
                                    '<ul class="subnav group js-mobile-nav-level-2-subnav">' +
                                        '{{products_top_level}}' +
                                    '</ul>' +
                                '</div>';
            
            var products_top_level_html = "";
            for(var c = 0; c < categories.length; c++) {
                
                products_top_level_html += '<li><a class="subnav-' + format_name(categories[c].name, "-") + '" href="javascript:void(0);" data-tab="dd17-cat-' + c + '"><span>' + categories[c].name + '</span></a></li>';
                
                if(!categories[c].hasOwnProperty('sub_categories')){
                    output += '<div class="mobile-nav-level-3 js-tab-content" id="dd17-cat-' + c + '">' +
                                    '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
                                    '<div class="mobile-menu-dd17-data">' +
                                        '<div class="thead">' +
                                            '<div class="tr group">';
                                            
                    if(categories[c].columns[categories[c].columns.length-1].toLowerCase() == "compare"){
                        output += '<div class="th"><a href="javascript:void(0)" data="' + format_name(categories[c].columns[0], "_") + '" data-sort="asc">' + categories[c].columns[0] + '</a></div>' +
                                  '<div class="th">Compare</div>' +
                                  '<div class="th">Go</div>';
                        var has_compare = 1;
                    }else{
                        output += '<div class="th no-compare"><a href="javascript:void(0)" data="' + format_name(categories[c].columns[0], "_") + '" data-sort="asc">' + categories[c].columns[0] + '</a></div>' +
                                  '<div class="th">Go</div>';
                        var has_compare = 0;
                    }
                    
                    output +=           '</div>' +
                                    '</div>' +
                                    '<div class="tbody js-accordion">';
                                    
                    for(var i = 0; i < categories[c].items.length; i++) {
                        
                        output +=   '<div class="tr-wrapper">' +
                                            '<div class="tr header group js-accordion-control">' +
                                                '<div class="th"><span>' + categories[c].items[i].columns[format_name(categories[c].columns[0], "_")] + '</span></div>';
                                                
                        if(has_compare == 1){
                            output +=          '<div class="td">' +
                                                    '<span>' +
                                                        '<input type="checkbox" id="mobile-' + format_name(categories[c].items[i].columns[format_name(categories[c].columns[0], "_")], "-") + '" />' +
                                                        '<label for="mobile-' + format_name(categories[c].items[i].columns[format_name(categories[c].columns[0], "_")], "-") + '"><span>Select</span></label>' +
                                                    '</span>' +
                                                '</div>';
                        }
                        
                        output +=               '<div class="td arrow-link">' +
                                                    '<a href="' + categories[c].items[i].url + '">' +
                                                        '<i class="fa fa-arrow-right vcenter"></i>' +
                                                    '</a>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="js-accordion-panel">';
                                            
                        var column_count = 0;
                        for (var k in categories[c].items[i]['columns']){
                            if(column_count != 0){
                                if(k != "compare"){
                                    output += '<div class="tr group">' +
                                                    '<div class="th">' + categories[c].columns[column_count] + '</div>' +
                                                    '<div class="td">' + categories[c].items[i]['columns'][k] + '</div>' +
                                              '</div>';
                                }
                            }
                            column_count++;
                        }
                        
                        output +=           '</div>' +
                                    '</div>';
                        
                    }
                    
                    output +=       '</div>' +
                                '</div>'; // close mobile-menu-dd17-data
                                
                    if(has_compare == 1){
                        output += '<div class="menu-footer group">' +
                                    '<div class="compare-buttons">' +
                                        '<a href="javascript:void(0);" class="compare-selected-button disabled">Compare Selected</a>' +
                                        '<a href="javascript:void(0);" class="compare-reset-button disabled">Reset</a>' +
                                    '</div>' +
                                '</div>';
                    }
                    
                    output += '</div>';
                    
                }else{
                    
                    output += '<div class="mobile-nav-level-4 js-tab-content" id="dd17-cat-' + c + '">' +
                                    '<a class="mobile-nav-back js-mobile-back" href="javascript:void(0);">Back</a>' +
                                    '<ul class="mobile-nav-level-4-sub-nav js-level-4-sub-nav">';
                                    
                    for(var s = 0; s < categories[c].sub_categories.length; s++) {
                        output += '<li><a href="javascript:void(0);" data-tab="dd17-cat-' + c + '-sub-' + s + '"><img src="' + categories[c].sub_categories[s].image + '" alt="' + categories[c].sub_categories[s].name + '" /><span>' + categories[c].sub_categories[s].name + '</span></a></li>';
                    }
                    
                    output +=          '</ul>' +
                              '</div>';
                    
                    for(var s = 0; s < categories[c].sub_categories.length; s++) {
                    
                        output += '<div class="mobile-nav-level-5 js-tab-sub-content" id="dd17-cat-' + c + '-sub-' + s + '">' +
                                        '<a class="mobile-nav-back js-mobile-back" href="##">Back</a>' +
                                        '<div class="mobile-menu-dd17-data">' +
                                            '<div class="thead">' +
                                                '<div class="tr group">';
                                                
                        if(categories[c].sub_categories[s].columns[categories[c].sub_categories[s].columns.length-1].toLowerCase() == "compare"){
                            output += '<div class="th"><a href="javascript:void(0)" data="' + format_name(categories[c].sub_categories[s].columns[0], "_") + '" data-sort="asc">' + categories[c].sub_categories[s].columns[0] + '</a></div>' +
                                      '<div class="th">Compare</div>' +
                                      '<div class="th">Go</div>';
                            var has_compare = 1;
                        }else{
                            output += '<div class="th no-compare"><a href="javascript:void(0)" data="' + format_name(categories[c].sub_categories[s].columns[0], "_") + '" data-sort="asc">' + categories[c].sub_categories[s].columns[0] + '</a></div>' +
                                      '<div class="th">Go</div>';
                            var has_compare = 0;
                        }
                        
                        output +=           '</div>' +
                                        '</div>' +
                                        '<div class="tbody js-accordion">';
                                        
                        for(var i = 0; i < categories[c].sub_categories[s].items.length; i++) {
                            
                            output +=   '<div class="tr-wrapper">' +
                                                '<div class="tr header group js-accordion-control">' +
                                                    '<div class="th"><span>' + categories[c].sub_categories[s].items[i].columns[format_name(categories[c].sub_categories[s].columns[0], "_")] + '</span></div>';
                                                    
                            if(has_compare == 1){
                                output +=          '<div class="td">' +
                                                        '<span>' +
                                                            '<input type="checkbox" id="mobile-' + format_name(categories[c].sub_categories[s].columns[format_name(categories[c].sub_categories[s].columns[0], "_")], "-") + '" />' +
                                                            '<label for="mobile-' + format_name(categories[c].sub_categories[s].columns[format_name(categories[c].sub_categories[s].columns[0], "_")], "-") + '"><span>Select</span></label>' +
                                                        '</span>' +
                                                    '</div>';
                            }
                            
                            output +=               '<div class="td arrow-link">' +
                                                        '<a href="' + categories[c].sub_categories[s].items[i].url + '">' +
                                                            '<i class="fa fa-arrow-right vcenter"></i>' +
                                                        '</a>' +
                                                    '</div>' +
                                                '</div>' +
                                                '<div class="js-accordion-panel">';
                                                
                            var column_count = 0;
                            for (var k in categories[c].sub_categories[s].items[i]['columns']){
                                if(column_count != 0){
                                    if(k != "compare"){
                                        output += '<div class="tr group">' +
                                                        '<div class="th">' + categories[c].sub_categories[s].columns[column_count] + '</div>' +
                                                        '<div class="td">' + categories[c].sub_categories[s].items[i]['columns'][k] + '</div>' +
                                                  '</div>';
                                    }
                                }
                                column_count++;
                            }
                            
                            output +=           '</div>' +
                                        '</div>';
                            
                        }
                        
                        output +=       '</div>' +
                                    '</div>'; // close mobile-menu-dd17-data
                                    
                        if(has_compare == 1){
                            output += '<div class="menu-footer group">' +
                                        '<div class="compare-buttons">' +
                                            '<a href="javascript:void(0);" class="compare-selected-button disabled">Compare Selected</a>' +
                                            '<a href="javascript:void(0);" class="compare-reset-button disabled">Reset</a>' +
                                        '</div>' +
                                    '</div>';
                        }
                        
                        output += '</div>';
                    
                    }

                }
                
                
                
            }
            
            output += '</div>';
            
            output = output.replace("{{products_top_level}}", products_top_level_html);
            
            $(".index-menu-fade-alt").append(output);
            
            
            // Headroom
            var hrHeader = document.querySelector(".mobile-header");

            // construct an instance of Headroom, passing the element
            headroom = new Headroom(hrHeader, {
                "offset": 50,
                "tolerance": {
                        up : 0,
                        down : 0
                    },
                "classes": {
                    "initial": "headroom",
                    "pinned": "headroom--pinned",
                    "unpinned": "headroom--unpinned",
                    "top" : "headroom--top"
                    }
            });
            // initialise
            headroom.init();
            
            
        }

        function format_name(name, seperator){
            var formatted_name = name.split('&amp;').join('');
            switch(seperator){
                case "-":
                    formatted_name = formatted_name.toLowerCase();
                    formatted_name = formatted_name.split(' ').join('_');
                    formatted_name = formatted_name.replace(/\W/g, '')
                    formatted_name = formatted_name.split('_').join('-');
                    formatted_name = formatted_name.split('--').join('-');
                    break;
                case "_":
                    formatted_name = formatted_name.toLowerCase();
                    formatted_name = formatted_name.split(' ').join('_');
                    formatted_name = formatted_name.replace(/\W/g, '')
                    formatted_name = formatted_name.split('__').join('_');
                    break;
            }
            
            return formatted_name;
        }

        function compareStrings(a, b) {
          // Assuming you want case-insensitive comparison
          var a_temp = $("<div>" + a + "</div>");
          a_temp.find("a").remove();
          var a_value = a_temp.html();
          var b_temp = $("<div>" + b + "</div>");
          b_temp.find("a").remove();
          var b_value = b_temp.html();
          a_final = a_value.toLowerCase();
          b_final = b_value.toLowerCase();

          return (a_final < b_final) ? -1 : (a_final > b_final) ? 1 : 0;
        }

        //$(window).load(tbody);
        //$(window).resize(tbody);
    },
    error: function(data) {
        console.log("Product Nav Filter Error:")
        console.log(data);
    }
});