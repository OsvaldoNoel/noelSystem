<?php

namespace App\Livewire\Tenant\Config\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserProfile;

class UserProfileEdit extends Component
{
    use WithFileUploads;

    // Datos del usuario
    public $name;
    public $lastname;
    public $phone;
    public $address;
    public $barrio;
    public $city;
    public $email;

    // Foto de perfil
    public $photo;
    public $temporaryPhotoUrl;
    public $photoUrl;
    public $defaultPhotoUrl;

    // Cambio de contraseña
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $listeners = [ 
        'profile-photo-updated' => 'updatePhotoFromDatabase'
    ];

    public function updatePhotoFromDatabase()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        $this->updatePhotoUrls($profile);
    }

    // Mensajes de validación personalizados (consistentes con creación de usuarios)
    protected $messages = [
        'name.required' => 'El nombre es requerido',
        'name.min' => 'Mínimo 3 caracteres',
        'name.max' => 'Máximo 50 caracteres',
        'lastname.required' => 'El apellido es requerido',
        'lastname.min' => 'Mínimo 3 caracteres',
        'lastname.max' => 'Máximo 50 caracteres',
        'phone.required' => 'El teléfono es requerido',
        'phone.min' => 'Mínimo 6 caracteres',
        'phone.max' => 'Máximo 50 caracteres',
        'email.required' => 'El email es requerido',
        'email.email' => 'El formato es inválido',
        'email.unique' => 'Este email ya está registrado en su empresa',
        'address.required' => 'La dirección es requerida',
        'address.min' => 'Mínimo 5 caracteres',
        'address.max' => 'Máximo 100 caracteres',
        'barrio.required' => 'El barrio es requerido',
        'barrio.min' => 'Mínimo 5 caracteres',
        'barrio.max' => 'Máximo 50 caracteres',
        'city.required' => 'La ciudad es requerida',
        'city.min' => 'Mínimo 5 caracteres',
        'city.max' => 'Máximo 50 caracteres',
        'current_password.required' => 'La contraseña actual es requerida',
        'current_password.current_password' => 'La contraseña actual es incorrecta',
        'password.required' => 'La nueva contraseña es requerida',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'photo.image' => 'El archivo debe ser una imagen',
        'photo.max' => 'La imagen no debe superar los 2MB',
    ];

    public function mount()
    {
        $user = User::find(Auth::id());
        $profile = $user->profile ?? new UserProfile();

        // Cargar datos editables
        $this->name = $profile->name;
        $this->lastname = $profile->lastname;
        $this->phone = $profile->phone;
        $this->address = $profile->address;
        $this->barrio = $profile->barrio;
        $this->city = $profile->city;
        $this->email = $user->email;

        // Inicializar URLs de foto
        $this->updatePhotoUrls($profile);
    }

    protected function updatePhotoUrls($profile)
    {
        // Prioridad 1: Mostrar vista previa temporal si existe
        if ($this->temporaryPhotoUrl) {
            $this->photoUrl = $this->temporaryPhotoUrl;
            return;
        }

        // Prioridad 2: Mostrar imagen guardada en perfil
        if ($profile->profile_photo_path) {
            $this->photoUrl = asset('storage/' . $profile->profile_photo_path) . '?' . now()->timestamp;
            return;
        }

        // Prioridad 3: Mostrar avatar por defecto
        $this->photoUrl = $this->getDefaultPhotoUrl($profile);
        $this->defaultPhotoUrl = $this->photoUrl;
    }

    protected function getDefaultPhotoUrl($profile)
    {
        $name = $profile ?
            urlencode($profile->name . ' ' . $profile->lastname) :
            urlencode('Usuario');

        return 'https://ui-avatars.com/api/?name=' . $name . '&color=7F9CF5&background=EBF4FF';
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo', ['photo' => 'image|max:2048']);

        // Mostrar spinner mientras se procesa
        $this->dispatch('show-loading');

        // Generar vista previa temporal
        $this->temporaryPhotoUrl = $this->photo->temporaryUrl();
        $this->photoUrl = $this->temporaryPhotoUrl;

        // Ocultar spinner cuando esté listo (se maneja automáticamente con wire:loading)
    }

    public function savePhoto()
    {
        $this->validate(['photo' => 'image|max:2048']);

        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();

        if ($this->photo) {
            try {
                // Generar nombre único
                $safeName = Str::slug($profile->name . ' ' . $profile->lastname, '_');
                $randomString = Str::random(8);
                $fileName = $user->id . '-' . $safeName . '-' . $randomString . '.' . $this->photo->extension();

                // Guardar nueva foto PRIMERO
                $path = $this->photo->storeAs('profile-photos', $fileName, 'public');

                // Actualizar modelo ANTES de eliminar la anterior
                $oldPhotoPath = $profile->profile_photo_path;
                $profile->profile_photo_path = $path;
                $profile->save();

                // Actualizar URLs SIN resetear propiedades todavía
                $newUrl = asset('storage/' . $path) . '?' . now()->timestamp;
                $this->photoUrl = $newUrl;
                $this->defaultPhotoUrl = $newUrl;

                // Eliminar foto anterior (si existe) DESPUÉS de que todo está listo
                if ($oldPhotoPath) {
                    Storage::disk('public')->delete($oldPhotoPath);
                }

                // Mostrar notificación
                $this->dispatch('notify', text: 'Foto de perfil actualizada', bg: 'success');

                // Emitir evento global para actualizar el header
                $this->dispatch('profile-photo-updated');

            } catch (\Exception $e) {
                $this->dispatch('notify', text: 'Error al actualizar foto: ' . $e->getMessage(), bg: 'danger');
            } finally {
                // Limpiar propiedades al final
                $this->reset(['photo', 'temporaryPhotoUrl']);
            }
        }
    }

    public function saveProfile()
    {
        $user = User::find(Auth::id());
        $profile = $user->profile ?? new UserProfile();

        $this->validate([
            'name' => 'required|min:3|max:50',
            'lastname' => 'required|min:3|max:50',
            'phone' => 'required|min:6|max:50',
            'address' => 'required|min:5|max:100',
            'barrio' => 'required|min:5|max:50',
            'city' => 'required|min:5|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($user) {
                    return $query->where('tenant_id', $user->tenant_id);
                })->ignore($user->id)
            ],
        ]);

        // Actualizar perfil
        $profile->update([
            'name' => $this->name,
            'lastname' => $this->lastname,
            'phone' => $this->phone,
            'address' => $this->address,
            'barrio' => $this->barrio,
            'city' => $this->city,
        ]);

        // Actualizar email si cambió
        if ($user->email !== $this->email) {
            $user->email = $this->email;
            $user->save();
        }

        $this->dispatch('notify', text: 'Datos personales actualizados', bg: 'success');
    }

    public function updatePassword()
    {
        $user = User::find(Auth::id()); // Obtener instancia completa del modelo

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([ // Ahora funciona correctamente
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('notify', text: 'Contraseña actualizada correctamente', bg: 'success');
    }

    public function render()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();

        return view('livewire.tenant.config.users.user-profile-edit', [
            'ci' => $profile->ci,
            'currentEmail' => $user->email,
        ]);
    }
}
