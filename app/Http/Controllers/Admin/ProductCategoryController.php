<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use BalajiDharma\LaravelAdminCore\Requests\StoreMenuItemRequest;
use BalajiDharma\LaravelAdminCore\Requests\UpdateMenuItemRequest;
use BalajiDharma\LaravelMenu\Models\Menu;
use BalajiDharma\LaravelMenu\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Menu $menu)
    {
        $items = (new MenuItem)->toTree($menu->id, true);

        return Inertia::render('Admin/Menu/Item/Index', [
            'menu' => $menu,
            'items' => $items,
            'can' => [
                'create' => Auth::user()->can('menu.item create'),
                'edit' => Auth::user()->can('menu.item edit'),
                'delete' => Auth::user()->can('menu.item delete'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(Menu $menu)
    {
        $itemOptions = MenuItem::selectOptions($menu->id, null, true);
        $roles = Role::all()->pluck('name', 'id');

        return Inertia::render('Admin/Menu/Item/Create', compact('menu', 'itemOptions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMenuItemRequest $request, Menu $menu)
    {
        $menu->menuItems()->create($request->except(['roles']));

        $roles = $request->roles ?? [];
        $menu->assignRole($roles);

        return redirect()->route('admin.menu.item.index', $menu->id)
            ->with('message', 'Menu created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(Menu $menu, MenuItem $item)
    {
        $itemOptions = MenuItem::selectOptions($menu->id, $item->parent_id ?? $item->id);
        $roles = Role::all()->pluck('name', 'id');
        $itemHasRoles = array_column(json_decode($item->roles, true), 'id');

        return Inertia::render('Admin/Menu/Item/Edit', compact('menu', 'item', 'itemOptions', 'roles', 'itemHasRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMenuItemRequest $request, Menu $menu, MenuItem $item)
    {
        $item->update($request->except(['roles']));

        $roles = $request->roles ?? [];
        $item->syncRoles($roles);

        return redirect()->route('admin.menu.item.index', $menu->id)
            ->with('message', 'Menu Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BalajiDharma\LaravelMenu\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu $menu, MenuItem $item)
    {
        $item->delete();

        return redirect()->route('admin.menu.item.index', $menu->id)
            ->with('message', __('Menu deleted successfully'));
    }
}
