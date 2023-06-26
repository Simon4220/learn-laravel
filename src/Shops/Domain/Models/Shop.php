<?php

namespace Shops\Domain\Models;

use App\Helpers\DomainModel;
use Shops\Contracts\DataTransferObjects\ShopDto;

/**
 * @property mixed $id
 * @property mixed $title
 * @property mixed $url
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Shop extends DomainModel
{
    protected $fillable = [
        'title',
        'url',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'title' => 'string',
        'url' => 'string',
    ];

    public function toDto(): mixed
    {
        return new ShopDto(
            id: $this->id,
            title: $this->title,
            url: $this->url,
            created_at: $this->created_at,
            updated_at: $this->updated_at
        );
    }

    public function fillableRules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:50'],
            'url' => ['required', 'string', 'url']
        ];
    }

    public function scopeMaybeSearch($query, ?string $q): void
    {
        if ($q !== null and $q !== '') {
            if (preg_match('/(http|https):\/\//', $q)) {
                $query->where('url', 'ilike', "%{$q}%");
            } else {
                $query->where('title', 'ilike', "%{$q}%");
            }
        }
    }
}
