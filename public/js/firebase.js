 import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-app.js";

        var firebaseConfig = {
            apiKey: "AIzaSyD7L3wopmKrU5bsxDnFDVi80MH-xIjyJvc",
            authDomain: "shipping-8eaed.firebaseapp.com",
            projectId: "shipping-8eaed",
            storageBucket: "shipping-8eaed.appspot.com",
            messagingSenderId: "1000456317200",
            appId: "1:1000456317200:web:b49955b30070fd49b25d95"
        };

                  const app = initializeApp(firebaseConfig);
        const messaging = app.messaging();