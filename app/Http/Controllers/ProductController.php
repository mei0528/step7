<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request) {
        $model = new Product();
        $products = $model->getList();
        $companies = Company::all();
        $products = Product::with('company')->get();
        $user=auth()->user();

    // 検索フォームからの入力を取得
    $keyword = $request->input('keyword');
    $companyId = $request->input('company_id');

    // デフォルトは全商品を取得
    $query = Product::query();
    $query->with('company');

    // 商品名での検索
    if ($keyword) {
        $query->where('product_name', 'like', '%' . $keyword . '%');
    }

    // メーカー名での検索
    if ($companyId) {
        $query->whereHas('company', function ($q) use ($companyId) {
            $q->where('id', $companyId);
        });
    }

    // 検索結果を取得
    $products = $query->get();
        return view('layouts.Product.list', ['products' => $products, 'companies' => $companies,'companyId' => $companyId]);
    }

    public function showRegistForm() {
        return view('regist');
    }

    public function create(){   
        $companies = Company::all();
        return view('layouts.Product.create', ['companies' => $companies]);
    }


    public function store(Request $request) {
        $request->validate([
        'product_name' => 'required|string|max:255',
        'company_id' => 'required|exists:companies,id',
        'price' => 'required|numeric',
        'stock' => 'required|numeric',
        'comment' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/products', 'public');
        } else {
            $imagePath = null;
        }

        $companyId = $request->company_id;

        Product::create([
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
            'image' => $imagePath,
        ]);
        

        return redirect()->route('products.index')->with('success', '商品が新規登録されました');

        
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();
    
        return redirect()->route('products.index')->with('success', '商品が削除されました');
    }
    
    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('layouts.Product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('layouts.Product.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
   

  
    $product->update($request->all());


    return redirect()->route('product.show', $product->id)->with('success', '商品情報が更新されました');
}
    }