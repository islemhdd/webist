<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="/css/fontawesome-free-6.6.0-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <title>Profile
        Page</title>
    <style>
        /* * {
            box-sizing: border-box;
        } */
        a {
            text-decoration: none;
            color: #000000;
        }

        body,
        html {
            margin: 0;


            /* Changed from 100vh */
            display: flex;
            justify-content: flex-start;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
        }

        .sidebar {
            background: #333;
            color: white;
            padding: 5px 20px 10px 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-height: 100vh;
            transition: 0.5s ease all;
            width: 20px;
            /* Default width for the sidebar */
            /* Adjust the sidebar width on hover */
        }

        .sidebar:hover {
            width: 250px;
            /* Expands to 250px on hover */
            transition: 0.5s ease all;
            overflow-y: auto;
            /* Enables scrolling */
            overflow-x: hidden;

            .search-bar {
                transition: 0.5s ease all;
                width: 80%;
            }

            #pos {
                transition: 0.5s ease all;
                z-index: -1;

                /* Prevents horizontal scrolling */
            }

            .search-bar {
                transition: 0.5s ease all;
                z-index: 1;
            }

            i {
                transition: 0.1s
            }

            .search-bar:focus {
                transition: 0.1s background: #444;
                outline: none;
                color: rgb(255, 255, 255);
            }

        }

        .sidebar a {
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            /* overflow: auto; */
            text-decoration: none;
            color: rgb(146, 143, 143);
            padding: 8px;
            padding-left: 2px;
            min-width: 23px;

            height: 14px;
            /* max-height: 30px; */
            background: #444;
            border-radius: 5px;
            text-align: center;
            transition: 0.3s;
            display: block;


        }

        .sidebar a:hover {
            background: #555;
        }

        .search-bar {
            transition: 0ms 0.5s ease;
            padding: 6px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            outline: none;
            width: 8px;

            z-index: -1;

            /* Ensures the search bar fits well */
            display: block;
            background: #444;
            /* Matches sidebar color */
            color: white;


        }

        .search-bar:focus {
            background: #444;
            outline: none;
            color: rgba(255, 255, 255, 0);
        }


        .container {

            height: auto;
            background: white;
            padding: 20px;

            border-radius: 0 12px 12px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 0;
            text-align: center;
            color: black;
            overflow: hidden;
            /* Prevents extra content overflow */
        }

        a {
            text-decoration: none;

        }

        .post-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            max-width: 1200px;
            margin-top: 20px;
        }

        .post {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            height: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
        }

        .post img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .post strong {
            font-size: 18px;
            color: #333;
        }

        .post p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .post a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }

        .post a:hover {
            color: #0056b3;
        }

        .post h4 {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 6px;
            background: #74ebd5;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #5ac8d4;
        }

        #pos {
            position: relative;
            top: -47px;
            left: 2px;
        }
    </style>
</head>
@php

@endphp

<body>
    <div class="sidebar">
        <form action="">
            </i> <!-- Search Icon -->
            <input type="text" class="search-bar" id="searchInput" placeholder="&nbsp; &nbsp;&nbsp;&nbsp;Search...">
        </form>
        <i class="fas fa-search" id="pos"></i>
        <a href="{{ route('posts.index') }}"><i class="fas fa-home"></i>&nbsp;&nbsp; Home</a>
        <a href="{{ route('explorePage') }}"><i class="fas fa-sun"></i>&nbsp;&nbsp;&nbsp; Surf</a>

    </div>

    </div>


    <div class="container">
        <h2>Explore Posts</h2>
        <div class="post-gallery">
            <!-- Static example of posts -->
            <div class="post">
                @foreach ($posts as $post)
                    <strong>{{ $post->title }}</strong>
                    <p>{{ $post->description }}</p>
                    <img src="{{ asset('img/bbb.jpg') }}" alt="Post Image">

                    <a href="{{ route('posts.show', ['profile' => $post->id]) }}">see the post</a>
                    <h4>owner: {{ $post->student->name }}</h4>
                @endforeach

            </div>

        </div>
    </div>
</body>

</html>
