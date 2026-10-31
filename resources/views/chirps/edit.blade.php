
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Chirp - Chirper</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            max-width: 650px;
            margin: 40px auto;
            padding: 15px;
        }

        .card {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
        }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel {
            background: #64748b;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

    <h1>Chirper 🐦</h1>

    <div class="card">

        <h2>Edit Chirp</h2>

        <p>Update your Chirp below.</p>

        <form
            action="{{ route('chirps.update', $chirp['id']) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <textarea
                name="content"
                rows="5"
                maxlength="280"
                required
            >{{ old('content', $chirp['content']) }}</textarea>

            @error('content')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror

            <p>
                <button type="submit" class="button">
                    Update Chirp
                </button>

                <a href="{{ route('home') }}"
                   class="button cancel">
                    Cancel
                </a>
            </p>

        </form>

    </div>

</body>
</html>
