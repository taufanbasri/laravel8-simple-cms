<?php

namespace App\Http\Livewire;

use App\Models\NavigationMenu;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class NavigationMenus extends Component
{
    use WithPagination;

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $label;
    public $slug;
    public $sequence = 1;
    public $type = 'SidebarNav';

    public function mount()
    {
        $this->resetPage();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedLabel($value)
    {
        $this->slug = Str::slug($value);
    }

    public function rules()
    {
        return [
            'label' => 'required',
            'slug' => ['required', Rule::unique('navigation_menus', 'slug')->ignore($this->modelId)],
            'sequence' => 'required',
            'type' => 'required'
        ];
    }

    public function create()
    {
        $this->validate();

        NavigationMenu::create($this->modelData());

        $this->reset();
    }

    public function read()
    {
        return NavigationMenu::paginate(5);
    }

    public function update()
    {
        $this->validate();

        NavigationMenu::find($this->modelId)->update($this->modelData());

        $this->reset();
    }

    public function delete()
    {
        NavigationMenu::destroy($this->modelId);
        
        $this->reset();
        $this->resetPage();
    }

    public function createShowModal()
    {
        $this->reset();
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
        $data = NavigationMenu::findOrFail($this->modelId);

        $this->label = $data->label;
        $this->slug = $data->slug;
        $this->type = $data->type;
        $this->sequence = $data->sequence;
    }

    private function modelData()
    {
        return [
            'label' => $this->label,
            'slug' => $this->slug,
            'type' => $this->type,
            'sequence' => $this->sequence
        ];
    }
    
    public function render()
    {
        return view('livewire.navigation-menus', [
            'data' => $this->read()
        ]);
    }
}
