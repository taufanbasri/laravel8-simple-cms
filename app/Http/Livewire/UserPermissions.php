<?php

namespace App\Http\Livewire;

use App\Models\UserPermission;
use Livewire\Component;
use Livewire\WithPagination;

class UserPermissions extends Component
{
    use WithPagination;

    public $modalFormVisible, $modalConfirmDeleteVisible, $modelId;

    public $role, $routeName;

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
            'role' => 'required',
            'routeName' => 'required'
        ];
    }

    public function create()
    {
        $this->validate();

        UserPermission::create($this->modelData());

        $this->reset();
    }

    public function read()
    {
        return UserPermission::paginate(5);
    }

    public function update()
    {
        $this->validate();

        UserPermission::find($this->modelId)->update($this->modelData());

        $this->reset();
    }

    public function delete()
    {
        UserPermission::destroy($this->modelId);
        
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
        $data = UserPermission::findOrFail($this->modelId);

        $this->role = $data->role;
        $this->routeName = $data->route_name;
    }

    private function modelData()
    {
        return [
            'role' => $this->role,
            'route_name' => $this->routeName
        ];
    }
    
    public function render()
    {
        return view('livewire.user-permissions', [
            'data' => $this->read()
        ]);
    }
}
