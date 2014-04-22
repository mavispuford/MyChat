angular.module('MyChatApp',['ngSanitize'])

//function RouteCtrl($route) {
//
//    var self = this;
//
//    $route.when('/wines', {template:'tpl/welcome.html'});
//
//    $route.when('/wines/:wineId', {template:'tpl/wine-details.html', controller:WineDetailCtrl});
//
//    $route.otherwise({redirectTo:'/wines'});
//
//    $route.onChange(function () {
//        self.params = $route.current.params;
//    });
//
//    $route.parent(this);
//
//    this.addWine = function () {
//        window.location = "#/wines/add";
//    };
//
//}
//
//function WineListCtrl(Wine) {
//
//    this.wines = Wine.query();
//
//}
//
//function WineDetailCtrl(Wine) {
//
//    this.wine = Wine.get({wineId:this.params.wineId});
//
//
//    this.saveWine = function () {
//        if (this.wine.id > 0)
//            this.wine.$update({wineId:this.wine.id});
//        else
//            this.wine.$save();
//        window.location = "#/wines";
//    }
//
//    this.deleteWine = function () {
//        this.wine.$delete({wineId:this.wine.id}, function() {
//            alert('Wine ' + wine.name + ' deleted')
//            window.location = "#/wines";
//        });
//    }
//
//}

function MyChatCtrl($scope,$http, $location, $anchorScroll){
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
        {text: "*LOL*", path: "images/emoticons/what.png", width: 32, height: 32, class:"emoticon"},
        {text: ":O", path: "images/emoticons/horror.png", width: 32, height: 32, class:"emoticon"},
        {text: "*h*", path: "images/emoticons/black_heart.png", width: 32, height: 32, class:"emoticon"},
        {text: "*hh*", path: "images/emoticons/red_heart.png", width: 32, height: 32, class:"emoticon"}
    ];

    $http.get("/api/messages")
        .success(function(data, status, headers, config){
            console.log("messages retrieved successfully");
            $scope.messages = data;
//            console.log($scope.messages);

            // Scroll to the newest message
            $location.hash('msg_id-' + $scope.messages[$scope.messages.length - 1].id);
            $anchorScroll();
        });

    $scope.addMessage = function() {
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
            if (outputText.indexOf(emoticons[i].text) > -1) {
                var icon = emoticons[i];
                var re = new RegExp(escapeRegExp(icon.text), 'g');
                outputText = outputText.replace(re, "<span class=\"" + icon.class + "\"><img src=\"" + icon.path + "\" width=\"" + icon.width + "\" height=\"" + icon.height + "\"></span>");
            }
        }

        return outputText;
    }

    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }
}