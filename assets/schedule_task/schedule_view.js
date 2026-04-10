$(document).ready(function () {

    get_employees();
    // getFirmemployees().then((result) => {
        let newDate1 = new Date();
    let date1 = newDate1.getDate();
    let tempMonth1 = newDate1.getMonth();
    let year1 = newDate1.getFullYear();
        changeDateWiseTask(tempMonth1, year1);
         var month1 = new Array();
    month1[0] = "Jan";
    month1[1] = "Feb";
    month1[2] = "Mar";
    month1[3] = "Apr";
    month1[4] = "May";
    month1[5] = "Jun";
    month1[6] = "Jul";
    month1[7] = "Aug";
    month1[8] = "Sep";
    month1[9] = "Oct";
    month1[10] = "Nov";
    month1[11] = "Dec";
    $('#txtDate').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'MM yy',
        value: new Date(),
        /*onSelectDate: function () {
            var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
            var month = month1[iMonth];
            console.log('onSelectDate');
            changeDateWiseTask(iMonth, iYear);
        },*/
        onClose: function () {
            var iMonth1 = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(iYear, iMonth1, 1));
            var month = month1[iMonth1];
            var year = iYear;
            changeDateWiseTask(iMonth1 , iYear);
        },

        beforeShow: function () {
            if ((selDate = $(this).val()).length > 0) {
                var iYear = selDate.substring(selDate.length - 4, selDate.length);
                var iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
                $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
                $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
                var month = month1[iMonth];
                changeDateWiseTask(iMonth , iYear);
            }
        }
    });

    // });
    get_employee_taskList();

    //get_taskViewInCalendar(tempMonth1,year1);


    // get_boardList();

});

function get_boardList(employee_id="")
{
    if(employee_id==""){
        employee_id=$("#employee_id").val();
    }
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/get_board_list",
        dataType: "json",
        data:{employee_id:employee_id},
        success: function (result) {
            //$.LoadingOverlay("hide");
            if(result.code==200){
                var data = result.board_data;
                var html = '<option value="">Select Board</option>';
                data.forEach(function(item,index){
                    html += `<option value="${item.board_id }">${item.board_name}</option>`;
                });
                $('#board_id').html(html);
            }else if(result.code==201){
                var html=`<option selected value=''>No Board created</option>`;
                $('#board_id').html(html);
            }else{

            }
        },
    });
}

function get_cardList(item_id=''){
    console.log($('#board_id').val());
    var board_id = $('#board_id').val();
    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/get_item_list",
        data:{board_id:board_id},
        dataType: "json",
        success: function (result) {
            $.LoadingOverlay("hide");
            if(result.code==200){
                var data = result.item_data;
                var html = '';
                data.forEach(function(item,index){
                    html += `<option value="${item.item_id }">${item.name}</option>`;
                });
                $('#item_id').html(html);
                if(item_id!="")
                {
                    $('#item_id').val(item_id);
                }
            }else{
                var html ='<option value="">Select Card Item</option>';
                $('#item_id').html(html);
            }
        },
    });
}

/*---------- New Add Code ------------*/
$('#add-new').on('hidden.bs.modal', function () {
    //resetForm();
    //$('#add_task')[0].reset();

    $('#add_total_time').val("0");
    $("#add_total_time").select2();
    $('#select2-add_total_time-container').text("select Time");

    $('.select_board').hide();
    $('.select_card').hide();
    // $("#add_total_time").val([]).trigger('change');
    //$("#add_total_time").empty();

})

$(window).on("load", function () {
    ride();

    /*=== Menu On Right For Edit Or Delete A Ride ===*/
    document.oncontextmenu = function () {
        return false;
    };
    $("body").on("mousedown", ".grids-body .ride", function (e) {
        var x = e.clientX;
        var y = e.clientY;
        if (e.button == 2) {
            let Tid = e.currentTarget.parentElement.id;
            let task_id = $("#task_id" + Tid).val();
            // $("#task_id1").val(task_id);
            $(".new-task").removeClass("show");
            $(".ride").removeClass("selected");
            $(this).addClass("selected");
            $(".ride-opts").addClass("show");
            let left = $(this).offset().left;
            let top = $(this).offset().top;

            $(".ride-opts").css({
                "left": x - 50,
                "top": y -220,
                "z-index": 99,
                /*"top": 196.484,*/
            });

           /* $(".ride-opts").css({
                "left": left + 5,
                "top": top + 3,
                // "top": 196.484,
            });*/
            return false;
        }
        return true;
    });

    /*=== Delete Ride ===*/
    $(".delete-ride").on("click", function () {
        $(".ride.selected").detach();
    });

    /*=== Edit Ride Popup Open ===*/
    $(".edit-ride").on("click", function () {
        if ($(".ride.selected").hasClass('maintenance') || $(".ride.selected").hasClass('preparation') || $(".ride.selected").hasClass('return') || $(".ride.selected").hasClass('service')) {
            $("#change-ride").modal('show');
            $('.change-ride').val('service').trigger('change');
        } else {
            $("#add-new").modal('show');
            let task_id = $('#task_id').val();

            let name = $(".ride.selected .passenger-info .pass-name").text();
            //let contact = $(".ride.selected .passenger-info .pass-contact").text();
            let loc = $(".ride.selected .passenger-info .pass-loc").text();
            // $("#add_task #task_title").val(name);
            //$("#add_task #pass-contact").val(contact);
            $("#add_task #task_desc").val(loc);
            $("#add_task .modal-title h4").text('Edit Task');
            $("#add_task .add-ride-btn").hide();
            $("#add_task .edit-ride-btn").show();
        }
    });


    /*=== Ride Changer ===*/
    $("#ride-changer").submit(function () {
        let changedRide = $('.change-ride').select2('data')[0].element.value;
        let rideName = $('.change-ride').select2('data')[0].text;
        $(".ride.selected .other-info span").html(rideName);
        if (rideName == 'Return') {
            $(".ride.selected .other-info i").attr('class', 'ion-arrow-return-left');
        }
        if (rideName == 'Maintenance') {
            $(".ride.selected .other-info i").attr('class', 'ion-settings');
        }
        if (rideName == 'Preparation') {
            $(".ride.selected .other-info i").attr('class', 'ion-pinpoint');
        }
        if (rideName == 'Service') {
            $(".ride.selected .other-info i").attr('class', 'ion-pull-request');
        }
        $(".ride.selected").attr('class', "ride resize butNotHere " + changedRide);
        $("#change-ride").modal('hide');
        return false;
    });

    /*=== New Ride Menu Open Position ===*/
    let myposleft;
    let mypostop;
    $(".timeslot").on("click", function () {
        myposleft = $(this).offset().left - $(".grids-body").offset().left - 1;
        mypostop = $(this).offset().top - $(".grids-body").offset().top + 2;
    });

    /*=== Task Cancel ===*/
    $(".ride-cancel").on("click", function () {
        $(".ride.selected").removeClass('selected');
        // $("#task_title").val("");
        $("#pass-contact").val("");
        $("#task_desc").val("");
        $("#task_id").val("");
        $("#add-new").modal('hide');
    });


    /*=== Return To Base ===*/
    $(".other-info-btn").on("click", function () {
        let iconClass = $(this).find('i').attr("class");
        let text = $(this).find('span').text();
        let rideClass = $(this).attr('data-rideclass');
        $(".grids-body").prepend(`
				<div class="ride resize butNotHere ${rideClass}" style="left:${myposleft}px; top:${mypostop}px">
					<div class="other-info">
						<i class="${iconClass}"></i>
						<span>${text}</span>
					</div>
				</div>`);
        ride();
        $(".new-task").removeClass("show");
        return false;
    });


    /*=== Select Ride To Assign Driver ===*/
    $('body').on("click", 'a[data-target="#assign-driver"]', function () {
        $(this).parents('.ride').addClass('assign-to-me');
        $(".driver-selector tr").removeClass('selected');
    });


    $("body").on("click", ".driver-selector tr", function () {
        $(this).addClass('selected').siblings().removeClass('selected');
    });

    /*=== Select Driver ===*/
    $("#select-driver").submit(function () {
        // let selectedDriver = $('.all-driver').select2('data')[0].text;
        // let selectedDriver = $("#new-driver").val();
        // let driverImage = $(".inputpicker-wrapped-list .table>tbody>tr>td img").attr('src');
        let selectedDriver = $(".driver-selector tr.selected td.console-driver").text();
        let driverImage = $(".driver-selector tr.selected td.console-driver-img img").attr('src');
        $(".assign-to-me .assign-driver").detach();
        $(".assign-to-me").append(`
			<div class="driver-info">
				<ul>
					<li><img src="${driverImage}"> <i class="ion-android-person"></i> <strong class="driver-name">${selectedDriver}</strong></li>
					<li><i class="ion-ios-clock-outline"></i> <span><i class="hr">02</i>:<i class="mins">02</i>Hours</span></li>
				</ul>
			</div>`);

        RideTime(".assign-to-me");

        $(".assign-to-me").addClass('assigned').removeClass('assign-to-me');

        if ($('.ride').hasClass('change-driver')) {
            $(".change-driver .driver-info .driver-name").html(selectedDriver);
            $(".change-driver .driver-info ul li img").attr('src', driverImage);
            $(".change-driver").removeClass('change-driver');
            $("#assign-driver .modal-header .modal-title h4").text('Assign A Driver');
        }

        $("#assign-driver").modal('hide');
        return false;
    });


    /*=== Cancel Popup ===*/
    $(".cancel-popup").on("click", function () {
        $(".change-driver").removeClass('change-driver');
    });


    /*=== Change Driver ===*/
    $("body").on("click", ".driver-info", function () {
        $(this).parents('.ride').addClass('change-driver');
        let thisDriver = $(this).find('.driver-name').text();
        $("#assign-driver .modal-header .modal-title h4").text('Change The Employee');
        $("#assign-driver").modal('show');
        $(".driver-selector tr").removeClass('selected');
        //console.log($(`td[drivername='${thisDriver}']`));
        $("td[drivername='" + thisDriver + "']").parents('tr').addClass('selected');
    });


    /* ===  Add Short Ride Info To Each Ride === */
    $(".ride").each(function () {
        if ($(this).hasClass('return') || $(this).hasClass('maintenance') || $(this).hasClass('preparation') || $(this).hasClass('service')) {
            return true
        } else {
            // Add Short Ride Info To Each Ride
            let name = $(this).find(".passenger-info .pass-name").text();
            //let contact = $(this).find(".passenger-info .pass-contact").text();
            let loc = $(this).find(".passenger-info .pass-loc").text();
            $(this).append(`<div class="short-ride-info" style="line-height:normal">
					<ul>
						<li> <strong class="pass-name" style="font-size: 17px;">${name}</strong></li>
					    <li> <span class="pass-loc">${loc}</span></li>
					</ul>
				</div>`);
            RideTime(this);
        }
    });


    // Update Driver Time For the Assigned Drive


    /*--------- Calculate Total Hours---------*/

    /* === Date Time Picker == */
    jQuery('.new-datepicker').datetimepicker({
        validateOnBlur: false,
        timepicker: false,
        //format:'D, M d,Y',
        format: 'M-d-Y',
        value: new Date(),
        defaultDate: false,
        onSelectDate: function (dp, $input) {
            let date = dp.getDate();
            let tempMonth = month[dp.getMonth()];
            let year = dp.getFullYear();
            //changeDateWiseTask(tempMonth + "-" + date + "-" + year);
        }
    });

    jQuery('.datetimepicker').datetimepicker({
        validateOnBlur: false,
        format: 'm-d-Y h:i A',
        value: new Date()
    });

    jQuery('.timepicker').datetimepicker({
        validateOnBlur: false,
        datepicker: false,
        format: 'h:i A',
        value: new Date()
    });


    var month = new Array();
    month[0] = "Jan";
    month[1] = "Feb";
    month[2] = "Mar";
    month[3] = "Apr";
    month[4] = "May";
    month[5] = "Jun";
    month[6] = "Jul";
    month[7] = "Aug";
    month[8] = "Sep";
    month[9] = "Oct";
    month[10] = "Nov";
    month[11] = "Dec";


    $(".nextDate").on("click", function () {
        let a = $(".new-datepicker").val();
        let newDate = new Date(new Date(a).getTime() + (1 * 24 * 60 * 60 * 1000));
        let date = newDate.getDate();
        let tempMonth = month[newDate.getMonth()];
        let year = newDate.getFullYear();

        $(".new-datepicker").val(tempMonth + "-" + year);
        //$(".new-datepicker").val(tempMonth + "-" + date  + "-" + year);
        //changeDateWiseTask(tempMonth + "-" + date + "-" + year);
        return false;
    });

    $(".prevDate").on("click", function () {
        let a = $(".new-datepicker").val();
        let newDate = new Date(new Date(a).getTime() - (1 * 24 * 60 * 60 * 1000));
        let date = newDate.getDate();
        let tempMonth = month[newDate.getMonth()];
        let year = newDate.getFullYear();

        //$(".new-datepicker").val(tempMonth + "-" + date  + "-" + year);
        $(".new-datepicker").val(tempMonth + "-" + year);
        //changeDateWiseTask(tempMonth + "-" + date + "-" + year);
        return false;
    });

    /*=== Select2 ===*/
    $('.select,.bottom select').select2({
        placeholder: 'Select an option'
    });
    customScroll();
});


