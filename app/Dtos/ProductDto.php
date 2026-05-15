<?php

namespace App\Dtos;

use App\Helpers\TaxHelper;
use App\Models\Category;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Validation\ValidationException;

class ProductDto extends BaseDto
{
    public function __construct(
        public string  $name,
        public ?string $description,
        public ?string $unit_uuid,
        public string  $price_list_uuid,
        public string  $supplier_uuid,
        public string  $category_uuid,
        public ?string $bar_code,
        public ?string $branch,
        public ?string $sku,
        public bool    $is_service,
        public string  $tax_uuid,
        public float   $price,
        public float   $min_price,
        public float   $special_price,
        public float   $cost,
        public float   $benefits,
        public float   $benefits_rate,
        public float   $product_no_tax,
        public ?float  $weight,
        public ?string $dimensions,
        public bool    $inventoried,
        public bool    $has_fraction,
        public bool    $status,
        public bool    $has_tax,
        public bool    $has_special,
        public bool    $has_promotion,
        public bool    $handle_warehouse,
        public array  $warehouse_product,
        public ?string $default_warehouse = null,
        public ?string $default_price_list = null
    )
    {
    }

    /**
     * Propiedades calculadas (getters)
     */
    public function getTaxRate(): float
    {
        return TaxHelper::getTaxRate($this->tax_uuid);
    }

    public function getTotalWithTax(): float
    {
        return TaxHelper::getTaxProduct($this->tax_uuid, $this->price);
    }


    /**
     * Crear DTO desde un array
     */
    public static function fromArray(array $data): self
    {
        $instance = new self(
            name: $data['name'] ?? '',
            description: $data['description'] ?? null,
            unit_uuid: $data['unit_uuid'] ?? null,
            price_list_uuid: $data['price_list_uuid'] ?? '',
            supplier_uuid: $data['supplier_uuid'] ?? '',
            category_uuid: $data['category_uuid'] ?? '',
            bar_code: $data['bar_code'] ?? null,
            branch: $data['branch'] ?? null,
            sku: $data['sku'] ?? null,
            is_service: (bool) ($data['is_service'] ?? false),
            tax_uuid: $data['tax_uuid'] ?? '',
            price: (float) ($data['price'] ?? 0),
            min_price: (float) ($data['min_price'] ?? 0),
            special_price: (float) ($data['special_price'] ?? 0),
            cost: (float) ($data['cost'] ?? 0),
            benefits: (float) ($data['benefits'] ?? 0),
            benefits_rate: (float) ($data['benefits_rate'] ?? 0),
            product_no_tax: (float) ($data['product_no_tax'] ?? 0),
            weight: isset($data['weight']) ? (float) $data['weight'] : null,
            dimensions: $data['dimensions'] ?? null,
            inventoried: (bool) ($data['inventoried'] ?? false),
            has_fraction: (bool) ($data['has_fraction'] ?? false),
            status: (bool) ($data['status'] ?? false),
            has_tax: (bool) ($data['has_tax'] ?? false),
            has_special: (bool) ($data['has_special'] ?? false),
            has_promotion: (bool) ($data['has_promotion'] ?? false),
            handle_warehouse: (bool) ($data['handle_warehouse'] ?? false),
            warehouse_product: $data['warehouse_product'] ?? null,
        );

        // warehouse
        if(empty($instance->default_warehouse)){
            $instance->default_warehouse = $instance->getDefaultWarehouse();
        }

        // Price list
        if(empty($instance->default_price_list)){
            $instance->default_price_list = $instance->getDefaultPriceList();
        }

        //
        return $instance;
    }

    /**
     * Sobrescribir toArray para incluir propiedades calculadas
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->getPrefix(),
            'description' => $this->description,
            'unit_uuid' => $this->unit_uuid,
            'price_list_uuid' => $this->price_list_uuid,
            'supplier_uuid' => $this->supplier_uuid,
            'category_uuid' => $this->category_uuid,
            'bar_code' => $this->bar_code,
            'branch' => $this->branch,
            'sku' => $this->sku,
            'is_service' => $this->is_service,
            'tax_uuid' => $this->tax_uuid,
            'price' => $this->price,
            'min_price' => $this->min_price,
            'special_price' => $this->special_price,
            'cost' => $this->cost,
            'benefits' => $this->benefits,
            'benefits_rate' => $this->benefits_rate,
            'product_no_tax' => $this->product_no_tax,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'inventoried' => $this->inventoried,
            'has_fraction' => $this->has_fraction,
            'status' => $this->status,
            'has_tax' => $this->has_tax,
            'has_special' => $this->has_special,
            'has_promotion' => $this->has_promotion,
            'handle_warehouse' => $this->handle_warehouse,
            'warehouse_product' => $this->warehouse_product,
            // Propiedades calculadas
            'tax_rate' => $this->getTaxRate(),
            'tax' => $this->getTotalWithTax(),
        ];
    }

    private function getPrefix():string
    {

//        Verificar si existe para no genear codigo
        $code = self::existsProducts($this->name);

        if ($code){
            return $code;
        }

//        Contar los registro
        $next_number = Product::count() + 1;
//        Buscar la categorya
        $category = Category::find($this->category_uuid);

//        Verificar si existe
        if(!$category->prefix){
            $prefix = "GEN";
        }else{
            $prefix = $category->prefix;
        }

//        Crea los datos
        $number_padded = str_pad((string)$next_number, 6, "0", STR_PAD_LEFT);

        return $prefix.$number_padded;

    }

    /**
     * @param string $name
     * @return string|null
     */
    private function existsProducts(string $name): string | null
    {
        $product = Product::where('name', $name)->first();

        return $product?->code;
    }

    /**
     * @return string
     */
    private function getDefaultWarehouse():string
    {
        // Tomar el primer almacen
        $warehouse =  Warehouse::first();

        // Verificar si existe
        if(!$warehouse){
            throw ValidationException::withMessages([
                'warehouse' => ['No se encontró un almacén por defecto configurado en el sistema.']
            ]);
        }

        // Devolver el id
        return $warehouse->uuid;
    }

    /**
     * @return string
     */
    private function getDefaultPriceList():string
    {
        // Lista de precio
        $priceList =  PriceList::first();

        // Verificar si existe
        if(!$priceList){
            throw ValidationException::withMessages([
                'price_list' => ['No se encontró una lista de precio por defecto configurado en el sistema.']
            ]);
        }

        // Devolver la lista de pecio
        return $priceList->uuid;
    }
}
