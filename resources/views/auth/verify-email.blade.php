<!-- resources/views/auth/verify-email.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email Address</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
            <h1 class="mb-4 text-2xl font-bold text-center">Verify Your Email Address</h1>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm font-medium text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <p class="mb-4">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn’t receive the email, we will gladly send you another.
            </p>

            <form method="POST" action="">
                @csrf
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                        Resend Verification Email
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="text-sm text-gray-600 underline hover:text-gray-900">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
