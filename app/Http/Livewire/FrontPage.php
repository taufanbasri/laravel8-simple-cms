<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;

class FrontPage extends Component
{
    public $title;
    public $content;
    public $urlslug;

    public function mount($urlslug = null)
    {
        $this->retrieveContent($urlslug);
    }

    public function retrieveContent($urlslug)
    {
        if (empty($urlslug)) {
            $data = Page::where('is_default_home', true)->first();
        } else {
            $data = Page::where('slug', $urlslug)->first();

            if (!$data) {
                $data = Page::where('is_default_not_found', true)->first();
            }
        }

        $this->title = $data->title;
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.front-page')->layout('layouts.frontpage');
    }
}
