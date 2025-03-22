<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet"
        href="C:\Users\ENPEI\Downloads\fontawesome-free-6.6.0-web\fontawesome-free-6.6.0-web\css\all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input,
        textarea {
            width: 90%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #74ebd5;
            outline: none;
        }

        button {
            width: 50%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background: #74ebd5;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5ac8d4;
        }

        input[type="file"] {
            display: block;
            width: 80%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="file"]:hover {
            border-color: #74ebd5;
        }



        input[type="file"]::-webkit-file-upload-button {
            background: #74ebd5;
            border: none;
            padding: 10px;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;

            font-weight: 900;
            content: "hey"
        }




        input[type="file"]::-webkit-file-upload-button:hover {
            background: #5ac8d4;
        }
    </style>
</head>

<body>


    <div class="container">
        <h2>Create a New Post</h2>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="input-group">
                <input type="file" name="image" required>

            </div>
            <div class="input-group">
                <label for="description">Content</label>
                <textarea id="description" name="description" rows="5" required></textarea>


            </div>
            <button type="submit">Publish</button>

        </form>
        <br><br>

        <a style="text-decoration: none;
    color: inherit;
" href="{{ route('posts.index') }}">Go to My Profile
            &amp; View My Posts</a></button>
        back</a>
        @foreach ($errors as $error)
            <p>{{ $error }}</p>
        @endforeach


    </div>
</body>

</html>
