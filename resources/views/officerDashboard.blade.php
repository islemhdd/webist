<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4 flex justify-between">
        <h1 class="text-xl font-bold">Officer Dashboard</h1>
        <button class="bg-red-500 px-4 py-2 rounded">Logout</button>
    </nav>

    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Notifications Section -->
        <div class="bg-white p-4 rounded-lg shadow-md md:col-span-1">
            <h2 class="text-lg font-semibold mb-3">🔔 Notifications</h2>
            <ul class="space-y-2">
                <li class="p-2 bg-gray-200 rounded">New report submitted by Student #12</li>
                <li class="p-2 bg-gray-200 rounded">Section meeting scheduled for Friday</li>
                <li class="p-2 bg-gray-200 rounded">Your report has been reviewed</li>
            </ul>
        </div>

        <!-- Assigned Students -->
        <div class="bg-white p-4 rounded-lg shadow-md md:col-span-1">
            <h2 class="text-lg font-semibold mb-3">🎓 Assigned Students</h2>
            <ul class="space-y-2">
                <li class="p-2 bg-gray-200 rounded">Student #101 - John Doe</li>

            </ul>
        </div>

        <!-- Reports Written -->
        <div class="bg-white p-4 rounded-lg shadow-md md:col-span-1">
            <h2 class="text-lg font-semibold mb-3">📝 Reports</h2>
            <ul class="space-y-2">
                <li class="p-2 bg-gray-200 rounded">Report #201 - About Student #101</li>
                <li class="p-2 bg-gray-200 rounded">Report #202 - About Student #102</li>
                <li class="p-2 bg-gray-200 rounded">Report #203 - About Student #103</li>
            </ul>
        </div>
    </div>

    <!-- Write Report Section -->
    <div class="p-6">
        <div class="bg-white p-4 rounded-lg shadow-md max-w-3xl mx-auto">
            <h2 class="text-lg font-semibold mb-3">✍️ Write a Report</h2>
            <form>
                <label class="block mb-2">Select Student</label>
                <select class="w-full p-2 border rounded mb-3">
                    <option>Student #101 - John Doe</option>
                    <option>Student #102 - Jane Smith</option>
                    <option>Student #103 - Alex Brown</option>
                </select>

                <label class="block mb-2">Report Content</label>
                <textarea class="w-full p-2 border rounded mb-3" rows="4"></textarea>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit Report</button>
            </form>
        </div>
    </div>

</body>

</html>
