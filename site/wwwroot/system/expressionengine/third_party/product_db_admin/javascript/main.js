var cols = [
        { data: 'title', sTitle: "<th>Specs</th>"},
        { data: 'html', sTitle: "<th>1</th>"},
        { data: "btitle", sTitle: "<th>Specs</th>"}
    ],
    datatable_text = [],
    new_option,
    counter = 0;

function showButtons() {
    $('.submit').show();
    $('.btnAddCol').show();
}

function hideBtns() {
    $('.uncheck_all').hide();
    $('.submit').hide();
    $('.btnAddCol').hide();
}

function block(name) {
    for (var i = 0; i < name.length; i++){
        name[i].style.display = "block";
    }
}
                  
function display(name){
    for (var i = 0; i < name.length; i++){
        name[i].style.display = "none";
    }  
}

//$('.btnAddCol').unbind('click').bind('click', function (e) {
//    var col_num = 2,
//        new_count,
//        html_input;
//    
//    html_input = "more_html" + counter;
//    new_count = counter +2;
//    cols.splice(counter+2, 0, {"data": html_input, sTitle: '<th>'+ new_count +'</th>'});
//
//    addInputs();
//    $('#new_p').dataTable().fnDestroy();
//    $("#new_p thead tr th").eq(col_num).after('<th></th>');
//    showTable();
//    counter++;
//});  

function showTable() {
    $('form').sisyphus();
    $('#new_p').DataTable({
        stateSave: true,
        keys: true,
        select: true,
        destroy: true,
        bFilter: true,
        "aaData": datatable_text,
        "aoColumns": cols,
        "ordering": false,
        "pageLength":25
    });
    
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');
    setBtns();
}

function setBtns() {
    /* Start Modals */
    var span = document.getElementsByClassName('close'),
        tempModal = document.getElementsByClassName('tempModal'),
        edit_modal = document.getElementsByClassName('editModal');
    
    $('.editModal').on('click', function() {
        block(edit_modal);
    })
    
    $('.add_templates').on('click', function () {
        display(tempModal);
    });
    
    $('.templates').on('click', function () {
        block(tempModal);
    });

    $(span).on('click', function () {
        display(tempModal);
    });
}
/* End Modal */

function addInputs(){
    var placeholder = [],
        htmlContent,
        html_input;
    
    for(var i = 0; i < datatable_text.length; i++){
        if(Object.keys(datatable_text).length === Object.keys(cols).length){
            for (var j = 0; j < datatable_text.length; j++){
                placeholder[j] = convertJSONforID(datatable_text[j].title);
                htmlContent = '<td><input type="text" class="' +placeholder[j]+ '" name="' +placeholder[j]+ '"></td>';
                datatable_text[j][html_input] = htmlContent;
            }
        }   
        else if(Object.keys(datatable_text).length ){
            for(var y = 0; y < Object.keys(cols).length; y++){
                for (var x = 0; x < datatable_text.length; x++){
                    placeholder[x] = convertJSONforID(datatable_text[x].title);
                    htmlContent = '<td><input type="text" class="' +placeholder[x]+ '" name="' +placeholder[x]+ '"></td>';
                    datatable_text[x]["more_html" + y] = htmlContent;
                }
            }
        }
    } 
}

//Function to convert JSON and Arrays to use for titles
function convertJSON(x){
    var returnValue;
    x = x.toString().replace(/_/g, " ");  
    return returnValue = x.charAt(0).toUpperCase() + x.slice(1);
}

//Function to convert JSON and Arrays to use for ids in html
function convertJSONforID(x){
    var returnValue;

    x = x.toString().replace(/ /g, "_");  
    return returnValue = x.toLowerCase();
}


function onSubmit() {
    alert("Product saved to the database.");
    datatable_text = [];
    if(cols > 3) {
        cols.splice(2, 1);
    }
    console.log(cols);
    $('#new_p').DataTable().clear().draw();
    hideBtns();
}

function populate_table(key){
    var checkbox_text = [],
        check,
        convertedOption;

    $.getJSON("/system/expressionengine/third_party/product_db_admin/assets/json/categories.json", function(json) {
        new_option = json[key].options;
        
        for(var i = 0; i < new_option.length; i++){        
            checkbox_text[i] = '<li>' + new_option[i] + '</li><br>';
        } 
        $('.info_more1').html(checkbox_text);
        
        $('.add_templates').on("click", function() {
            datatable_text = [];
            for(var x = 0; x < new_option.length; x++){
                check = new_option[x];
                convertedOption = convertJSONforID(check);
                
                datatable_text.push({
                    "title" : check,
                    "html" : '<td><input type="text" class="' + convertedOption+ '" name="' +check+ '"></td>',
                    "btitle" : check
                }); 
            }
            if(Object.keys(cols).length > 2){
                addInputs();   
            }
            showButtons();
            showTable(); 
        })
        
    });
}

$(".searchTemplates").submit(function(e) {
    e.preventDefault();
});

$(".templates").click(function() {
    $.getJSON("/system/expressionengine/third_party/product_db_admin/assets/json/categories.json", function(data) {
        var list = '',
            id_key;

        $.each(data, function(key, value) {
            id_key = convertJSONforID(key);

            list += '<li class="nav-item size"><a href="#' + key + '" onclick="populate_table(\'' + key + '\')" id="' + id_key + '">' + key + '</a></li>';
        });
        $('.tempTab_col').html(list);  
    });   
});
/* End Inner Modal */

$(document).ready(function () {
    setBtns();
    hideBtns();
});