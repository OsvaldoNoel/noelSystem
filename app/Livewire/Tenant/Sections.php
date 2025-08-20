<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sections extends Component
{
    public $tenantName;
    public $user;
    public $profilePhotoUrl;
    public $sidebarOpen = false;
    public $showNormalUI;

    protected $listeners = [
        'profile-photo-updated' => 'updateProfilePhoto'
    ];

    public function mount($showNormalUi = false)
    {
        $this->showNormalUI = $showNormalUi;
        $this->loadUserData();
        $this->prepareProfilePhoto();
    } 


    public function loadUserData()
    {
        // 1. Instanciación explícita como en tu UserProfileEdit
        $user = User::find(Auth::id());

        // 2. Carga de relaciones con tu patrón de optional()
        $this->user = $user->load([
            'profile',
            'roles' => function ($query) use ($user) {
                $query->where('model_has_roles.tenant_id', $user->tenant_id);
            }
        ]);
    }

    public function prepareProfilePhoto()
    {
        $profile = $this->user->profile ?? new UserProfile();

        $this->profilePhotoUrl = $profile->profile_photo_path
            ? asset('storage/' . $profile->profile_photo_path) . '?' . now()->timestamp
            : $this->generateDefaultAvatar($profile);
    }

    protected function generateDefaultAvatar($profile)
    {
        $name = $profile
            ? urlencode($profile->name . ' ' . $profile->lastname)
            : urlencode('Usuario');

        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

    public function updateProfilePhoto()
    {
        $this->loadUserData(); // Recargar datos del usuario
        $this->prepareProfilePhoto(); // Regenerar URL de la foto 
    }

    public function getUserRoleText()
    {
        $role = $this->user->getRoleNames()->first();

        // Solo mostramos los roles específicos, cualquier otro será "Usuario"
        return match ($role) {
            User::ROLE_OWNER => 'Propietario',
            User::ROLE_ADMIN => 'Admin',
            default => 'Usuario'
        };
    }

    public function render()
    {
        return view('livewire.tenant.sections');
    }
}