function customScroll() {
    $(".scrolly").mCustomScrollbar({
        axis: "y",
        theme: "light-3",
        scrollbarPosition: "outside"
    });

}

function get_curr_weekDate() {
    const month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var html = '';
    Array.from(Array(10).keys()).map((idx) => {
        if (idx > 0) {
            const d = new Date();
            d.setDate(d.getDate() - d.getDay() + idx);

            var month1 = month[d.getMonth()];
            var day = d.getDate();
            var year = d.getFullYear();

            html += `<div class="timeslot date1">${day}-${month1}-${year}</div>`;
            return d;
        }
    });

    //$('#getWeekDate').html(html);
}

/*=== New Task Menu ===*/

function changeDateWiseTask(month, year) {
    $('#select_year').val(year);
    $('#select_month').val(month);
    const date1 = new Date(year, month, 1);

    const dates1 = [];
    while (date1.getMonth() == month) {
        dates1.push(new Date(date1));
        date1.setDate(date1.getDate() + 1);
    }
    setMonthDates();
    get_date1(dates1,month,year);
}

function get_date1(dates,month,year)
{
    const month2 = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let html1 = '';

    getFirmemployees().then((result) => {
    let employeeList=result;
    if(employeeList.length>0)
    {
        employeeList.map(e=>{
            html1 += `<div class="grids-row " id="${e.user_id}">`;
            for(let y=0;y<=dates.length-1;y++)
                {
                    var date = new Date(dates[y]);
                    date.setDate(date.getDate());

                    var month3 = month2[date.getMonth()];
                    var day = String(date.getDate()).padStart(2, '0');
                    var year = date.getFullYear();
                    var new_date = day + "-" + month3 + "-" + year;
                    var day_name = date.getDay();

                    let day1 = String(date.getDate()).padStart(2, '0');
                    let month11 = date.getMonth();
                    let month112 = month11 + 1;
                    let year1 = date.getFullYear();
                    var new_date11 = year1 +'-'+ month112 +'-'+ day1;

                    let objectDate = new Date();
                    let currday = String(objectDate.getDate()).padStart(2, '0');
                    var currmonth = month2[objectDate.getMonth()];
                    let curryear = objectDate.getFullYear();
                    let currDate = `${currday}-${currmonth}-${curryear}`;

                    let active = '';
                    if (new_date == currDate) {
                        active += 'active';
                    } else {
                        active += '';
                    }
                    var is_holiday = '';
                    var holiday_attr = '';
                    if(day_name==0) {
                        is_holiday +='is_holiday';
                        holiday_attr +='holiday_div="holiday_div"';
                    }else{
                        is_holiday +='';
                        holiday_attr +='';
                    }


                        /*slotInterval: 30,*/


                    var currentDate = new Date();

                    // Get the current year and month
                    var year = currentDate.getFullYear();
                    var month = currentDate.getMonth(); // 0-based month (0 = January, 1 = February, etc.)

                    // Create a new Date object for the first day of the current month
                    var startOfMonth = new Date(year, month, 1);

                    // Create a new Date object for the last day of the current month
                    // We use the 0th day of the next month to get the last day of the current month
                    var endOfMonth = new Date(year, month + 1, 0);
                     let x = {
                        /*slotInterval: 30,*/
                        slotInterval: 15,
                        openDate: startOfMonth,
                        closeDate: endOfMonth
                    };

                    // console.log(x);
                    let startTime = moment(x.openTime, "HH:mm");
                    let endTime = moment(x.closeTime, "HH:mm").add(1, 'days');
                    //html += `<div class="timeslot date1 active_class${day} ${active}" >${day}-${month3}-${year}</div>`;

                     let tTask=e.totalTask;
                     let totalTask=0;
                     let pendingTask=0;
                     let completeTask=0;
                     let taskBadges=`<div style="display: flex;justify-content: space-around;visibility:hidden;"><i class="badge badge-danger">${totalTask}</i> <i class="badge badge-warning">${pendingTask}</i> <i class="badge badge-success">${completeTask}</i></div>`;

                        if(tTask!="")
                        {
                            if(new_date in tTask)
                            {
                                console.log(tTask[new_date]);
                                let workProcess="3";
                                totalTask=tTask[new_date].filter(num => num === workProcess).length;
                                let pendiTarget="1";
                                pendingTask = tTask[new_date].filter(num => num === pendiTarget).length;
                                let compleTarget="2";
                                completeTask = tTask[new_date].filter(num => num === compleTarget).length;
                                taskBadges = `<div style="display: flex;justify-content: space-around;"><i class="badge badge-danger" onclick="get_dateTimeValue('${new_date}','${e.user_id}',1)">${pendingTask}</i> <i class="badge badge-warning" onclick="get_dateTimeValue('${new_date}','${e.user_id}',3)">${totalTask}</i> <i class="badge badge-success" onclick="get_dateTimeValue('${new_date}','${e.user_id}',2)">${completeTask}</i></div>`;
                             }
                        }
                        html1 += `<div class="timeslot time_div drag_div" style="padding: 2px 8px !important;"  data_attr_date="${new_date}" data_attr_time="${e.user_id}"  id="${new_date}" ${holiday_attr}> ${taskBadges} <i onclick="get_dateTimeValue('${new_date}','${e.user_id}',0)">+</i></div>`;
                        startTime.add(x.slotInterval, 'minutes');


                    //$('#getWeekDate').html(html);
                }
                 html1 += `</div>`;

            });
        $('#grids_body').html(html1);
        let todayDate=new Date();
        let formDate=formatDate(todayDate);
        let elementId=document.getElementById(formDate);
        if(elementId)
         {
            elementId.scrollIntoView({
                behavior: 'auto', // Optional: can also use 'auto' for an instant scroll
                // block: 'nearest',     // Align to the top of the scrolling container
                inline: 'center'   // Adjusts horizontal alignment (can omit if not needed)
            });
            // let parentContainer = elementId.closest('.scrollable-container'); // Adjust the selector to match your container
            // if (parentContainer) {
            //     parentContainer.scrollBy({
            //         left: -100, // Scroll 100px to the left
            //         behavior: 'smooth' // Optional: smooth scrolling
            //     });
            // }
         }
        }

        get_taskViewInCalendar(month,year);
        get_totalCalculateHours(dates);
    });

}


function get_dateTimeValue(date, time, status=0)
{
    // $("#task_title").val("");
    // $("#task_id").val("");
    // $("#task_desc").val("");

    // $("#get_date1").val(date);
    // $("#select_date").val(convertDate(date));
    // $("#get_time1").val(time);
    // $("#task_status1").val(status);
    // $('#str_time_show').val(time);
    // $('#str_time_show').select2();
    // $("#add-new").modal('show');

    // // $('#task_title').select2();
    // $('#task_title').select2();

    $("#task_status1").val(status);
    $("#project_name").val('');
    $("#project_name").select2();
    $("#task_id").val('');
    $("#task_id").select2();
    $("#select_date").val(convertDate(date));
    $("#select_task_hours").val('');
    $("#task_desc").val('');
    $("#taskType").val('');

    $("#get_date1").val(date);
    $("#get_time1").val(time);
    $("#taskStatus").val('');

    $("#add-new").modal('show');
    close_list_modal()
}



