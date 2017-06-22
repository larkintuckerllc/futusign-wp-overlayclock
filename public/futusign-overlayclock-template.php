<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
// SETTINGS
$futusign_oc_options = get_option( 'futusign_overlayclock_option_name' );
$futusign_oc_size = $futusign_oc_options !== false && array_key_exists( 'size', $futusign_oc_options ) ? $futusign_oc_options['size'] : '10';
$futusign_oc_theme = $futusign_oc_options !== false && array_key_exists( 'theme', $futusign_oc_options ) ? $futusign_oc_options['theme'] : 'dark';
// OUTPUT
header( 'Content-Type: text/html' );
header( 'Cache-Control: no-cache, no-store, must-revalidate');
?>
<!DOCTYPE html>
<html lang="en">
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
      padding-left: <?php echo $futusign_oc_size ?>px;
      padding-right: <?php echo $futusign_oc_size ?>px;
      padding-top: <?php echo strval(intval($futusign_oc_size, 10) / 2) ?>px;
      padding-bottom: <?php echo strval(intval($futusign_oc_size, 10) / 2) ?>px;
      background-color: <?php echo $futusign_oc_theme === 'dark' ? 'rgba(0,0,0,0.7)' : 'rgba(255,255,255,0.7)'; ?>;
      color: <?php echo $futusign_oc_theme === 'dark' ? 'white' : 'black'; ?>;
      font-size: <?php echo $futusign_oc_size ?>px;
      font-weight: bold;
    }
  </style>
</head>
<body>
	<div id="frame">
  	<div id="frame__clock"></div>
	</div>
  <script>
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
			let justify;
			let align;
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
      var updateClock = function() {
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
      updateClock();
      var updateTime = function() {
        window.parent.postMessage({
          type: 'MSG_TIME',
        }, '*');
        setTimeout(updateTime, 1000 * 60 * 60);
      }
      updateTime();
    });
  </script>
</body>
</html>
