<div>

    @include('layouts.landlord.partial.include.header')

    <div x-data="{ show: {{ $bool }} }">

        <div x-show="show">
            @include('layouts.landlord.partial.include.sidebar')
        </div>
        
        <div x-show="!show">
            @include('layouts.landlord.partial.include.top-nav')
        </div>
         
        <div wire:ignore>
            @include('layouts.landlord.partial.include.theme-panel')
        </div>
            
    </div>      

</div>