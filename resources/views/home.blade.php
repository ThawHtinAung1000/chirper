
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Chirper</title>
</head>
<body>

    <h1>Chirper</h1>
    <p>Share your thoughts!</p>

    @if(session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <form action="{{ route('chirps.store') }}"
          method="POST">

        @csrf

        <textarea
            name="content"
            rows="4"
            cols="50"
            maxlength="280"
            placeholder="What's happening?"
            required
        >{{ old('content') }}</textarea>

        @error('content')
            <p style="color: red;">
                {{ $message }}
            </p>
        @enderror

        <p>
            <button type="submit">
                Post Chirp
            </button>
        </p>
    </form>

</body>
</html>
