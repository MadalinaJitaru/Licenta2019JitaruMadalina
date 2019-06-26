var lastFacultyId = 0;
var lastSpecializationId = 0;
var lastYearOfStudyId = 0;
function verifyValues()
{
    var facultyId = document.getElementById("facultySelect").value;
    var specializationId = document.getElementById("specializationSelect").value;
    var yearOfStudyId = document.getElementById("yearOfStudySelect").value;
    var groupId = document.getElementById("groupSelect").value;
    
    if(facultyId != lastFacultyId)
    {
        $('#specializationSelect-Title').html("Selecteaza specializarea");
        $('#yearOfStudySelect-Title').html("Selecteaza anul de studiu");
        $('#groupSelect-Title').html("Selecteaza grupa");
        
        document.getElementById("specializationSelect-Title").classList.remove("disabledDropdownSelect");
        document.getElementById("yearOfStudySelect-Title").classList.remove("disabledDropdownSelect");

        $.ajax({
            url:"./controllers/student/moreInformationFetchData.php",
            method:"POST",
            data:{facultyIdSpecialization:facultyId},
            success:function(data){
                $('#specializationSelect').html(data);
                replaceOptions("specializationSelect", "specializationSelect-Inner");
            }
        });
        $.ajax({
            url:"./controllers/student/moreInformationFetchData.php",
            method:"POST",
            data:{facultyIdYear:facultyId},
            success:function(data){
                $('#yearOfStudySelect').html(data);
                replaceOptions("yearOfStudySelect", "yearOfStudySelect-Inner");
            }
        });
        lastFacultyId = facultyId;
    }

    if(specializationId != lastSpecializationId && yearOfStudyId != lastYearOfStudyId)
    {
        document.getElementById("groupSelect-Title").classList.remove("disabledDropdownSelect");

        $.ajax({
            url:"./controllers/student/moreInformationFetchData.php",
            method:"POST",
            data:{specializationId:specializationId,
              yearOfStudyId:yearOfStudyId,
              facultyId:facultyId},
              success:function(data){
                $('#groupSelect').html(data);
                replaceOptions("groupSelect", "groupSelect-Inner");
            }
        });
        lastSpecializationId = specializationId;
        lastYearOfStudyId = yearOfStudyId;
    }
}

function replaceOptions(idOfSelect, idOfOptions)
{
    selElmnt = document.getElementById(idOfSelect);
    b = document.getElementById(idOfOptions);
    b.innerHTML = "";
    for (j = 1; j < selElmnt.length; j++) 
    {
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/

        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) 
        {
            if (s.options[i].innerHTML == this.innerHTML) 
            {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                y = this.parentNode.getElementsByClassName("sameAsSelected");
                for (k = 0; k < y.length; k++) 
                {
                    y[k].removeAttribute("class");
                }
                this.setAttribute("class", "sameAsSelected");
                break;
            }
        }
        h.click();
        verifyValues();
    });
        b.appendChild(c);
    }
}