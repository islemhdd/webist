<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="/css/fontawesome-free-6.6.0-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Profile Page</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: flex-start;
            min-height: 100vh;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #333;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-height: 100vh;
            width: 60px;
            transition: width 0.5s ease;
            overflow: hidden;
        }

        .sidebar:hover {
            width: 250px;
        }

        .sidebar a,
        .sidebar button {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgb(146, 143, 143);
            padding: 10px;
            border-radius: 5px;
            background: #444;
            transition: background 0.3s;
            white-space: nowrap;
        }

        .sidebar a:hover,
        .sidebar button:hover {
            background: #555;
        }

        .sidebar i {
            min-width: 20px;
            text-align: center;
        }

        .search-bar {
            padding: 8px;
            border: none;
            border-radius: 5px;
            background: #444;
            color: white;
            width: 100%;
            transition: width 0.5s ease;
        }

        .search-bar:focus {
            outline: none;
            background: #555;
        }

        /* Container Styles */
        .container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            flex-grow: 1;
            max-width: 1200px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .post-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .post {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .post img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .post strong {
            margin: 10px 0;
        }

        .post p {
            text-align: center;
            color: #555;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 6px;
            background: #74ebd5;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #5ac8d4;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <form action="">
            <input type="text" class="search-bar" placeholder="Search...">
        </form>
        <a href="{{ route('homePage') }}"><i class="fas fa-home"></i>Home</a>
        <a href="{{ route('explorePage') }}"><i class="fas fa-sun"></i>Surf</a>
        <a href="{{ route('explore') }}"><i class="fa-solid fa-compass"></i>Explore</a>
        @if ($student->id == 2022002 || $student->id == 2022003)
            <a href="{{ route('reports.create') }}"><i class="fas fa-flag"></i>Report</a>
        @endif
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i>Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="container">
        <img class="profile-img"
            src="{{ asset($student->posts()->oldest('created_at')->first()?->image->url ?? '/img/default.png') }}"
            alt="Profile Picture">
        <h2>{{ $student->name }} </h2>



        <p>Price: {{ $student->price ?? 'You don’t have a price yet' }}</p>
        <p>Photographer | Traveler | Blogger</p>
        <a href="{{ route('posts.create') }}">
            <button>Create a Post</button>
        </a>
        @if ($student->id == 2022001 || ($student->id == 2022002 || $student->id == 2022003))
            @if (!$student->notifications)
                <p>You don't have any notifications yet</p>
            @else
                <p>You have {{ $student->notifications->count() }} unread notifications</p>
            @endif

            @foreach ($student->notifications as $notification)
                <div class="notification">
                    <strong>{{ $notification->data['title'] }}</strong>
                    <p>{{ $notification->data['description'] }}</p>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            @endforeach

        @endif
        <h3>Your Posts</h3>
        <div class="post-gallery">
            @if ($posts->isNotEmpty())
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', ['profile' => $post->id]) }}">
                        <div class="post">
                            <strong>{{ $post->title }}</strong>
                            <img src="{{ asset(optional($post->image)->url ?? 'default.png') }}" alt="Post Image">
                            <p>{{ $post->description }}</p>
                        </div>
                    </a>
                @endforeach
            @else
                <p>No posts yet</p>
            @endif

        </div>
    </div>
</body>

</html>
