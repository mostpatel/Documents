
      var currentUser;






      // Set the configuration for your app
      // TODO: Replace with your project's config object
      var config = {
        apiKey: "AIzaSyCVa5aEoKbZQjYvhkvPav-XtGsL3F3E7zs",
        authDomain: "v-carry.firebaseapp.com",
        databaseURL: "https://v-carry.firebaseio.com",
        storageBucket: "",
        messagingSenderId: "581157640018"
      };
      firebase.initializeApp(config);







      var provider = new firebase.auth.GoogleAuthProvider();
      provider.addScope('https://www.googleapis.com/auth/plus.login');







      function tryLogin(){
        firebase.auth().signInWithRedirect(provider);
      }







      firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
          // User is signed in.
         
        } else {
          // No user is signed in.
          alert("you are not logged in");
		  tryLogin();
        }
      });







      firebase.auth().getRedirectResult().then(function(result) {
        if (result.credential) {
          // This gives you a Google Access Token. You can use it to access the Google API.
          var token = result.credential.accessToken;
          // ...
        }
        // The signed-in user info.

      }).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
        // ...
      });







      function writeData() {
        token=(new Date()).getTime();
        id="New request";
        firebase.database().ref('request/' + token).set({
          id: id
        });
      }
	  
	  function writeTripStartData(trip_id,from_location,to_location) {
       
        firebase.database().ref('request/' + trip_id).set({
          id: trip_id,
		  from: from_location,
		  to: to_location
        });
		
      }
	  
	    function writeTripStartData(email,from_location,to_location) {
       
        firebase.database().ref('request/' + trip_id).set({
          id: trip_id,
		  from: from_location,
		  to: to_location
        });
		
      }
	  
	  
	  var totalRequest;
	  
	  var requestCount=0;
	  
	  firebase.database().ref('accepted').once('value', function(snapshot) {
		  
 totalRequest=snapshot.numChildren();
 
 


 firebase.database().ref('accepted').on('child_added', function(snapshot,prevChildKey) {
 
 

	 
	 requestCount++;
	 
	 
 if(requestCount>totalRequest){
	
	totalRequest++;
	var driver_email = snapshot.val().email;
	var driver_latlong = snapshot.val().location;
	var driver_name = snapshot.val().name;
	var driver_accept_time = snapshot.val().time;
	var trip_id =  snapshot.val().trip_id;
		$.notificationcenter.alert({'text':'Driver :'+driver_name+' Has Accepted Trip!', 'title':'Driver Alert'}, 'sticky', function(notif) { 
		 window.location.href=document.web_root+"admin/customer/trip/index.php?view=updateDriver&id="+trip_id+"&driver_email="+driver_email+"&loc="+driver_latlong+"&time="+driver_accept_time;
		 });
	
	 
	
	 
	 
 }
});
 
});




// JavaScript Document