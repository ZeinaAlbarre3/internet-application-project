<?php
namespace App\Domains\Complaint\Http\Resources;
use App\Traits\PaginateTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ComplaintHistoryResourceCollection extends ResourceCollection
{
    use PaginateTrait;
    public $collects = ComplaintHistoryResource::class;

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
