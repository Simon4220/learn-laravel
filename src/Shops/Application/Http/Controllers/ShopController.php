<?php

namespace Shops\Application\Http\Controllers;

use Accounts\Contracts\DataTransferObjects\UserDto;
use Accounts\Domain\Models\User;
use Accounts\Domain\Services\UserService;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotDeletedException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotUpdatedException;
use App\Exceptions\EntityValidationException;
use App\Helpers\DomainModelController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Domain\Models\Shop;
use Shops\Domain\Services\ShopService;

class ShopController extends Controller
{
    use DomainModelController;

    public function __construct(
        private readonly ShopService $shopService
    ) {
    }

    /**
     * Список пользователей
     * GET /shops
     */
    public function index(Request $request): \Inertia\Response
    {
        abort_if($request->user()->cannot('viewAny', Shop::class), 403);

        $paginatedShops = $this->shopService->list(
            searchQuery: $request->get('q'),
        );

        return Inertia::render('Shops/Index', [
            'shops' => $this->outputPaginatedList($paginatedShops, function (ShopDto $shop) {
                return [
                    'id' => $shop->id,
                    'title' => $shop->title,
                    'url' => $shop->url,
                    'created_at' => $shop->created_at
                ];
            }),
            'initialFilter' => $request->q,
        ]);
    }

    /**
     * Форма создания пользователя
     * GET /shops/create
     */
    public function create(Request $request)
    {
        abort_if($request->user()->cannot('create', Shop::class), 403);

        return Inertia::render('Shops/Create');
    }

    /**
     * Сохранение созданного магазина
     * POST /shops
     */
    public function store(Request $request)
    {
        abort_if($request->user()->cannot('create', Shop::class), 403);

        try {
            $this->shopService->create(
                title: $request->string('title'),
                url: $request->string('url')
            );
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotCreatedException $exception) {
            return back()->withErrors(['name' => "Не удается создать магазин: {$exception->getMessage()}"]);
        }

        return redirect()->route('shops.index');
    }

    /**
     * Страница магазина
     * GET /shops/{id}
     */
    public function show(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('view', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Show', [
            'shop' => (array)$shopDto,
        ]);

    }

    /**
     * Форма редактирования магазина
     * GET /shops/{id}/edit
     */
    public function edit(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('update', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);

        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Edit', [
            'id' => $shopDto->id,
            'values' => $shopDto,
        ]);
    }

    /**
     * Сохранение редактируемого магазина
     * PUT /shops/{id}
     */
    public function update(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('update', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
            $this->shopService->update(
                id: $shopDto->id,
                title: $request->string('title'),
                url: $request->string('url'),
            );
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotUpdatedException $exception) {
            return back()->withErrors(['email' => 'Не удается отредактировать магазин: ' . $exception->message]);
        }

        return redirect()->route('shops.index');
    }

    /**
     * Удаление магазина
     * DELETE /shops/{id}
     */
    public function destroy(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('delete', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);

            $this->shopService->delete($shopDto->id);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityNotDeletedException $exception) {
            // TODO В будущем нужно логировать такие ошибки, и выводить системную ошибку.
            return back()->withErrors(['id' => 'Не удается удалить магазин: ' . $exception->message]);
        }

        return Redirect::back(303);
    }
}
