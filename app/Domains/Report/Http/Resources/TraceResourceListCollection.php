<?php

namespace App\Domains\Report\Http\Resources;

use App\Traits\PaginateTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TraceResourceListCollection extends ResourceCollection
{
    use PaginateTrait;
    public $collects = TraceResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->paginateResponse($this->collection, $this->resource);
    }
}
