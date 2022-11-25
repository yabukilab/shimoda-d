//--------------------------------------------------------------------
//画像ファイル表示
//--------------------------------------------------------------------
//グローバル変数
var std_w=400;
var std_h=300;
var n_w;
var n_h;
var xposi=300;
var img = new Image();
//--------------------------------------------------------------------
//メインプログラム
window.onload = function main() {
  var dataUrl;
  var objFile = document.getElementById("selfile");
  var display = document.getElementById('display_canvas');
  var displayContext = display.getContext('2d');
  var c_w = display.width;
  var c_h = display.height;

  objFile.addEventListener(
     "change",
     function(evt) {
     	dataUrl = URL.createObjectURL(objFile.files[0]);
        img.onload = function(){
　　　　	//画像サイズ調整
	  		sub_size();
　　　　	//画像表示
        	displayContext.clearRect(0, 0, c_w, c_h);//クリア
        	displayContext.drawImage(img, 0, 0, img.width, img.height, 0, 0, n_w, n_h);
　　　　	//画像処理
	  		sub_imageproc();
        };
        img.src = dataUrl;
     },
     false
  );
}
//--------------------------------------------------------------------
//画像処理サブプログラム
function sub_imageproc() {
  var buffer = document.createElement('canvas');
  var display = document.getElementById('display_canvas');
  var bufferContext = buffer.getContext('2d');
  var displayContext = display.getContext('2d');

  var render = function() {
    var width=n_w;
    var height=n_h;
    buffer.width=width;
    buffer.height=height;
    var src = displayContext.getImageData(0, 0, width, height);
    var dest = bufferContext.createImageData(buffer.width, buffer.height);

    //ここからメインの処理 +++++++++++++++++++++++++++++++++++
    for (var i = 0; i < dest.data.length; i += 4) {
       dest.data[i + 0] = src.data[i + 0];// Red
       dest.data[i + 1] = src.data[i + 1];// Green
       dest.data[i + 2] = src.data[i + 2];// Blue
       dest.data[i + 3] = 255;            // Alpha
    }
    //ここまでメインの処理 +++++++++++++++++++++++++++++++++++

    //結果表示
    displayContext.putImageData(dest, xposi, 0);

  };
　//画像処理開始
  render();
}
//--------------------------------------------------------------------
//画像サイズ調整サブプログラム
function sub_size() {
  var w_mag=img.width/std_w;
  var h_mag=img.height/std_h;

  if((img.width <= std_w)&&(img.height <= std_h)){
    n_w=img.width;
    n_h=img.height;
  }
  else if((img.width > std_w)&&(img.height <= std_h)){
    n_w=img.width / w_mag;
    n_h=img.height / w_mag;
  }
  else if((img.width <= std_w)&&(img.height > std_h)){
    n_w=img.width / h_mag;
    n_h=img.height / h_mag;
  }
  else if(w_mag > h_mag){
    n_w=img.width / w_mag;
    n_h=img.height / w_mag;
  }
  else{
    n_w=img.width / h_mag;
    n_h=img.height / h_mag;
  }
}
//--------------------------------------------------------------------
