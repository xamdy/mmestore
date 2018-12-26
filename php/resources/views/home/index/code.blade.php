<!DOCTYPE HTML>
<html>
<head>  
    <meta http-equiv="content-type" content="text/html" />  
    <meta name="author" content="https://www.baidu.com" />  
    <title>websocket test</title>  
</head>

<body>
<input id="msg" type="text">
<button id="connect" onclick="Connect();">Connect</button>
<button id="send" onclick="Send();">Send</button>
<button id="close" onclick="Close();">Close</button>
<audio id="audio" controls="controls" >
    <source src="bhs.mp3" type="audio/mp3" />
</audio>
 <script>
            var user_id = "22";
            var stores_id = "23";
            var audio = document.getElementById('audio');
            
            var wsServer = 'ws://47.94.158.154:2111';
            var websocket = new WebSocket(wsServer);

            //onopen监听连接打开
            websocket.onopen = function (evt) {
                alert(1);
                console.log("connect");
            };
              //监听连接关闭
           websocket.onclose = function (evt) {
                alert(2);
               console.log("Disconnected");
           };
            //onmessage 监听服务器数据推送
            websocket.onmessage = function (evt) {
                 var message = JSON.parse(evt.data);
                 console.log(message);
                alert(33);
                 if(message.code == 1000){
                    audio.play();
                 }else if(message.code == 10000){
                    var json  = {"userLogin":user_id};
                    var data = JSON.stringify(json);
                    alert(json);
                        websocket.send(data);
                 }

            };
           websocket.onerror = function (evt, e) {
               console.log('Error occured: ' + evt.data);
           };

    // window.onload = function () {
    //     var audio = document.getElementById('audio');
    //     //播放就调用这个方法
    //     audio.play()
    // }

</script>
</body>
</html>
