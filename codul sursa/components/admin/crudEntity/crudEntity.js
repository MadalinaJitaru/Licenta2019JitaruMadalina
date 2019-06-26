function showElement(idElement) {
	document.getElementById(idElement).style.display = "block";
}
function hideElement(idElement) {
	document.getElementById(idElement).style.display = "none";
}
function setValueToDeleteButton(nameDeleteButtonEntity, radiosValue)
{
	document.getElementById(nameDeleteButtonEntity).value=radiosValue;
}
function getFetchDataStudent(studentId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudStudentFetchData.php",
		method:"POST",
		data:{editStudentId:studentId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});
}
function getFetchDataSpecialization(specializationId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudSpecializationFetchData.php",
		method:"POST",
		data:{editSpecializationId:specializationId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});
}
function getFetchDataQuestion(questionId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudQuestionFetchData.php",
		method:"POST",
		data:{editQuestionId:questionId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});	
}
function getFetchDataStudyYear(studyYearId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudStudyYearFetchData.php",
		method:"POST",
		data:{editStudyYearId:studyYearId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});	
}
function getFetchDataCourse(courseId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudCourseFetchData.php",
		method:"POST",
		data:{editCourseId:courseId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});	
}
function getFetchDataGroup(groupId, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudGroupFetchData.php",
		method:"POST",
		data:{editGroupId:groupId},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});
}
function getFetchDataProfessor(professorId, titular, idPopUp)
{
	$.ajax({
		url:"./controllers/admin/crudProfessorFetchData.php",
		method:"POST",
		data:{editProfessorId:professorId,
			  editTitular:titular},
		success:function(data){
			$('#editPopUpContainer').html(data);
			showElement(idPopUp);
		}
	});
}
function selectedEntity(idPopUp, nameEntity) {
	var radios = document.getElementsByName(nameEntity);
	var isSelected = false;
	for (var i = 0, length = radios.length; i < length; i++)
	{
		if (radios[i].checked)
		{
			if(idPopUp == "deletePopUp")
			{
				var nameDeleteButtonEntity = "delete" + nameEntity.charAt(0).toUpperCase() + nameEntity.slice(1);
				setValueToDeleteButton(nameDeleteButtonEntity, radios[i].value);
			}
			if(idPopUp == "editPopUp")
			{
				if(nameEntity == "student")
				{
					var studentId = radios[i].value;
					getFetchDataStudent(studentId, idPopUp);
				}
				if(nameEntity == "specialization")
				{
					var specializationId = radios[i].value;
					getFetchDataSpecialization(specializationId, idPopUp);
				}
				if(nameEntity == "question")
				{
					var questionId = radios[i].value;
					getFetchDataQuestion(questionId, idPopUp);
				}
				if(nameEntity == "studyYear")
				{
					var studyYearId = radios[i].value;
					getFetchDataStudyYear(studyYearId, idPopUp);
				}
				if(nameEntity == "course")
				{
					var courseId = radios[i].value;
					getFetchDataCourse(courseId, idPopUp);
				}
				if(nameEntity == "group")
				{
					var groupId = radios[i].value;
					getFetchDataGroup(groupId, idPopUp);
				}
				if(nameEntity == "professor")
				{
					var idsProfessor = radios[i].value.split('.');
					var professorId = idsProfessor[0];
					var titular = idsProfessor[1];
					getFetchDataProfessor(professorId, titular, idPopUp);
				}
			}
			else
			{
				showElement(idPopUp);
			}
			isSelected = true;
			break;
		}
	}
	if (!isSelected) 
	{
		showElement("optionError");
	}
}