function get_totalCalculateHours(dates=[]) {
    //$.LoadingOverlay("show");
    if(dates.length<=0)
    {
        let month = $("#select_month").val();
        let year = $("#select_year").val();
        const datent = new Date(year, month, 1);
        var dates = [];
        while (datent.getMonth() == month) {
            dates.push(new Date(datent));
            datent.setDate(datent.getDate() + 1);
        }
    }
    var employee_id=$("#employee_id").val();
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/get_total_hours_time",
        dataType: "json",
        data:{dates:dates,employee_id:employee_id},
        success: function (result) {
            //$.LoadingOverlay("hide");
            if (result.status == 200) {

              //  $("#getTotalHour").html(result.total_hr);
                $('#getWeekDate1').html(result.date);
                //--Show current Date tab
                let objectDate1 = new Date();
                let currday1 = String(objectDate1.getDate()).padStart(2, '0');
                var activeDateid = 'active_class'+currday1;
                var element = document.getElementById(activeDateid);
                console.log(element);
                console.log('element');
                // element.scrollIntoView();
                // $('div .main-schedular').scrollIntoView();

                if (!$('.grids-head').is('.sticky')) {
                    $('.grids-head').addClass('sticky');
                }

            }
        },
    });

}


/*function get_totalCalculateHours(month, year, day) {
	var html2 = '';
	$.ajax({
		type: "POST",
		url: base_url + "TimesheetController/get_total_hours_time",
		dataType: "json",
		data: {
			month: month,
			year: year,
			day: day
		},
		success: function (result) {
			if (result.status == 200) {
				var data = result.totalWorkinghours;
				data.forEach(function (item, index) {
					if (item.total_hr != '' || item.total_hr != null) {
						html2 += `<div class="timeslot date1 total_workinghours">${item.total_hr}</div>`;
					} else {
						html2 += `<div class="timeslot date1 total_workinghours">-</div>`;
					}

				});
			}
		},
	});
}*/

function check_time()
{
    var end_time = $('#add_total_time').val();
    var date = $('#get_date1').val();
    var str_time = $('#get_time1').val();
    var employee_id=$("#employee_id_task").val();
    if(employee_id!="" && employee_id!="Search by Employee")
    {
        str_time=$("#str_time_show").val();
        date=$("#select_date").val();
        date=formatDate(date);
    }
    if(end_time =='' && end_time=='0')
    {

    }else{
        $.LoadingOverlay("show");
        $.ajax({
            type: "POST",
            url: base_url + "TimesheetController/check_taskTime_avail",
            dataType: "json",
            data:{end_time:end_time,date:date,str_time:str_time,employee_id:employee_id},
            success: function (result) {
                $.LoadingOverlay("hide");
                if(result.status==200){
                    $('.add-ride-btn').show();
                }else if(result.status==202){
                    $('.add-ride-btn').show();
                }else{
                    $('.add-ride-btn').hide();
                    swal(result.body);
                    return false;


                    // alert(result.body);
                }
            }
        });
    }

}

function attach_board_function()
{
    $('.select_board').toggle();
    $('.select_card').toggle();
}
function resetForm()
{
    $('#add_task')[0].reset();
    $('#task_id1').val('');
    $('#board_id').val("");
    $('#item_id').val("");
    $('#add_total_time').val("0");
    $("#add_total_time").select2();
    $('#select2-add_total_time-container').text("select Time");
    $('#str_time_show').val("");
    $('#str_time_show').select2({});
    $("#add_task_btn").show();
    $("#edit_task_btn").hide();

}
function schedule_task(type='') {
    var task_id = $('#task_id').val();
    let task_title = [...document.querySelectorAll('#task_id option')].filter(opt => opt.value == task_id)[0]?.innerText || '';
    var project_name = $('#project_name').val();
    var task_desc = $('#task_desc').val();

    if(project_name=='')
    {
        swal('Please fill project name');
        return false;
    }else{
        $.LoadingOverlay("show");
        let date = $("#get_date1").val();
        let time = $("#get_time1").val();

        var d = new Date(date);
        var month = d.getMonth();
        var year = d.getFullYear();

        let myposleft;
        let mypostop;
        $(".timeslot").on("click", function () {
            myposleft = $(this).offset().left - $(".grids-body").offset().left - 1;
            mypostop = $(this).offset().top - $(".grids-body").offset().top + 2;
        });



        var form = document.getElementById('add_task');
        let form_data= new FormData(form);
        form_data.set('month',month);
        form_data.set('year',year);
        form_data.set('time',time);
        form_data.set('type',type);
        form_data.set('date',date);
        form_data.set('task_title', task_title);
        form_data.set('select_task_hours',$('#select_task_hours').val());

        $.ajax({
            type: "POST",
            url: base_url + "TimesheetController/add_new_task",
            data: form_data,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success: function (result) {
                $.LoadingOverlay("hide");
                //window.location.reload();
                if (result.status == 200) {
                    $("#toggle_div").hide();
                    // $("#add-new").modal('hide');
                    // $('body').removeClass('modal-open');
                    // $('.modal-backdrop').remove();
                    resetForm();
                    //document.getElementById("add_task").reset();
                    changeDateWiseTask(month, year);
                    // get_taskViewInCalendar(month,year);
                    get_schedule_task_list(time,date);
                    if (type == 'edit') {
                        // $("#add-new").modal('hide');
                        // $('body').removeClass('modal-open');
                        // $('.modal-backdrop').remove();
                        $(".ride.selected .pass-name").text(task_title);
                        $(".ride.selected .pass-loc").text(task_desc);
                        $(".ride.selected").removeClass('selected');
                        // $("#task_title").val("");
                        $("#task_id1").val("");
                        $("#task_desc").val("");
                        $("#add_task_btn").show();
                        $("#edit_task_btn").hide();
                        // $("#add-new").modal('hide');
                        // $("#add_task .edit-ride-btn").hide();
                        // $("#add_task .add-ride-btn").show();
                        // $("#add_task .modal-title h4").text('Schedule Task');
                        return false;
                    }

                } else {
                    swal(result.body);
                }
            },
        });
    }


}

function saveTotalTime(totalTime, min, task_id, total_width) {
    // $.LoadingOverlay("show");
    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/saveTotalTaskTime",
        dataType: "json",
        data: {
            totalTime: totalTime, min: min, task_id: task_id, total_width: total_width
        },
        success: function (result) {
            get_taskViewInCalendar(sel_month1,sel_year1);
            //$.LoadingOverlay("hide");
            if (result.status == 200) {
                // $.LoadingOverlay("hide");
                //$('.resize .butNotHere').css('width',total_width);
                // get_taskViewInCalendar(sel_month1,sel_year1);
            }else{
                // $.LoadingOverlay("hide");
                swal(result.body);
            }

        },
    });
}

