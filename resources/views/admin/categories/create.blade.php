<x-layouts.admin :title="__('Add category')">
    <div class="card space-y-6 p-6">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @include('admin.categories._form')
        </form>
    </div>
</x-layouts.admin>
