<h1>Edit Employee</h1>

<form action="{{ route('employees.update', $employee->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Task Name -->
    <div class="form-group">
        <label for="taskName">Task Name</label>
        <input type="text" name="taskName" id="taskName" class="form-control" value="{{ $employee->taskName }}" required>
    </div>

    <!-- Title -->
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ $employee->title }}" required>
    </div>

    <!-- Description -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" required>{{ $employee->description }}</textarea>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Update</button>
</form>
