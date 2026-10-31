
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

        .card, .chirp {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button, .edit-button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-button {
            background: #475569;
            font-size: 14px;
        }

        .author {
            font-weight: bold;
        }

        .date {
            color: #777;
            font-size: 12px;
        }

        .content {
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
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

    <h1>Chirper</h1>

    <p>Share your thoughts with the world.</p>

    <!-- Success Message -->
    @if(session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif

    <!-- Create Chirp Form -->
    <div class="card">

        <h2>Create a Chirp</h2>

        <form action="{{ route('chirps.store') }}"
              method="POST">

            @csrf

            <textarea
                name="content"
                rows="4"
                maxlength="280"
                placeholder="What's happening?"
                required
            >{{ old('content') }}</textarea>

            @error('content')
                <p class="error">{{ $message }}</p>
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
                Created:
                {{ \Carbon\Carbon::parse(
                    $chirp['created_at']
                )->format('d M Y, h:i A') }}

                @if(!empty($chirp['updated_at']))
                    <span>
                        | Edited
                    </span>
                @endif
            </div>

            <p class="content">{{ $chirp['content'] }}</p>

            <!-- Feature 4: Edit Button -->
            <a
                href="{{ route('chirps.edit', $chirp['id']) }}"
                class="edit-button"
            >
                Edit
            </a>

        </div>

    @empty

        <div class="card empty">
            No Chirps yet. Be the first to post!
        </div>

    @endforelse

</body>
</html>
