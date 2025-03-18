<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            width: 60%;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Hide checkboxes */
        input[type="checkbox"] {
            display: none;
        }

        /* Popup styles */
        .popup {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid black;
            display: none;
            width: 300px;
        }

        /* Show popup when checkbox is checked */
        input[type="checkbox"]:checked+.popup {
            display: block;
        }

        .popup a {
            display: block;
            margin-top: 10px;
            color: red;
            text-decoration: none;
        }

        /* Overlay effect */
        .overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        input[type="checkbox"]:checked~.overlay {
            display: block;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Student List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->grade }}</td>
                        <td>
                            <label for="popup-{{ $student->id }}">⚙️</label>
                            <input type="checkbox" id="popup-{{ $student->id }}">
                            <div class="overlay"></div>
                            <div class="popup">
                                <p>Modify or Delete?</p>
                                <form action="modify.php" method="post">
                                    <input type="hidden" name="id" value="{{ $student->id }}">
                                    <input type="text" name="name" placeholder="New Name"
                                        value="{{ $student->name }}">
                                    <input type="submit" value="Modify">
                                </form>
                                <a href="delete.php?id={{ $student->id }}">Delete</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
