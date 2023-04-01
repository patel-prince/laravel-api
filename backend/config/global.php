<?php

return [
    'status' => [
        'success' => 200,
        'error' => 422
    ],
    'regex' => [
        'password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{8,32}$/',  // Pasword@123
        'phone' => '/^(\+)([0-9]{1,3})(\s\()([0-9]{1,3})(\))([0-9]{1,3})(-)([0-9]{1,4})$/', // +91 (123) 234-3455
    ],
    'message' => [
        'invalid_google_token' => 'User verification has been failed.',
        'token_expired' => 'Token has been expired.',
        'already_verified' => 'Your account is already activated.',
        'verification_mail_sent' => 'Verification link has been sent. Please check your email id',
        'unverified_account' => 'Your account is not been activated yet. Please check your email for verification link.',
        'user_account_verified' => 'Congratulations, your account has been activated. You can now login with your email id and password.',
        'invalid_verify_code' => 'This verification link has been expired. Please try to resend the verification link.',
        'unauthorized' => 'Unauthorized Acccess',
        'login' => [
            'invalid' => 'Username or Password is invalid.',
            'email' => [
                'required' => 'Email ID is required.',
                'email' => 'Email ID is invalid.',
                'max' => 'Email ID should contain maximum 255 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'regex' => 'Password should contain atleast one capital letter, number and special character.',
                'max' => 'Password should contain maximum 32 characters.',
                'min' => 'Password should contain minimum 8 characters.',
            ]
        ],
        'register' => [
            'success' => 'You have been successfully registered in ' . env('APP_NAME', 'Docto App'),
            'firstname' => [
                'required' => 'Firstname is required.',
                'max' => 'Firstname should contain maximum 50 characters.',
            ],
            'lastname' => [
                'required' => 'Lastname is required.',
                'max' => 'Lastname should contain maximum 50 characters.',
            ],
            'email' => [
                'required' => 'Email ID is required.',
                'email' => 'Email ID is invalid.',
                'max' => 'Email ID should contain maximum 255 characters.',
                'unique' => 'Email ID is already in use.',
            ],
            'phone' => [
                'required' => 'Phone Number is required.',
                'regex' => 'Phone Number is invalid.',
                'max' => 'Phone Number should contain maximum 20 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'regex' => 'Password should contain atleast one capital letter, number and special character.',
                'max' => 'Password should contain maximum 32 characters.',
                'min' => 'Password should contain minimum 8 characters.',
            ]
        ]
    ]
];
