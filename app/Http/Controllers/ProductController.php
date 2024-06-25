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
        $user = auth()->user();
    
        // 検索フォームからの入力を取得
        $keyword = $request->input('keyword');
        $companyId = $request->input('company_id');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $stockMin = $request->input('stock_min');
        $stockMax = $request->input('stock_max');
    
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
    
        // 価格範囲での検索
        if ($priceMin) {
            $query->where('price', '>=', $priceMin);
        }
    
        if ($priceMax) {
            $query->where('price', '<=', $priceMax);
        }
    
        // 在庫範囲での検索
        if ($stockMin) {
            $query->where('stock', '>=', $stockMin);
        }
    
        if ($stockMax) {
            $query->where('stock', '<=', $stockMax);
        }
    
        $products = $query->get();
    
        // AJAXリクエストかどうかをチェック
        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }
    
        return view('layouts.Product.list', ['products' => $products, 'companies' => $companies, 'companyId' => $companyId]);
    }

    public function showRegistForm() {
        return view('regist');
    }

    public function create(){   
        $companies = Company::all();
        return view('layouts.Product.create', ['companies' => $companies]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('layouts.Product.show', compact('product'));
    }


    
    public function store(Request $request) {
        try {
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
            }
    
            Product::create([
                'product_name' => $request->product_name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'image' => $imagePath,
            ]);
            
            return redirect()->route('products.index')->with('success', '商品が新規登録されました');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('layouts.Product.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id) {
        try {
            $product = Product::findOrFail($id);
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
            }
    
            
            $product->update([
                'product_name' => $request->product_name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'image' => $imagePath,
            ]);
            
            return redirect()->route('products.show', $product->id)->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    }
