<?php

namespace App\Livewire\Tenant\Config;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Panel de Configuración')]
class ConfigController extends Component
{
    // Opciones de configuración disponibles
    public array $configOptions = [
        [
            'title' => 'Gestión de Usuarios',
            'description' => 'Administra usuarios, roles y permisos del sistema',
            'icon' => 'fas fa-users-cog',
            'route' => 'app.usersTenant',
            'hover_color' => 'primary'
        ],
        [
            'title' => 'Gestión de Tesorería',
            'description' => 'Control de ingresos, egresos y reportes financieros',
            'icon' => 'fas fa-cash-register',
            'route' => 'app.tesoreriaConfig',
            'hover_color' => 'success'
        ],
        // [
        //     'title' => 'Utilidades Sobre Productos',
        //     'description' => 'Herramientas para gestión de productos y servicios',
        //     'icon' => 'fas fa-boxes',
        //     'route' => 'app.home',
        //     'hover_color' => 'info'
        // ],
        // [
        //     'title' => 'Gestión de Medios de Pago',
        //     'description' => 'Configuración de métodos de pago aceptados',
        //     'icon' => 'fas fa-credit-card',
        //     'route' => 'app.home',
        //     'hover_color' => 'warning'
        // ],
        // [
        //     'title' => 'Configuración del Sistema',
        //     'description' => 'Ajustes generales de la aplicación',
        //     'icon' => 'fas fa-cogs',
        //     'route' => 'app.home',
        //     'hover_color' => 'yellow'
        // ],
        // [
        //     'title' => 'Reportes y Analytics',
        //     'description' => 'Generación de reportes y estadísticas',
        //     'icon' => 'fas fa-chart-bar',
        //     'route' => 'app.home',
        //     'hover_color' => 'danger'
        // ]
    ];

    public function render()
    {
        return view('livewire.tenant.config.config-controller');
    }
}
