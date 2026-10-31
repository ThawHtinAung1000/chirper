
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Chirper - Mini Blog</title>

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
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
        }

        button {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .chirp {
            background: white;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .author {
            font-weight: bold;
        }

        .date {
            font-size: 12px;
            color: #777;
        }

        .content {
            margin-top: 12px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .empty {
            color: #777;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>

    <h1>Chirper</h1>
    <p>Share your thoughts with the world.</p>

    <!-- Create Chirp -->
    <div class="card">

        <h2>Create a Chirp</h2>

        @if(session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif

        <form action="{{ route('chirps.store') }}"
              method="POST">

            @csrf

            <textarea
                name="content"
                rows="4"
                maxlength="280"
                required
                placeholder="What's happening?"
            >{{ old('content') }}</textarea>

            @error('content')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror

            <p>
                <button type="submit">
                    Post Chirp
                </button>
            </p>

        </form>
    </div>

    <!-- Chirp Feed -->
    <h2>Latest Chirps ({{ count($chirps) }})</h2>

    @forelse($chirps as $chirp)

        <div class="chirp">

            <div class="author">
                {{ $chirp['author'] }}
            </div>

            <div class="date">
                <time datetime="{{ $chirp['created_at'] }}">
                    {{ \Carbon\Carbon::parse(
                        $chirp['created_at']
                    )->format('d M Y, h:i A') }}
                </time>
            </div>

            <p class="content">{{ $chirp['content'] }}</p>

        </div>

    @empty

        <div class="card empty">
            No Chirps yet. Be the first to post!
        </div>

    @endforelse

</body>
</html>
