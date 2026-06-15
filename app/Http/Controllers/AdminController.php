<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Counter;
use App\Models\Ticket;
use App\Models\CallHistory;

class AdminController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        $counters = Counter::orderBy('sort_order')->get();
        return view('admin', compact('settings', 'counters'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'header_title' => 'required|string|max:255',
            'header_subtitle' => 'required|string|max:255',
            'header_address' => 'required|string|max:255',
            'marquee_text' => 'required|string',
            'static_text' => 'nullable|string',
            'media_type' => 'required|in:video,slideshow',
            'video_url' => 'nullable|string',
            'speech_rate' => 'required|numeric|min:0.5|max:2.0',
            'speech_pitch' => 'required|numeric|min:0.5|max:2.0',
            'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15360',
        ]);

        // Handle logo deletion
        if ($request->delete_logo == '1') {
            $oldLogo = Setting::where('key', 'header_logo')->value('value');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }
            Setting::updateOrCreate(['key' => 'header_logo'], ['value' => null]);
        }

        // Handle logo upload
        if ($request->hasFile('header_logo')) {
            $file = $request->file('header_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/logo');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);
            $relativePath = 'uploads/logo/' . $filename;
            
            // Delete old file if it exists
            $oldLogo = Setting::where('key', 'header_logo')->value('value');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }
            
            Setting::updateOrCreate(['key' => 'header_logo'], ['value' => $relativePath]);
        }

        foreach ($data as $key => $value) {
            if ($key !== 'header_logo') {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui.',
        ]);
    }

    public function storeCounter(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:counters,id',
            'name' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        Counter::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'room' => $request->room,
                'sort_order' => $request->sort_order
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil disimpan.',
        ]);
    }

    public function deleteCounter(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:counters,id',
        ]);

        Counter::destroy($request->id);

        return response()->json([
            'success' => true,
            'message' => 'Loket berhasil dihapus.',
        ]);
    }

    public function reset()
    {
        // Safe delete for any SQL dialect without table locks/foreign keys issues
        Ticket::query()->delete();
        CallHistory::query()->delete();
        
        // Reset counters current number
        Counter::query()->update(['current_call_number' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Seluruh antrean telah berhasil direset ke nomor 1.',
        ]);
    }

    public function uploadSlideshowImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:15360',
        ]);

        $setting = Setting::firstOrCreate(['key' => 'slideshow_images'], ['value' => '[]']);
        $images = json_decode($setting->value, true) ?: [];

        if (count($images) >= 9) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah maksimal gambar adalah 9.',
            ]);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'slide_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/slideshow');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);
            $relativePath = 'uploads/slideshow/' . $filename;
            
            $images[] = $relativePath;
            
            $setting->value = json_encode($images);
            $setting->save();

            return response()->json([
                'success' => true,
                'images' => $images,
                'message' => 'Gambar berhasil diunggah.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada berkas yang diunggah.',
        ]);
    }

    public function deleteSlideshowImage(Request $request)
    {
        $request->validate([
            'image_url' => 'required|string',
        ]);

        $setting = Setting::firstOrCreate(['key' => 'slideshow_images'], ['value' => '[]']);
        $images = json_decode($setting->value, true) ?: [];

        $targetUrl = $request->image_url;
        
        // Find and remove from array
        $key = array_search($targetUrl, $images);
        if ($key !== false) {
            unset($images[$key]);
            $images = array_values($images); // Re-index array
            
            // Delete physical file from disk
            // Parse filename from URL
            $parsedUrl = parse_url($targetUrl);
            $path = $parsedUrl['path'] ?? ''; 
            
            // Extract the relative path starting from 'uploads/slideshow'
            $parts = explode('uploads/slideshow/', $path);
            if (isset($parts[1])) {
                $filePath = public_path('uploads/slideshow/' . $parts[1]);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $setting->value = json_encode($images);
            $setting->save();

            return response()->json([
                'success' => true,
                'images' => $images,
                'message' => 'Gambar berhasil dihapus.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gambar tidak ditemukan.',
        ]);
    }
}
