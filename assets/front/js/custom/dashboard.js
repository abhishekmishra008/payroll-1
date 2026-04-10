"use strict";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

let hasPunchedIn = false; // jab user punchin karega tab true ho jayega

// Hide all buttons by default first
function hideAllPunchButtons() {
    document.querySelectorAll('[id$="_div"]').forEach(div => {
        div.style.display = 'none';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    hideAllPunchButtons();
    document.getElementById('ifOutSideLocation').style.display = 'none';
    let currentDate;
    let todayAttendanceModal = new bootstrap.Modal(document.getElementById('todayAttendanceModal'));
    let regularizeAttendanceModal = new bootstrap.Modal(document.getElementById('regularizeAttendanceModal'));
    let applyLeaveModal = new bootstrap.Modal(document.getElementById('applyLeaveModal'));
    let eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    let calendarEl = document.getElementById('calendar');
    let eventForm = document.getElementById('eventForm');
    let eventDateInput = document.getElementById('eventDate');
    let eventTitleInput = document.getElementById('eventTitle');
    let eventTimeInput = document.getElementById('eventTime');
    let eventColorInput = document.getElementById('eventColor');

    // STEP 1: Laravel API se holidays fetch kar rahe hain
    fetch(getHolidayDetailsURL).then(response => response.json()).then(data => {
            // STEP 2: API ke data ko FullCalendar ke event format me convert karna
            if (!data.success) {
                console.error("Failed to fetch holidays:", data.message);
                return;
            }
            let holidayData = data.yearWiseHolidays || [];
            let holidayEvents = holidayData.map(h => ({
                title: h.holiday_name, // 3 letters max
                start: h.start_date,
                color: h.holiday_color || '#dc3545',
                display: 'auto', // text dikhe, background me fill nahi
                extendedProps: { isHoliday: true }
            }));

            // STEP 3: Calendar initialize kar rahe hain (ab API ke holiday data ke sath)
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                selectable: true,
                editable: false,
                events: holidayEvents, // ← holiday events yahan add kiye
                // STEP 4: Sunday aur Saturday ke background color change kar rahe hain
                dayCellDidMount: function(info) {
                    let day = info.date.getDay(); // 0 = Sunday, 6 = Saturday
                        if (day === 0 || day === 6) {
                        info.el.style.backgroundColor = day === 0 ? '#fd4747ff' : (day === 6 ? '#fd4747ff' :'#e9ecef');

                        // Naya span add kar rahe hain jisme "Weekend" likha hai
                        let weekendLabel = document.createElement('div');
                        weekendLabel.textContent = 'Weekend';
                        weekendLabel.style.fontSize = '10px';
                        weekendLabel.style.color = '#555';
                        weekendLabel.style.textAlign = 'center';
                        weekendLabel.style.marginTop = '10px';
                        weekendLabel.style.fontWeight = '600';
                        
                        info.el.appendChild(weekendLabel);
                    }
                },

                // STEP 5: Date par click hone par pehle check karenge holiday/weekend hai ya nahi
                dateClick: function (info) {
                    let clickedDate = info.dateStr;
                    let matchedHoliday = holidayData.find(h => h.start_date === clickedDate);
                    let holidayId = matchedHoliday ? matchedHoliday.id : null;
                    let day = info.date.getDay();
                    let isWeekend = (day === 0 || day === 6);

                    if (matchedHoliday || isWeekend) {
                        if (matchedHoliday) {
                            showHolidayDetails(holidayId); // ← yahan se ID bhej do
                        } else {
                            alert('Weekend par action allowed nahi hai.');
                        }
                        return;
                    }

                    // Normal working date par aapka purana attendance logic chalega
                    handleDateClick(info);
                },

                // STEP 6: Holiday event par delete action disable karna
                eventClick: function (info) {
                    if (info.event.extendedProps.isHoliday) {
                        alert("Holiday event delete nahi kar sakte.");
                    } else {
                        if (confirm("Do you want to delete this event?")) {
                            info.event.remove();
                        }
                    }
                }
            });

            calendar.render();

            // STEP 7: Form submit par custom event add karna (agar aap chahein)
            eventForm.addEventListener('submit', function (e) {
                e.preventDefault();
                let title = eventTitleInput.value;
                let date = eventDateInput.value;
                let time = eventTimeInput.value;
                let color = eventColorInput.value;

                if (title) {
                    let startDate = date + (time ? 'T' + time : '');
                    calendar.addEvent({
                        title: title,
                        start: startDate,
                        color: color
                    });
                    eventModal.hide();
                }
            });

            // STEP 8: attendance / leave / regularize logic yahan function me rakha gaya hai
            function handleDateClick(info) {
                eventDateInput.value = info.dateStr;
                let now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                let currentLoginTime = `${hours}:${minutes}`;
                if (now.getHours() >= 12) {
                    let tomorrow = new Date(now);
                    tomorrow.setDate(now.getDate() + 1);
                    currentDate = tomorrow.toISOString().slice(0, 10);
                } else {
                    currentDate = now.toISOString().slice(0, 10);
                }

                eventTitleInput.value = '';
                eventTimeInput.value = '';
                eventColorInput.value = '#0d6efd';
                let selectedDate = eventDateInput.value;

                if (selectedDate == currentDate) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            const targetLat = 19.1185;
                            const targetLng = 73.0051;
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;

                            function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
                                const R = 6371000;
                                const dLat = (lat2 - lat1) * Math.PI / 180;
                                const dLon = (lon2 - lon1) * Math.PI / 180;
                                const a =
                                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                                return R * c;
                            }

                            const distance = getDistanceFromLatLonInMeters(userLat, userLng, targetLat, targetLng);

                            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${userLat}&lon=${userLng}`)
                                .then(response => response.json())
                                .then(data => {
                                    const address = data.display_name || "Address not found";
                                    document.getElementById("yourCurrentLocation").value = address;
                                });

                            if (distance <= 200) {
                                console.log("Inside Location");
                                if (!hasPunchedIn) {
                                    if (currentLoginTime > '09:00' && currentLoginTime < '12:00') {
                                        document.getElementById('inside_punchin_div').style.display = 'block';
                                    } else {
                                        document.getElementById('inside_late_punchin_div').style.display = 'block';
                                    }
                                } else {
                                    if (currentLoginTime >= '18:00' && currentLoginTime < '18:30') {
                                        document.getElementById('inside_punchout_div').style.display = 'block';
                                    } else {
                                        document.getElementById('inside_before_punchout_div').style.display = 'block';
                                    }
                                }
                                todayAttendanceModal.show();

                            } else {
                                console.log("Outside Location");
                                document.getElementById('ifOutSideLocation').style.display = 'block';
                                if (!hasPunchedIn) {
                                    if (currentLoginTime > '09:00' && currentLoginTime < '12:00') {
                                        document.getElementById('outside_punchin_div').style.display = 'block';
                                    } else {
                                        document.getElementById('outside_late_punchin_div').style.display = 'block';
                                    }
                                } else {
                                    if (currentLoginTime >= '18:00' && currentLoginTime < '18:30') {
                                        document.getElementById('outside_punchout_div').style.display = 'block';
                                    } else {
                                        document.getElementById('outside_before_punchout_div').style.display = 'block';
                                    }
                                }
                                todayAttendanceModal.show();
                            }
                        }, function (error) {
                            alert("Geolocation error: " + error.message);
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }

                } else if (selectedDate > currentDate) {
                    applyLeaveModal.show();
                    console.log("User Select Upcoming Date");
                } else {
                    regularizeDate(selectedDate, ['punchInTimeId', 'punchOutTimeId']);
                    regularizeAttendanceModal.show();
                    console.log("User Select Past Date");
                }
            }
        })
});



const getYearWiseHoliday = (event) => {
    event.preventDefault();
    let year = event.target.value;
    $.ajax({
        url: 'admin/dashboard/get-holiday-details',
        method: 'GET',
        data: {
            holiday_year: year
        },
        success: function(response) {
            if (response.success == true) {
                const holidays = response.yearWiseHolidays; // backend array
                const tbody = $('#holidayTable tbody');
                tbody.empty(); // clear previous rows

                holidays.forEach((holiday, index) => {
                    const holidayId = holiday.id; 
                    const dayShort = holiday.holiday_day.slice(0, 3).toUpperCase(); 
                    const monthShort = holiday.holiday_month.slice(0, 3).toUpperCase(); 
                    const holidayName = holiday.holiday_name.toUpperCase();
                    const dateOnly = holiday.start_date.split("-")[2]; 

                    // agar color null/empty hai to default partition line use kare
                    const rowStyle = holiday.holiday_color 
                        ? `background-color:${holiday.holiday_color}; color:#fff;`
                        : `border-bottom:1px solid #ccc;`;

                    const rowHtml = `
                        <tr style="${rowStyle}" onclick="showHolidayDetails(${holidayId})">
                            <!-- Circle Date Column -->
                            <td style="width:100px; text-align:center;">
                                <div style="
                                    width: 70px; 
                                    height: 70px; 
                                    border-radius: 50%; 
                                    background-color: #fff; 
                                    color: ${holiday.holiday_color || '#000'}; 
                                    display: flex; 
                                    flex-direction: column; 
                                    justify-content: center; 
                                    align-items: center;
                                    font-size: 12px;
                                    font-weight: bold;
                                    margin: auto;
                                    border: 2px solid ${holiday.holiday_color || '#ccc'};
                                ">
                                    <div>${dayShort}</div>
                                    <div style="font-size:18px;">${dateOnly}</div>
                                    <div>${monthShort}</div>
                                </div>
                            </td>

                            <!-- Holiday Name + Image -->
                            <td style="vertical-align: middle;">
                                <div class="d-flex align-items-center">
                                    <img src="${holiday.image_url || ''}" 
                                        alt="holiday" 
                                        style="width:40px; height:40px; object-fit:cover; border-radius:50%; margin-right:10px; border:2px solid #fff;">
                                    <span class="holiday-name-span-tag" style="font-size:16px; font-weight:600; color:#fff;">
                                        ${holidayName}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(rowHtml);
                });
            }
        },
        error: function(err) {
            console.error(err);
        }
    });
};


const showHolidayDetails = (id) => {
    $.ajax({
        url: 'admin/dashboard/get-id-holiday-details',
        method: 'GET',
        data: { holiday_id: id },
        success: function (response) {
            if (response.success === true && response.code === 0) {
                const data = response.data;
                const color = data.holiday_color || '#3b82f6'; // default blue
                // Remove existing modal if exists
                $('#holidayIdBaseDetailShow').remove();

                // Append new modal
                const html = `
                    <div class="modal fade" id="holidayIdBaseDetailShow" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content border-0 shadow-lg" 
                                 style="
                                    background: ${color};
                                    color: #fff;
                                    border-radius: 15px;
                                    overflow: hidden;
                                 ">
                                <div class="modal-header border-0" 
                                    style="background: rgba(255,255,255,0.1);">
                                    <h5 class="modal-title fw-bold">🎉 Holiday Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body py-4 px-5">
                                    <div class="row g-3">
                                        ${createInput('Branch ID', data.branch_id)}
                                        ${createInput('Holiday Name', data.holiday_name)}
                                        ${createInput('Holiday Day', data.holiday_day)}
                                        ${createInput('Holiday Month', data.holiday_month)}
                                        ${createInput('Holiday Year', data.holiday_year)}
                                        ${createInput('Holiday Category', data.holiday_category)}
                                        ${createInput('Start Date', data.start_date)}
                                        ${createInput('End Date', data.end_date)}
                                    </div>
                                </div>

                                <div class="modal-footer border-0" 
                                     style="background: rgba(255,255,255,0.1);">
                                    <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Helper function for elegant glass-style inputs
                function createInput(label, value) {
                    return `
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small mb-1">${label}</label>
                            <div class="form-control border-0 shadow-sm" 
                                style="
                                    background: rgba(255,255,255,0.2);
                                    color: #fff;
                                    border-radius: 10px;
                                    font-weight: 500;
                                ">
                                ${value || '-'}
                            </div>
                        </div>
                    `;
                }

                $('body').append(html);
                const modal = new bootstrap.Modal(document.getElementById('holidayIdBaseDetailShow'));
                modal.show();
            }
        }
    });
};



