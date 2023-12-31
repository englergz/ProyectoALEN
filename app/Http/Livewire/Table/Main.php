<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;

    public $model;
    public $name;

    public $perPage = 10;
    public $sortField = "id";
    public $sortAsc = false;
    public $search = '';

    protected $listeners = [ "deleteItem" => "delete_item" ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function get_pagination_data ()
    {
        switch ($this->name) {
            case 'user':
                $users = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.user',
                    "users" => $users,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('admin.users.create'),
                            'create_new_text' => 'Crear usuario',
                            'export' => '#',
                            'export_text' => 'Exportar'
                        ]
                    ])
                ];
                break;
            case 'post':
                $posts = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.post',
                    "posts" => $posts,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('admin.posts.store', '#create'),
                            'create_new_text' => 'Crear publicación',
                            'export' => '#',
                            'export_text' => 'Exportar'
                        ]
                    ])
                ];
                break;
            case 'role':
                $roles = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.role',
                    "roles" => $roles,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('admin.roles.create'),
                            'create_new_text' => 'Crear rol',
                            'export' => '#',
                            'export_text' => 'Exportar'
                        ]
                    ])
                ];
                break;

            case 'permission':
                $permissions = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.permission',
                    "permissions" => $permissions,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => '#',
                            'create_new_text' => 'Crear permiso',
                            'export' => '#',
                            'export_text' => 'Exportar'
                        ]
                    ])
                ];
                break;

            default:

                ##
                break;
        }
    }
    

    public function delete_item ($id)
    {
        $data = $this->model::find($id);

        if (!$data) {
            $this->emit("deleteResult", [
                "status" => false,
                "message" => "No se pudieron borrar los datos " . $this->name
            ]);
            return;
        }

        $data->delete();
        $this->emit("deleteResult", [
            "status" => true,
            "message" => "¡Eliminado correctamente!"
        ]);
    }

    public function render()
    {
        $data = $this->get_pagination_data();

        return view($data['view'], $data);
    }
}
