@extends('admin.layout.default')
@section('content')
    <style>
        .chat-container {
            margin: 0 auto;
            padding: 20px 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            max-height: 400px;
            overflow-y: auto;
        }

        .message {
            background-color: #f2f2f2;
            padding: 10px;
            margin: 20px 0;
            border-radius: 10px;
            position: relative;
            max-width: 80%;
        }

        .message.received {
            background-color: #e0e0e0;
            text-align: left;
        }

        .message.sent {
            background-color: #DCF8C6;
            text-align: right;
            margin-left: auto;
        }

        .timestamp {
            font-size: 12px;
            color: #777;
            position: absolute;
            bottom: -20px;
        }

        .sent .timestamp {
            right: 0;
        }
    </style>
    <div class="content">
        <div class="chat-container">
            <div class="message received"> Hello there! <div class="timestamp">Today, 10:30 AM</div>
            </div>
            <div class="message sent"> Hi! How are you? <div class="timestamp">Today, 10:32 AM</div>
            </div>
            <div class="message received"> I'm doing well, thanks for asking! <div class="timestamp">Today, 10:35 AM</div>
            </div>
            <div class="message received"> Hello there! <div class="timestamp">Today, 10:30 AM</div>
            </div>
            <div class="message sent"> Hi! How are you? <div class="timestamp">Today, 10:32 AM</div>
            </div>
            <div class="message received"> I'm doing well, thanks for asking! <div class="timestamp">Today, 10:35 AM</div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    {{-- <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script> --}}

    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-analytics.js";
        import { getFirestore } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-firestore.js";

        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyBkQiZ-4-ydvqZZ-e0BeiZeVJRgn2dlY4c",
            authDomain: "laytoapp-9cea5.firebaseapp.com",
            databaseURL: "https://laytoapp-9cea5-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "laytoapp-9cea5",
            storageBucket: "laytoapp-9cea5.appspot.com",
            messagingSenderId: "1069115115757",
            appId: "1:1069115115757:web:d19d6cd8a5a9f0b4c0e4e4",
            measurementId: "G-MDMDDCXTQR"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        const db = getFirestore(app);
        console.log(db._databaseId)
        console.log(db)

        // const docRef = db.collection('my_users').doc('alovelace');
        // await docRef.set({
        //     first: 'Ada',
        //     last: 'Lovelace',
        //     born: 1815
        // });
        // const aTuringRef = db.collection('my_users').doc('aturing');
        // await aTuringRef.set({
        //     'first': 'Alan',
        //     'middle': 'Mathison',
        //     'last': 'Turing',
        //     'born': 1912
        // });

        // const snapshot = await db.collection('my_users').get();
        // snapshot.forEach((doc) => {
        //     console.log(doc.id, '===>', doc.data());
        // });


        

        //        var token = '111222333444555666777888999000';
        //        firebase.auth().signInWithCustomToken(token).catch(function(error) {
        //            // Handle Errors here.
        //            var errorCode = error.code;
        //            var errorMessage = error.message;
        //            alert(errorMessage);
        //        }).then(function(data){
        //            $("#login-btn").html(btnHTML);
        //            if (data.user.uid != "") {
        //                window.location.href = "chat.php?"+data.user.uid;
        //            }
        //        });
        // var db = app.firestore();
        // db.settings({
        //     timestampsInSnapshots: true
        // });
    </script>

    {{-- <script>
        firebase.initializeApp({
            apiKey: {{ Js::from(env('FIREBASE_KEY')) }},
            authDomain: 'laytoapp-9cea5.firebaseapp.com',
            databaseURL: 'https://laytoapp-9cea5.firebaseio.com',
            projectId: "laytoapp-9cea5",
            storageBucket: 'laytoapp-9cea5.appspot.com',
            messagingSenderId: 1069115115757,
            appId: '1:1069115115757:android:0716604f7d8a1a6bc0e4e4',
            measurementId: 'G-measurement-id',
        });
        const messaging = firebase.messaging();
        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);
            alert('New message')
        });

        // messaging.usePublicVapidKey('');

        function sendTokenToServer(fcm_token) {
            const user_id = 5;
            axios.post('/api/save-token', {
                fcm_token,
                user_id
            }).then(res => {
                console.log(res);
            });
        }
        messaging.onMessage((payload) => {
            console.log('Message received');
            console.log(payload);
        })
        function retrieveToken() {
            messaging.getToken().then((currentToken) => {
                if (currentToken) {
                    console.log('Token received :' + currentToken);
                    sendTokenToServer(currentToken);
                    // updateUIForPushEnabled (currentToken);
                } else {
                    alert('You should allow notification!');
                }
            }).catch((err) => {
                // console.log('An error occurred while retrieving token. ', err)
                console.log(err.message)
                // showToken('Error retrieving Instance ID token. ', err);
                // setTokenSentToServer (false);
            });
        }
        retrieveToken();
        message.onTokenRefresh(() => {
            retrieveToken()
        });
    </script> --}}
@endsection
