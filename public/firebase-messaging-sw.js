// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyD7L3wopmKrU5bsxDnFDVi80MH-xIjyJvc",
    authDomain: "shipping-8eaed.firebaseapp.com",
    projectId: "shipping-8eaed",
    storageBucket: "shipping-8eaed.appspot.com",
    messagingSenderId: "1000456317200",
    appId: "1:1000456317200:web:b49955b30070fd49b25d95",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/images/logo.png",
    };

    return self.registration.showNotification(title, options);
});
