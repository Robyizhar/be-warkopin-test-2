<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\User;
use DB;

class FetchProductController extends Controller
{
    public function index(Request $request) {

        $this->validate($request, [
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = Merchant::with('user', 'product')
            ->when($request->search, function($q) use($request){
                $q->where('name', 'ilike', '%'.$request->search.'%');
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate($request->page_size ?? 10000);

            return $this->respond($response, 'berhasil menampilkan data!', 200);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage(),500,null);
        }
    }
}
