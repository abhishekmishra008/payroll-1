var counter = 0;
var filter_dropdown;
const value_array = [];
const arra_for_year = [];

function add_exp_list() {
    var expence_name = $('#exp_name').val();
    var expence_date = $('#exp_date').val();
    var expence_amount = $("#exp_amount").val();
    let expence_object = { "title": expence_name, "date": expence_date, "amount": expence_amount };
    value_array.push(expence_object);
    myarray(expence_object);
}

$("#add_expences").click(function() {
    $(".expance_number").show();
});

function myarray(value) {
    counter++;
    var expence_list =
        `<div class="row expance_${counter} bg-light" style="display:flex; box-shadow:1px 1px 3px 1px lightgray; width:100%; margin:auto;  padding: 1rem; margin-bottom: .5rem; align-items:center; margin-top:.5rem">
                <div class="expance_name col-md-7" style="width:65%;margin-left:20px">
                    <p class="mb-0" style="text-transform: capitalize;">${value.title}</p>
                </div>
                <div class="expance_date col-md-2" style="width:20%;">
                    <p class="mb-0">${value.date}</p>
                </div>
                <div class="expance_amount col-md-2" style="width:15%;">
                    <p class="mb-0">${value.amount}</p>  
                </div>
            </div>`;
    var year1 = value.date
    year_array = year1.split("-");
    year_number = year_array[0];

    console.log(year_number);

    filter_dropdown = `<p class="text mb-1">${year_number}</p>`;
    var name = arra_for_year.push(year_number);
    console.log(name);
    $("#year_list").append(filter_dropdown);

    if ($('#exp_name').val() == '' || $('#exp_name').val() == '' || $("#exp_amount").val() == '') {
        alert("please enter the expence details")
    } else {
        $("#expences_list").append(expence_list);
        $(".expance_number").hide();
        $('#exp_name').val('');
        $('#exp_date').val('');
        $("#exp_amount").val('');
        console.log(value_array);
    }
    $(`.expance_${counter}`).click(function() {
        $(this).remove();
    });
};
$(".filter_dropdown").click(function() {
    $(".filter_dropdown-content").toggle();
});

$("#filter_expences").click(function() {
    $(".filter_dropdown").show();
});

function create_ledger() {
    var company_name = document.getElementById('company_name').value;
    var ledger_name = document.getElementById('ledger_name').value;
    var ledger_id = document.getElementById('ledger_id').value;
    var opening_balance = document.getElementById('opening_balance').value;
    var gstin = document.getElementById('gstin').value;
    var address = document.getElementById('address').value;
    var panno = document.getElementById('panno').value;
    console.log(company_name);
    console.log(ledger_name);
    console.log(ledger_id);
    console.log(opening_balance);
    console.log(gstin);
    console.log(address);
    console.log(panno);
    if (company_name !== '' && ledger_name !== '' && ledger_id !== '' && opening_balance !== '' && gstin !== '' && address !== '' && panno !== '') {
        var gst_regex = /[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9]{1}/;
        if (gst_regex.test(gstin)) {
            console.log("corect gst number");
        }
    } else {
        console.log('inside else');
    }
}