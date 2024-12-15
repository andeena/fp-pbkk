<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class HomeController extends Controller
{
    public function redirect()
    {
        $usertype = Auth::user()->usertype;

        if ($usertype == '1') {
            return view('admin.home');
        } else {
            $data = Product::paginate(3);
            $user = auth()->user();
            $count = Cart::where('phone', $user->phone)->count();
            return view('User.home', compact('data', 'count'));
        }
    }

    public function index()
    {
        if (Auth::id()) {
            return redirect('redirect');
        } else {
            $data = Product::paginate(3);
            return view('User.home', compact('data'));
        }
    }

    public function addcart(Request $request, $id)
    {
        if (Auth::id()) {
            $user = auth()->user();
            $product = Product::find($id);

            // Gunakan AI untuk klasifikasi kategori
            $category = $this->classifyCategory($product->title);

            $cart = new Cart;
            $cart->name = $user->name;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->product_title = $product->title;
            $cart->price = $product->price;
            $cart->quantity = $request->quantity;
            $cart->category = $category; // Tambahkan kategori hasil AI
            $cart->save();

            return redirect()->back()->with('status', 'Product added to cart successfully');
        } else {
            return redirect('login');
        }
    }

    public function showcart()
    {
        $user = auth()->user();

        $cart = Cart::where('phone', $user->phone)->get();
        $count = Cart::where('phone', $user->phone)->count();
        return view('User.showcart', compact('count', 'cart'));
    }

    public function deletecart($id)
    {
        $data = Cart::find($id);
        $data->delete();
        return redirect()->back()->with('status', 'Product deleted from cart successfully');
    }

    public function showProductsByCategory($category)
    {
        // Ambil produk berdasarkan kategori
        $products = Product::where('category', $category)->paginate(3);

        if ($products->isEmpty()) {
            return redirect()->back()->with('status', 'No products found for this category');
        }

        return view('User.home', compact('products', 'category'));
    }

    public function classifyCategory($productTitle)
    {
        // API URL Roboflow
        $apiUrl = 'https://detect.roboflow.com/klasifikasi-produk/4?api_key=fC8vXFLDrKrKvssWHpZy';

        // Kirim Permintaan ke API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'title' => $productTitle, // Kirimkan judul produk
        ]);

        // Periksa apakah API berhasil
        if ($response->successful()) {
            // Ambil kategori yang dikembalikan oleh AI
            $category = $response->json()['category'] ?? null;

            return $category;
        }

        // Jika gagal, return null atau error
        return 'Uncategorized';
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');

        $data = Product::where('title', 'like', '%'.$keyword.'%')
                        ->orWhere('description', 'like', '%'.$keyword.'%')
                        ->get();

        return view('User.home', compact('data'));
    }




    public function confirmorder(Request $request)
    {
        $user = auth()->user();
        $name = $user->name;
        $phone = $user->phone;
        $address = $user->address;

        foreach ($request->productname as $key => $productname) {
            $order = new Order;
            $order->name = $name;
            $order->phone = $phone;
            $order->address = $address;
            $order->product_name = $request->productname[$key];
            $order->quantity = $request->quantity[$key];
            $order->price = $request->price[$key];
            $order->status = 'Pending';
            $order->save();
        }

        DB::table('carts')->where('phone', $user->phone)->delete();
        return redirect()->back()->with('status', 'Order confirmed successfully');
    }
}
