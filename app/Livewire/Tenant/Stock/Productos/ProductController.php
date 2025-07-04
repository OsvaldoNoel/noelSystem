<?php

namespace App\Livewire\Tenant\Stock\Productos;

use App\Models\Configtenants;
use App\Models\Product; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads; 

class ProductController extends Component
{
    use WithFileUploads;

    public $selected_id, $code, $name, $stock=0, $stockMin , $stockMax, $costoPromedio=0, $priceList1, $priceList2, $priceList3, $tipoUtilidad=2, $image, $status, $vto=0, $redondeo=10, $categoria_id, $subcategoria_id, $marca_id, $presentation_id; 
    public $componentName, $imagePrev; 
    public $datos, $porcent_lista1, $porcent_lista2, $porcent_lista3;

    public function mount()
    {
        $this->componentName = 'Producto'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $this->datos = Product::query() 
            ->select(
                'products.id',
                'products.tenant_id',
                'products.code',
                'products.name',
                'products.stock',
                'products.stockMin',
                'products.stockMax',
                'products.costoPromedio',
                'products.priceList1',
                'products.priceList2',
                'products.priceList3',
                'products.image',
                'products.status',
                'products.vto',

                'categorias.name as categoria_name',
                'subcategorias.name as subcategoria_name',
                'marcas.name as marca_name',
                'presentations.name as presentation_name',
            ) 
            ->join('categorias', 'products.categoria_id', '=', 'categorias.id')
            ->join('subcategorias', 'products.subcategoria_id', '=', 'subcategorias.id')
            ->join('marcas', 'products.marca_id', '=', 'marcas.id')
            ->join('presentations', 'products.presentation_id', '=', 'presentations.id')
            ->get()->toJson();
        $this->dispatch($this->componentName, $this->datos);  
    } 

