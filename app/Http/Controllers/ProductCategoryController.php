<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = @ProductCategory::query();

            return DataTables::of($query)
                -> addColumn('action', function($item){
                    return '
                    <a href="' . route('dashboard.category.edit', $item->id) . '" class="inline-block border border-gray-500 bg-green-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-green-600 focus:outline-none focus:shadow-outline">
                            <i class="fas fa-edit text-lg"></i> Edit
                    </a>

                    <form class="inline-block" action="' . route('dashboard.category.destroy', $item->id) . '" method="POST">
                    <button class="inline-block border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline">
                        <i class="fas fa-trash text-lg"></i>
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request)
    {
        $data = $request->all();

        ProductCategory::create($data);

        return redirect()->route('dashboard.category.index')->with('success', 'Category has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $category)
    {
        return view('pages.dashboard.category.edit',[
            'item' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryRequest $request, ProductCategory $category)
    {
        $data  = $request->all();

        $category->update($data);

        return redirect()->route('dashboard.category.index')->with('success', 'Category gas been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()->route('dashboard.category.index')->with('success', 'Category has been deleted!');
    }
}
