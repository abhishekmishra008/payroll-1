function getReportData() {
	$("#pagesList").html('');
	let report_id = $("#report_id").val();
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('report_id', report_id);
	formdata.set('type', type);
	app.request("getReportData", formdata).then(res => {
		if (res.status === 200) {
			showPagesList(res.body.code);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

let pageContent = null;

function showPagesList(data) {
	let pagesData = JSON.parse(data);

	pagesData = pagesData[0].pages;

	pageContent = pagesData;
	pagesData.map((e, i) => {
		let color = '#ffa42633';
		if (e.dataset.length > 0) {
			color = '#28a7452b';
		}
		let tablehtml = `<tr style="background-color: ${color};"><td><span onclick="showPage('${i}','${e.page_id}')">${e.page_name}</span></td></tr>`;
		$("#pagesList").append(tablehtml);
	});
}

async function showPage(indexId, pageId) {
	if (pageContent != null) {
		if (indexId in pageContent) {
			let templateIndex = pageContent.findIndex(m => (m.page_id == pageId));
			let object = null;
			if (templateIndex !== -1) {
				object = pageContent[templateIndex];
			}
			if (object) {
				let page = object;
				let pageInput = await changeInputTextToHTML(page.keyPairs, page.dataset, page.keys);
				let pageCode = page.html_code;

				let editorArr = [];
				if (pageInput.length > 0) {
					pageInput.map((rInp, ind) => {

						let replacekey = `<span style="background-color: rgb(255, 255, 0);">${rInp.key}</span>`;

						// console.log(replacekey);
						// let replacekey=rInp.key;
						// pageCode = pageCode.replace('<span style="background-color: rgb(255, 255, 0);">', rInp.html);

						// pageCode = pageCode.replace('</span>', rInp.html);
						pageCode = pageCode.replace(replacekey, rInp.html);
						if (rInp.type == "texteditor") {
							editorArr.push(rInp.key);
						}

					});

				}
				$("#wordReportDiv").html(pageCode);
				$("#page_id").val(pageId);
				$("#page_input").val(page.keys);
				$("#pageName").html(page.page_name);
				if (editorArr.length > 0) {
					editorArr.map(editT => {
						// new RichTextEditor("#"+editT);
						$('#' + editT).trumbowyg({
							btns: [
								['viewHTML'],
								['undo', 'redo'],
								['formatting'],
								['strong', 'em', 'del'],
								['superscript', 'subscript'],
								['link'],
								['insertImage'],
								['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
								['unorderedList', 'orderedList'],
								['horizontalRule'],
								['removeformat'],
								['table']
							]
						});
					});
				}
			}
		}
	}
}

async function changeInputTextToHTML(keyPairs, dataset, keys) {
	let user_id = $("#user_id").val();
	let usertype = $("#type").val();
	let keyPairValue = getInputFilledValue(dataset, keys);
	let keyPairInputArr = [];
	if (keyPairs.length > 0) {
		let formulas = await getFormula(keyPairs);
		return Promise.all(keyPairs.map(async (inp, index, arr) => {

			let options = '';
			let onchange = '';
			if (inp.length > 3) {
				if (inp[1] != '' && inp[1] != null) {
					let type = inp[1];
					let inputValue = '';
					let readOnly = '';
					if (typeof keyPairValue[inp[0]] !== 'undefined') {
						inputValue = keyPairValue[inp[0]];
					}
					if (usertype == 1) {
						readOnly = '';
					} else {
						if (inp[3] != '' && inp[3] != null) {
							let accessUser = inp[3].split('-');
							if (accessUser.length > 1) {
								if (user_id === accessUser[0]) {
									readOnly = '';
								}
							}
						}
					}

					let onchangeFunction = '';
					let onchangeCount = 0;
					formulas.map(e => {
						if (e.hasOwnProperty('formulas')) {
							if (e.formulas !== null && e.formulas !== "") {
								if (e.formulas[0] !== undefined) {
									if (e.formulas[0].includes(index + 1)) {
										onchangeFunction += `getCalculatedValue('${e.formula_string}','${e.inputVal}','${btoa(JSON.stringify(keyPairs))}');`;
										onchangeCount++;
									}
								}
							}
						}
					});

					if (onchangeCount > 0) {
						onchange = `onkeyup="${onchangeFunction}"`;
					}

					switch (type) {
						case "text":
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('select')) {
								inputValue = await getColumnNames(inp[10], inputValue, 1, 1);
							}

							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								inputValue = await getParamValue(inp[10]);
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="text" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Write here..." class="form_control" style="height: 33px;resize:both;">`,
								'type': 'text'
							});
							break;
						case "label":
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('select')) {
								inputValue = await getColumnNames(inp[10], inputValue, 1, 1);
							}

							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								inputValue = await getParamValue(inp[10]);
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<label name="${inp[0]}" ${onchange} id="${inp[0]}" ${readOnly} class="form_control" style="height: 33px;resize:both;">${inputValue}</label>`,
								'type': 'text'
							});
							break;
						case "toRupee":
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="text" name="${inp[0]}" id="${inp[0]}" value="${inputValue}" ${readOnly} class="form_control" style="height: 33px;resize:both;">`,
								'type': 'text'
							});
							break;
						case "textbox":
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('select')) {
								inputValue = await getColumnNames(inp[10], inputValue, 1, 1);
							}

							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								inputValue = await getParamValue(inp[10]);
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<textarea type="text" name="${inp[0]}" ${onchange} id="${inp[0]}" ${readOnly} placeholder="Write here..." class="form_control" style="height: 33px;resize:both;">${inputValue}</textarea>`,
								'type': 'textbox'
							});
							break;
						case "number":
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('select')) {
								inputValue = await getColumnNames(inp[10], inputValue, 1, 1);
							}

							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								inputValue = await getParamValue(inp[10]);
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="number" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Write here..." class="form_control">`,
								'type': 'number'
							});
							break;
						case "date":
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('select')) {
								inputValue = await getColumnNames(inp[10], inputValue, 1, 1);
							}

							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								inputValue = await getParamValue(inp[10]);
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="date" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Write here..." class="form_control">`,
								'type': 'date'
							});
							break;
						case "calculated":

							let conVal = '';
							if (inp[2] !== null && inp[2] !== '' && inp[2].includes('#') && !inp[2].includes('select')) {
								conVal = `toRupeeCon('${inp[0]}','${inp[2]}')`;
							}

							let copyVal = '';
							if (inp[10] !== null && inp[10] !== '' && inp[10].includes('#') && !inp[10].includes('select')) {
								if (onchangeCount > 0) {
									copyVal = `onchange="getInputValue('${inp[0]}','${inp[10]}');${conVal};${onchangeFunction}"`;
								} else {
									copyVal = `onchange="getInputValue('${inp[0]}','${inp[10]}');${conVal}"`;
								}
							} else {
								copyVal = `onchange="${onchangeFunction};${conVal}"`;
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="text" readonly name="${inp[0]}" ${copyVal} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Calculated Field" class="form-control">`,
								'type': 'calculated'
							});
							break;
						case "dropdown":
							if (inp[10] != null && inp[10].includes('select')) {
								$("#" + inp[0]).val(keyPairValue).trigger('change');
								options = await getColumnNames(inp[10], inputValue, 1);

							} else {
								let inputValueArr = inp[10].split(',');
								if (inputValueArr.length > 0) {

									inputValueArr.map(e => {
										let selected = '';
										if (inputValue == e) {
											selected = "selected";
										}
										options += `<option ${selected} value="${e}">${e}</option>`;
									});
								}
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<select name="${inp[0]}" id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Write here..." class="form_control">${options}</select>`,
								'type': 'dropdown'
							});

							break;
						case "checkbox":
							let checkValueArr = inp[10].split(',');
							let checkboxes = '';
							let checkboxInputArr = inputValue.split(',');
							if (checkValueArr.length > 0) {

								checkValueArr.map((e, indexC) => {
									let selected = '';
									if (checkboxInputArr.includes(e)) {
										selected = "checked";
									}
									checkboxes += `${e} <input type="checkbox" name="${inp[0]}[]" id="${inp[0] + indexC}" ${selected} value="${e}" ${readOnly} placeholder="Write here..." class="form_control mr-1">`;
								});
							}
							keyPairInputArr.push({"key": inp[0], "html": `${checkboxes}`, 'type': 'checkbox'});
							break;
						case "file":
							let filesAng = '';
							let fileNames = '';
							let fileDiv = '';
							if (inputValue != "" && inputValue != null) {
								fileNames = await getAwsLinkToDownload(inputValue);
								if (fileNames.length > 0) {
									fileNames.map((e, index) => {
										filesAng += `<a class="btn btn-link" href="${e.urlPath}" download><i class="fa fa-download"></i> ${e.filename}</a><a class="btn btn-link text-danger" onclick="removeReportFile('${inp[0]}')"><i class="fa fa-times"></i></a><input type="hidden" name="${inp[0]}_file" value="${e.originalfilename}"> `;
									});
								}
								fileDiv = `<div id="${inp[0]}_div">${filesAng}</div>`;
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="file" name="${inp[0]}[]" id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control" multiple> ${fileDiv}`,
								'type': 'file'
							});
							break;
						case "texteditor":
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<textarea class="bg-secondary"  name="${inp[0]}" id="${inp[0]}">${inputValue}</textarea>`,
								"type": "texteditor"
							});
							break;
					}
				}
			}
		})).then(e => {
			return keyPairInputArr;
		});
	} else {
		return keyPairInputArr;
	}
}

function getInputFilledValue(dataset, keys) {
	let keyPairValue = [];
	if (dataset.length > 0) {
		// var boolVar = dataset.some(
		// 	value => { return typeof value == "object" } );
		// if(boolVar)
		// {
		// 	$.each(dataset, function(index, obj) {
		// 		$.each(obj, function(attr, value) {
		// 			keyPairValue[attr]= value.value;
		// 		});
		// 	});
		// }
		// else
		// {
		$.each(dataset, function (attr, value) {

			// keyPairValue[attr]= value.value;
			for (let k in value) {
				keyPairValue[k] = value[k].value;
			}
		});
		// }
	}
	return keyPairValue;
}

function saveReportPageData() {
	// $.LoadingOverlay("show");
	let form = document.getElementById('saveReportPage');
	let formData = new FormData(form);
	formData.set('report_id', $("#report_id").val());
	formData.set('type', 2);
	app.request("saveReportPageData", formData).then(res => {
		// $.LoadingOverlay("hide");
		if (res.status === 200) {
			toastr.success(res.body);
			getInvoiceCerationTable();
			// getReportData();
			fetch_wordReport($("#report_id").val(), $('#_itemID').val());

		} else {
			toastr.error(res.body);
		}
	}).catch(error => console.log(error));
}

//$('#wordReportTableModal').openModal({dismissible:false});

function saveReportPageDataDataLink() {
	// $.LoadingOverlay("show");
	let form = document.getElementById('saveReportPage');
	let formData = new FormData(form);
	formData.set('report_id', $("#report_id").val());
	formData.set('type', 2);
	app.request("saveReportPageDatadatalink", formData).then(res => {
		// $.LoadingOverlay("hide");
		if (res.status === 200) {
			toastr.success(res.body);
			// getReportData();
			fetch_wordReport($("#report_id").val(), $('#_itemID').val());
		} else {
			toastr.error(res.body);
		}
	}).catch(error => console.log(error));
}

function getHistoryTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getHistoryTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getMaterialTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getMaterialTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getIndentTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getIndentTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getProcessFlowChart() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getProcessFlowChart", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getColumnNames(query, inputValue, page_id, type = null) {
	return new Promise(function (resolve, reject) {
		let params = $("#queryParameters").val();
		let id = $("#report_id").val();
		let page_type = $("#type").val();
		let formdata = new FormData();
		formdata.set('query', query);
		formdata.set('inputValue', inputValue);
		formdata.set('type', type);
		formdata.set('params', params);
		formdata.set('page_id', page_id);
		formdata.set('id', id);
		formdata.set('page_type', page_type);
		app.request("getColumnNames", formdata).then(res => {
			if (res.status === 200) {

				resolve(res.data);
			} else {
				resolve(res.data);
			}
		}).catch(error => console.log(error));
	});
}

function getParamValue(val) {
	let paramsData = $("#queryParameters").val();
	paramsData = JSON.parse(atob(paramsData));
	let name = val.split('#');
	let n = '';
	if (name.length > 0) {
		n = name[1];
	}

	if(name[1] === 'salary_table'){

		return JSON.parse(paramsData[n]);
	}else{
		return paramsData[n];
	}
}

function getFormula(keyPairs) {
	let formulas = '';
	let formula_string = '';
	let inputVal = '';
	let formulaArr = [];
	keyPairs.map((r, i) => {
		if (r[1] === 'calculated' || r[1] === 'get') {
			formulas = r[4];
			formula_string = r[4];
			inputVal = r[0];
			if (formulas != null) {
				formulas = formulas.replace(/#/g, "");
				formulas = formulas.split(')').join('').split('(').join('').split('+').join(',').split('-').join(',').split('*').join(',').split('/');
			}

			formulaArr.push({
				formulas: formulas,
				formula_string: formula_string,
				inputVal: inputVal
			})
		}
	})
	return formulaArr;
}

function getCalculatedValue(formula_string, inputVal, keyPairs) {
	let Kp = JSON.parse(atob(keyPairs));
	let formula_s = formula_string.split(')').join('').split('(').join('').split('+').join(',').split('-').join(',').split('*').join(',').split('/');
	formula_s = formula_s[0].split(',');
	// console.log(formula_s);
	formula_s.map(r => {

		let hashVal = r.split('#');

		let val = $("#" + Kp[hashVal[1] - 1][0]).val();

		if (val != null && val != '') {
			val = val;
		} else {
			val = 0;
		}
		// let re = new RegExp(`${r}`, '');
		formula_string = formula_string.replaceAll(r, val);
	})
	let value = eval(formula_string);
	$("#" + inputVal).val(value);
	$("#" + inputVal).trigger('change');
}

function getAwsLinkToDownload(file) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('file', file);
		app.request("getAwsLinkToDownload", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function removeReportFile(id) {
	$("#" + id + '_div').remove();
}

function uploadInvoice() {
	var form = document.getElementById('uploadInvoiceForm');
	let formdata = new FormData(form);
	app.request("uploadInvoiceForm", formdata).then(res => {
		if (res.status === 200) {
			$('#customerInvoiceModal').modal('hide');
			$('.modal-backdrop').remove()
			getInvoiceCerationTable();
			$("#uploadInvoiceForm")[0].reset();
		} else {
			toastr.error(res.body);
		}
	}).catch(error => console.log(error));
}

function getInvoiceTemplates() {

	let company_id = $("#tallyCompany").val();
	let branch_id = $('#tallyBranch').val();
	$("#wordTemplate").html('');

	if (company_id != -1 && company_id != null && branch_id != -1 && branch_id != null) {
		let formdata = new FormData();
		formdata.set('tallyCompany', company_id);
		formdata.set('tallyBranch', branch_id);
		app.request("getInvoiceTemplates", formdata).then(res => {
			if (res.status === 200) {
				$("#wordTemplate").html(res.body);
				$("#wordTemplate").select2({
					dropdownParent: $('#customerInvoiceModal')
				});
			} else {
				$("#wordTemplate").html('');
				$("#wordTemplate").select2({});
			}
		}).catch(error => console.log(error));
	} else {
		app.errorToast('Select Company');
	}
}

function getInvoiceToCustomer() {
	$("#toCustomer").html('');
	let formdata = new FormData();
	app.request("getInvoiceToCustomer", formdata).then(res => {
		if (res.status === 200) {
			$("#toCustomer").html(res.body);
			$("#toCustomer").select2({
				dropdownParent: $('#customerInvoiceModal')
			});
		} else {
			$("#toCustomer").html('');
			$("#toCustomer").select2();
		}
	}).catch(error => console.log(error));
}

function getInputValue(from, to) {
	let fromVal = $("#" + from).val();
	let toVal = to.split('#');
	$('#Input' + toVal[1]).val(fromVal);

}


// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];


function toWords(s) {

	s = s.toString();

	s = s.replace(/[\, ]/g, '');

	if (s != parseFloat(s)) return 'not a number';
	var x = s.indexOf('.');
	var fulllength = s.length;

	if (x == -1) x = s.length;
	if (x > 15) return 'too big';
	var startpos = fulllength - (fulllength - x - 1);
	var n = s.split('');

	var str = '';
	var str1 = ''; //i added another word called cent
	var sk = 0;
	for (var i = 0; i < x; i++) {
		if ((x - i) % 3 == 2) {
			if (n[i] == '1') {
				str += tn[Number(n[i + 1])] + ' ';
				i++;
				sk = 1;
			} else if (n[i] != 0) {
				str += tw[n[i] - 2] + ' ';

				sk = 1;
			}
		} else if (n[i] != 0) {
			str += dg[n[i]] + ' ';
			if ((x - i) % 3 == 0) str += 'hundred ';
			sk = 1;
		}
		if ((x - i) % 3 == 1) {
			if (sk) str += th[(x - i - 1) / 3] + ' ';
			sk = 0;
		}
	}
	if (x != s.length) {

		str += 'point '; //i change the word point to and
		str1 += ''; //i added another word called cent
		//for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ' ;
		var j = startpos;

		for (var i = j; i < fulllength; i++) {

			if ((fulllength - i) % 3 == 2) {
				if (n[i] == '1') {
					str += tn[Number(n[i + 1])] + ' ';
					i++;
					sk = 1;
				} else if (n[i] != 0) {
					str += tw[n[i] - 2] + ' ';

					sk = 1;
				}
			} else if (n[i] != 0) {

				str += dg[n[i]] + ' ';
				if ((fulllength - i) % 3 == 0) str += 'hundred ';
				sk = 1;
			}
			if ((fulllength - i) % 3 == 1) {

				if (sk) str += th[(fulllength - i - 1) / 3] + ' ';
				sk = 0;
			}
		}
	}
	var result = str.replace(/\s+/g, ' ') + str1;
	let words = result.replace(/\b\w/g, c => c.toUpperCase());
	return words; //i added the word cent to the last part of the return value to get desired output

}

function toRupeeCon(input1, input2) {
	let val = $("#" + input1).val();
	let rupee = price_in_words(val);
	let inpVAl = input2.split('#');
	$("#Input" + inpVAl[1]).val(rupee);
}

function bmrReport(){
	let report_id = $("#report_id").val();
	let year = $("#year").val();
	let month = $("#month").val();
	let type = 2;
	let queryParameters = $("#queryParameters").val();
	console.log(queryParameters)
	window.location.href = baseURL + "bmrReport/" + type + '/' + report_id + '/' + queryParameters+'/'+year+'/'+month;
}


/*$("#yeardatepicker").datepicker({
	format: "yyyy",
	viewMode: "years",
	minViewMode: "years"
});*/







function hitFile(divId) {
	// $('#'+divId).show();
	$("#" + divId).click();
}

$('input[type="file"][name="userfile[]"]').change(function () {
	$fileCount = this.files.length;
	$("#fileUploadCountDiv").text('total no. of file uploded : ' + $fileCount);
});

function price_in_words(price) {
	var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
		dblDigit = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"],
		tensPlace = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"],
		handle_tens = function (dgt, prevDgt) {
			return 0 == dgt ? "" : " " + (1 == dgt ? dblDigit[prevDgt] : tensPlace[dgt])
		},
		handle_utlc = function (dgt, nxtDgt, denom) {
			return (0 != dgt && 1 != nxtDgt ? " " + sglDigit[dgt] : "") + (0 != nxtDgt || dgt > 0 ? " " + denom : "")
		};

	var str = "",
		digitIdx = 0,
		digit = 0,
		nxtDigit = 0,
		words = [];
	if (price += "", isNaN(parseInt(price))) str = "";
	else if (parseInt(price) > 0 && price.length <= 10) {
		for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
			case 0:
				words.push(handle_utlc(digit, nxtDigit, ""));
				break;
			case 1:
				words.push(handle_tens(digit, price[digitIdx + 1]));
				break;
			case 2:
				words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] && 0 != price[digitIdx + 2] ? " and" : "") : "");
				break;
			case 3:
				words.push(handle_utlc(digit, nxtDigit, "Thousand"));
				break;
			case 4:
				words.push(handle_tens(digit, price[digitIdx + 1]));
				break;
			case 5:
				words.push(handle_utlc(digit, nxtDigit, "Lakh"));
				break;
			case 6:
				words.push(handle_tens(digit, price[digitIdx + 1]));
				break;
			case 7:
				words.push(handle_utlc(digit, nxtDigit, "Crore"));
				break;
			case 8:
				words.push(handle_tens(digit, price[digitIdx + 1]));
				break;
			case 9:
				words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] || 0 != price[digitIdx + 2] ? " and" : " Crore") : "")
		}
		str = words.reverse().join("")
	} else str = "";
	return str

}

function ocr_formClose()
{
	$("#uploadOCRFileForm")[0].reset();
	$("#ocr_fileDiv").html('');
	$('#FileViewD').css('display','none');
}

$('#OCRInvoiceModal').on('hidden.bs.modal', function(e) {
	$(this).find('#uploadOCRFileForm')[0].reset();
	$('#FileViewD').css('display','none');

});


