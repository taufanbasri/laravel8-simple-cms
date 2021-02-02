<div class="p-6">
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>

    {{-- Data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Role</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Route Name</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($data as $permission)
                                <tr>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $permission->role }}</td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $permission->route_name }}</td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <x-jet-button wire:click="updateShowModal({{ $permission->id }})">
                                            {{ __('Update') }}
                                        </x-jet-button>
                                        <x-jet-danger-button wire:click="deleteShowModal({{ $permission->id }})">
                                            {{ __('Delete') }}
                                        </x-jet-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap" colspan="4">No Results Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        {{ $data->links() }}
    </div>

    {{-- Modal form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save User Permission') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="role" value="{{ __('Role') }}" />
                <select wire:model="role" class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-100 border border-gray-200 appearance-none round focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select Role --</option>
                    @foreach (App\Models\User::userRoleList() as $key => $roleName)
                        <option value="{{ $key }}">{{ $roleName }}</option>
                    @endforeach
                </select>
                @error('role') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="routeName" value="{{ __('Route Name') }}" />
                <select wire:model="routeName" class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-100 border border-gray-200 appearance-none round focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select Route --</option>
                    @foreach (App\Models\UserPermission::routeNameList() as $route)
                        <option value="{{ $route }}">{{ $route }}</option>
                    @endforeach
                </select>
                @error('routeName') <span class="error">{{ $message }}</span> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            @if ($modelId)
                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-jet-button>
            @else
                <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
        <x-slot name="title">
            {{ __('Delete Permission') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this Permission?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete Permission') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
