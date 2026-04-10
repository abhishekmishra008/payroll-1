<?php
$username_array = $this->session->userdata('login_session');
$user_type = $username_array['user_type'] ?? 'HR';
// if ($user_type !== 'HR') {
//     echo "<h2>Access Denied</h2>";
//     exit;
// }
$this->load->view('human_resource/navigation');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Notification - HR Panel</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    h2 { margin-bottom: 20px; }
    form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        /* max-width: 800px; */
    }
    .form-group { margin-bottom: 15px; }
    label { display: block; font-weight: bold; margin-bottom: 5px; }
    input[type="text"], input[type="date"], select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }
    #messageEditor { height: 200px; background: #fff; }
    .attachment-preview {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .attachment-item {
        background: #eee;
        padding: 5px 10px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        font-size: 13px;
    }
    .attachment-item span {
        margin-left: 5px;
        cursor: pointer;
        color: red;
        font-weight: bold;
    }
    button {
        padding: 10px 15px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover { background: #218838; }
    #notificationList { margin-top: 20px; }
    .notification-card {
        border: 1px solid #ddd;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        background: white;
    }
    .notif-type { font-weight: bold; }
    .attachment-list img { max-width: 80px; margin: 5px; }
    .button-labels { display: flex; gap: 10px; }
    button.yes{
          background-color: lightblue;
        border: 1px solid #7ad8f6;
        color: black;
        border-radius: 10px !important;

    }
    button.no{
          background-color: white;
        border: 1px solid #7ad8f6;
        color: black;
        border-radius: 10px !important;
        
    }
</style>
</head>
<body>
 <div style="display:flex;justify-content:center;width:100%;">
  <div style="width: 60%;">
    <h2>HR Panel - Add Notification</h2>
    <form id="addNotificationForm">
      
      <div class="form-group">
        <label for="notifTitle">Title</label>
        <input type="text" id="notifTitle" required>
      </div>

      <!-- Type + Posting Date + Notification Date Row -->
      <div class="form-row" style="display:flex; gap: 15px;">
        <div style="flex: 1;">
          <label for="notifType">Notification Type</label>
          <select id="notifType" required>
            <option value="">Select type</option>
            <option value="Birthday">Birthday</option>
            <option value="Event">Event</option>
            <option value="Festival">Festival</option>
            <option value="Appreciation">Appreciation</option>
            <option value="Work Anniversary">Work Anniversary</option>
            <option value="New Joiner">New Joiner</option>
          </select>
        </div>
        <div style="flex: 1;">
          <label for="postingDate">Posting Date</label>
          <input type="date" id="postingDate" required>
        </div>
        <div style="flex: 1;">
          <label for="notifDate">Notification Date</label>
          <input type="date" id="notifDate" required>
        </div>
      </div>

      <div class="form-group">
        <label for="shortDesc">Short Description</label>
        <input type="text" id="shortDesc" required>
      </div>

      <div class="form-group">
        <label for="message">Message (Detailed)</label>
        <div id="messageEditor"></div>
        <input type="hidden" name="message" id="message">
      </div>

      <div class="form-group">
        <label for="notifAttachments">Attachments (Images/PDFs)</label>
        <input type="file" id="notifAttachments" multiple>
        <div class="attachment-preview" id="attachmentPreview"></div>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" id="notifButtons"> Show Custom Buttons
        </label>
        <div id="buttonLabels" style="display:none;" class="button-labels">
          <input type="text" id="yesLabel" placeholder="Yes Button Label">
          <input type="text" id="noLabel" placeholder="No Button Label">
        </div>
      </div>

      <button type="submit">Add Notification</button>
    </form>
  </div>
</div>
<div class="" style="display:flex;justify-content:center;width:100%;">
<div style="    width: 60%;">
        
<h3>Preview</h3>
<div id="notificationList"></div>
</div>

</div>
<script>
let notifications = [];
let attachmentsList = [];

// Quill Editor
var quill = new Quill('#messageEditor', {
    theme: 'snow',
    placeholder: 'Write your message here...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link', 'clean']
        ]
    }
});

// Show button labels when checkbox checked
$("#notifButtons").on("change", function(){
    $("#buttonLabels").toggle(this.checked);
});

// File attachments preview & remove
$("#notifAttachments").on("change", function(){
    attachmentsList = [...attachmentsList, ...Array.from(this.files)];
    renderAttachmentPreview();
    this.value = ""; // reset input
});

function renderAttachmentPreview(){
    let html = '';
    attachmentsList.forEach((file, index) => {
        html += `<div class="attachment-item">${file.name} <span onclick="removeAttachment(${index})">×</span></div>`;
    });
    $("#attachmentPreview").html(html);
}

function removeAttachment(index){
    attachmentsList.splice(index, 1);
    renderAttachmentPreview();
}

// Submit form
$("#addNotificationForm").on("submit", function(e){
    e.preventDefault();

    let notif = {
        title: $("#notifTitle").val(),
        type: $("#notifType").val(),
        shortDesc: $("#shortDesc").val(),
        postingDate: $("#postingDate").val(),
        date: $("#notifDate").val(),
        message: quill.root.innerHTML,
        attachments: attachmentsList.map(f => URL.createObjectURL(f)),
        buttons: $("#notifButtons").is(":checked"),
        yesLabel: $("#yesLabel").val(),
        noLabel: $("#noLabel").val()
    };

    notifications.push(notif);
    renderNotifications();

    // Reset form
    this.reset();
    quill.setContents([]);
    attachmentsList = [];
    renderAttachmentPreview();
    $("#buttonLabels").hide();
});

function renderNotifications(){
    let html = "";
    notifications.forEach(n => {
        html += `
        <div class="notification-card ${n.type}">
            <div class="notif-type">${n.title} (${n.type})</div>
            <small>Posting: ${n.postingDate} | Notification: ${n.date}</small>
            <p><em>${n.shortDesc}</em></p>
            <div class="notif-message">${n.message}</div>
            <div class="attachment-list">
                ${n.attachments.map(a => `<a href="${a}" target="_blank">📎 File</a>`).join('')}
            </div>
            ${n.buttons ? `<div><button class="yes">${n.yesLabel || "Yes"}</button> <button class="no">${n.noLabel || "No"}</button></div>` : ''}
        </div>
        `;
    });
    $("#notificationList").html(html);
}
</script>

</body>
</html>
