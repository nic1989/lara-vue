function getSubCourse(id, subId) {
	$.ajax({
		method:'get',
		url: BASE_URL+'/getSubCourse/'+id+'/'+subId,
		success: function(result) {
			$('#subcourseId').html(result);
		}
	})
}

function addQuestion() {
	var portionlen = $('#questionDiv [id^=portion_]').length;
	var html = `<div id="portion_${portionlen}" class="border border-dark px-2 mt-3">
					<a href="javascript:void(0)" class="float-right removeQes"><i class="fa fa-trash"></i></a>
					<div class="form-group">
						<label class="form-control-label">Question Type</label>
						<select name="quiz[question_type][${portionlen}]" id="questionType_${portionlen}" class="form-control required" onchange="getQuestionsByType(event, this.value)">
							<option value="">choose Question</option>
							<option value="text">Text</option>
							<option value="select">Mutiple Choice</option>
						</select>
						<span class="invalid-feedback" id="QtypeError" style="display:none">
							<strong>Choose Question type</strong>
						</span>
						<span class="selectBoxHelp" style="display:none"><mark><small>Choose one radio button for the correct answer</small></mark></span>
					</div>
				</div>`;
	
	$('#questionDiv').append(html);
}

function getQuestionsByType(event, val) {
	var id = event.target.id.split('_')[1];
	$('#quesdiv_'+id).remove();
	$('#questionType_'+id).next().next('.selectBoxHelp').hide();
	
	var html = '';
	if (val == 'text') {
		html = getText(id);
	} else if (val == 'select') {
		$('#questionType_'+id).next().next('.selectBoxHelp').show();
		html = getSelect(id);
	}

	$('#questionDiv #portion_'+id).append(html);
}

function checkvalidation()
{
	var setFlag = 1;
	$('.required').next('span').hide();
	$('.required').each( function() {
		if ($(this).val() == '') {
			setFlag = 0;
			$(this).next('span').show();
		}
	});

	if (setFlag) {
		$('#QuizForm').submit();
	}
}

function getText(id) {
	return `<div id="quesdiv_${id}" class="form-group">
					<div class="form-group">
						<label class="form-control-label">Question</label>
						<input typt="text" class="form-control required" name="quiz[text][${id}][question]">
						<span class="invalid-feedback" style="display:none">
							<strong>This field is required</strong>
						</span>
					</div>
					<div class="form-group">
						<label class="form-control-label">Answer</label>
						<textarea class="form-control required" name="quiz[text][${id}][answer]" rows="10" cols="50"></textarea>
						<span class="invalid-feedback" style="display:none">
							<strong>This field is required</strong>
						</span>
					</div>
				</div>`;
}

function getSelect(id) {
	return `<div id="quesdiv_${id}" class="form-group">
				<label class="form-control-label">Question</label>
				<input typt="text" class="form-control required" name="quiz[select][${id}][question]">
				<span class="invalid-feedback" style="display:none">
					<strong>This field is required</strong>
				</span>

				<div class="row mt-2">
					<div class="col-md-3 option">
						<div class="float-left">
							<input typt="text" id="option_0" class="form-control required" name="quiz[select][${id}][option][0]">
							<span class="invalid-feedback" style="display:none">
								<strong>This field is required</strong>
							</span>
						</div>
						<input type="radio" checked	name="quiz[select][${id}][answer]" value="0" class="ml-2">
					</div>

					<div class="col-md-3 option">
						<div class="float-left">
							<input typt="text" id="option_1" class="form-control required" name="quiz[select][${id}][option][1]">
							<span class="invalid-feedback" style="display:none">
								<strong>This field is required</strong>
							</span>
						</div>
						<input type="radio"	name="quiz[select][${id}][answer]" value="1" class="ml-2">
					</div>
				</div>

				<button type="button" onclick="addOption(${id})" class="btn btn-info mt-2">Add Option</button>
			</div>`;
}

function addOption(id) {
	var lstoption = $('#quesdiv_'+id+' .option > .float-left:last > input').attr('id');
	var lstoptId = lstoption.split('_')[1];
	var optId = parseInt(lstoptId) + 1;
	var html = '';
	html += `<div class="col-md-3 mb-2 option">
				<div class="float-left">
					<input typt="text" id="option_${optId}" class="form-control required" name="quiz[select][${id}][option][${optId}]">
				</div>
				<div class="float-right">
					<input type="radio"	name="quiz[select][${id}][answer]" value="${optId}" class="ml-2">
					<a href="javascript:void(0)" class="ml-2 remove"><i class="fa fa-trash"></i></a>
				</div>
				<span class="invalid-feedback" style="display:none">
					<strong>This field is required</strong>
				</span>
			</div>`;

	$('#quesdiv_'+id+' div.row').append(html);
}

function removeDiv(elem) {
	var classname = elem.parentNode.className;
	$(classname).remove()
}

$(document).ready(function() {
	$('#questionDiv').on('click', '.remove', function() {
		$(this).parents('div.option').remove();
	});

	$('#questionDiv').on('click', '.removeQes', function() {
		var borderDivlen = $('div.border').length;
		if (borderDivlen > 1) {
			$(this).parent('div.border').remove();
		} else {
			$(this).next('div').find('span:first').show()
		}
	});
})