function post_to_url(path, params, method) {
    method = method || "post"; 
    var html = '<form method="' + method + '" action="' + path + '">';

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
          var input = '<input type="hidden" name="' + key + '" value="' + params[key] + '" />';
          html += input;
         }
    }
    
    html += '</form>';
    var form = $(html);
    $('body').append(form);
    form.submit();
}