function get_taskViewInCalendar(month='',year=''){
    let myposleft;
    let mypostop;
    $(".timeslot").on("click", function () {
        myposleft = $(this).offset().left - $(".grids-body").offset().left - 1;
        mypostop = $(this).offset().top - $(".grids-body").offset().top + 2;
    });
    if(month=="")
    {
        month = $("#select_month").val();
        year = $("#select_year").val();
    }
    var employee_id=$("#employee_id").val();
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/getTaskCalendarView",
        data:{year:year,month:month,employee_id:employee_id},
        dataType: "json",
        success: function (result) {
              console.log(result);
            if (result.status == 200) {
                // $(".time_div").html('');
                // $(".time_div").html('+');

                let data = result.body;
                let leaveArr = result.leaveArr;
                let alternate_satArr = result.alternate_satArr;
                let holidayArr = result.holidayArr;
                //---For Time---
                if(result.hasOwnProperty('body')){
                    data.forEach(function (element, index) {
                        var totalHrsum1 = '';
                        let html = '';
                        let objectDate = new Date(element.date);
                        //let day1 = objectDate.toDateString();
                        let day1 = String(objectDate.getDate()).padStart(2, '0');
                        let month11 = objectDate.getMonth();
                        let month112 = month11 + 1;
                        let year1 = objectDate.getFullYear();
                        let date11 = `${year1}${month112}${day1}`;

                        let time11 = new Date(element.date + " " + element.from_time);
                        var hours = time11.getHours();
                        var minutes = time11.getMinutes();
                        var seconds = time11.getSeconds();
                        var h = hours.toString().padStart(2, '0');
                        var m = minutes.toString().padStart(2, '0');
                        var s = seconds.toString().padStart(2, '0');
                        let from_time = `${h}${m}${s}`;

                        var date_new = `${date11}-${month112}-${day1}`;
                        let total_timehr = result.total_timehr;

                        if(total_timehr[element.date]!==undefined || total_timehr[element.date]!==false){
                            const sum = total_timehr[element.date].reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());
                            //totalHrsum1 +=[Math.floor(sum.asHours()), sum.minutes()].join(':');
                            //totalHrsum1 +=[Math.floor(sum.asHours() > 0 ? (sum.asHours() < 10 ? "0" + sum.asHours() : sum.asHours()) : "00"), sum.minutes() > 0 ? (sum.minutes() < 10 ? "0" + sum.minutes() : sum.minutes()) : "00"].join(':');
                            var hDisplay = sum.asHours() > 0 ? (sum.asHours() < 10 ? "0" + sum.asHours() : sum.asHours()) : "00";
                            var mDisplay = sum.minutes() > 0 ? (sum.minutes() < 10 ? "0" + sum.minutes() : sum.minutes()) : "00";
                            totalHrsum1 += [Math.floor(hDisplay), mDisplay].join(':');
                        }else{
                            totalHrsum1 += '-';
                        }
                        var month22 = month112 < 10 ? "0" + month112 : month112;
                        $("#t_" + day1 +"_"+ month22).text(totalHrsum1);
                        //$("#t_" + day1 +"_"+ month112).text(totalHrsum1);

                        let width = 100 * element.count;
                        //html += ` <div class="ride resize butNotHere" style="left:${myposleft}px; top:${mypostop}px;width: ${width}px;">
                        var peselected = '';
                        var comselected = '';
                        var cardTaskBag_color='#fff2be';
                        var cardTask_color='#948133';
                        var newTaskBag_color='#fcedef';
                        var newTask_color='#c26370';
                        if(element.work_status=="2")
                        {
                            comselected = 'selected';
                            cardTaskBag_color='#caefc8';
                            cardTask_color='#158854';
                            newTaskBag_color='#caefc8';
                            newTask_color='#158854';
                        }
                        else {
                            peselected= 'selected';
                        }
                        if(element.type=="planned_work")
                        {
                            html += ` <div class="ride resize butNotHere sch_task_id_${element.id}" schedule_task_id="${element.id}" style="left:${myposleft}px; top:${mypostop}px;width: ${element.width}px;color: ${cardTask_color};background: ${cardTaskBag_color};">

                                <div class="passenger-info complete-add scrolly">
									  <ul>
										  <li data-id1="${element.id}"><i class="ion-ios-location-outline"></i>${element.task_desc}</li>
									 </ul>
						       </div>
							   <div class="passenger-info task_scroll scrolly drag_drop_div">
								   <ul class="">
								        <input type="hidden" class="hdn_task_id" name="task_id" id="task_id${date11}${from_time}" value="${element.id}">
								        <li data-id="${element.id}"><strong class="pass-name">${element.task_title}</strong></li>
										<li><span  class="pass-loc">${element.task_desc}</span></li>
									  </ul>
								</div>
							<div class="short-ride-info" id="hover_task_detail_${element.id}" style="line-height:normal;">
										<ul>
										<li><span  class="task-time">${element.total_hour}</span></li>
									       <li id="editableTitleLi${element.id}"><strong class="pass-name" style="font-size:17px;">${element.task_title}</strong></li>
                                            <li id="editableDescLi${element.id}" style="text-align:start;">
                                                     <span class="pass-loc"> ${element.task_desc}</span>
                                            </li>
                                            <select name="work_status" id="work_status" onchange="update_work_status(${element.id},this.value)" style="border: none;">
                                                 <option value="1" ${peselected}>Pending</option>
                                                 <option value="2" ${comselected}>Complete</option>
                                            </select>
                                          <div class="align-items-center d-flex" id="save_cancel_task_title_${element.id}"></div>
										</ul>

								</div>
							   </div>`;
                        }else if(element.type=='' || element.type==null){

                                html += ` <div class="ride resize butNotHere sch_task_id_${element.id}" schedule_task_id="${element.id}" style="left:${myposleft}px; top:${mypostop}px;width: ${element.width}px;background-color: ${newTaskBag_color};color: ${newTask_color}">

                                <div class="passenger-info complete-add scrolly" id="sch_task_id_${element.id}" >
									  <ul>
										  <li data-id1="${element.id}"><i class="ion-ios-location-outline"></i>${element.task_desc}</li>
									 </ul>
						       </div>
							   <div class="passenger-info task_scroll scrolly" >
								   <ul class="">
								        <input type="hidden" class="hdn_task_id" name="task_id" id="task_id${date11}${from_time}" value="${element.id}">
								       <li data-id="${element.id}"><strong class="pass-name">${element.task_title}</strong></li>
										<li><span  class="pass-loc">${element.task_desc}</span></li>
									  </ul>
								</div>
							<div class="short-ride-info" id="hover_task_detail_${element.id}" style="line-height:normal;">
										<ul>
										<li><span  class="task-time">${element.total_hour}</span></li>
									       <li onclick="contentEditableFocus('editableTitleLi',${element.id},this)" onblur="updateFocusViewData('editableTitleLi${element.id}',${element.id},'editableTitleLi',this)" onkeypress="return event.keyCode != 13;"
                                                     id="editableTitleLi${element.id}" ><strong class="pass-name" style="font-size:17px;">${element.task_title}</strong></li>
                                            <li onclick="contentEditableFocus('editableDescLi',${element.id},this)" onblur="updateFocusViewData('editableDescLi${element.id}',${element.id},'editableDescLi',this)" onkeypress="return event.keyCode != 13;"
                                                     id="editableDescLi${element.id}" style="text-align:start;">
                                                     <span class="pass-loc">${element.task_desc}</span>
                                            </li>

                                             <select name="work_status" id="work_status" onchange="update_work_status(${element.id},this.value)" style="border: none;">
                                                 <option value="1" ${peselected}>Pending</option>
                                                 <option value="2" ${comselected}>Complete</option>
                                            </select>
                                            <button type="button" class="btn btn-sm btn-link" data-toggle="modal" data-target="#add-new" onclick="editScheduleTask(${element.id})"><i class="fa fa-edit"></i></button>
                                       <div class="align-items-center d-flex" id="save_cancel_task_title_${element.id}"></div>
										</ul>
								</div>
							   </div>`;

                        }


                        // $('#task_id1').val(element.id);

                        $(".grids-body #" + date11 + from_time).html(html);

                            $(".grids-body #" + date11 + from_time).addClass('drag_drop_eve'+date11);

                        //customScroll();

                    });

                }

                if(result.hasOwnProperty('leaveArr')){
                    leaveArr.map(e=>{
                        let objectDate = new Date(e);
                        //let day1 = objectDate.toDateString();
                        let day1 = String(objectDate.getDate()).padStart(2, '0');
                        let month11 = objectDate.getMonth();
                        let month112 = month11 + 1;
                        let year1 = objectDate.getFullYear();
                        let date11 = `${year1}-${month112}-${day1}`;


                        $("#"+date11).css('pointer-events','none');
                        $("#"+date11).css('opacity','0.3');
                        $(".grids-body #"+date11+ " .drag_div").attr('holiday_div','holiday_div');
                    });
                }

               if(result.hasOwnProperty('alternate_satArr')){
                   alternate_satArr.map(e=>{
                        let objectDate = new Date(e);
                        //let day1 = objectDate.toDateString();
                        let day1 = String(objectDate.getDate()).padStart(2, '0');
                        let month11 = objectDate.getMonth();
                        let month112 = month11 + 1;
                        let year1 = objectDate.getFullYear();
                        let date11 = `${year1}-${month112}-${day1}`;

                       var sat_html = ` <div class="ride resize butNotHere maintenance" style="left:${myposleft}px; top:${mypostop}px;width: 3770px;background: #f1f3fb;color: #8d8f96;">
                                                <div class="other-info">
                                                    <i class="ion-settings"></i>
                                                    <span>Maintenance</span>
                                                </div>
                                            </div>`;

                      // $(".grids-body #"+date11+"100000").html(sat_html);

                       $("#"+date11).css('pointer-events','none');
                       $("#"+date11).css('opacity','0.3');
                       $(".grids-body #"+date11).addClass('holiday_div');
                       $(".grids-body #"+date11+ " .drag_div").attr('holiday_div','holiday_div');
                       //$('.holiday_div').droppable('disable');
                    });
                }

                if(result.is_holiday==1)
                {
                    holidayArr.map(e=>{
                        console.log(e.holiday_date);
                        let objectDate = new Date(e.holiday_date);
                        //let day1 = objectDate.toDateString();
                        let day1 = String(objectDate.getDate()).padStart(2, '0');
                        let month11 = objectDate.getMonth();
                        let month112 = month11 + 1;
                        let year1 = objectDate.getFullYear();
                        let date11 = `${year1}${month112}${day1}`;
                        var html22 = ` <div class="ride resize butNotHere "  style="left:${myposleft}px; top:${mypostop}px;width: 3770px;color: #333;background: #ca2b368f;">

                                <div class="passenger-info complete-add scrolly">
									  <ul>
										  <li data-id1=""><i class="ion-ios-location-outline"></i>${e.holiday_name}</li>
									 </ul>
						       </div>
							   <div class="passenger-info task_scroll scrolly">
								   <ul class="">
								       <li data-id=""><strong class="pass-name">${e.holiday_name}</strong></li>
									  </ul>
								</div>
							   </div>`;

                        $(".grids-body #"+date11+"100000").html(html22);
                    });
                }

               if(result.hasOwnProperty('holidayArr')){
                   holidayArr.map(e1=>{
                       let holiday_Date = new Date(e1.holiday_date);
                       //let day1 = holiday_Date.toDateString();
                       let h_day = String(holiday_Date.getDate()).padStart(2, '0');
                       let h_month11 = holiday_Date.getMonth();
                       let h_month112 = h_month11 + 1;
                       let h_year1 = holiday_Date.getFullYear();
                       let h_date11 = `${h_year1}-${h_month112}-${h_day}`;

                       $("#"+h_date11).css('pointer-events','none');
                       $("#"+h_date11).css('opacity','0.6');
                       $(".grids-body #"+h_date11+ " .drag_div").attr('holiday_div','holiday_div');
                   });
               }

                // var new_date = new date()
                //var day = date.getDay();
                //return [(day == 1 || day == 2 || day == 3 || day == 4 ||day == 5 ||day == 6 )];
                /*if (date.getFullYear() == natDays[i][0] && date.getMonth() == natDays[i][1] - 1 && date.getDate() == natDays[i][2]) {
                    return [false, natDays[i][3] + '_day'];
                }*/

                ride();
                //$(".grids-body").prepend(html);
            } else {

            }


        },
    });
}
function update_work_status(task_id,status)
{
    $.LoadingOverlay("show");
    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();

    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/update_work_status",
        dataType: "json",
        data:{task_id:task_id,status:status},
        success: function (result) {
            $.LoadingOverlay("hide");
            if (result.status == 200) {
                get_taskViewInCalendar(sel_month1,sel_year1);
            }
        },
    });
}

/*
<p class=" fchild mb-0 mr-2 w-100" onclick="contentEditableFocus('fcount1',184,'focus',null,1,'fcbtn_hide1','1-focus')" onkeypress="return event.keyCode != 13;" id="fcount1"
contenteditable="false" data-tribute="true" style="background-color: transparent;">payroll timesheetj
</p>

* */


