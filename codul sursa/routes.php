<?php
//student
Route::set('login-student', function() {
        LoginStudent::CreateView('loginStudentPage');
});
Route::set('moreInformation-student', function() {
        MoreInformation::CreateView('moreInformationStudentPage');
});
Route::set('chooseProfessor-student', function() {
        ChooseProfessorForStudent::CreateView('chooseProfessorForStudentPage');
});
Route::set('questionsForFeedback-student', function() {
        QuestionsForFeedback::CreateView('questionsForFeedbackStudentPage');
});

//professor
Route::set('login-professor', function() {
        LoginProfessor::CreateView('loginProfessorPage');
});
Route::set('changePassword-professor', function() {
        ChangePassword::CreateView('changePasswordProfessorPage');
});
Route::set('chooseProfessor-professor', function() {
        ChooseProfessorForProfessor::CreateView('chooseProfessorForProfessorPage');
});
Route::set('feedbackResults-professor', function() {
        FeedbackResults::CreateView('feedbackResultsProfessorPage');
});

//admin
Route::set('chooseOption-admin', function() {
        ChooseOption::CreateView('chooseOptionForAdminPage');
});
Route::set('chooseProfessor-admin', function() {
        ChooseProfessorForAdmin::CreateView('chooseProfessorForAdminPage');
});
Route::set('feedbackResults-admin', function() {
        FeedbackResults::CreateView('feedbackResultsProfessorPage');
});
Route::set('crudStudent-admin', function() {
        CrudStudent::CreateView('crudStudentForAdminPage');
});
Route::set('crudProfessor-admin', function() {
        CrudProfessor::CreateView('crudProfessorForAdminPage');
});
Route::set('crudGroup-admin', function() {
        CrudGroup::CreateView('crudGroupForAdminPage');
});
Route::set('crudCourse-admin', function() {
        CrudCourse::CreateView('crudCourseForAdminPage');
});
Route::set('crudSpecialization-admin', function() {
        CrudSpecialization::CreateView('crudSpecializationForAdminPage');
});
Route::set('crudQuestion-admin', function() {
        CrudQuestion::CreateView('crudQuestionForAdminPage');
});
Route::set('crudStudyYear-admin', function() {
        CrudStudyYear::CreateView('crudStudyYearForAdminPage');
});
?>