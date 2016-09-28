var Client = function () {

    //用户对象
    this.user = {
        uid: 0,                 //通信服务全局ID
        id: 0,                  //用户id
        pname: "",               //用户名
        role: "tv-app-chat",    //角色 tv-app,tv-app-chat,lx,web-client,daobo,board
        room: "default"         //房间号
    };
    this.user.init=function(id,pname,roomid){
        this.id = id;
        this.pname = pname;
        this.room= roomid;
        if(this.id==undefined){
            alert("参数不正确");
            return false;
        }
        if(this.pname==undefined){
            alert("参数不正确");
            return false;
        }
        if(this.room==undefined){
            alert("参数不正确");
            return false;
        }
        return true;
    };

    this.socket;



    /**
     * socket连接初始化
     * @constructor
     */
    this.init = function (getMessageCallBack) {
        socket = io("http://edu.iguangj.com:8888");

        socket.on("connection",function(data){
            console.log("socket.id",socket.id);
            console.log(data);
            console.log(socket.id);
        });
        socket.on('connectsuccess', function (data) {
            console.log("connectsuccess", data);
            socket.emit('usersignin', client.user);
        });

        socket.on('userLoginSuccess', function (data) {
            console.log("userLoginSuccess", data);
            console.log("socket.id", socket.id);
        });
        socket.on('getchatmsg', getMessageCallBack);
    };

    /**
     * 发送消息
     * @param msg
     */
    this.sendChatMsg = function (msg) {
        var data = {};
        data.type = 0;
        data.to = '';
        data.content = msg;
        socket.emit('sendchatmsg', data);
    };

}


/**
 * URL HELPER
 */
var Url = {
    /**
     * 获取URL参数
     * @returns {{}}
     */
    getArgs: function () {
        var args = {};
        var query = location.search.substring(1);
        // Get query string
        var pairs = query.split("&");
        // Break at ampersand
        for (var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            // Look for "name=value"
            if (pos == -1) continue;
            // If not found, skip
            var argname = pairs[i].substring(0, pos);// Extract the name
            var value = pairs[i].substring(pos + 1);// Extract the value
            value = decodeURIComponent(value);// Decode it, if needed
            args[argname] = value;
            // Store as a property
        }
        return args;// Return the object
    }
}
