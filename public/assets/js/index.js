
// Form data serialising
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

// Creating summernote
function summernoteInit(){
    $('.summernote').summernote({
        placeholder: 'Print here post text',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
        ]
    });
}


// Ajax request function
function ajaxRequest(url, form) {
    var form_data = form.serializeObject();

    $('#postcreatesubmit').attr("disabled",true);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success:function(data){
            if(data == 'error'){
                // Creating success toast
                $(document).ready(function(){
                    $('.toast .toast-body').text("Please enter all data");
                    $('.toast').removeClass("bg-success").addClass("bg-danger").toast('show');
                });
            }
            if(data == 'success'){
                // Creating error toast
                $(document).ready(function(){
                    $('.toast .toast-body').text("Success");
                    $('.toast').removeClass("bg-danger").addClass("bg-success").toast('show');
                });
            }

            $('#postcreatesubmit').attr("disabled",false);
        },
        error:function (e) {
            $(document).ready(function(){
                $('.toast .toast-body').text("Please enter all data");
                $('.toast').removeClass("bg-success").addClass("bg-danger").toast('show');
            });

            $('#postcreatesubmit').attr("disabled",false);
        }
    });
}