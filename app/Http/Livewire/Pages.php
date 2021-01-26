<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Pages extends Component
{
    use WithPagination;

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
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
            'slug' => ['required', Rule::unique('pages', 'slug')->ignore($this->modelId)],
            'content' => 'required'
        ];
    }

    public function mount()
    {
        $this->resetPage(); // Reset page after refresh page.
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

    public function read()
    {
        return Page::paginate(5);
    }

    public function update()
    {
        $this->validate();

        Page::find($this->modelId)->update($this->modelData());

        $this->reset('title', 'slug', 'content', 'modalFormVisible');
    }

    public function delete()
    {
        Page::destroy($this->modelId);
        
        $this->reset('modalConfirmDeleteVisible', 'modelId');
        $this->resetPage();
    }
    
    /**
     * Show the form modal
     * of the create function.
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->reset('title', 'slug', 'content', 'modelId');
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    public function loadModel()
    {
        $data = Page::findOrFail($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
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
        return view('livewire.pages', [
            'data' => $this->read()
        ]);
    }
}
