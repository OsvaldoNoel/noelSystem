<?php

namespace App\Livewire\Landlord\Entidad;

use App\Models\Entidad; 
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads; 

class EntidadController extends Component
{
    use WithFileUploads;

    public $selected_id, $entidad, $name, $color, $addres, $phone, $image, $status;
    public $componentName, $datos, $imagePrev;

    public function mount()
    {
        $this->componentName = 'Entidad';
        $this->updateTable();
    }

    public function updateTable()
    {
        $this->datos = Entidad::query()
            ->select('id', 'entidad', 'name', 'nombre', 'color', 'addres', 'phone', 'image', 'status')->get()->toJson();
        $this->dispatch('Entidad', $this->datos);
    }

    #[On('statusEntidad')]
    public function status($id)
    {
        $record = Entidad::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
    }

    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->resetValidation(); 
    }

    #[On('renderizar')]
    public function renderizar($color, $image)
    { 
        $this->color = $color;
        $this->imagePrev = $image;
    }
 
    public function renderizarColor($color)
    { 
        $this->color = $color; 
    }

    protected $messages = [
        'entidad.required' => 'La Entidad es requerida',

        'name.required' => 'El nombre de la Entidad es requerido',
        'name.unique' => 'Una entidad con este nombre ya fue registrada',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',

        'color.required' => 'El color es requerido',

        'addres.required' => 'La Dirección de la Entidad es requerida',
        'addres.min' => 'La Dirección debe tener al menos tres caracteres',
        'addres.max' => '50 caracteres el maximo permitido para este campo',

        'phone.required' => 'El telefono de la Entidad es requerido',
        'phone.min' => 'El teléfono debe tener al menos seis caracteres',
        'phone.max' => '20 caracteres el maximo permitido para este campo',

        'image.required' => 'La imagen es requerida',
        'image.image' => 'El fomato del archivo debe ser de tipo imagen',
        'image.max' => 'El tamano maximo es de 1024 bits',
    ];

    public function store()
    {
        $this->validate([
            'entidad' => 'required',
            'name' => ['required', 'unique:entidads', 'min:3', 'max:50'],
            'color' => 'required',
            'addres' => ['required', 'min:3', 'max:50'],
            'phone' => ['required', 'min:6', 'max:20'],
        ]);

        $customFileName = null;

        if ($this->image) {
            $this->validate([
                'image' => ['image', 'max:1024'],
            ]);

            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs(path: 'img/config/entidades', name: $customFileName);
        }

        Entidad::create([
            'entidad' => $this->entidad,
            'name' => $this->name,
            'nombre' => $this->entidad . ' ' . $this->name,
            'color' => $this->color,
            'addres' => $this->addres,
            'phone' => $this->phone,
            'image' => $customFileName,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('msg-added');
        $this->updateTable();
    }

    public function update()
    {
        $this->validate([
            'entidad' => 'required',
            'name' => [
                'required',
                'min:3',
                'max:50',
                Rule::unique('entidads')->ignore($this->selected_id),
            ],
            'color' => 'required',
            'addres' => ['required', 'min:3', 'max:50'],
            'phone' => ['required', 'min:6', 'max:20'],
        ]);

        $customFileName = $this->imagePrev;

        if ($this->image) {
            $this->validate([
                'image' => ['image', 'max:1024'],
            ]);

            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs(path: 'img/config/entidades', name: $customFileName);

            if ($this->imagePrev != null) {
                if (file_exists('storage/img/config/entidades' . '/' . $this->imagePrev)) {
                    unlink('storage/img/config/entidades' . '/' . $this->imagePrev);
                }
            }
        }

        if ($this->selected_id) {
            $record = Entidad::find($this->selected_id);
            $record->update([
                'entidad' => $this->entidad,
                'name' => $this->name,
                'nombre' => $this->entidad . ' ' . $this->name,
                'color' => $this->color,
                'addres' => $this->addres,
                'phone' => $this->phone,
                'image' => $customFileName,
            ]);

            $this->dispatch('closeModal');
            $this->dispatch('msg-updated');
            $this->updateTable();
        }
    }

    #[On('deleteEntidad')]
    public function destroy(Entidad $id)
    {
        if ($id) {
            $record = Entidad::findOrFail($id);
            $this->imagePrev = $record-> image;
            $id->delete();
            if($this->imagePrev != null){
                if(file_exists('storage/img/config/entidades' .'/'.$this->imagePrev)){
                    unlink('storage/img/config/entidades' .'/'.$this->imagePrev);
                }
            }
            $this->updateTable();
            $this->dispatch('msg-deleted');
        }
    } 
}
