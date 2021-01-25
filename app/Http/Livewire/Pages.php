<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Pages extends Component
{
    public $modalFormVisible = false;
    public $slug;
    public $title;
    public $content;
    
    /**
     * Validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages', 'slug')],
            'content' => 'required'
        ];
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedTitle($value)
    {
        $this->generateSlug($value);
    }
    
    /**
     * create function
     *
     * @return void
     */
    public function create()
    {
        $this->validate();

        Page::create($this->modelData());

        $this->reset('title', 'slug', 'content', 'modalFormVisible');
    }
    
    /**
     * Show the form modal
     * of the create function.
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }
    
    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
        ];
    }
    
    /**
     * generate url slug base on title.
     *
     * @param  mixed $value
     * @return void
     */
    public function generateSlug($value)
    {
        $slug = str_replace(' ', '-', $value);
        $slugLower = strtolower($slug);

        $this->slug = $slugLower;
    }
    
    /**
     * The livewire render function.
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.pages');
    }
}