function close_list_modal(type=0) {
    // $(".ride-opts").removeClass("show");
    // $(".new-task").removeClass("show");
    // $(".new-task").css({
    //     "left": 0,
    //     "top": 0
    // });
    if(type==1)
    {
        // $("#start_time_div").show();
        $("#str_time_show").val('');
        $('#str_time_show').select2({});
        // $("#dateRow").show();
        $("#get_time1").val('');
        $("#get_date1").val('');
        $("#task_id1").val('');
        $("#edit_task_btn").hide();
        $("#add_task_btn").show();
    }
    else
    {
        // $("#start_time_div").hide();
        // $("#dateRow").hide();
        $("#edit_task_btn").hide();
        $("#add_task_btn").show();

        get_schedule_task_list($("#get_time1").val(),$("#get_date1").val());
    }

    getProjectList();
    myTaskListOption()
    // console.log("modal closed!");

     // Get the current time
    var now = new Date();
var hours = now.getHours().toString().padStart(2, '0');
var minutes = now.getMinutes();

// Round minutes down to the nearest 15 minutes interval
minutes = Math.floor(minutes / 15) * 15;
var currentTime = `${hours}:${minutes.toString().padStart(2, '0')}:00`;

// Select the dropdown
var selectElement = document.getElementById('str_time_show');

// Initialize variables to track the closest time
var closestValue = selectElement.options[1].value; // Start with the first time option
var closestDifference = Infinity;

// Loop through options to find the nearest time
for (var i = 1; i < selectElement.options.length; i++) { // Start from 1 to skip "Select Start Time"
    var optionTime = selectElement.options[i].value;

    // Calculate the time difference in minutes
    var difference = Math.abs(timeToMinutes(currentTime) - timeToMinutes(optionTime));

    if (difference < closestDifference) {
        closestDifference = difference;
        closestValue = optionTime;
    }
}

// Set the nearest time as selected
console.log('closestValue:', closestValue);
$(selectElement).val(closestValue).trigger('change'); // Use jQuery to set value and trigger change

// Add one hour to the closest time and set it to the end time dropdown
var newTime = addOneHour(closestValue);
$("#add_total_time").val(newTime).trigger('change');

}
function timeToMinutes(time) {
    var parts = time.split(':');
    return parseInt(parts[0]) * 60 + parseInt(parts[1]);
}
function addOneHour(time) {
    var parts = time.split(':');
    var hours = parseInt(parts[0]) + 1;
    if (hours === 24) hours = 0; // Handle wrap-around to midnight
    return hours.toString().padStart(2, '0') + ':' + parts[1] + ':' + parts[2];
}
function deleteTask(id) {
    //  $('.select_board').hide();
    // $('.select_card').hide();
    // close_list_modal();

    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();

   swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this Task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            $.LoadingOverlay("show");
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: base_url + "TimesheetController/deleteTask",
                    dataType: "json",
                    data: {
                        task_id: id
                    },
                    success: function (result) {
                        $.LoadingOverlay("hide");
                        if(result.status==200)
                        {
                            swal("Your Task has been deleted!", {
                                icon: "success",
                            });
                            $('#t_'+result.day+'_'+result.month).text('-');
                        }
                        get_schedule_task_list(result.user_id,result.date)
                        get_taskViewInCalendar(sel_month1,sel_year1);

                    },
                });

            } else {
                //swal("Your imaginary file is safe!");
                get_taskViewInCalendar(sel_month1,sel_year1);
                $.LoadingOverlay("hide");
            }
        });

}

$('body').click(function (event) {
    if (!$(event.target).closest('.ride-opts').length && !$(event.target).is('.ride-opts')) {
        $(".ride-opts").removeClass("show");
        //$(".new-task").removeClass("show");
    }
});

document.addEventListener(
    "click",
    function (event) {

        if (!$(event.target).is(".grids-body .timeslot")) {
            closeModal();
        }
    },
    false
)

function closeModal() {
    $(".ride-opts").removeClass("show");
    $(".new-task").removeClass("show");
    $(".new-task").css({
        "left": 0,
        "top": 0
    });
}


var drag_task_id = '';
function ride() {
    /*=== Draggable Box ===*/
  $(".resize").draggable({
        axis: "x,y",
        animate: true,
        grid: [ 102,91],
        containment:'.grids-body',
        scroll:true,
        revert: "valid",
        refreshPositions: true,
        start: function (event, ui) {
            //console.log(event);
            drag_task_id = event.target.attributes[1].textContent;
            //console.log(drag_task_id);
        },
        stop: function (event, ui) {
            setTimeout(function () {
              //update_draggable_Task(event,ui);
            }, 100);
        }
    }).click(function() {
        $(this).draggable( {disabled: false});
    }).dblclick(function() {
        $(this).draggable({ disabled: true });
    });

 $(".drag_div").droppable({
        tolerance: "touch",
      drop: function (event, ui) {
            var div_id1 = event.target.attributes[4].textContent;
          //ui.draggable.addClass("dropped");
          //$("#"+div_id1).html(ui.draggable);
      }
    });
 /*$(".ride").droppable({
        tolerance: "touch"
    });*/

    var targetPos = [];
    var sp = "";
    /*=== Resizable Box ===*/
    $(".resize").resizable({
        grid: [102, 0],
        animate: false,
        handles: 'e',
        containment: '.grids-body',
        maxHeight: 77,
        minHeight: 77,

        start: function (event, ui) {
            targetPos = [];
            $('.ride').each(function () {
                targetPos.push($(this).position());
            });
            sp = ui.position.left + ui.size.width;
        },
        stop: function (event, ui) {
            console.log(event);
            var ep = ui.position.left + ui.size.width;
            var tp = ui.position.top;
            $.each(targetPos, function (i, e) {
                if (targetPos[i].top == tp) {
                    if (targetPos[i].left > sp && targetPos[i].left < ep) {
                        ui.element.css(ui.originalSize);
                        setTimeout(function () {
                            detectShortRides();
                        }, 100);
                    }
                }
            });
        }
        //resize: updateHandle,
    });

   /* $( ".grids" ).scroll(function() {
        updateHandle();
    });

    function updateHandle() {
        $( ".grids" ).css('overflow-x','scroll');
        table = $('.grids');
        var bottom =  table.scrollLeft () + 25;
       $('.ui-resizable').css('left', bottom + 'px');
        console.log(bottom);
        console.log('aaa');
    }*/


    $(".resize").on("resizestop", function (event, ui) {

        let Tid = event.currentTarget.parentElement.id;
        let task_id = $("#task_id" + Tid).val();
        $(event.currentTarget.parentElement).addClass("drag_drop_eve");


        /*if( e.button == 2 ) {
            let Tid = e.currentTarget.parentElement.id;
            let task_id = $("#task_id" + Tid).val();
        }*/

        //if (ui.size.width <= 204) {
        $(this).addClass("short-ride");

        /*} else {
            $(this).removeClass("short-ride");
        }*/
        setTimeout(function () {
            RideTime(ui.element[0], task_id);
        }, 400);
    });

    function detectShortRides() {
        $(".ride").each(function () {
            //if ($(this).width() <= 204) {
            $(this).addClass("short-ride");
            // } else {
            // 	$(this).removeClass("short-ride");
            // }
        });
    }

    detectShortRides();
    $(".show-add").on("click", function () {
        $(this).parents('.ride').find('.complete-add').fadeIn();
    });

    $(".complete-add").on("click", function () {
        $(this).fadeOut();
    });
}

function RideTime(thisRide, task_id) {
    let rideWidth = $(thisRide).width();
    //let onePx = 17900.00;
    let onePx = 8950.00;

    /*--- Code Add for exact width ex.(100,200,300...) on 2/2/2023 ---*/
    let boxRideWidth = $(thisRide).width();
    var widthLastDig = rideWidth % 100;
    if(widthLastDig >=1 && widthLastDig<=99)
    {
         rideWidth = rideWidth.toString();
         rideWidth = rideWidth.slice(0, -2) + "00";

    }
    /*--- Code Add for exact width ex.(100,200,300...) on 2/2/2023 ---*/


    var miliseconds = rideWidth * onePx;
    var days, hours, minutes, seconds, total_hours, total_minutes, total_seconds;

    total_seconds = parseInt(Math.floor(miliseconds / 1000));
    total_minutes = parseInt(Math.floor(total_seconds / 60));
    total_hours = parseInt(Math.floor(total_minutes / 60));
    days = parseInt(Math.floor(total_hours / 24));

    seconds = parseInt(total_seconds % 60);
    minutes = parseInt(total_minutes % 60);
    hours = parseInt(total_hours % 24);

    let lastdigit = total_minutes % 10;
    console.log(total_minutes);
    if (lastdigit >5 && lastdigit != 0) {
        total_minutes = total_minutes + 10;
        total_minutes = total_minutes.toString();
        total_minutes = total_minutes.slice(0, -1) + "0";
    }else if(lastdigit < 5 && lastdigit != 0){
        total_minutes = total_minutes + 1;

    }/* else if (lastdigit < 5 && lastdigit != 0) {
		total_minutes = total_minutes.toString();
		total_minutes = total_minutes.slice(0, -1) + "0";
	}*/

    timeConvert(total_minutes, task_id, boxRideWidth);
}

/* ===  Convert Mili Seconds Hours === */

//function convertMiliseconds(miliseconds, format) {
function convertMiliseconds(miliseconds, task_id) {
    var days, hours, minutes, seconds, total_hours, total_minutes, total_seconds;

    total_seconds = parseInt(Math.floor(miliseconds / 1000));
    total_minutes = parseInt(Math.floor(total_seconds / 60));
    total_hours = parseInt(Math.floor(total_minutes / 60));
    days = parseInt(Math.floor(total_hours / 24));

    seconds = parseInt(total_seconds % 60);
    minutes = parseInt(total_minutes % 60);
    hours = parseInt(total_hours % 24);

    timeConvert(total_minutes, task_id, miliseconds);
    /*switch(format) {
        case 's':
            return total_seconds;
            break;
        case 'm':
            return total_minutes;
            break;
        case 'h':
            return total_hours;
            break;
        case 'd':
            return days;
            break;
        default:
            return { d: days, h: hours, m: minutes, s: seconds };
    }*/
};

