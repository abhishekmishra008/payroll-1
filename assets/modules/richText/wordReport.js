let BMR_obj;
let KP = [];
$(document).ready(function () {
	// $('#summernote').summernote({
	// 	placeholder: 'Hello stand alone ui',
	// 	tabsize: 2,
	// 	height: 100,
	// 	toolbar: [
	// 		['style', ['style']],
	// 		['font', ['bold', 'underline', 'clear']],
	// 		['color', ['color']],
	// 		['para', ['ul', 'ol', 'paragraph']],
	// 		['table', ['table']],
	// 		['insert', ['link', 'picture', 'video']],
	// 		['view', ['fullscreen', 'codeview', 'help']]
	// 	],
	// 	disableResizeEditor:false
	// });

	// getUserProfiles();
	let report_id=$("#update_id").val();
	getKeyPairs();
	getPageDataToEditor(report_id,1)

	let bmr_no = $("#bmr_no").val();
	let production_id = $("#production_id").val();
	BMR_obj = {
		"bmr_no": bmr_no,
		"production_id": production_id,
		"pages": []
	}

});
var editor1 = new RichTextEditor("#summernote");
let currentInput = document.getElementById('summernote');
$(document).on('focus', 'textarea', function () {
	currentInput = this;
})

// let counter = 1;
let keys = [];
let staticArr = [];

function setTextBoxCode(type, inputName = '') {
	let elementCnt = $("#elementCount").val();
	elementCnt = (elementCnt * 1) + 1;
	$("#elementCount").val(elementCnt);
	let eleV = '';
	switch (type) {
		case 1:
			eleV = 'Input' + elementCnt;
			break;
		case 2:
			eleV = 'histable' + elementCnt;
			break;
		case 3:
			eleV = inputName;
			break;
		case 4:
			eleV = 'materialTable' + elementCnt;
			break;
		case 5:
			eleV = 'indentTable' + elementCnt;
			break;
		case 6:
			eleV = 'processChart' + elementCnt;
			break;
	}
	console.log(eleV);
	let elementTag = '<span style="background-color: rgb(255, 255, 0);">' + eleV + '</span>';
// 	eleV='<span class="inputText">'+eleV+'</span>';
// 	$('#summernote').RichTextEditor('editor.saveRange');
// 	$('#summernote').RichTextEditor('editor.restoreRange');
// 	$('#summernote').RichTextEditor('editor.focus');
// 	$('#summernote').RichTextEditor('pasteHTML', eleV);
// 	let cursorPos = currentInput.selectionStart;
// 	let v = currentInput.value;
// 	let textBefore = v.substring(0,  cursorPos );
// 	let textAfter  = v.substring( cursorPos, v.length );
// 	currentInput.val( textBefore+ 'aaaaa' +textAfter );

	// let cursorPos = currentInput.selectionStart;
	// let v = currentInput.value;
	// let textBefore = v.substring(0,  cursorPos );
	// let textAfter  = v.substring( cursorPos, v.length );
	// editor1.setHTMLCode(textBefore + eleV + textAfter);
	//
	// cursorPos += eleV.length;
	// editor1.focus();
	// editor1.setSelectionRange(cursorPos, cursorPos);


	editor1.insertHTML(elementTag)
	editor1.collapse(false);
	editor1.focus();


	keys.push(eleV);
	getKeyPairs();
	if (type == 3) {
		staticArr.push(eleV);
	}
	// counter++;
}


