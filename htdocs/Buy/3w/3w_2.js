//--------------------------------------------------------------------
//カメラ画像表示
//--------------------------------------------------------------------
navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia ;
window.URL = window.URL || window.webkitURL ;
//--------------------------------------------------------------------
function initialize() {
	navigator.getUserMedia(
		{audio: true, video: true},
		function(stream) {
			var video = document.getElementById('video');
			//video.src = URL.createObjectURL(stream);
			video.srcObject = stream;
			video.play();
			renderStart();
		},
		function(error) {
			console.error(error);
		}
	);
}
//--------------------------------------------------------------------
function renderStart() {
	var video = document.getElementById('video');
	var buffer = document.createElement('canvas');
	var display = document.getElementById('display_canvas');
	var bufferContext = buffer.getContext('2d');
	var displayContext = display.getContext('2d');
	
	var render = function() {
		requestAnimationFrame(render);
		var width = video.clientWidth;
		var height = video.clientHeight;
		if (width == 0 || height == 0) {return;}
		buffer.width = display.width = width;
		buffer.height = display.height = height;
		bufferContext.drawImage(video, 0, 0, width, height);//new
		
		// カメラ画像のデータ
		var src = bufferContext.getImageData(0, 0, width, height);
		// 空のデータ（サイズはカメラ画像と一緒）
		var dest = bufferContext.createImageData(buffer.width, buffer.height);

		//ここからメインの処理 +++++++++++++++++++++++++++++++++++
		for (var i = 0; i < dest.data.length; i += 4) {
			dest.data[i + 0] = src.data[i + 0]; // Red
			dest.data[i + 1] = src.data[i + 1]; // Green
			dest.data[i + 2] = src.data[i + 2]; // Blue
			dest.data[i + 3] = 255;             // Alpha
		}
		//ここまでメインの処理 +++++++++++++++++++++++++++++++++++

    	displayContext.putImageData(dest, 0, 0);
	};
	render();
}
//--------------------------------------------------------------------
window.addEventListener('load', initialize);
//--------------------------------------------------------------------
