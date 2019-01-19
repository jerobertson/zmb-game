var session = "";
var stageInt = 0;
var textArray = [];

function submitButton(btn) {
  $(".to-fade").fadeOut();
  $(".to-fade").promise().done(function() {
    $.ajax({
      url: "progressions/" + btn + ".php",
      type: "POST",
      dataType: "json",
      data: ({k: $.url().param('k'), stage: stageInt, textInput: $('#text-input-field').val()}),
      success: function(data) {
        processData(data);
      }
    });
  });
}

function submitLink(lnk) {
  $(".to-fade").fadeOut();
  $(".to-fade").promise().done(function() {
    $.ajax({
      url: "progressions/link.php",
      type: "POST",
      dataType: "json",
      data: ({k: $.url().param('k'), stage: stageInt, textInput: $('#text-input-field').val(), linkText: lnk}),
      success: function(data) {
        processData(data);
      }
    });
  });
}

function processData(data) {
  stageInt = data['stage'];
  textArray = data['text'];
  if ('thirst' in data) {
    updateThirstFont(data['thirst']);
  }
  if ('fire' in data) {
    updateFireBg(data['fire']);
  }
  if ('time' in data) {
    updateTimeBg(data['time']);
  }
  if ('temp' in data) {
    updateTempBg(data['temp']);
  }
  if ('danger' in data) {
    $('#text').css('color', "rgb(255," + data['danger'] + "," + data['danger'] + ")");
  }
  else {
    $('#text').css('color', "white");
  }
  if ('health' in data) {
    updateHealthBg(data['health']);
  }
  if ('map' in data) {
    $('#maptiles').html(data['map']);
    $('#map').addClass("to-fade").removeClass("no-fade");
    $('#loc-map-container').addClass("to-fade").removeClass("no-fade");
  }
  else {
    $('#maptiles').html("");
    $('#map').addClass("no-fade").removeClass("to-fade");
    $('#loc-map-container').addClass("no-fade").removeClass("to-fade");
  }
  if ('camps' in data) {
    displayNearbyCamps(data['camps'], data['campsD'], data['discoveredTilesCount']);
  }
  getButtonText();
}

function updateThirstFont(thirstInt) {
  if (thirstInt > 5) {
    $('.container').css('font-family', "'Covered By Your Grace', cursive");
  }
  else {
    $('.container').css('font-family', "'Architects Daughter', cursive");
  }
}

function updateFireBg(fireInt) {
  $('#fr' + fireInt).fadeTo(2000, 1);
  for (i = 0; i < 5; i++) {
    if (i !== fireInt) {
      $('#fr' + i).fadeTo(2000, 0);
    }
  }
}

function updateTimeBg(timeInt) {
  if (7 <= timeInt && timeInt < 9) {
    $('#ti1').fadeTo(2000, 1);
    $('#ti2').fadeTo(2000, 0);
    $('#ti3').fadeTo(2000, 0);
  }
  else if (9 <= timeInt && timeInt < 12) {
    $('#ti1').fadeTo(2000, 1);
    $('#ti2').fadeTo(2000, 1);
    $('#ti3').fadeTo(2000, 0);
  }
  else if (12 <= timeInt && timeInt < 15) {
    $('#ti1').fadeTo(2000, 1);
    $('#ti2').fadeTo(2000, 1);
    $('#ti3').fadeTo(2000, 1);
  }
  else if (15 <= timeInt && timeInt < 18) {
    $('#ti1').fadeTo(2000, 1);
    $('#ti2').fadeTo(2000, 1);
    $('#ti3').fadeTo(2000, 0);
  }
  else if (18 <= timeInt && timeInt < 20) {
    $('#ti1').fadeTo(2000, 1);
    $('#ti2').fadeTo(2000, 0);
    $('#ti3').fadeTo(2000, 0);
  }
  else if (20 <= timeInt) {
    $('#ti1').fadeTo(2000, 0);
    $('#ti2').fadeTo(2000, 0);
    $('#ti3').fadeTo(2000, 0);
  }
}

function updateTempBg(tempInt) {
  if (tempInt == 0) {
    $('#co1').fadeTo(2000, 1);
    $('#co2').fadeTo(2000, 0);
    $('#ho1').fadeTo(2000, 0);
    $('#ho2').fadeTo(2000, 0);
  }
  else if (tempInt < 0) {
    $('#co1').fadeTo(2000, 0);
    $('#co2').fadeTo(2000, 1);
    $('#ho1').fadeTo(2000, 0);
    $('#ho2').fadeTo(2000, 0);
  }
  else if (tempInt == 6) {
    $('#co1').fadeTo(2000, 0);
    $('#co2').fadeTo(2000, 0);
    $('#ho1').fadeTo(2000, 1);
    $('#ho2').fadeTo(2000, 0);
  }
  else if (tempInt > 6) {
    $('#co1').fadeTo(2000, 0);
    $('#co2').fadeTo(2000, 0);
    $('#ho1').fadeTo(2000, 0);
    $('#ho2').fadeTo(2000, 1);
  }
  else {
    $('#co1').fadeTo(2000, 0);
    $('#co2').fadeTo(2000, 0);
    $('#ho1').fadeTo(2000, 0);
    $('#ho2').fadeTo(2000, 0);
  }
}

