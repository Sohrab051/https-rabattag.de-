<x-layouts.admin :title="__('Edit store')">
    <div class="card space-y-6 p-6">
        <form method="POST" action="{{ route('admin.merchants.update', ['merchant' => $merchant]) }}" enctype="multipart/form-data">
            @include('admin.merchants._form')
        </form>
    </div>
</x-layouts.admin>
