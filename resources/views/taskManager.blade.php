<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <form id="taskForm" class="w-full max-w-lg" method="POST" action="/api/tasks">
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
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline category" required>
                            <option value="">Select Category</option>
                        </select>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline priority" required>
                            <option value="">Select Priority</option>
                        </select>
                    </div>
                </div>
                <button type="button" id="addCategoryPriorityBtn" class="mt-2 text-blue-500">Add Another Category and Priority</button>
            </div>
            <div class="mb-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Create Task</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    let categorySelects = document.querySelectorAll('.category');
                    categorySelects.forEach(select => {
                        data.forEach(category => {
                            let option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            select.appendChild(option);
                        });
                    });
                });

            fetch('/api/priorities')
                .then(response => response.json())
                .then(data => {
                    let prioritySelects = document.querySelectorAll('.priority');
                    prioritySelects.forEach(select => {
                        data.forEach(priority => {
                            let option = document.createElement('option');
                            option.value = priority.id;
                            option.textContent = priority.name;
                            select.appendChild(option);
                        });
                    });
                });

            document.getElementById('addCategoryPriorityBtn').addEventListener('click', function () {
                let wrapper = document.getElementById('categoryPriorityWrapper');
                let div = document.createElement('div');
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

                fetch('/api/categories')
                    .then(response => response.json())
                    .then(data => {
                        let categorySelect = div.querySelector('.category');
                        data.forEach(category => {
                            let option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    });

                fetch('/api/priorities')
                    .then(response => response.json())
                    .then(data => {
                        let prioritySelect = div.querySelector('.priority');
                        data.forEach(priority => {
                            let option = document.createElement('option');
                            option.value = priority.id;
                            option.textContent = priority.name;
                            prioritySelect.appendChild(option);
                        });
                    });
            });

            document.getElementById('taskForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = {
                    title: document.getElementById('title').value,
                    description: document.getElementById('description').value,
                    start_date: document.getElementById('start_date').value,
                    deadline: document.getElementById('deadline').value,
                    categories: []
                };

                let categoryPriorityDivs = document.querySelectorAll('.category-priority');
                categoryPriorityDivs.forEach(div => {
                    let categorySelect = div.querySelector('.category');
                    let prioritySelect = div.querySelector('.priority');
                    if (categorySelect.value && prioritySelect.value) {
                        formData.categories.push({
                            id: categorySelect.value,
                            priority_id: prioritySelect.value
                        });
                    }
                });

                fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    console.log("the text I got to be writtedn")
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>