function convertPixelToTime(pixels, task_id) {
    //let onePx = 17647.05882352941;
    //return convertMiliseconds(pixels * onePx,task_id);
}


setTimeout(function () {
    $(".xdsoft_today").trigger('click');
}, 1000);

/*--------- Calculate Total Hours---------*/
function timeConvert(n, task_id, total_width) {
    var total_min = n;

    var hours = (total_min / 60);
    var rhours = Math.floor(hours);
    var minutes = (hours - rhours) * 60;
    var rminutes = Math.round(minutes);

    //--code add for exact 15 min on 2/2/2023--
    var rmin_lastDig = rminutes % 10;
    // alert(rmin_lastDig);
    if(rmin_lastDig >=1 && rmin_lastDig <=9)
    {
        rminutes = rminutes.toString();
        rminutes = rminutes.slice(0,-1) +"5";
        console.log(rminutes);
    }
    //--END code add for exact 15 min on 2/2/2023--

    /*if(rminutes<10 && rminutes!=0)
    {
        var rminutes1 = 15;
    }else{
        var rminutes1 = rminutes;
    }*/

    var hDisplay = rhours > 0 ? (rhours < 10 ? "0" + rhours : rhours) : "00";
    var mDisplay = rminutes > 0 ? (rminutes < 10 ? "0" + rminutes : rminutes) : "00";
    //var total_time1 = rhours + " h " + rminutes + " m";
    var total_time = hDisplay + ":" + mDisplay;
    console.log(total_time);
    saveTotalTime(total_time, total_min, task_id, total_width);

    //return num + " minutes = " + rhours + " hour(s) and " + rminutes + " minute(s).";
}

//----For Inline editable----
function contentEditableFocus(licont_id,task_id,div)
{
    var div_id = div.id;
    if(licont_id=='editableTitleLi')
    {
        div.setAttribute("contenteditable",true);
        $('#editableTitleLi'+task_id).css('border','1px solid lightgrey');
        $('#editableDescLi'+task_id).css('border','unset');

    }else if(licont_id=='editableDescLi'){
        div.setAttribute("contenteditable", true);
        $('#editableDescLi'+task_id).css('border','1px solid lightgrey');
        $('#editableTitleLi'+task_id).css('border','unset');
    }
    /*var html = `<button type="button" class="btn btn-link btn-sm fcbtn_hide fcbtn_hide1" style="text-decoration:none;margin-top:3px;" onclick="updateFocusViewData('${div_id}',${task_id},'${licont_id}',div)">
		<i class="fa fa-check fa-sm" style="color: #f8f9fa;margin-left: auto;padding: 4px 4px 4px 4px;background-color: #75b780;border-radius: 8px;"></i></button>
		<button type="button" class="btn btn-link btn-sm fcbtn_hide tobtn_hide1" style="text-decoration:none;margin-top:3px;" onclick="goBackToOriginal('1-focus')">
		<i class="fa fa-times fa-sm" style="color: #f8f9fa;margin-left: auto;padding: 4px 5px 4px 5px;background-color: #dc3545;border-radius: 12px;"></i></button>`;*/

    //$('#save_cancel_task_title_'+task_id).html(html);


}

function updateFocusViewData(divId,task_id,editDivid,div)
{
    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();
    var content = $('#'+divId).text();
    div.setAttribute("contenteditable", false);
    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/updateEditableContent",
        dataType: "json",
        data: {
            content: content, task_id: task_id,flag:editDivid
        },
        success: function (result) {
            //div.setAttribute("contenteditable", false);
            //$('#save_cancel_task_title_'+task_id).css('display','none');
            $.LoadingOverlay("hide");
            if (result.status == 200) {

                get_taskViewInCalendar(sel_month1,sel_year1);
            }else{
                swal(result.body);
                return false;
            }

        },
    });
}

function get_employee_taskList()
{
    var employee_id=$("#employee_id").val();
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/get_employeeTaskList",
        dataType: "json",
        data:{employee_id:employee_id},
        success: function (result) {

            if(result.status==200)
            {
                $('#work_planing_data').html(result.body);
            }else{
                console.log(result.body);
            }

        },
    });
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    var sel_year2 = $('#select_year').val();
    var sel_month2 = $('#select_month').val();
    ev.preventDefault();
    var attr_div = ev.target.attributes;
    if(attr_div.hasOwnProperty('holiday_div')==true)
    {
        swal('Task can not Drag n drop on holiday');
        return false;
        // console.log('yes');
    }else {
        // console.log('No');
    }

    if(ev.hasOwnProperty('target')==true)
    {
        var drag_task_id1 = drag_task_id;
        //var drop_task_id1 = ev.target.firstElementChild.attributes[1].nodeValue;
        //var drop_task_id1 = ev.toElement.offsetParent.attributes[1].textContent;

        // if(drag_task_id1==drop_task_id1)
        // {

            drag_drop_schedule_task(ev);

        // }else{
        //
        //     $.LoadingOverlay("show");
        //     swal('Please Select another time');
        //     get_taskViewInCalendar(sel_month2,sel_year2);
        //     $.LoadingOverlay("hide");
        //    // return false;
        // }
    }else{

        var drag_div = ev.target.offsetParent.attributes[1].nodeValue;
        if(drag_div=="grids_body")
        {
            /*-----Code For Drag Planned Work only ------*/
            var data = ev.dataTransfer.getData("text");
            ev.target.appendChild(document.getElementById(data));
            //var div_id = ev.path[0].id;
            var div_id = ev.target.childNodes[1].id;
            $('#'+div_id).addClass('ride');
            $('#'+div_id).addClass('passenger-info');
            $('#'+div_id).removeClass('car');
            $('#'+div_id).removeClass('car-top');
            $('#'+div_id).removeClass('car-bottom');
            $('#'+div_id).css({"background-color":"#fff2be","color":"#948133"});
             drag_workplaning_task(ev,div_id);
        }else{
            $.LoadingOverlay("show");
            swal('Task can not schedule at same date and time');
            $.LoadingOverlay("hide");
            return false;

        }
    }

}
/*+++++++++++++++*/
/*var drag_drop_schedule_task = (function (event) {
    var done = false;
    return function (event) {
        if (!done) {
            done = true;
            console.log(event);
            console.log('aaa');
            var sel_year1 = $('#select_year').val();
            var sel_month1 = $('#select_month').val();

            var div_attr = event.target;
            var work_date = div_attr.attributes[1].textContent;
            var work_time = div_attr.attributes[2].textContent;
            if(event.toElement.offsetParent.attributes[1].textContent == undefined || event.toElement.offsetParent.attributes[1]==null)
            {
                var schedule_task_id = '0';
            }else{

                var schedule_task_id = event.toElement.offsetParent.attributes[1].textContent;
            }
            $.LoadingOverlay("show");
            $.ajax({
                type: "POST",
                url: base_url + "TimesheetController/add_drop_schedule_work",
                dataType: "json",
                data:{work_date:work_date,
                    work_time:work_time,
                    schedule_task_id:schedule_task_id,
                },
                success: function (result) {
                    get_taskViewInCalendar(sel_month1,sel_year1);
                    if (result.status == 200) {
                        var month=result.month-1;
                        var year=result.year;
                        $('#t_'+result.day+'_'+result.month).text('-');
                        $.LoadingOverlay("hide");
                        // get_taskViewInCalendar(month,year);
                    }else{
                        $.LoadingOverlay("hide");
                        //swal(result.body);
                        console.log(result.body);

                    }
                }
            });

        }
       location.reload();
    };

})();*/
var runSearch = true;
var drag_drop_schedule_task = (function (event) {
    event.preventDefault();
    if(!runSearch){
        return;
    }
    /*------------------------*/
    console.log(event);
    var drag_task_id_new = drag_task_id;
    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();
    //var drag_start_id = drag_task_id;
    //var div_drop_id = event.target.firstElementChild;

    var div_attr = event.target;
    var work_date = div_attr.attributes[1].textContent;
    var work_time = div_attr.attributes[2].textContent;
    var target_value = event.toElement.offsetParent;

    /*if(event.toElement.offsetParent.attributes[1].textContent == undefined || event.toElement.offsetParent.attributes[1]==null)
    {
        var schedule_task_id = '0';
    }else{
        var schedule_task_id = event.toElement.offsetParent.attributes[1].textContent;
    }*/

    if(drag_task_id_new !==null && drag_task_id_new!==''){
        var schedule_task_id = drag_task_id_new;
    }else{
        var schedule_task_id = event.toElement.offsetParent.attributes[1].textContent;
    }
     console.log(schedule_task_id);
    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/add_drop_schedule_work",
        dataType: "json",
        data:{work_date:work_date,
            work_time:work_time,
            schedule_task_id:schedule_task_id,
        },
        success: function (result) {
            get_taskViewInCalendar(sel_month1,sel_year1);
            if (result.status == 200) {
                var month=result.month-1;
                var year=result.year;
                $('#t_'+result.day+'_'+result.month).text('-');
                $.LoadingOverlay("hide");
            }else{
                $.LoadingOverlay("hide");
                //swal(result.body);
                console.log(result.body);

            }
        },
    });
    /*------------------------*/

    runSearch = false;
    setTimeout(function(){
        runSearch = true;
        console.log('dddd');
    }, 3000);

});
/*+++++++++++++++*/

