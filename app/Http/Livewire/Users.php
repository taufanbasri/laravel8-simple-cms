<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $modalFormVisible, $modalConfirmDeleteVisible, $modelId;

    public $name, $role;

    public function mount()
    {
        $this->resetPage();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'role' => 'required'
        ];
    }

    public function create()
    {
        $this->validate();

        User::create($this->modelData());

        $this->reset();
    }

    public function read()
    {
        return User::paginate(5);
    }

    public function update()
    {
        $this->validate();

        User::find($this->modelId)->update($this->modelData());

        $this->reset();
    }

    public function delete()
    {
        User::destroy($this->modelId);
        
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
        $data = User::findOrFail($this->modelId);

        $this->name = $data->name;
        $this->role = $data->role;
    }

    private function modelData()
    {
        return [
            'name' => $this->name,
            'role' => $this->role
        ];
    }
    
    public function render()
    {
        return view('livewire.users', [
            'data' => $this->read()
        ]);
    }
}
