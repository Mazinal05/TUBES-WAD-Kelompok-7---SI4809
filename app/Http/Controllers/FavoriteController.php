<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($id)
    {
        $user = Auth::user();
        $umkm = Umkm::findOrFail($id);

        if ($user->favorites()->where('umkm_id', $id)->exists()) {
            $user->favorites()->detach($id);
            return back()->with('success', 'UMKM dihapus dari favorit.');
        } else {
            $user->favorites()->attach($id);
            return back()->with('success', 'UMKM ditambahkan ke favorit!');
        }
    }

    public function index()
    {
        $favorites = Auth::user()->favorites;
        return view('favorites.index', compact('favorites'));
    }
}
