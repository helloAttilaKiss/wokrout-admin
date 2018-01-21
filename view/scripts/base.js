var addSuccessMessage = function(title, message){
    $('<div class="callout callout-success"><h4>' + title + '</h4><p>' + message + '</p></div>').appendTo($('#message-container'));
    setTimeout(function(){ 
        $('#message-container .callout').first().fadeOut(function(){
            $(this).remove();
        }); 
    }, 2000);
}

var addErrorMessage = function(title, message){
    $('<div class="callout callout-danger"><h4>' + title + '</h4><p>' + message + '</p></div>').appendTo($('#message-container'));
    setTimeout(function(){ 
        $('#message-container .callout').first().fadeOut(function(){
            $(this).remove();
        }); 
    }, 2000);
}

function pad(num) {
    return ("0" + num).slice(-2);
}

function formatTohhmmss(secs) {
  var minutes = Math.floor(secs / 60);
  secs = secs % 60;
  var hours = Math.floor(minutes / 60)
  minutes = minutes % 60;
  if (hours === 0) {
     if (minutes === 0) return pad(secs) + " sec";
     return pad(minutes) + " min " + pad(secs) + " sec";
  };
  return pad(hours) + " h " + pad(minutes) + " min " + pad(secs) + " sec";
}

