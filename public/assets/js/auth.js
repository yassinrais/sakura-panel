(function ($) {
    "use strict";

    $('.auth-form').on('submit',function (e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');
        
        let btn = form.find('*[type=submit]');

        let _obtn = btn.html();
        btn.html("<i class='fas fa-spin fa-clock'></i>");

        $('.auth-msgs').html("");
        $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            for(var i in data.msg)
                $('.auth-msgs').append('<div class="alert alert-'+ (i === "error" ? "danger" : i)+'">'+data.msg[i]+"</div>");
                if (data.status == "success") window.location.reload();

                btn.html(_obtn);

        },
            error: function(){
                $('.auth-msgs').html('<div class="alert alert-warning">Somthing wrong ! please reload this page </div>');
                btn.html(_obtn);
            },
            timeout: 5000 // sets timeout to 3 seconds
        });
    });

    $('.input').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })


})(jQuery);