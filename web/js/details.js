var MAIN = {
    delete: function () {
        var elem = $(this);
        var todoId = elem.data("value2");
        var taskId = elem.data("value");
        // var parentElem = elem.closest("");
        $.ajax({
            type: "POST",
            url: "../"+todoId+"/"+taskId+"/delete",
            success : function(response){
               var response = jQuery.parseJSON(response);
                if(response.success){
                   $("#"+taskId).remove();
                }
            }
        });
    },
    start : function () {
        $(document).on('click','#delete-link',MAIN.delete);
    }
}
$(document).ready(MAIN.start);