/*function drag_drop_schedule_task(event)
{
    var sel_year1 = $('#select_year').val();
    var sel_month1 = $('#select_month').val();
        //var drag_start_id = drag_task_id;
        //var div_drop_id = event.target.firstElementChild;

        var div_attr = event.target;
        var work_date = div_attr.attributes[1].textContent;
        var work_time = div_attr.attributes[2].textContent;
        if(event.toElement.offsetParent.attributes[1].textContent == undefined || event.toElement.offsetParent.attributes[1]==null)
        {
            var schedule_task_id = '0';
        }else{

            var schedule_task_id = event.toElement.offsetParent.attributes[1].textContent;
        }
    $.LoadingOverlay("show");
        $.ajax({
            type: "POST",
            url: base_url + "TimesheetController/add_drop_schedule_work",
            dataType: "json",
            data:{work_date:work_date,
                work_time:work_time,
                schedule_task_id:schedule_task_id,
            },
            success: function (result) {
                get_taskViewInCalendar(sel_month1,sel_year1);
                if (result.status == 200) {
                    var month=result.month-1;
                    var year=result.year;
                    $('#t_'+result.day+'_'+result.month).text('-');
                    $.LoadingOverlay("hide");
                   // get_taskViewInCalendar(month,year);
                }else{
                    $.LoadingOverlay("hide");
                    //swal(result.body);
                    console.log(result.body);

                }
            },
        });

}*/
/*-----Code For Drag Planned Work only ------*/
function drag_workplaning_task(ev,div_id)
{
    get_employee_taskList();
    //var div_attr = ev.path[0];
    /*-----Code For Drag Planned Work only ------*/
    var div_attr = ev.target;
    var work_date = div_attr.attributes[1].textContent;
    var work_time = div_attr.attributes[2].textContent;
    var work_task_id =ev.target.childNodes[1].attributes[2].nodeValue;
    /*-----OLD code For Planned Work only ------*/
    var employee_id=$("#employee_id").val();
    $.LoadingOverlay("show");
   $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/add_drop_planned_work",
        dataType: "json",
        data:{work_date:work_date,
            work_time:work_time,
            work_task_id:work_task_id,
            employee_id:employee_id,
        },
        success: function (result) {
            if (result.status == 200) {
                var month=result.month-1;
                var year=result.year;
                //$('#'+div_id).css({"width":result.width+"px"});
                get_taskViewInCalendar(month,year);

                $.LoadingOverlay("hide");
            }else{
                $.LoadingOverlay("hide");
                //swal(result.body);
               console.log(result.body);
            }
        },
    });

}

function hoverdiv(e,divid){

    var left  = e.clientX  + "px";
    var top  = e.clientY  + "px";
    var x = e.clientX;
    var y = e.clientY;
    var div = document.getElementById(divid);
    //div.style.left = left;
   // div.style.top = top;
    $('.short-ride-info').css({'top':(y-600) +'px','left':(x-400) +'px'});
    //$("#"+div).toggle();
    return false;
}


// $(".ride").on("hover", function (e) {
//     var tooltipSpan = document.getElementById("short-ride-info");
//     console.log(tooltipSpan);
//     var x = e.clientX;
//     var y = e.clientY;
//     console.log(y);
//    $('.short-ride-info').css({'top':(y-350) +'px','left':(x-350) +'px'});

// });

/*  document.onmousemove = handleMouseMove;
    function handleMouseMove(event) {
       console.log(event);
        var dot, eventDoc, doc, body, pageX, pageY;

        event = event || window.event; // IE-ism

        // If pageX/Y aren't available and clientX/Y are,
        // calculate pageX/Y - logic taken from jQuery.
        // (This is to support old IE)
        if (event.pageX == null && event.clientX != null) {
            eventDoc = (event.target && event.target.ownerDocument) || document;
            doc = eventDoc.documentElement;
            body = eventDoc.body;

            event.pageX = event.clientX +
                (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
                (doc && doc.clientLeft || body && body.clientLeft || 0);
            event.pageY = event.clientY +
                (doc && doc.scrollTop  || body && body.scrollTop  || 0) -
                (doc && doc.clientTop  || body && body.clientTop  || 0 );
        }


    }*/

/*----- END Code For Drag Planned Work only ------*/

function get_employees() {
    $.ajax({
        url: base_url + 'DashboardController/get_junior_employees',
        type: "POST",
        success: function (success) {
            success = JSON.parse(success);
            var data = success.data;
            if (success.message == 'success') {
                $(".employee_id").html(data);
            } else {
                $(".employee_id").hide();
                $("#daily_task").hide();
            }
        }
    });
}
function formatDate(date) {
    date=new Date(date);
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
function editScheduleTask(id)
{
    console.log(id);
    $("#toggle_div").show();
    $("#edit_task_btn").show();
    $("#add_task_btn").hide();
    $("#task_id1").val(id);
    $.ajax({
        url: base_url + 'TimesheetController/getScheduleTaskData',
        type: "POST",
        dataType: "json",
        data:{id:id},
        success: function (success) {
            var data = success.body;
            if (success.status == 200) {
                console.log(data);

                console.log($("#project_name"), data.project_id);


                $("#project_name").val(`${data.project_id}-${data.project_name}`);
                $("#project_name").select2();
                $("#task_id").val(data.task_id);
                $("#task_id").select2();
                $("#select_date").val(data.date);
                $("#select_task_hours").val(data.time_spand);
                $("#task_desc").val(data.task_desc);
                $("#taskType").val(data.activity_type);
                $("#taskStatus").val(data.work_status);


                // $("#board_id").val(data.board_id);
                // get_cardList(data.item_id);
            } else {
                // $("#task_title").val('');
                $("#project_name").val('');
                $("#project_name").select2();
                $("#task_id").val('');
                $("#task_id").select2();
                $("#select_date").val('');
                $("#select_task_hours").val('');
                $("#task_desc").val('');
                $("#taskType").val('');
                $("#taskStatus").val('');
            }
        }
    });
}
function setDate(date)
{
    $("#get_date1").val(formatDate(date));
}
function setTime(time)
{
    $("#get_time1").val(time);
}
function convertDate(dateStr) {
    // Split the original date string
    const [day, monthAbbrev, year] = dateStr.split('-');

    // Create a mapping of month abbreviations to numbers
    const monthMap = {
        'Jan': '01',
        'Feb': '02',
        'Mar': '03',
        'Apr': '04',
        'May': '05',
        'Jun': '06',
        'Jul': '07',
        'Aug': '08',
        'Sep': '09',
        'Oct': '10',
        'Nov': '11',
        'Dec': '12'
    };

    // Get the numerical month
    const month = monthMap[monthAbbrev];

    // Construct the new date string in MM/DD/YYYY format
    const formattedDate = `${year}-${month}-${day}`;

    return formattedDate;
}
function monthlyTask()
{
    let month=$("#txtDate").val();
    let employee_id=$("#employee_id").val();
    window.location.href=base_url+"Excel_export/monthlyTask?employee_id="+employee_id+"&month="+month;
}
function getDaysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }
    function createDateFromMonthYear(month, year) {
       return new Date(year, month - 1, 1);
    }
    function setMonthDates()
    {
        // Get the container where the dates will be inserted
        let dateSlotsContainer = $('#dateSlots');
        let month=parseInt($("#select_month").val());
        month=(month*1)+1;
        let year=parseInt($("#select_year").val());
       let currentDate = createDateFromMonthYear(month, year);
        let daysInMonth = getDaysInMonth(month, year);
        dateSlotsContainer.empty();
        let todayDate=new Date();
        let formattedTodayDate = todayDate.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        // Loop through and add dates for each time slot
        for (let i = 0; i < daysInMonth; i++) {
            // Clone the current date
            // let date = new Date(currentDate);

            // Format the date as 'DD-MM-YYYY'
            let formattedDate = currentDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            // Create a new div for each date
            let classNmae="";

            if(formattedTodayDate===formattedDate)
            {
                classNmae="active";
            }
            let dateDiv = $('<div class="timeslot '+classNmae+'" id="div_'+formattedDate+'"></div>').text(formattedDate);

            // Append the date div to the container
            dateSlotsContainer.append(dateDiv);

            // Move to the next day
            currentDate.setDate(currentDate.getDate() + 1);
        }
    }
    let emplIds=[];
    function getFirmemployees() {
        return new Promise((resolve, reject) => {
        $.ajax({
            url: base_url + 'TimesheetController/getFirmemployees',
            type: "POST",
            success: function (success) {
                success = JSON.parse(success);
                emplIds=success.result;
                resolve(emplIds);
            }
        });
    });
}
function getProjectList()
{
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/getProjectList",
        dataType: "json",
        data:'',
        success: function (result) {
            //$.LoadingOverlay("hide");
            if(result.status==200){
                var data = result.result;
                // $('#project_name').html(data);
                // $('#project_name').select2();
            }
        },
    });
}

