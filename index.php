<!DOCTYPE html>
<html>
  <head>
    <title>zmb</title>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Architects+Daughter" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Covered+By+Your+Grace" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/typed.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/purl.min.js"></script>
    <script src="js/zmb.js"></script>
  </head>
  <body>
    <div id="info">
      <p id="info-content">zmb alpha. <a href="#">about</a>.</p>
    </div>
    <div id="ti1"></div>
    <div id="ti2"></div>
    <div id="ti3"></div>
    <div id="fr1"></div>
    <div id="fr2"></div>
    <div id="fr3"></div>
    <div id="fr4"></div>
    <div id="fr5"></div>
    <div id="co1"></div>
    <div id="co2"></div>
    <div id="ho1"></div>
    <div id="ho2"></div>
    <div id="he"></div>
    <div class="container">
      <div class="col-xs-12" id="text">
        <span class="info"></span>
      </div>
      <div class="col-xs-3"></div>
      <div class="col-xs-6">
        <input type="text" class="form-control no-fade">
      </div>
      <div class="col-xs-3"></div>
      <div class="col-xs-12">
        <button class="btn btn-lg btn-default to-fade" id="btn1">Button 1</button>
        <button class="btn btn-lg btn-default no-fade" id="btn2">Button 2</button>
        <button class="btn btn-lg btn-default no-fade" id="btn3">Button 3</button>
        <button class="btn btn-lg btn-default no-fade" id="btn4">Button 4</button>
        <button class="btn btn-lg btn-default no-fade" id="btn5">Button 5</button>
        <button class="btn btn-lg btn-default no-fade" id="btn6">Button 6</button>
      </div>
      <div class="col-xs-3 no-fade text-input-format-column"></div>
      <div class="col-xs-6 no-fade" id="text-input">
        <div class="input-group input-group-lg">
          <input type="text" class="form-control" id="text-input-field" placeholder="Enter your new camp's name here.">
          <span class="input-group-btn">
            <button class="btn btn-default" id="btnText">Submit</button>
          </span>
        </div>
      </div>
      <div class="col-xs-3 no-fade text-input-format-column"></div>
      <div class="col-xs-12 no-fade" id="loc-map-container">
        <div class="col-xs-8 no-fade" id="locations">
          <div class="col-xs-12">
            <h1>Nearby Camps:</h1>
          </div>
          <div class="col-xs-12" id="location-names"></div>
        </div>
        <div class="col-xs-4 no-fade" id="map">
          <div class="col-xs-12">
            <h1>Map:</h1>
          </div>
          <div class="col-xs-12" id="maptiles"></div>
        </div>
      </div>
      <div class="col-xs-12 no-fade" id="about">
        <div class="col-xs-12">
          <h1>About:</h1>
        </div>
        <div class="col-xs-12" id="about-content">
          <p>A little text adventure. Discover the mechanics as you play. How long can you survive?</p>
          <p>This game concept is currently a very early alpha.</p>
          <p>The following link can be used to continue your adventure from elsewhere:</p>
          <p id="session-link">Generating...</p>
        </div>
      </div>
    </div>
  </body>
</html>