function updateHealthBg(healthInt) {
  $('#he').fadeTo(2000, (10 - healthInt) / 20);
}

function displayNearbyCamps(jsonCamps, jsonCampsD, tileCountInt) {
  if (jsonCamps != "[]") {
    var camps = JSON.parse(jsonCamps);
    var campsD = JSON.parse(jsonCampsD);
    var campString = "";
    for (var i in camps) {
      if (camps[i] == 1) {
        campString += "<a href=\"#\" style=\"color:rgb(255," + campsD[i] + "," + campsD[i] + ")\">\"" + i + "\" (" + camps[i] + " hour away)</a>, ";
      }
      else {
        campString += "<a href=\"#\" style=\"color:rgb(255," + campsD[i] + "," + campsD[i] + ")\">\"" + i + "\" (" + camps[i] + " hours away)</a>, ";
      }
    }
    $("#location-names").html(campString);
  }
  else {
    $("#location-names").html("");
  }
  if (tileCountInt > 1) {
    $("#locations").addClass("to-fade").removeClass("no-fade");
    $('#loc-map-container').addClass("to-fade").removeClass("no-fade");
  }
  else {
    $("#locations").addClass("no-fade").removeClass("to-fade");
    $('#loc-map-container').addClass("no-fade").removeClass("to-fade");
  }
}

function getButtonText() {
  $.ajax({
    url: "getButtonText.php",
    type: "POST",
    dataType: "json",
    data: ({stage: stageInt}),
    success: function(data) {
      $("#text-input-field").val("");
      for (var key in data) {
        if (key == "btnText") {
          if (data[key] !== "<no action>") {
            $("#" + key).html(data[key]);
            $("#text-input").addClass("to-fade").removeClass("no-fade");
            $(".text-input-format-column").addClass("to-fade").removeClass("no-fade");
          }
          else {
            $("#text-input").addClass("no-fade").removeClass("to-fade");
            $(".text-input-format-column").addClass("no-fade").removeClass("to-fade");
          }
        }
        else if (data[key] !== "<no action>") {
          $("#" + key).html(data[key]);
          $("#" + key).addClass("to-fade").removeClass("no-fade");
        }
        else {
          $("#" + key).addClass("no-fade").removeClass("to-fade");
        }
      }
      displayText(textArray);
    }
  });
}

function getBg() {
  $.ajax({
    url: "getBg.php",
    type: "POST",
    dataType: "json",
    data: ({k: $.url().param('k')}),
    success: function(data) {
      updateFireBg(data['fire']);
      updateTimeBg(data['time']);
      updateTempBg(data['temp']);
      updateHealthBg(data['health']);
      updateThirstFont(data['thirst']);
      if ('danger' in data) {
        $('#text').css('color', "rgb(255," + data['danger'] + "," + data['danger'] + ")");
      }
      else {
        $('#text').css('color', "white");
      }
      if ('map' in data) {
        $('#maptiles').html(data['map']);
        $('#map').addClass("to-fade").removeClass("no-fade");
        $('#loc-map-container').addClass("to-fade").removeClass("no-fade");
      }
      else {
        $('#maptiles').html("");
        $('#map').addClass("no-fade").removeClass("to-fade");
        $('#loc-map-container').addClass("no-fade").removeClass("to-fade");
      }
      if ('camps' in data) {
        displayNearbyCamps(data['camps'], data['campsD'], data['discoveredTilesCount']);
      }
    }
  });
}

function displayText(textArr) {
  $(".info").typed({
    strings: textArr,
    typeSpeed: 0,
    backDelay: 1000,
    showCursor: false,
    callback: function() {
      $(".to-fade").delay(1000).fadeIn();
    }
  });
}

$(document).ready(function() {
  $(function(){
    $.ajax({
      url: "getText.php",
      type: "POST",
      dataType: "json",
      data: ({k: $.url().param('k')}),
      success: function(data) {
        session = data['k'];
        stageInt = data['stage'];
        
        if (data['text'] == null) {
          window.location.replace("?k=" + data['k']);
        }
        else {
          $('#session-link').html("<a href=\"" + $.url().attr("source") + "\">" + $.url().attr("source") + "</a>");
          textArray.push(data['text']);
          getBg();
          getButtonText();
        }
      }
    })
  });
  
  $(".container").on("click", ".btn", function(event) {
    submitButton(event.target.id);
  });
  
  $(".container").on("click", "a", function(event) {
    submitLink($(this).text());
  });
  
  $("#info").on("click", "a", function(event) {
    if ($("#about").css("display") == "none") {
      $('#about').fadeIn();
    }
    else {
      $('#about').fadeOut();
    }
  });
});