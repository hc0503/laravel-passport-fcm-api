/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
	apiKey: "AIzaSyBpgwVTndKUTULOlM_C2Mal83zhTSlaAHY",
	authDomain: "ugigs-2d93f.firebaseapp.com",
	databaseURL: "https://XXXX.firebaseio.com",
	projectId: "ugigs-2d93f",
	storageBucket: "ugigs-2d93f.appspot.com",
	messagingSenderId: "298698754445",
	appId: "1:298698754445:web:a7aea079c05960fa4915c4",
	measurementId: "G-62FDG03DHC"
});
  
/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
	console.log(
		"[firebase-messaging-sw.js] Received background message ",
		payload,
	);
	/* Customize notification here */
	const notificationTitle = "Background Message Title";
	const notificationOptions = {
		body: "Background Message body.",
		icon: "/itwonders-web-logo.png",
	};

	return self.registration.showNotification(
		notificationTitle,
		notificationOptions,
	);
});