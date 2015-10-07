
var app = angular.module("bhimgeete", ['ui.bootstrap','ngRoute' ,'djds4rce.angular-socialshare']);

app.filter('startFrom', function() {
    return function(input, start) {
        if (!input || !input.length) { return; }
        start = +start; //parse to int
        return input.slice(start);
    }
});

app.config(['$routeProvider','$locationProvider', function($routeProvider,$locationProvider){
  // routeProvider config, allows minification
    //$routeProvider.when('/',{title:'index',templateUrl:'modules/test.html',controller: 'HomeCtrl'})
  //  $routeProvider.when('/home/:id',{title:'home',templateUrl:'modules/home.html',controller: 'AlbmCtrl'})
    // $routeProvider.when('/albums',{title:'album',templateUrl:'modules/album.html',controller: 'HomeCtrl'})
    // $routeProvider.when('/contact',{title:'contact',templateUrl:'modules/test.html',controller: 'HomeCtrl'})
     $routeProvider.when('/',{title:'contact',templateUrl:'modules/contact.html',controller: 'contactCtrl'})
    $routeProvider.otherwise({redirectTo:'/'})
 //  $locationProvider.html5Mode(true);

}]);
app.run(function($FB){
  $FB.init('1617467908502490');
});

app.controller('HomeCtrl', function($scope, $http,$timeout,$rootScope)
{
    $scope.albums={};
    $scope.singers={};
    $scope.categories={};
    $scope.songs={}; 
    $scope.totalalbum = {};
    $scope.currentPage = 1; //current page
    $scope.entryLimit = 10;
    $http.get('index.php/home/alldt')
    .success(function(data){
    $scope.albums=data.albums;
    $scope.singers=data.singers;
    $scope.categories=data.categories;
    $scope.songs=data.songs;  
    $scope.filteredItems_songs = $scope.songs.length; //Initially for no filter  
    $scope.totalItems_songs = $scope.songs.length;
    $scope.filteredItems = $scope.albums.length; //Initially for no filter  
    $scope.totalItems = $scope.albums.length;
    $scope.music = null;
    });
     $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
            
        }, 10);
    };$scope.songfilter = function() {
        $timeout(function() { 
            $scope.filteredItems_songs = $scope.filtered.length;
            
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    $scope.test = function(user)
    {
      $scope.song={};
        $http.get('index.php/home/addToPlay/'+user)
    .success(function(data){          
            if(data.success)
            {
                $rootScope.$broadcast('playMusic',data.song);
            }
    });
    }
    $scope.getShareCount= function (i,j,k,url) {
        apiUtility.executeApi('GET','http://graph.facebook.com/?id='+url)
            .then(function(data)
            {
                $scope.GalleryModel.gallery[i].events[j].photos[k].shared=(data.shares!=undefined?data.shares:0);
            });
}
});
app.controller('musicPlayerCtrl',function($scope){
    $scope.currentMusic = null;
    $scope.$on('playMusic', function(event,music){
        $scope.currentMusic = music;
    });
    

});app.controller('contactCtrl',function ($scope, $http) {
    $scope.result = 'hidden'
    $scope.resultMessage;
    $scope.formData; //formData is an object holding the name, email, subject, and message
    $scope.submitButtonDisabled = false;
    $scope.submitted = false; //used so that form errors are shown only after the form has been submitted
    $scope.submit = function(contactform) {
        $scope.submitted = true;
        $scope.submitButtonDisabled = true;
        if (contactform.$valid) {
            $http({
                method  : 'POST',
                url     : 'index.php/home/contactus',
                data    : $.param($scope.formData),  //param method from jQuery
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  //set the headers so angular passing info as form data (not request payload)
            }).success(function(data){
                console.log(data);
                if (data.success) { //success comes from the return json object
                    $scope.submitButtonDisabled = true;
                    $scope.resultMessage = data.message;
                    $scope.result='bg-success';
                } else {
                    $scope.submitButtonDisabled = false;
                    $scope.resultMessage = data.message;
                    $scope.result='bg-danger';
                }
            });
        } 
    }
});
app.controller('AlbmCtrl',function($scope,$routeParams,$http,$rootScope){
   var id=$routeParams.id;
    $http.get('index.php/home/albumSongs/'+id)
    .success(function(data){
        console.log(data.AlbumSongs);
    $scope.albumsdata=data.AlbumSongs;
    $scope.albums=data.albums;
    $scope.selectalbuminfo=data.selectalbuminfo;
    });
     $scope.test = function(user)
    {
      $scope.song={};
        $http.get('index.php/home/addToPlay/'+user)
    .success(function(data){          
            if(data.success)
            {
                $rootScope.$broadcast('playMusic',data.song);
            }
    });
    }
        $scope.getShareCount= function (i,j,k,url) {
        apiUtility.executeApi('GET','http://graph.facebook.com/?id='+url)
            .then(function(data)
            {
                $scope.GalleryModel.gallery[i].events[j].photos[k].shared=(data.shares!=undefined?data.shares:0);
            });
} 
    });