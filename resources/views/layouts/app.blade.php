<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
        // Fetch tasks data from API and render it dynamically
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/api/tasks')
                .then(response => response.json())
                .then(data => {
                    const tasksContainer = document.getElementById('tasksContainer');
                    data.forEach(task => {
                        const taskDiv = document.createElement('div');
                        taskDiv.classList.add('border', 'rounded-md', 'p-4');
                        taskDiv.innerHTML = `
                            <h3 class="font-semibold text-lg">${task.title}</h3>
                            <p class="mt-2">Description: ${task.description}</p>
                            <p>Start Date: ${task.start_date}</p>
                            <p>Deadline: ${task.deadline}</p>
                            ${task.categories_with_priorities.length > 0 ? `
                            <div class="mt-4">
                                <h4 class="font-semibold">Categories with Priorities:</h4>
                                <ul>
                                    ${task.categories_with_priorities.map(category => `<li>${category.category.name} - Priority: ${category.priority.name}</li>`).join('')}
                                </ul>
                            </div>
                            ` : ''}
                            <div class="mt-4 flex space-x-4">
                            <button class="delete-btn bg-red-500 hover:bg-red-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" data-id="${task.id}">Delete</button>
                            <button class="edit-btn bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" data-id="${task.id}">Edit</button>
                            </div>
                        `;
                        tasksContainer.appendChild(taskDiv);
                    });
                    document.querySelectorAll('.edit-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const taskId = this.getAttribute('data-id');
                            window.location.href = `/tasks/${taskId}/edit`; // Adjust the route as necessary
                        });
                    });

                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const taskId = this.getAttribute('data-id');
                            fetch(`/api/tasks/${taskId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    this.closest('div.border').remove();
                                } else {
                                    console.error('Failed to delete task');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        });
                    });
                })
                .catch(error => console.error('Error fetching tasks:', error));
        });
    </script>
    </body>
</html>
