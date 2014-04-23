angular.module('MyChatApp',['ngSanitize'])

function MyChatCtrl($scope,$http, $location, $anchorScroll, $timeout, $filter){
    $location.hash('');
    $scope.messages = [];
    var snd_nyan = new Audio("resources/nyan_cut.mp3");
    var snd_msg = new Audio("resources/water-droplet-1.mp3");

    var emoticons = [
        {text: ":)", path: "images/emoticons/big_smile.png", width: 32, height: 32, class:"emoticon"},
        {text: ":(", path: "images/emoticons/unhappy.png", width: 32, height: 32, class:"emoticon"},
        {text: "8|", path: "images/emoticons/amazing.png", width: 32, height: 32, class:"emoticon"},
        {text: ":|", path: "images/emoticons/what.png", width: 32, height: 32, class:"emoticon"},
        {text: ":.", path: "images/emoticons/nothing.png", width: 32, height: 32, class:"emoticon"},
        {text: "$)", path: "images/emoticons/money.png", width: 32, height: 32, class:"emoticon"},
        {text: ":P", path: "images/emoticons/grimace.png", width: 32, height: 32, class:"emoticon"},
        {text: ":D", path: "images/emoticons/exciting.png", width: 32, height: 32, class:"emoticon"},
        {text: ":'(", path: "images/emoticons/cry.png", width: 32, height: 32, class:"emoticon"},
        {text: ">:(", path: "images/emoticons/anger.png", width: 32, height: 32, class:"emoticon"},
        {text: ":E", path: "images/emoticons/electric_shock.png", width: 32, height: 32, class:"emoticon"},
        {text: "*n*", path: "images/emoticons/nyan_cat.png", width: 64, height: 45, class:"nyancat"},
        {text: "*LOL*", path: "images/emoticons/haha.png", width: 32, height: 32, class:"emoticon"},
        {text: ":O", path: "images/emoticons/horror.png", width: 32, height: 32, class:"emoticon"},
        {text: "*h*", path: "images/emoticons/black_heart.png", width: 32, height: 32, class:"emoticon"},
        {text: "*hh*", path: "images/emoticons/red_heart.png", width: 32, height: 32, class:"emoticon"}
    ];

    $scope.refreshChat = function() {
        if ($scope.messages === undefined || $scope.messages.length == 0){
            $http.get("/api/messages")
                .success(function(data, status, headers, config){
                    if (data !== undefined && $scope.messages != data) {
                        console.log(data.length + " messages retrieved successfully");

                        $scope.messages = data;

                        scrollToNewestMessage();
                    }
                });
        }
        else if ($scope.messages.length > 0) {
            $http.get("/api/messages/" + $scope.messages[$scope.messages.length - 1].id)
                .success(function(data, status, headers, config){
                    if (data !== undefined && data.length > 0) {
                        console.log(data.length + " messages retrieved successfully");

                        data.forEach(function(msg) {
                            // Look for any duplicate messages
                            var found = $filter('filter')($scope.messages, {id: msg.id}, true);

                            // Make sure the message wasn't just inserted and we are out of sync
                            if (!found.length) {
                                $scope.messages.push({id: msg.id, username: msg.username, contents: msg.contents, msg_time: msg.msg_time});
                            }
                        });

                        scrollToNewestMessage();
                    }
                });
        }

        var mytimeout = $timeout($scope.refreshChat, 500);
    }

    var mytimeout = $timeout($scope.refreshChat,0);

    $scope.addMessage = function() {
        // Default the username to Anonymous if empty
        if (!$scope.username)
        {
            $scope.username = 'Anonymous';
        }

        $http.post("/api/messages",{'username': $scope.username, 'contents': $scope.contents})
            .success(function(data, status, headers, config){
                console.log("inserted Successfully: " + data.contents);
                $scope.messages.push(data);

                // Scroll to the newly-added message
                $location.hash('msg_id-' + data.id);
                $anchorScroll();

                // Clear the user's message box
                $scope.contents = '';

                // Play sound effect
                if (data.contents.indexOf("*n*") > -1){
                    snd_nyan.play();
                }
                else {
                    snd_msg.play();
                }
            });
    }

    $scope.getMessageText = function(message) {
        var outputText = message.contents;

        for(var i = 0; i < emoticons.length; i++) {
            // If there are any emoticons
            if (outputText.indexOf(emoticons[i].text) > -1) {
                var icon = emoticons[i];
                var re = new RegExp(escapeRegExp(icon.text), 'g');
                outputText = outputText.replace(re, "<span class=\"" + icon.class + "\"><img src=\"" + icon.path + "\" width=\"" + icon.width + "\" height=\"" + icon.height + "\"></span>");
            }
        }

        return outputText;
    }

    $scope.deleteMessage = function(message) {
        $http.delete("/api/messages/" + message.id)
            .success(function(data, status, headers, config){
                console.log("Deleted " + message.id + " Successfully: ");

                // Maybe implement $scope.messages updating code here, along with somehow alerting other clients
            });
    }

    // Helps to escape any characters in a string before being passed into a new RegExp object
    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

    // Scrolls to the newest message in the chat
    function scrollToNewestMessage() {
        // Scroll to the newest message
        $location.hash('msg_id-' + $scope.messages[$scope.messages.length - 1].id);
        $anchorScroll();
    }
}