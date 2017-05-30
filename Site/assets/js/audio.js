
						var list = document.getElementsByTagName("script");
						var i = list.length, apiHowler = false, apiKnob = false;
						while (i--) {
							if (list[i].src == "https://www.audioclient.nl/api/v1/playerAPI/howler.js-master/howler.js") {
								apiHowler = true;
							} else if (list[i].src == "https://www.audioclient.nl/api/v1/playerAPI/volumecontrol/dist/jquery.knob.min.js") {
								apiKnob = true;
							}
						}
						if (!apiHowler) {
							var tag = document.createElement("script");
							tag.src = "https://www.audioclient.nl/api/v1/playerAPI/howler.js-master/howler.js";
							document.getElementsByTagName("head")[0].appendChild(tag);
						}
						if (!apiKnob) {
							var tag = document.createElement("script");
							tag.src = "";
							document.getElementsByTagName("head")[0].appendChild(tag);
						}
						
						$(document).ready(function(){
							$("#status_true").hide();
							$("#status_false").show();
						});
						
						setTimeout(function (){
							load();
						}, 1000);
						function load(){
							var name				= get("name");
							
							function start(){
								console.info("AudioClient >> Trying to open connection")
								try{
									ws				= null;
									setTimeout(function() {
										if (ws.readyState != 1) {
											console.info("AudioClient >> wait for connection...")
											load();
										}
									}, 5000);
									ws				= new WebSocket("ws://2503479357:8887/");
								} catch (error) {
									console.error("AudioClient >> " + error)
								}
							};
							start();
							
							var sound				= null;
							var crossfadeDuration	= 0;
							var volume_sound		= 1.0;
							var volume_effects		= 1.0;
							
							ws.onopen = function () {
							
								if (name != null) {
									console.info("AudioClient >> Connected to websocket server!");
									ws.send("name:" + name);
									console.info("AudioClient >> Sent data >> name:" + name);
									
									$("#status_true").show();
									$("#status_false").hide();
								}
							
							};
							
							ws.onmessage = function (evt) {
								if (evt.data.match("^effect:")) {
									$.getJSON( "https://www.audioclient.nl/api/v1/effects/2591499e-6f2c-45a5-b2a4-32d84c2245cd", function( data ) {
										var effect_url	= "";
										var effect_name	= "";
										$.each( data, function( key, val ) {
											if (key === evt.data.replace("effect:", "")) {
												$.each( val, function( key2, val2 ) {
													if (key2 === "name") {
														effect_name = val2;
													} else if (key2 === "url") {
														effect_url = val2;
													}
												});
												return;
											} else {
												var iscorrect = false;
												$.each( val, function( key2, val2 ) {
													if (key2 === "name") {
														if (val2 === evt.data.replace("effect:", "")) {
															effect_name = val2;
															iscorrect = true;
														}
													} else if (key2 === "url") {
														if (iscorrect) {
															effect_url = val2;
															return;
														}
													}
												});
											}
										});
										effect_url = checkurl(effect_url);
										$.ajax({
											url: "https://www.audioclient.nl/api/v1/playerAPI/filecheck.php?url=" + encodeURIComponent(effect_url),
											success: function(data){
												if (data === "200") {
													new Howl({
														urls: [effect_url]
														,html5: true
														
													}).play().fade(0, volume_effects, 1000);
													console.info("AudioClient >> Start playing: " + effect_name);
												} else {
													console.error("AudioClient >> Effect url of effect '" + effect_name + "' not correct!");
												}
											},
											error: function(data){
												console.error("AudioClient >> Can't get api information!");
											},
										})
									});
								} else if (evt.data == "stop") {
									if (sound != null) {
										var oldsound = sound;
										oldsound.fade(volume_sound, 0, crossfadeDuration, function(){oldsound.stop();oldsound.stop(); console.info("AudioClient >> stopped");});
										console.info("AudioClient >> Stop playing all sounds");
									}
								} else {
									$.getJSON( "https://www.audioclient.nl/api/v1/sounds/2591499e-6f2c-45a5-b2a4-32d84c2245cd", function( data ) {
										var sound_url	= "";
										var sound_name	= "";
										$.each( data, function( key, val ) {
											if (key === evt.data.replace("effect:", "")) {
												$.each( val, function( key2, val2 ) {
													if (key2 === "name") {
														sound_name = val2;
													} else if (key2 === "url") {
														sound_url = val2;
													}
												});
												return;
											} else {
												var iscorrect = false;
												$.each( val, function( key2, val2 ) {
													if (key2 === "name") {
														if (val2 === evt.data.replace("effect:", "")) {
															sound_name = val2;
															iscorrect = true;
														}
													} else if (key2 === "url") {
														if (iscorrect) {
															sound_url = val2;
															return;
														}
													}
												});
											}
										});
										sound_url = checkurl(sound_url);
										$.ajax({
											url: "https://www.audioclient.nl/api/v1/playerAPI/filecheck.php?url=" + encodeURIComponent(sound_url),
											success: function(data){
												if (data === "200") {
													if (sound != null) {
														var oldsound = sound;
														oldsound.fade(volume_sound, 0, crossfadeDuration, function(){oldsound.stop();oldsound.stop(); console.info("AudioClient >> stopped");});
													}
													var newsound = new Howl({
														urls: [sound_url]
														,html5: true
														
														
													}).play().fade(0, volume_sound, 1000);
													if (newsound != sound) {
														sound = newsound;
														sound.play().fade(0, volume_sound, crossfadeDuration);
														
														console.info("AudioClient >> Start playing: " + sound_name);
													} else {
														console.info("AudioClient >> '" + sound_name + "' Already playing");
													}
												} else {
													console.error("AudioClient >> Sound url of sound '" + sound_name + "' not correct!");
												}
											},
											error: function(data){
												console.error("AudioClient >> Can't get api information!");
											},
										})
									});
								}
								
								if (evt.data == "stop") {
									$("#audiotitle").text("---");
								} else {
									if (!evt.data.match("^effect:")) {
										$("#audiotitle").text(evt.data);
									}
								}
							};
							
							ws.onclose = function () {
								console.error("AudioClient >> Connection closed!")
								setTimeout(function(){
									start();
								}, 5000);
								
								$("#status_true").hide();
								$("#status_false").show();
							};
							
							ws.onerror = function (err) {
								console.error("AudioClient >> " + err);
								
								if (ws.readyState != 1) {
									setTimeout(function(){
										start();
									}, 5000);
								}
								
								$("#status_true").hide();
								$("#status_false").show();
							};
							
							function get(name){
							   if(name=(new RegExp("[?&]" + encodeURIComponent(name) + "=([^&]*)")).exec(location.search))
								  return decodeURIComponent(name[1]);
							}
							
							function checkurl(url) {
								url = url.replace("://clyp.it", "://a.clyp.it");
								if (url.indexOf("a.clyp.it") >= 0) {
									if (!url.endsWith(".mp3")) {
										url = url + ".mp3";
									}
								}
								console.error("AudioClient >> TEST >> Url: " + url)
								return url;
							}
							
							/*********************
								Controls
							*********************/
							$(document).ready(function(){
								$("#name").text(name);
								$("#skin").attr("src", "http://api.codexgaming.nl/MinecraftAvatar/face.php?u=" + name + "&s=250&v=f");
								$("#skinBG").css("background-image", "url('http://api.codexgaming.nl/MinecraftAvatar/face.php?u=" + name + "&s=250&v=f')");
								$("#skin3D").css("background-image", "url('http://api.codexgaming.nl/MinecraftAvatar/3d.php?user=" + name + "&ratio=7&aa=true')");
								$("#skinBG3D").css("background-image", "url('http://api.codexgaming.nl/MinecraftAvatar/3d.php?user=" + name + "&ratio=7&aa=true')");
								
								$('#volumecontrol_sounds').slider({
									formatter: function(v) {
										if (sound != null) {
											sound.volume(v / 100);
										}
										volume_sound = v / 100;
										return 'Sounds: ' + v;
									}
								});
								$("#volumecontrol_effects").slider({
									formatter: function(v) {
										volume_effects = v / 100;
										
										return 'Effects:' + v;
									}
								});
							});
						};
function update() {
	$("#skin").attr("src", "http://api.codexgaming.nl/MinecraftAvatar/face.php?u=" + name + "&s=250&v=f");
}			