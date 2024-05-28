<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <form id="editTaskForm" class="w-full max-w-lg">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" placeholder="Task Title" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Task Description" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" type="date" name="start_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="deadline">Deadline</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline" type="date" name="deadline" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category and Priority</label>
                <div id="categoryPriorityWrapper">
                    <div class="category-priority">
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline category">
                            <option value="">Select Category</option>
                        </select>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline priority">
                            <option value="">Select Priority</option>
                        </select>
                    </div>
                </div>
                <button type="button" id="addCategoryPriorityBtn" class="mt-2 text-blue-500">Add Another Category and Priority</button>
            </div>
            <div class="mb-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Update Task</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pathParts = window.location.pathname.split('/');
        const taskId = pathParts[pathParts.length - 2]; // Assuming taskId is the second last part of the path
            console.log("the taskId i got",taskId)
            if (!taskId) {
                alert('Task ID not provided');
                window.location.href = '{{ route("dashboard") }}';
            }
            fetchOptions('/api/categories', '.category');
            fetchOptions('/api/priorities', '.priority');
            // Fetch categories and priorities for select options
            function fetchOptions(url, className) {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let selects = document.querySelectorAll(className);
                        selects.forEach(select => {
                            data.forEach(item => {
                                let option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = item.name;
                                select.appendChild(option);
                            });
                        });
                    });
            }
            
            
            
            // Fetch the task data to pre-fill the form
            fetch(`/api/tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('title').value = data.title;
                    document.getElementById('description').value = data.description;
                    document.getElementById('start_date').value = data.start_date;
                    document.getElementById('deadline').value = data.deadline;

                    data.categories_with_priorities.forEach(cp => {
                        const wrapper = document.getElementById('categoryPriorityWrapper');
                        const div = document.createElement('div');
                        div.className = 'category-priority mt-4';
                        div.innerHTML = `
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline category">
                                <option value="">Select Category</option>
                            </select>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline priority">
                                <option value="">Select Priority</option>
                            </select>
                        `;
                        wrapper.appendChild(div);
                        const categorySelect = div.querySelector('.category');
                        const prioritySelect = div.querySelector('.priority');
                        categorySelect.value = cp.category.id;
                        prioritySelect.value = cp.priority.id;
                        fetchOptions('/api/categories', '.category');
                        fetchOptions('/api/priorities', '.priority');   
                    });
                })
                .catch(error => console.error('Error fetching task data:', error));

            // Add another category and priority
            document.getElementById('addCategoryPriorityBtn').addEventListener('click', function () {
                const wrapper = document.getElementById('categoryPriorityWrapper');
                const div = document.createElement('div');
                div.className = 'category-priority mt-4';
                div.innerHTML = `
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline category" required>
                        <option value="">Select Category</option>
                    </select>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline priority" required>
                        <option value="">Select Priority</option>
                    </select>
                `;
                wrapper.appendChild(div);
                fetchOptions('/api/categories', '.category');
                fetchOptions('/api/priorities', '.priority');
            });

            // Handle form submission
            document.getElementById('editTaskForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = {
                    title: document.getElementById('title').value,
                    description: document.getElementById('description').value,
                    start_date: document.getElementById('start_date').value,
                    deadline: document.getElementById('deadline').value,
                    categories: []
                };

                const categoryPriorityDivs = document.querySelectorAll('.category-priority');
                categoryPriorityDivs.forEach(div => {
                    const categorySelect = div.querySelector('.category');
                    const prioritySelect = div.querySelector('.priority');
                    if (categorySelect.value && prioritySelect.value) {
                        formData.categories.push({
                            id: categorySelect.value,
                            priority_id: prioritySelect.value
                        });
                    }
                });

                fetch(`/api/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = '{{ route("dashboard") }}';
                })
                .catch(error => console.error('Error updating task:', error));
            });
        });
    </script>
</body>
</html>
