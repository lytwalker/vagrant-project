(function($){
    function RESTclient(endpoint, username, password){
        this.endpoint = endpoint;
        this.username = username;
        this.password = password;
        this.sessionToken = null;
    }
    
    RESTclient.prototype.login = function(){
        $.ajax({
            type: "POST",
            dataType: 'json',
            crossOrigin: true,
            url: this.endpoint + "sessions/HTTP/1.1",
            data: {username: this.username, password: this.password},
            success: function(response){
                console.log(response);
            }
        });
    }
    
    window.RESTclient = window.RESTclient || RESTclient;
})(jQuery);