<?php

namespace Shops\Domain\Services;

use App\Contracts\DataTransferObjects\PaginatedListDto;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotFoundException;
use App\Helpers\DomainModelService;
use Illuminate\Database\QueryException;
use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Contracts\ShopServiceContract;
use Shops\Domain\Models\Shop;

class ShopService extends DomainModelService implements ShopServiceContract
{
    /**
     * @throws EntityNotFoundException
     */
    public function getById(int $id): ShopDto
    {
        $shop = Shop::query()->find($id);
        if (! $shop) {
            throw new EntityNotFoundException();
        }

        return $shop->toDto();
    }

    public function list(?string $searchQuery = null, int $perPage = 25): PaginatedListDto
    {
        $users = Shop::query()
            ->maybeSearch($searchQuery);

        return $this->toPaginatedListDto($users, $perPage);
    }

    public function create(string $title, string $url): ShopDto
    {
        $shop = new Shop();

        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);

        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotCreatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    public function update(int $id, ?string $title, ?string $url): ShopDto
    {
        $shop = Shop::query()->find($id);
        if (!$shop) {
            throw new EntityNotFoundException();
        }

        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);

        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotCreatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    public function delete(int $id): void
    {
        $shop = Shop::query()->find($id);
        if (! $shop) {
            throw new EntityNotFoundException();
        }

        $shop->delete();
    }
}