const regularizeDate = (selectedDate, ids) => {
    ids.forEach(id => {
        let input = document.getElementById(id);
        if (input) {
            if (input._flatpickr) {
                input._flatpickr.destroy();
            }

            // Default time
            let defaultTime = getEmployeeAttendenceDetails(selectedDate);
            if (id.toLowerCase().includes("out")) {
                defaultTime = "06:00 PM"; 
            }

            input._flatpickr = flatpickr(input, {
                enableTime: true,              // ⏰ Enable time
                noCalendar: false,             // Date + time picker
                dateFormat: "Y-m-d h:i K",     // Backend format (2025-09-30 10:00 AM)
                altInput: true,
                altFormat: "d F Y h:i K",      // Pretty format (30 September 2025 10:00 AM)
                allowInput: true,              // User can type manually
                minDate: selectedDate,
                maxDate: selectedDate,
                defaultDate: selectedDate + " " + defaultTime,
                time_24hr: false,              // ✅ 12-hour format with AM/PM
                minuteIncrement: 1             // Exact minute selection
            });
        }
    });
};


const getEmployeeAttendenceDetails = (selectedDate) => {
    $.ajax({
        url: 'admin/dashboard/get-employee-details',
        method: 'GET',
        data: {
            regularize_date: selectedDate
        },
        success: function(response) {
            if(response.success == true) {
                
            }
        }
    });
}



