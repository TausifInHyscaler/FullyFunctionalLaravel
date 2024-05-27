<!-- resources/views/employees/create.blade.php -->

<h1>Create New Employee</h1>

<form action="{{ route('employees.store') }}" method="POST">
    @csrf

    <!-- Task Name -->
    <div class="form-group">
        <label for="taskName">Task Name</label>
        <input type="text" name="taskName" id="taskName" class="form-control" required>
    </div>

    <!-- Title -->
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <!-- Description -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
