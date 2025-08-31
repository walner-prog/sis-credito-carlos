<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use Livewire\Attributes\Layout;

class CreatePost extends Component
{

    public $title = 'Post title...';
       #[Layout('layouts.app')] 
   public function render()
    {
        return view('livewire.create-post')->with([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ]);
    }
}
