<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;

class FrontPage extends Component
{
    public $title;
    public $content;
    public $urlslug;

    public function mount($urlslug)
    {
        $this->retrieveContent($urlslug);
    }

    public function retrieveContent($urlslug)
    {
        $data = Page::where('slug', $urlslug)->first();

        $this->title = $data->title;
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.front-page')->layout('layouts.frontpage');
    }
}
