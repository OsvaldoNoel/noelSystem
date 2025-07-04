<div>

    @include('layouts.tenant.partial.include.header')

    <div x-data="{ show: {{ $bool }} }">

        <div x-show="show">
            @include('layouts.tenant.partial.include.sidebar')
        </div>
        
        <div x-show="!show">
            @include('layouts.tenant.partial.include.top-nav')
        </div>
         
        <div wire:ignore>
            @include('layouts.tenant.partial.include.theme-panel')
        </div>
            
    </div>      

</div>