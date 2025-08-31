<div>
     <input class="border border-gray-300 p-2 rounded" type="text" wire:model.live="title" placeholder="Post title..." />
     {{ $title }}

      <span class="text-gray-600 mt-3">Author: {{ $name }}</span>
      <span class="text-gray-600">Email: {{ $email }}</span>

</div>
