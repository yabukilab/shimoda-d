//--------------------------------------------------------------------
//カメラ画像表示
//--------------------------------------------------------------------
//変数
var img = new Image();
var temp;
var templateWidth;
var templateHeight;
var target;
var targetWidth;
var targetHeight;
//--------------------------------------------------------------------
navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia ;
window.URL = window.URL || window.webkitURL ;
//--------------------------------------------------------------------
function initialize() {

	//変数
	var dataUrl;
	var objFile = document.getElementById("selfile");
	var display = document.getElementById('display_canvas2');
	var displayContext = display.getContext('2d');

	//テンプレート
	objFile.addEventListener(
		"change",
		function(evt) {
			dataUrl = URL.createObjectURL(objFile.files[0]);
			img.onload = function(){
			displayContext.clearRect(0, 0, display.width, display.height);
			displayContext.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);
			templateWidth=img.width;
			templateHeight=img.height;
			temp = displayContext.createImageData(templateWidth, templateHeight);
			for (var i = 0; i < temp.data.length; i += 4) {
				var r = temp.data[i + 0];
				var g = temp.data[i + 1];
				var b = temp.data[i + 2];
				var gray = 0.3 * r + 0.6 * g + 0.1 * b;
				temp.data[i + 0] = gray;
				temp.data[i + 1] = gray;
				temp.data[i + 2] = gray;
				temp.data[i + 3] = 255;
			}
		};
		img.src = dataUrl;
		},
		false
	);

	//カメラ
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

		targetWidth=width;
		targetHeight=height;

		//ここからメインの処理 +++++++++++++++++++++++++++++++++++

		//定義
		//var t_x;				//テンプレート画像のx座標
		//var t_y;				//テンプレート画像のy座標
		//var temp;				//テンプレート画像の値
		//var templateWidth;	//テンプレート画像の幅
		//var templateHeight;	//テンプレート画像の高さ
		//var x;				//ターゲット画像のx座標
		//var y;				//ターゲット画像のy座標
		//var target;			//ターゲット画像の値
		//var targetWidth;		//ターゲット画像の幅
		//var targetHeight;		//ターゲット画像の高さ
		//var THR = 0.5;		//しきい値（この値を0.0から1.0の範囲で調節する）

		//グレースケール画像
		for (var i = 0; i < dest.data.length; i += 4) {
       		dest.data[i + 0] = src.data[i + 0];// Red
       		dest.data[i + 1] = src.data[i + 1];// Green
       		dest.data[i + 2] = src.data[i + 2];// Blue
			dest.data[i + 3] = 255;  // Alpha
		}

		//図形表示設定
		displayContext.putImageData(dest, 0, 0);

		//（仮）テンプレート画像サイズ

		//テンプレート画像の原点の移動

		//ここまでメインの処理 +++++++++++++++++++++++++++++++++++

	};
	render();
}
//--------------------------------------------------------------------
window.addEventListener('load', initialize);
//--------------------------------------------------------------------
