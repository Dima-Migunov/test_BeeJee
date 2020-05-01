function htmlspecialchars_decode (string, quoteStyle) {
    var optTemp = 0,
        i = 0,
        noquotes = false;
  
    if (typeof quoteStyle === 'undefined') {
        quoteStyle = 2;
    }

    string  = string.toString()
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>');
    
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    };
    
    if (quoteStyle === 0) {
        noquotes = true;
    }

    if (typeof quoteStyle !== 'number') {
        quoteStyle = [].concat(quoteStyle);

        for (i = 0; i < quoteStyle.length; i++) {
            if (OPTS[quoteStyle[i]] === 0) {
                noquotes = true;
            } else if (OPTS[quoteStyle[i]]) {
                optTemp = optTemp | OPTS[quoteStyle[i]];
            }
        }
      
        quoteStyle = optTemp;
    }

    if (quoteStyle & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'");
    }

    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }

    string = string.replace(/&amp;/g, '&');
  
    return string
  }


$(function(){
    $("#button-add-task").click(function(){
        $("#form-task-id").val("");
    });

    $("#task-list").on("click", ".btn-edit", function(){
        var card    = $(this).closest(".card");

        var id          = card.data("id"),
            status      = card.data("status"),
            username    = card.find(".badge-user").text(),
            email       = card.find(".badge-email").text(),
            text        = card.find(".card-body>pre").html();

        text    = htmlspecialchars_decode(text);
        
        $("#form-task-id").val(id);
        $("#modal-add-task input[name='username']").val(username);
        $("#modal-add-task input[name='email']").val(email);
        $("#modal-add-task textarea").val(text);        
    });
});