    #[On('statusProducto')]  
    public function status($id)
    {  
        $record = Product::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]); 
    }

    #[On('vtoProducto')]  
    public function vto($id)
    {  
        $record = Product::findOrFail($id);
        ($record->vto == 1) ? $record->update(['vto' => 0]): $record->update(['vto' => 1]);
    }

    public function vtoSwith()
    {   
        $this->vto == 1 ? $this->vto = 1 : $this->vto = 0; 
    }

    public function calcularRedondeo()
    {    
        if($this->costoPromedio != 0){
            if($this->costoPromedio > 0 && $this->costoPromedio < 500){
                $this->redondeo = 10; 
            }elseif ($this->costoPromedio > 499 && $this->costoPromedio < 5000) {
                $this->redondeo = 100;
            }elseif ($this->costoPromedio > 4999 && $this->costoPromedio < 50000) {
                $this->redondeo = 500;
            }elseif ($this->costoPromedio > 49999 && $this->costoPromedio < 500000) {
                $this->redondeo = 1000; 
            }elseif ($this->costoPromedio > 499999) {
                $this->redondeo = 10000;
            }
        };
        $this->costoPromedio = number_format($this->costoPromedio,0,",",".");
    }

    public function updatedTipoUtilidad()
    {   
        $config = Configtenants::all();  
        foreach($config as $dato) {
            if($this->tipoUtilidad == 1){
                $this->porcent_lista1 = $dato->bajaLista1;
                $this->porcent_lista2 = $dato->bajaLista2;
                $this->porcent_lista3 = $dato->bajaLista3;
            }

            if($this->tipoUtilidad == 2){
                $this->porcent_lista1 = $dato->mediaLista1;
                $this->porcent_lista2 = $dato->mediaLista2;
                $this->porcent_lista3 = $dato->mediaLista3;
            }
            
            if($this->tipoUtilidad == 3){
                $this->porcent_lista1 = $dato->altaLista1;
                $this->porcent_lista2 = $dato->altaLista2;
                $this->porcent_lista3 = $dato->altaLista3;
            }

            if($this->tipoUtilidad == 4){
                $this->porcent_lista1 = null;
                $this->porcent_lista2 = null;
                $this->porcent_lista3 = null;
            }
        }; 
        $this->calcularPrecio(); 
    }

    public function calcularPrecio()
    {   
        if($this->tipoUtilidad != 4){
            $redondeo = (int)str_replace(".", "" , $this->redondeo);
            $costoPromedio = (int)str_replace(".", "" , $this->costoPromedio); 
     
            $this->priceList1 = number_format((ceil((intval($costoPromedio * (float)(($this->porcent_lista1 / 100) + 1))) / $redondeo) * $redondeo),0,",",".");
            $this->priceList2 = number_format((ceil((intval($costoPromedio * (float)(($this->porcent_lista2 / 100) + 1))) / $redondeo) * $redondeo),0,",",".");
            $this->priceList3 = number_format((ceil((intval($costoPromedio * (float)(($this->porcent_lista3 / 100) + 1))) / $redondeo) * $redondeo),0,",",".");
        }
    }

    public function generateCode()
    {   
        if ($this->selected_id){
            $this->code = Auth::user()->tenant_id . ($this->selected_id + 1000);
        }else{
            $this->code = Auth::user()->tenant_id . (Product::count() + 1 + 1000);
        } 
    }

    public function convertInteger()
    {
        $List1 = (int)str_replace(".", "" , $this->priceList1); 
        $List2 = (int)str_replace(".", "" , $this->priceList2); 
        $List3 = (int)str_replace(".", "" , $this->priceList3); 
        $redondeo = (int)str_replace(".", "" , $this->redondeo);
        $stockMin = (int)str_replace(".", "" , $this->stockMin);
        $stockMax = (int)str_replace(".", "" , $this->stockMax);

        $List1 != null ? $this->priceList1 = number_format($List1,0,",",".") : $this->priceList1 = null;
        $List2 != null ? $this->priceList2 = number_format($List2,0,",",".") : $this->priceList2 = null;
        $List3 != null ? $this->priceList3 = number_format($List3,0,",",".") : $this->priceList3 = null;   

        $redondeo == null || $redondeo > 10 ? $this->redondeo = number_format($redondeo,0,",",".") : $this->redondeo = 10;
        $stockMin != null ? $this->stockMin = number_format($stockMin,0,",",".") : $this->stockMin = null;
        $stockMax != null ? $this->stockMax = number_format($stockMax,0,",",".") : $this->stockMax = null;
        
        $this->calcularPrecio();
    }

    public function inicializar()
    {
        $this->imagePrev = null;
        $this->image = null;
    }
	
    public function cancel()
    {
		$this->reset(['selected_id', 'categoria_id', 'subcategoria_id', 'marca_id','presentation_id','code','name','stock','stockMin','stockMax','costoPromedio','priceList1','priceList2','priceList3','tipoUtilidad','redondeo','vto','image','imagePrev','status']);
        $this->resetErrorBag();
        $this->resetValidation();
    } 


    #[On('addProducto')]
    public function addProducto()
    { 
        $this->cancel();
        $this->dispatch('categoria_id', $this->categoria_id);
        $this->dispatch('subcategoria_id', $this->subcategoria_id);
        $this->dispatch('presentation_id', $this->presentation_id);
        $this->dispatch('marca_id', $this->marca_id);
        
        $this->updatedTipoUtilidad();
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre del Producto es requerido',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',

		'code.required' => 'El código del Producto es requerido',
		'code.unique' => 'Un Producto con este código ya fue registrado',
        
		'categoria_id.required' => 'Debe seleccionar una Categoria',
		'subcategoria_id.required' => 'Debe seleccionar una Subcategoria',
		'marca_id.required' => 'Debe seleccionar una Marca',
        'presentation_id.required' => 'Debe seleccionar una Presentacion',

        'image.image' => 'El fomato del archivo debe ser de tipo imagen',
        'image.max' => 'El tamano maximo es de 1024 bits',
		'stockMin.required' => 'El stock minimo es requedrido',
        'stockMax.required' => 'El stock maximo es requedrido',

        'priceList1.required' => 'El precio para lista 1 es requedrido',
        'priceList2.required' => 'El precio para lista 2 es requedrido',
        'priceList3.required' => 'El precio para lista 3 es requedrido',
    ];

    public function store()
    {  
        $this->validate([
        'name' => ['required', 'min:3', 'max:50'],
        'code' => [
            'required',
                Rule::unique('products')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
		'categoria_id' => 'required',
		'subcategoria_id' => 'required',
		'marca_id' => 'required',
        'presentation_id' => 'required',
		'stockMin' => 'required',
        'stockMax' => 'required', 

        'priceList1' => ['required_if:costoPromedio,!=0'],
        'priceList2' => ['required_if:costoPromedio,!=0'],
        'priceList3' => ['required_if:costoPromedio,!=0'],

        ]);

		$customFileName = null;
 
        if($this->image)
        {
            $this->validate([
                'image' => ['image', 'max:1024'],
            ]);

            $customFileName = uniqid(). '_.' .$this->image->extension();
            $this->image->storeAs(path: 'img/'. Auth::user()->tenant_id . '/productos' , name: $customFileName); 
        }

        Product::create([ 
            'tenant_id' => Auth::user()->tenant_id,
			'code' => $this-> code,
			'name' => $this-> name,
			'image' => $customFileName,
			'categoria_id' => $this-> categoria_id,
			'subcategoria_id' => $this-> subcategoria_id,
			'marca_id' => $this-> marca_id,
			'presentation_id' => $this-> presentation_id,
            'stock' => $this-> stock,
            'stockMin' => (int)str_replace(".", "" , $this-> stockMin),
            'stockMax' => (int)str_replace(".", "" , $this-> stockMax),
            'redondeo' => (int)str_replace(".", "" , $this-> redondeo),
            'costoPromedio' => (int)str_replace(".", "" , $this-> costoPromedio),
            'priceList1' => (int)str_replace(".", "" , $this->priceList1),
            'priceList2' => (int)str_replace(".", "" , $this->priceList2),
            'priceList3' => (int)str_replace(".", "" , $this->priceList3),
            'tipoUtilidad' => $this-> tipoUtilidad,
            'vto' => $this-> vto,
        ]);
        
        $this->cancel();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }
 
    #[On('editProducto')]
    public function edit($id)
    {
		$this->cancel();

        $record = Product::findOrFail($id); 
        $this->selected_id = $id; 
		$this->code = $record-> code;
		$this->name = $record-> name;
		$this->imagePrev = $record-> image;
		$this->categoria_id = $record-> categoria_id;
		$this->subcategoria_id = $record-> subcategoria_id;
		$this->marca_id = $record-> marca_id;
		$this->presentation_id = $record-> presentation_id;
        $this->costoPromedio = number_format($record-> costoPromedio,0,",",".");
        $this->stockMax = number_format($record-> stockMax,0,",",".");
        $this->stockMin = number_format($record-> stockMin,0,",",".");
        $this->priceList1 = number_format($record-> priceList1,0,",",".");
        $this->priceList2 = number_format($record-> priceList2,0,",",".");
        $this->priceList3 = number_format($record-> priceList3,0,",",".");
        $this->redondeo = number_format($record-> redondeo,0,",",".");
        $this->tipoUtilidad = $record-> tipoUtilidad;  
        $this->vto = $record-> vto; 

        $this->dispatch('categoria_id', $this->categoria_id);
        $this->dispatch('subcategoria_id', $this->subcategoria_id);
        $this->dispatch('presentation_id', $this->presentation_id);
        $this->dispatch('marca_id', $this->marca_id);
        
        $this->dispatch('showModal', $this->componentName);
        //$this->calcularRedondeo();
        $this->updatedTipoUtilidad();
    }

    #[On('selectedCategoria')]  
    public function selectedCategoria($id)
    {   
        $this->categoria_id = $id;   
    }

    #[On('selectedSubcategoria')]  
    public function selectedSubcategoria($id)
    {   
        $this->subcategoria_id = $id;   
    }

    #[On('selectedMarca')]  
    public function selectedMarca($id)
    {   
        $this->marca_id = $id;   
    }

    #[On('selectedPresentation')]  
    public function selectedPresentation($id)
    {   
        $this->presentation_id = $id;   
    }

    public function update()
    { 
        $this->validate([
		'code' => ['required',
                    Rule::unique('products')->ignore($this->selected_id)->where(function ($query) {
                        $query->where('tenant_id', Auth::user()->tenant_id);
                    })
				],
		'name' => ['required', 'min:3', 'max:50'],
		'categoria_id' => 'required',
		'subcategoria_id' => 'required',
		'marca_id' => 'required',
		'presentation_id' => 'required',
        'stockMin' => 'required',
        'stockMax' => 'required',

        'priceList1' => ['required_if:costoPromedio,!=0'],
        'priceList2' => ['required_if:costoPromedio,!=0'],
        'priceList3' => ['required_if:costoPromedio,!=0'],
        ]); 
        
		$customFileName = $this->imagePrev;
    
            if($this->image)
            {
                $this->validate([
                    'image' => ['image', 'max:1024'],
                ]);
    
                $customFileName = uniqid(). '_.' .$this->image->extension();  
                $this->image->storeAs(path: 'img/'. Auth::user()->tenant_id . '/productos' , name: $customFileName); 

                if($this->imagePrev != null)
                {
                    if(file_exists('storage/img/'. Auth::user()->tenant_id . '/productos' .'/'.$this->imagePrev))
                    {
                        unlink('storage/img/'. Auth::user()->tenant_id . '/productos' .'/'.$this->imagePrev);
                    }
                }
            }

        if ($this->selected_id) {
			$record = Product::find($this->selected_id);
            $record->update([ 
			'code' => $this-> code,
			'name' => $this-> name,
			'image' => $customFileName,
			'categoria_id' => $this-> categoria_id,
			'subcategoria_id' => $this-> subcategoria_id,
			'marca_id' => $this-> marca_id,
			'presentation_id' => $this-> presentation_id,
            'stockMin' => str_replace(".", "" , $this-> stockMin),
            'stockMax' => str_replace(".", "" , $this-> stockMax),
            'costoPromedio' => str_replace(".", "" , $this-> costoPromedio),
            'redondeo' => str_replace(".", "" , $this-> redondeo),
            'priceList1' => str_replace(".", "" , $this->priceList1),
            'priceList2' => str_replace(".", "" , $this->priceList2),
            'priceList3' => str_replace(".", "" , $this->priceList3),
            'tipoUtilidad' => $this-> tipoUtilidad,
            'vto' => $this-> vto,
            ]);

            
            $this->cancel();
            $this->dispatch('closeModal', $this->componentName);
            $this->dispatch('msg-updated'); 
            $this->updateTable(); 
        }
    }

    #[On('deleteProducto')]
    public function destroy(Product $id)
    {
        if ($id) {
            $record = Product::findOrFail($id);
            $this->imagePrev = $record-> image;
            $id->delete();
            if($this->imagePrev != null){
                    if(file_exists('storage/img/'. Auth::user()->tenant_id . '/productos' .'/'.$this->imagePrev)){
                        unlink('storage/img/'. Auth::user()->tenant_id . '/productos' .'/'.$this->imagePrev);
                    }
                }
            $this->updateTable();
            $this->dispatch('msg-deleted');
        }
    }

}
