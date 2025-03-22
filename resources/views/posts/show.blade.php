<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Fixed positioning to stay on top of the page */
            z-index: 9999;
            /* Ensure it's on top of other content */
            left: 50%;
            /* Center the modal horizontally */
            top: 50%;
            /* Center the modal vertically */
            transform: translate(-50%, -50%);
            /* Adjust to ensure it's perfectly centered */
            width: 80%;
            /* Width of the modal */
            max-width: 600px;
            /* Optional: Limit the maximum width */
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
        }

        /* Modal Content (the "window" itself) */
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            /* To position the close button inside the modal */
            border: 2px solid #888;
            max-height: 80vh;
            /* Optional: Limit height */
            overflow-y: auto;
            /* Allow scrolling if content overflows */
        }

        /* The Close Button (to close the modal) */
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            /* Position it in the top-right corner */
            top: 10px;
            right: 15px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #message {
            cursor: pointer;
            color: white;
            background-color: #007BFF;
            transform: scalex(0.8);
            /* Button color */
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        #message:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }
    </style>

</head>

<body>




    <body class="bg-blue-100 flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">


            <!-- Modal Form -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form id="modalForm" action="{{ route('posts.update', $profile->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <label for="name">title:</label>
                        <input type="text" id="name" name="title" required><br><br>
                        <textarea name="description" id="description" cols="30" rows="5"></textarea>
                        <input type="file" name="image"><br><br>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
            <img src="{{ asset($profile->image->url) }}" alt="profile Image"
                class="w-full h-64 object-cover rounded-md mb-4">
            <h1 class="text-2xl font-bold">{{ $profile->title }}</h1>
            <p class="text-gray-700 mt-2">{{ $profile->description }}</p>
            <form action="{{ route('posts.destroy', $profile->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded-lg">
                    Delete the Post
                </button>

            </form>

            <a href="{{ route('posts.index') }}"
                class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg">Back</a>
            <p id="message" class=" bg-blue-500">Click here to open the form</p>
        </div>
        <script>
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the <p> element that opens the modal (your message)
            var message = document.getElementById("message");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the <p> (message), open the modal
            message.addEventListener("click", function() {
                modal.style.display = "block"; // Show the modal
            });

            // When the user clicks on <span> (x), close the modal
            span.addEventListener("click", function() {
                modal.style.display = "none"; // Hide the modal
            });

            // When the user clicks anywhere outside the modal content, close the modal
            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none"; // Hide the modal if clicking outside
                }
            });
        </script>
    </body>

</html>
