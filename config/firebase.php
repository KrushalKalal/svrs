<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Path to the Firebase service account JSON file.
    | This should point to the file you downloaded from
    | Firebase Console → Project Settings → Service Accounts
    |
    */

    'credentials' => env('FIREBASE_CREDENTIALS'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Database
    |--------------------------------------------------------------------------
    |
    | Firebase Realtime Database URL
    | Example:
    | https://your-project-id.firebaseio.com
    |
    */

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM)
    |--------------------------------------------------------------------------
    |
    | Used for sending push notifications to mobile apps.
    |
    */

    'messaging' => [
        'default_notification' => [
            'title' => env('APP_NAME', 'Laravel App'),
            'body'  => 'You have a new notification',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    |
    | Optional but useful for some Firebase services.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Auth
    |--------------------------------------------------------------------------
    |
    | Used if you plan to use Firebase Authentication.
    |
    */

    'auth' => [
        'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage
    |--------------------------------------------------------------------------
    |
    | Used if you plan to upload files to Firebase Storage.
    |
    */

    'storage' => [
        'default_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    ],

];
