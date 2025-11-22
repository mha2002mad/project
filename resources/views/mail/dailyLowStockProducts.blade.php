@component('mail::message')
# Low Stock Inventory Alert

The following items across your glbal inventory are currently below their minimum required stock levels and ned immediate attention.

@foreach ($items as $countryData)
## {{ $countryData['country'] }}

@foreach ($countryData['warehouses'] as $warehouseData)
### Warehouse: {{ $warehouseData['warehouse_location'] }}

@component('mail::table')
| Product (SKU) | Current Stock | Minimum Stock | Supplier Contact |
| :--- | :--- | :--- | :--- |
@foreach ($warehouseData['inventory'] as $item)
| **{{ $item['product'] }}** ({{ $item['sku'] }}) | **{{ $item['current_quantity'] }}** | {{ $item['minimium_quantity'] }} | {{ $item['supplier'] }} |
@endforeach
@endcomponent

@endforeach
---
@endforeach

<aside>
Please initiate reorder processes for all listed items to prevent stockouts.
</aside>

Thanks,
{{ config('app.name') }}
@endcomponent