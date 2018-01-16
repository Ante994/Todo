$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$(document).on("submit", "#form_todo_new", function(e){
    e.preventDefault();
    var data = $("#form_todo_new").serializeObject();
    console.log(data);
    $.ajax({
        url: "profiler/todos/new",
        type: 'POST',
        dataType: 'json',
        data: data,
        success:function(data){
            if(data){
                todoFormDiv.style.visibility = "hidden";
                todoFormButton.innerHTML = "Add new todo";
                $("#form_todo_new")[0].reset();
                $('table').find('tbody').append(data);
            }
        }
    });
});

$(document).on("submit", "#form_task_edit", function(e){
    e.preventDefault();
    var taskId = document.getElementById("edit-link").getAttribute('data-value');
    var data = $("#form_task_edit").serializeObject();
    console.log(taskId);
    $.ajax({
        url: "../"+taskId+"/edit",
        type: 'POST',
        dataType: 'json',
        data: data,
        success:function(data){
            if(data){
                console.log(data);
                taskEdit.style.visibility = "visible";
            }
        }
    });
});

var todoFormDiv = document.getElementById("todoForm");
var todoFormButton = document.getElementById("todoFormButton");

function showForm(){
    if (todoFormDiv.style.visibility === "visible") {
        todoFormDiv.style.visibility = "hidden";
        todoFormButton.innerHTML = "Add new todo";
    } else {
        todoFormDiv.style.visibility = "visible";
        todoFormButton.innerHTML = "Close form";
    }
}
