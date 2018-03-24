<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
// OUTPUT
header( 'Content-Type: text/html' );
header( 'Cache-Control: no-cache, no-store, must-revalidate');
?>
<!DOCTYPE html>
<html lang="en" manifest="<?php echo plugins_url( 'index.appcache', __FILE__ ); ?>">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>futusign Overlay Clock</title>
  <style>
    html, body {
      margin: 0px;
      font-family: sans-serif;
    }
    div {
      box-sizing: border-box;
    }
		#frame {
			position: fixed;
			display: flex;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
		}
    #frame__clock {
      display: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
	<div id="frame">
  	<div id="frame__clock"></div>
	</div>
  <script>
    var ENDPOINT = '/fs-oc-endpoint';
    var drift = 0;
    var parseQueryString = function() {
      var parsed = {};
      var qs = window.location.search;
      if (!qs) {
        return parsed;
      }
      var qsArray = qs.substr(1).split('&');
      for (var i = 0; i < qsArray.length; ++i) {
        var parameterArray = qsArray[i].split('=', 2);
        if (parameterArray.length === 1) {
          parsed[parameterArray[0]] = '';
        } else {
          parsed[parameterArray[0]] =
          decodeURIComponent(parameterArray[1].replace(/\+/g, ' '));
        }
      }
      return parsed;
    };
    window.addEventListener('message', function(message) {
      switch (message.data.type) {
        case 'MSG_TIME':
          if (message.data.value !== undefined) {
            drift = message.data.value;
          }
          break;
        default:
      };
    });
    document.addEventListener("DOMContentLoaded", function() {
			var frameEl = document.getElementById('frame');
      var clockEl = document.getElementById('frame__clock');
      var parsed = parseQueryString();
			var justify;
			var align;
      switch (parsed.position) {
        case 'upper-left':
					justify = 'flex-start';
					align = 'flex-start';
					break
        case 'upper-middle':
				case 'upper':
					justify = 'center';
					align = 'flex-start';
					break
        case 'upper-right':
					justify = 'flex-end';
					align = 'flex-start';
					break
        case 'middle-left':
				case 'left':
					justify = 'flex-start';
					align = 'center';
					break
        case 'middle-middle':
				case 'middle-row':
				case 'middle-column':
					justify = 'center';
					align = 'center';
					break
        case 'middle-right':
				case 'right':
					justify = 'flex-end';
					align = 'center';
					break
        case 'lower-left':
					justify = 'flex-start';
					align = 'flex-end';
					break
        case 'lower-middle':
				case 'lower':
					justify = 'center';
					align = 'flex-end';
					break
        case 'lower-right':
					justify = 'flex-end';
					align = 'flex-end';
					break
        default:
					justify = 'center';
					align = 'center';
      }
			frameEl.style.justifyContent = justify;
			frameEl.style.alignItems = align;
      // ASSUME CHROME
      fetch(ENDPOINT)
        .then(function(response) {
          return response.json();
        })
        .then(function(json) {
          styleClock(parseInt(json.size), json.theme);
          localStorage.setItem('futusign_oc_size', json.size);
          localStorage.setItem('futusign_oc_theme', json.theme);
        })
        .catch(function() {
          var size = localStorage.getItem('futusign_oc_size');
          var theme = localStorage.getItem('futusign_oc_theme');
          size = size !== null ? size : '10';
          theme = theme !== null ? theme : 'dark';
          styleClock(parseInt(size), theme);
         }) ;
      function styleClock(size, theme) {
        clockEl.style.paddingLeft = size.toString() + 'px';
        clockEl.style.paddingRight = size.toString() + 'px';
        clockEl.style.paddingTop = (size / 2).toString() + 'px';
        clockEl.style.paddingBottom = (size / 2).toString() + 'px';
        clockEl.style.backgroundColor = theme === 'dark' ? 'rgb(0,0,0)' : 'rgb(255,255,255)';
        clockEl.style.color = theme === 'dark' ? 'white' : 'black';
        clockEl.style.fontSize = size.toString() + 'px';
        clockEl.style.display = 'block';
      }
      updateClock();
      function updateClock() {
        var localTime = (new Date()).getTime();
        var now = new Date(localTime - drift);
        var h = now.getHours();
        var p = h > 12 ? 'PM' : 'AM';
        h = h > 12 ? h - 12 : h;
        var m = now.getMinutes();
        m = m < 10 ? '0' + m : m;
        clockEl.innerHTML =
          h + ":" + m + ' ' + p;
        setTimeout(updateClock, 1000);
      }
      updateTime();
      function updateTime() {
        window.parent.postMessage({
          type: 'MSG_TIME',
        }, '*');
        setTimeout(updateTime, 1000 * 60 * 60);
      }
    });
  </script>
</body>
</html>