function myTaskListOption()
{
    $.ajax({
        type: "POST",
        url: base_url + "myTaskListOption",
        dataType: "json",
        data:'',
        success: function (result) {
            if(result.status==200){
                var data = result.data;
                $('#task_id').html(data);
                $('#task_id').select2();
            }
        },
    });
}
function get_schedule_task_list(user_id,date) {
    let sts=$("#task_status1").val();
    $("#taskTableBody").html('');
    $.ajax({
        url: base_url + 'TimesheetController/get_schedule_task_list',
        type: "POST",
        dataType: "json",
        data:{user_id:user_id,date:date,status:sts},
        success: function (success) {
            if (success.status == 200) {
                let sc_data=success.body;
                sc_data.map(e=>{
                    let html=`<tr>
                                <td>${e.task_title}</td>
                                <td>${e.is_miscellaneous == "1"? 'Miscellaneous' : e.project_name}</td>
                                <td>${e.is_miscellaneous == "1"? e.total_hour : e.time_spand} Hr</td>
                                <td>
                                    ${e.is_miscellaneous != "1"? `<button type="button" class="btn btn-sm btn-link" onclick="editScheduleTask(${e.id})"><i class="fa fa-edit"></i></button> ` : ''}
                                    <button type="button" class="btn btn-sm btn-link" onclick="deleteTask(${e.id})"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>`;
                            $("#taskTableBody").append(html);
                });
            }
        }
    });
}
function yearlyTask()
{
    let month=$("#txtDate").val();
    let employee_id=$("#employee_id").val();
    window.location.href=base_url+"Excel_export/yearly_task";
}
function userTask()
{
    let month=$("#txtDate").val();
    let employee_id=$("#employee_id").val();
    window.location.href=base_url+"Excel_export/user_task";
}
function get_dateTimeValue_copy(date, time, divval) {
    if ($(divval).is(".grids-body .timeslot")) {
        $(".modal-title h4").text('Schedule Task');
        // $("#task_title").val("");
        $("#task_id").val("");
        $("#task_desc").val("");

        $("#get_date1").val(date);
        $("#select_date").val(convertDate(date));
        $("#get_time1").val(time);
        $('#str_time_show').val(time);
        $('#str_time_show').select2();
        $("#add_task .add-ride-btn").show();
        $("#add_task .edit-ride-btn").hide();
        $(".ride-opts").removeClass("show");
        $(".new-task").addClass("show");
        let left = $(divval).offset().left;
        let top = $(divval).offset().top;
        $(".new-task").css({
            "left": left +3,
            "top": top -200,
            "z-index": 99,
        });
        /*$(".new-task").css({
            "left": 427.906,
            "top": 285.484

        });*/

        // var theSelectedIndex = $(this)[0].selectedIndex;
        var strt_time = $('#str_time_show').val();

        $.each($('select[name=add_total_time] option'), function(){
            //console.log(this);
            var endOptionIndex = $(this).index();
            var selectTimeOption = $(this).val();
            if (selectTimeOption <= strt_time){
                // $(this).attr('disabled','disabled');
                $(this).prop("disabled", true);
                // console.log($(this));
                $('#add_total_time').addClass('option_disable');
                //$($(this)).css("color", "#ccc");
                $(this).addClass('option_disable');

                if($(this).hasClass("option_disable") == true){
                   // $($(this)).css("color", $(this).find("option[value="+$(this).val()+"]").css('color','blue'));
                }
            } else{
                //$(this).removeAttr('disabled').prop('selected', true);
                $(this).prop("disabled", false);
                //$(this).removeAttr('disabled');
                $(this).removeClass('option_disable');
                //return false;
            }
        });

    } else {
        $(".ride-opts").removeClass("show");
        $(".new-task").removeClass("show");
        $(".new-task").css({
            "left": 0,
            "top": 0
        });
    }

// close_list_modal()

    /*$.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: base_url + "TimesheetController/check_userOn_leave",
        dataType: "json",
        data:{date:date,time:time},
        success: function (result) {
            console.log(result);
            $.LoadingOverlay("hide");
            if (result.status == 200) {

                swal({
                    title: "Leave",
                    text: "leave on "+result.body,
                    icon: "warning",
                });

            }else if(result.status == 201){
                if ($(divval).is(".grids-body .timeslot")) {
                    $(".modal-title h4").text('Schedule Task');
                    $("#task_title").val("");
                    $("#task_id").val("");
                    $("#task_desc").val("");

                    $("#get_date1").val(date);
                    $("#get_time1").val(time);
                    $("#add_task .add-ride-btn").show();
                    $("#add_task .edit-ride-btn").hide();
                    $(".ride-opts").removeClass("show");
                    $(".new-task").addClass("show");
                    let left = $(divval).offset().left;
                    let top = $(divval).offset().top;
                    $(".new-task").css({
                        "left": left + 3,
                        "top": top + 1,

                    });
                    // $(".new-task").css({
                    //  "left": 427.906,
                    //  "top": 285.484
                    //
                    // });
                } else {
                    $(".ride-opts").removeClass("show");
                    $(".new-task").removeClass("show");
                    $(".new-task").css({
                        "left": 0,
                        "top": 0
                    });
                }
            }
        },
    });*/

}
function getFrequencyDate(value)
{
    const dailyOptions = document.getElementById('dailyOptions');
    const weeklyOptions = document.getElementById('weeklyOptions');
    const monthlyOptions = document.getElementById('monthlyOptions');
    // const projmonthlyOptions = document.getElementById('projmonthlyOptions');
    const projyearlyOptions = document.getElementById('yearlyOptions');

    // Hide all option sets initially
    dailyOptions.style.display = 'none';
    weeklyOptions.style.display = 'none';
    monthlyOptions.style.display = 'none';
    // projmonthlyOptions.style.display = 'none';
    projyearlyOptions.style.display = 'none';

    // Show the relevant option set based on selected frequency
    switch (value) {
        case 'daily':
            dailyOptions.style.display = 'block';
            break;
        case 'weekly':
            weeklyOptions.style.display = 'block';
            break;
        case 'monthly':
            monthlyOptions.style.display = 'block';
            break;
        case 'proj_monthly':
            projyearlyOptions.style.display = 'block';
            break;
        case 'proj_yearly':
            projyearlyOptions.style.display = 'block';
            break;
    }
}
function employeeList(updateFunc)
{
    $("#emp_name").html();
     getFirmemployees().then((result) => {
        if(result.length>0)
        {
            let options=`<option value="all">All</option>`;
            result.map(e=>{
                options+=`<option value="${e.user_id}">${e.user_name}</option>`;
            });
            $("#emp_name").html(options);
            $("#emp_name").select2();
        }
        updateFunc()
     });
}
function dailyTask(isExcel)
{
    let frequency=$("#frequency").val();
    let employee_id=$("#emp_name").val();
    if(frequency=="daily"){
        let date=$("#dailyDate").val();
        window.location.href=base_url+"Excel_export/daily_task?employee_id=all&date="+date+"&isExcel="+isExcel;
    }
    else if(frequency=="daily")
    {
        let date=$("#dailyDate").val();
        window.location.href=base_url+"Excel_export/daily_task?employee_id="+employee_id+"&date="+date+"&isExcel="+isExcel;
    }else if(frequency=="weekly")
    {
        let month=$("#weeklyMonth").val();
        let week=$("#weekOfMonth").val();
        window.location.href=base_url+"Excel_export/monthlyTask?frequency="+frequency+"&employee_id="+employee_id+"&month="+month+"&week="+week+"&isExcel="+isExcel;
    }else if(frequency=="monthly")
    {
        let month=$("#monthlyYear").val();
        window.location.href=base_url+"Excel_export/monthlyTask?frequency="+frequency+"&employee_id="+employee_id+"&month="+month+"&isExcel="+isExcel;
    }
}
function viewTask()
{
    let frequency=$("#frequency").val();
    let employee_id=$("#emp_name").val();
    if(frequency=="daily")
    {
        let date=$("#dailyDate").val();
        getDailyTaskData(date,employee_id);
    }else if(frequency=="weekly")
    {
        let month=$("#weeklyMonth").val();
        let week=$("#weekOfMonth").val();
        getWeeklyTaskData(frequency,employee_id,month,week);
    }else if(frequency=="monthly")
    {
        let month=$("#monthlyYear").val();
        getMonthlyTaskData(frequency,employee_id,month);
    }
}
function getDailyTaskData(date,employee_id)
{
    $("#employeeTable").html("");
    $.ajax({
        url: base_url + 'Excel_export/getDailyTaskData',
        type: "POST",
        dataType: "json",
        data:{date:date,employee_id:employee_id},
        success: function (success) {
            if (success.status == 200) {
                var data = success.body;
                $("#employeeTable").html(data);
            }
            else
            {
                 $("#employeeTable").html('');
            }
        }
    });
}
function getWeeklyTaskData(frequency,employee_id,month,week)
{
    $("#employeeTable").html("");
    $.ajax({
        url: base_url + 'Excel_export/getWeeklyTaskData',
        type: "POST",
        dataType: "json",
        data:{frequency:frequency,employee_id:employee_id,month:month,week:week},
        success: function (success) {
            if (success.status == 200) {
                var data = success.body;
                $("#employeeTable").html(data);
            }
            else
            {
                 $("#employeeTable").html('');
            }
        }
    });
}
function getMonthlyTaskData(frequency,employee_id,month)
{
    $("#employeeTable").html("");
    $.ajax({
        url: base_url + 'Excel_export/getWeeklyTaskData',
        type: "POST",
        dataType: "json",
        data:{frequency:frequency,employee_id:employee_id,month:month},
        success: function (success) {
            if (success.status == 200) {
                var data = success.body;
                $("#employeeTable").html(data);
            }
            else
            {
                 $("#employeeTable").html('');
            }
        }
    });
}
function projectTask()
{
    let frequency=$("#proj_frequency").val();
    let proj_year=$("#proj_year").val();
    if(frequency=="proj_yearly")
    {
        window.location.href=base_url+"Excel_export/yearly_task?year="+proj_year;
    }else if(frequency=="proj_monthly")
    {
        window.location.href=base_url+"Excel_export/user_task?year="+proj_year;
    }
}
function viewProjectTask()
{
    let frequency=$("#proj_frequency").val();
    let proj_year=$("#proj_year").val();
    if(frequency=="proj_yearly")
    {
        getProjectYearly(proj_year);
    }else if(frequency=="proj_monthly")
    {
        getProjectMonthly(proj_year);
    }
}
function getProjectYearly(year)
{
    $("#projectTable").html("");
    $.ajax({
        url: base_url + 'Excel_export/getProjectYearly',
        type: "POST",
        dataType: "json",
        data:{year:year},
        success: function (success) {
            if (success.status == 200) {
                var data = success.body;
                $("#projectTable").html(data);
            }
            else
            {
                 $("#projectTable").html('');
            }
        }
    });
}
function openTab(evt, tabName) {
    tabName='Tab'+tabName;
    // Get all elements with class="tabcontent" and hide them
    var tabcontent = document.getElementsByClassName("tabcontent");
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    var tablinks = document.getElementsByClassName("tablinks");
    for (var i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
function getProjectMonthly(year)
{
    $("#projectTable").html("");
    $.ajax({
        url: base_url + 'Excel_export/getProjectMonthly',
        type: "POST",
        dataType: "json",
        data:{year:year},
        success: function (success) {
            if (success.status == 200) {
                var data = success.body;
                $("#projectTable").html(data);
            }
            else
            {
                 $("#projectTable").html('');
            }
        }
    });
}
function loadFirstTab(){
    $(".tablinkCon1").addClass("active");
    $("#Tab1").show();
}

$(document).ready(function() {

    $('#task_id').change(function() {
        var taskId = $(this).val();

        if (taskId) {

            $.ajax({
                type: "POST",
                url: base_url + "getProjectsByTask",
                dataType: "json",
                data: { task_id: taskId },
                success: function(result) {
                    if (result.status == 200) {
                        var data = result.result;
                        $('#project_name').html(data);
                        $('#project_name').select2();

                    }
                }
            });
        } else {

            $('#project_name').html('<option value="">Select Project</option>');
            $('#project_name').select2();
        }
    });
});