function saveHtml() {
	let page_name = $("#section_name").val();
	let page_id = $("#page_id").val();
	let page_type = $('#page_type').val();
	let p_id = parseInt(page_id);

		let bmr_no = $("#bmr_no").val();
		let production_id = $("#production_id").val();
		let html_code = editor1.getHTMLCode();
		let keys_arr = keys;
		let keyPairs = hosController.getData();
		if (staticArr == undefined) {
			staticArr = [];
		}
		let staticFields = staticArr;
		let html_obj = [];
		console.log(staticFields);
		let pageObject = {
			"page_name": page_name,
			"page_type": page_type,
			"html_code": html_code,
			"keys": keys_arr,
			"keyPairs": keyPairs,
			"dataset": [],
			"staticFields": staticFields,
		};
		if (p_id != null && p_id != '' && !isNaN(p_id)) {

			BMR_obj.pages = BMR_obj.pages.map(r => {
				if (r.page_id === p_id) {
					r.page_name = page_name;
					r.page_type = page_type;
					r.html_code = html_code;
					r.keys = keys_arr;
					r.keyPairs = keyPairs;
					r.staticFields = staticFields;
				}
				return r;
			});


		} else {
			p_id = BMR_obj.pages.length + 1;
			pageObject.page_id = p_id;
			BMR_obj.pages.push(pageObject);
		}


		html_obj.push(BMR_obj);

		let form = document.getElementById('page_form');
		let formdata = new FormData(form);

		formdata.set('html_obj', JSON.stringify(html_obj));

		formdata.set('type', $("#type").val());
		app.request("saveHtmlTemplate", formdata).then(res => {
			if (res.status === 200) {
				app.successToast(res.body);
				$("#update_id").val(res.insert_id);
				window.location.href=baseURL+'word_report';
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => console.log(error));

}


let hosController;
let userProfiles;
let users;

function handson(data, columnsS, hiddenColumn) {

	if (data.length == 0) {
		data = [
			['', '', '', '', ''],

		];
	}
	const container = document.getElementById('keyPairsDiv');
	hosController != null ? hosController.destroy() : "";
	hosController = new Handsontable(container, {
		data: data,
		colHeaders: [
			"Keys",
			"Control Type",
			"Attribute Value",
			"Access Control",
			"Formulas",
			"Label",
			"is_Dashboard",
			"Graph Label",
			"is_Default_Graph",
			"Type",
			"Default Value"
		],
		manualColumnResize: true,
		manualRowResize: true,
		columns: columnsS,
		beforeChange: function (changes, source) {
			var row = changes[0][0];
			var prop = changes[0][1];
			var value = changes[0][3];
		},
		stretchH: 'all',
		colWidths: '100%',
		width: '100%',
		height: 320,
		rowHeights: 23,
		rowHeaders: true,
		filters: true,
		contextMenu: true,
		hiddenColumns: {
			// specify columns hidden by default
			columns: hiddenColumn,
			copyPasteEnabled: false,
		},
		autoColumnSize: true,
		dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
		licenseKey: 'non-commercial-and-evaluation'
	});
	hosController.validateCells();
}


function getKeyPairs() {

	let keyArr = keys;

	let data = [];
	if (KP.length > 0) {
		data = KP;
	}
	if (keyArr.length > 0) {

		if(KP.length > 0){
			let lastel = keyArr[keyArr.length - 1];
			let arr = [lastel, 'text', '', '','','','No','','','Count',,''];
			data.push(arr);
		}else{
			keyArr.map(r=>{
				let arr = [r, 'text', '', '','','','No','','','Count',''];
				data.push(arr);
			});
			KP=data;
		}

	} else {
		data = ['', '', '', '','','','','',''];
	}

	let hiddenColumn = [3];
	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text','label', 'number', 'date', 'dropdown','checkbox','calculated','file','textbox','texteditor','toRupee']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'dropdown', source: ['Count','Total']},
		{type: 'text'}
	];
	handson(data, columns, hiddenColumn);
}


function getUserProfiles() {
	app.request('userProfiles', null).then(res => {
		if (res.status === 200) {
			userProfiles = res.data;
			users = res.users;
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getPagesList() {
	let id = $("#update_id").val();
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('type', type);
	app.request('getPagesList', formdata).then(res => {
		if (res.status === 200) {
			$("#sectionTableBody").html(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getPageDataToEditor(id, page_id) {
	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('page_id', page_id);
	formdata.set('type', $("#type").val());
	app.request('getPageDataToEditor', formdata).then(res => {
		if (res.status === 200) {
			if(res.bmr_object!=null)
			{
				let obj = JSON.parse(res.bmr_object);

				BMR_obj = obj[0];
			}

			setPageEditorData(res.body.html_code, res.body.keys, res.body.keyPairs, res.body.staticFields);
			// $("#section_name").val(res.body.page_name);
			$("#update_id").val(res.id);
			$("#page_id").val(res.body.page_id);
			$("#page_type").val(res.body.page_type);
			$("#section_name").val(res.report_name);
			// $("#sectionTableBody").html(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}


function setPageEditorData(htmlCode, keys_arr, keyPairs, staticFields) {
	// htmlCode=JSON.parse(htmlCode);
	// htmlCode=htmlCode[0];
	if (htmlCode != "" && htmlCode != null) {
		keys = keys_arr;
		staticArr = staticFields;
		editor1.setHTMLCode(htmlCode);
		if (keyPairs != null) {
			KP = keyPairs;
			setKeyPairs(keyPairs);
			$("#elementCount").val(keys.length);
		}
	}
}

function setKeyPairs(data) {
	let hiddenColumn = [3];
	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text','label' ,'number', 'date', 'dropdown','checkbox','calculated','file','textbox','texteditor','toRupee']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'dropdown', source: ['Count','Total']},
		{type: 'text'}
	];
	handson(data, columns, hiddenColumn);
}

function addNewPage() {
	$("#elementCount").val(0);
	$("#section_name").val('');
	$("#page_id").val('');
	editor1.setText('');
	keys = [];

	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text','label','number', 'date', 'dropdown','checkbox','calculated','file','textbox','texteditor','toRupee']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'text'},
		{type: 'dropdown', source: ['No','Yes']},
		{type: 'dropdown', source: ['Count','Total']},
		{type: 'text'}
	];
	handson(['', '', '', '', '','','No','','','Count',''], columns, [3]);
	// getKeyPairs();
	getPagesList();
}

function addMaterial() {
	let bmr_id = $("#bmr_id").val();
	var formData = new FormData();
	formData.set('bmr_id', bmr_id);

	app.request("checkBMRId", formData).then(result => {
		// app.successToast(result.body);
		if (result.status === 200) {
			$("#addmaterialmodal").modal('show');

			$("#material_update_id").val(result.data);
			let product_id = result.data;

			// $('#addmaterialmodal').on('shown.bs.modal', function (event) {
			getUnits(1);

			// })
		} else {
			$("#addmaterialmodal").modal('hide');
			app.errorToast('Product Not Available For this BMR');
		}

	}).catch(error => console.log(error));
}

$('#summernote').parent().on('keydown', function (event) {

	if (event.which == 8) {
		console.log('hiii');
	}
});
