<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <a href="{{ url('/tasks/taskManager') }}" class="text-blue-500 hover:text-blue-700">
            {{ __('Create Task') }}
        </a>
    </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
                <div class="grid grid-cols-1 gap-4 mt-4" id="tasksContainer">
                    <!-- Tasks will be inserted here dynamically -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
