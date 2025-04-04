<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sun;
use Illuminate\Http\Request;
use App\Models\Paronama;
use App\Models\Setting;
use App\Models\Location;
use App\Models\Hotlink;
use App\Models\HotlinksSpecial;
use Gumlet\ImageResize;
use Dnsimmons\Imager\Imager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    public function dashboard()
    {
        $hero = "Dashboard";
        $icon = "bi-pie-chart";

        return view('frontend.admin.index.index', compact('hero', 'icon'));
    }

    //Gallery
    public function gallery()
    {
        $hero = "Thư Viện Ảnh";
        $icon = "bi-images";
        $paronamas = Paronama::get();

        return view('frontend.admin.gallery.gallery', compact('hero', 'icon', 'paronamas'));
    }
    public function gallery_add()
    {
        $hero = "Thêm Ảnh 360";
        $icon = "bi-images";

        return view('frontend.admin.gallery.add', compact('hero', 'icon'));
    }

    public function gallery_delete(Request $request)
    {
        $paronama = Paronama::find($request->id);

        if (!$paronama) {
            return redirect()->route('gallery')->with('alert', [
                "type" => "error",
                "title" => __("Lỗi!"),
                "body" => __("Ảnh không tồn tại."),
            ]);
        }

        $imagePath = public_path("storage/" . $paronama->image);
        $directoryPath = dirname($imagePath);

        // Xóa toàn bộ thư mục chứa ảnh panorama (gồm ảnh gốc, tiles, fisheye)
        if (is_dir($directoryPath)) {
            $this->deleteDirectory($directoryPath);
        }

        // Xóa bản ghi trong database
        $paronama->delete();

        return redirect()->route('gallery')->with('alert', [
            "type" => "success",
            "title" => __("Thành công!"),
            "body" => __("Đã xóa ảnh thành công."),
        ]);
    }

    // Hàm đệ quy xóa thư mục và tất cả file bên trong
    private function deleteDirectory($dirPath)
    {
        if (!is_dir($dirPath)) {
            return;
        }

        $files = array_diff(scandir($dirPath), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($dirPath);
    }


    //Xử ảnh
    public function gallery_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:paronamas,slug',
            'image360' => 'required|image|mimes:jpeg,png,jpg',
        ], [
            'name.required' => 'Tên ảnh 360 là bắt buộc.',
            'name.string' => 'Tên ảnh 360 phải là chuỗi ký tự.',
            'name.max' => 'Tên ảnh 360 không được vượt quá 255 ký tự.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại, hãy chọn tên khác.',
            'image360.required' => 'Vui lòng chọn một file ảnh.',
            'image360.image' => 'File phải là một ảnh hợp lệ.',
            'image360.mimes' => 'Ảnh chỉ chấp nhận các định dạng: jpeg, png, jpg',
        ]);

        $slug = $request->input('slug');
        $directoryPath = '360/' . $slug;

        if (!is_dir(public_path('storage/' . $directoryPath))) {
            mkdir(public_path('storage/' . $directoryPath), 0755, true);
        }

        $uploadedPath = $request->file('image360')->storeAs(
            $directoryPath,
            $slug . '.' . $request->file('image360')->getClientOriginalExtension(),
            'public'
        );

        $sourceImagePath = public_path("storage/" . $uploadedPath);

        $webpPath = public_path("storage/{$directoryPath}/low.webp");

        // dd($sourceImagePath);

        $this->saveAsWebp($sourceImagePath, $webpPath);

        $this->createParonameScene($sourceImagePath, $webpPath);

        Paronama::create([
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $uploadedPath,
        ]);

        $alert = [
            "type" => "success",
            "title" => __("Thành công!"),
            "body" => __("Lưu ảnh thành công!"),
        ];

        return redirect()->route('gallery')->with('alert', $alert);
    }

    //Gọi các hàm tạo các ảnh cần thiết
    public function createParonameScene($sourceImagePath, $webpPath)
    {
        $outputDir = dirname($sourceImagePath) . '/tiles/';
        $fisheyePath = dirname($sourceImagePath) . '/fisheye.webp';

        if (!file_exists($sourceImagePath)) {
            throw new \Exception('Source image not found.');
        }

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $this->cropTilePanorama($sourceImagePath, $outputDir, 16, 8);

        $image = Image::make($webpPath);
        $imageWidth = $image->width();
        $imageHeight = $image->height();
        $fisheyeImage = $this->applyFisheyeEffect($image, $imageWidth, $imageHeight);
        $fisheyeImage->save($fisheyePath, 80, 'webp');
    }

    //chia ảnh thành 128 cái
    public function cropTilePanorama($sourceImagePath, $outputDir, $cols, $rows)
    {
        $image = new ImageResize($sourceImagePath);
        $imageWidth = $image->getSourceWidth();
        $imageHeight = $image->getSourceHeight();


        $tileWidth = (int) ($imageWidth / $cols);
        $tileHeight = (int) ($imageHeight / $rows);

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {

                $x = $col * $tileWidth;
                $y = $row * $tileHeight;

                $tile = $image;

                $tile->freecrop($tileWidth, $tileHeight, $x, $y);

                $outputFile = "{$outputDir}/tile_{$col}_{$row}.webp";
                $tile->save($outputFile, IMAGETYPE_WEBP);
            }
        }
    }

    //tạo ảnh fisheye cho hot link
    private function applyFisheyeEffect($image, $width, $height)
    {
        $radius = $height / 2;
        $centerX = $width / 2;
        $centerY = $height / 2;

        $fisheyeImage = Image::canvas($width, $height);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $dx = $x - $centerX;
                $dy = $y - $centerY;
                $distance = sqrt($dx * $dx + $dy * $dy);

                if ($distance <= $radius) {
                    $theta = atan2($dy, $dx);
                    $phi = ($distance / $radius) * (M_PI / 2);

                    $u = ($theta + M_PI) / (2 * M_PI);
                    $v = 1 - $phi / M_PI;

                    $sourceX = floor($u * $width);
                    $sourceY = floor($v * $height);

                    $sourceX = max(0, min($sourceX, $width - 1));
                    $sourceY = max(0, min($sourceY, $height - 1));

                    $color = $image->pickColor($sourceX, $sourceY, 'array');

                    $fisheyeImage->pixel($color, $x, $y);
                }
            }
        }
        return $fisheyeImage;
    }

    //lưu file dạng webp
    protected function saveAsWebp($sourceImagePath, $webpPath, $maxWidth = 1200, $maxHeight = 600)
    {
        if (!file_exists($sourceImagePath)) {
            throw new \Exception('Source image not found.');
        }

        $extension = strtolower(pathinfo($sourceImagePath, PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            throw new \Exception('Unsupported image format. Only JPG, JPEG, and PNG are supported.');
        }

        $sourceImage = null;
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $sourceImage = imagecreatefromjpeg($sourceImagePath);
        } elseif ($extension === 'png') {
            $sourceImage = imagecreatefrompng($sourceImagePath);
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        $aspectRatio = $origWidth / $origHeight;

        if ($origWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = $newWidth / 2;
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($extension === 'png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
        }

        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        $result = imagewebp($resizedImage, $webpPath, 80);

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        if (!$result) {
            throw new \Exception('Failed to save the image as WebP.');
        }
    }



















    public function location_list_update(Request $request)
    {
        $orderedIds = array_map('intval', json_decode($request->order));
        $current_list_id = Location::orderBy('sort')->pluck('id')->toArray();

        if ($orderedIds !== $current_list_id) {
            foreach ($orderedIds as $index => $id) {
                Location::where('id', $id)->update(['sort' => $index]);
            }

            return response()->json(['message' => 'Order updated successfully'], 200);
        }

        return response()->json(['message' => 'No changes detected in the order'], 200);
    }

    public function location_eye_set(Request $request)
    {
        $set_eye_location = Location::find($request->location_id);

        if (!$set_eye_location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $yaw = $request->input('yaw');
        $pitch = $request->input('pitch');

        $eyePosition = json_encode([
            'yaw' => $yaw,
            'pitch' => $pitch
        ]);

        $set_eye_location->eyes = $eyePosition;

        $set_eye_location->save();

        return response()->json(['success' => true, 'message' => 'Đặt tầm nhìn thành công!']);
    }

    //các hàm xử lý điểm ảnh
    public function location()
    {
        $hero = "Quản Lý Điểm Ảnh";
        $icon = "bi-geo-alt";

        $locations = Location::with(['sun', 'hotlinks.nextLocation.paronama:id,image', 'paronama:id,image', 'hotlinksSpecial'])
            ->orderBy('sort')
            ->get();

        return view('frontend.admin.location.index', compact('hero', 'icon', 'locations')); // Pass the data to the view
    }
    public function location_add()
    {
        $hero = "Quản lý điểm ảnh";
        $icon = "bi-bar-chart-line";
        $paronamas = Paronama::all();
        $locations = Location::all();
        $categories = Category::where('status', true)->get();

        return view('frontend.admin.location.add', compact('hero', 'icon', 'paronamas', 'locations', 'categories'));
    }

    //Edit Location
    public function location_edit(Request $request)
    {
        $location = Location::find($request->location_id_edit);

        $hero = "Sửa điểm ảnh";
        $icon = "bi-bar-chart-line";
        $paronamas = Paronama::all();
        $locations = Location::all();
        $categories = Category::where('status', true)->get();

        return view('frontend.admin.location.edit', compact('hero', 'icon', 'paronamas', 'locations', 'location', 'categories'));
    }

    public function location_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:locations,slug',
            'paronama_id' => 'nullable|exists:paronamas,id',
            'yaw' => 'nullable|string|max:255',
            'pitch' => 'nullable|string|max:255',
            'next_location_id' => 'nullable|exists:locations,id',
            'status' => 'nullable|in:on',
            'voice' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'voice_en' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'Tên là bắt buộc.',

            'name_en.required' => 'Tên địa điểm (English) là bắt buộc.',

            'slug.required' => 'Slug là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại.',

            'paronama_id.exists' => 'Paronama không hợp lệ.',

            'yaw.string' => 'Kinh Độ phải là chuỗi ký tự.',
            'yaw.max' => 'Kinh Độ không được vượt quá 255 ký tự.',

            'pitch.string' => 'Vĩ Độ phải là chuỗi ký tự.',
            'pitch.max' => 'Vĩ Độ không được vượt quá 255 ký tự.',

            'next_location_id.exists' => 'Location kế tiếp không hợp lệ.',

            'voice.mimes' => 'File âm thanh phải là định dạng mp3, wav hoặc ogg.',
            'voice.max' => 'Dung lượng file âm thanh không được vượt quá 2MB.',

            'voice_en.mimes' => 'File âm thanh (English) phải là định dạng mp3, wav hoặc ogg.',
            'voice_en.max' => 'Dung lượng file âm thanh (English) không được vượt quá 2MB.',

            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('voice')) {
            $data['voice'] = $request->file('voice')->store('voices', 'public');
        }
        if ($request->hasFile('voice_en')) {
            $data['voice_en'] = $request->file('voice_en')->store('voices', 'public');
        }

        $data['name'] = [
            'vi' => $request->input('name'),
            'en' => $request->input('name_en'),
        ];

        $data['description'] = [
            'vi' => $request->input('description'),
            'en' => $request->input('description_en'),
        ];

        unset($data['name_en']);
        unset($data['description_en']);

        $location = Location::create($data);

        $location->sort = $location->id;

        $location->save();

        return redirect()->route('location')->with([
            'alert_type' => 'success',
            'alert_content' => 'Thêm thành công!',
        ]);;
    }

    public function location_update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:locations,slug,' . $location->id,
            'paronama_id' => 'nullable|exists:paronamas,id',
            'yaw' => 'nullable|string|max:255',
            'pitch' => 'nullable|string|max:255',
            'next_location_id' => 'nullable|exists:locations,id',
            'status' => 'nullable|in:on',
            'voice' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'voice_en' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'Tên là bắt buộc.',

            'name_en.required' => 'Tên địa điểm (English) là bắt buộc.',

            'slug.required' => 'Slug là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại.',

            'paronama_id.exists' => 'Paronama không hợp lệ.',

            'yaw.string' => 'Kinh Độ phải là chuỗi ký tự.',
            'yaw.max' => 'Kinh Độ không được vượt quá 255 ký tự.',

            'pitch.string' => 'Vĩ Độ phải là chuỗi ký tự.',
            'pitch.max' => 'Vĩ Độ không được vượt quá 255 ký tự.',

            'next_location_id.exists' => 'Location kế tiếp không hợp lệ.',

            'voice.mimes' => 'File âm thanh phải là định dạng mp3, wav hoặc ogg.',
            'voice.max' => 'Dung lượng file âm thanh không được vượt quá 2MB.',

            'voice_en.mimes' => 'File âm thanh (English) phải là định dạng mp3, wav hoặc ogg.',
            'voice_en.max' => 'Dung lượng file âm thanh (English) không được vượt quá 2MB.',

            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('voice')) {
            if ($location->voice) {
                Storage::disk('public')->delete($location->voice);
            }
            $data['voice'] = $request->file('voice')->store('voices', 'public');
        }

        if ($request->hasFile('voice_en')) {
            if ($location->voice_en) {
                Storage::disk('public')->delete($location->voice_en);
            }
            $data['voice_en'] = $request->file('voice_en')->store('voices', 'public');
        }

        $data['name'] = [
            'vi' => $request->input('name'),
            'en' => $request->input('name_en'),
        ];

        $data['description'] = [
            'vi' => $request->input('description'),
            'en' => $request->input('description_en'),
        ];

        unset($data['name_en']);
        unset($data['description_en']);

        $location->update($data);  // Update the location
        $location->save();

        return redirect()->route('location')->with([
            'alert_type' => 'success',
            'alert_content' => 'Cập nhật thành công!',
        ]);
    }


    public function location_delete(Request $request)
    {
        $location = Location::find($request->location_id);

        if (!$location) {
            return redirect()->back()->with([
                'alert_type' => 'danger',
                'alert_content' => 'Dữ liệu không hợp lệ!',
            ]);
        }

        // dd($location->hotlinks()->get(), $location->destinationHotlinks()->get());

        $location->hotlinks()->delete();

        $location->destinationHotlinks()->delete();

        $location->sun()->delete();

        $location->delete();

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_content' => 'Địa điểm và các liên kết đã được xóa thành công!',
        ]);
    }

















    //lưu hotspot (hotlink)
    public function location_hotspot_store(Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'link_to_location_id' => 'required|exists:locations,id',
            'type' => 'required|in:1,2,3,5',
            'yaw' => 'required|numeric|between:-360,360',
            'pitch' => 'required|numeric|between:-360,360',
        ], [
            'location_id.required' => 'Trường điểm ảnh là bắt buộc.',
            'location_id.exists' => 'Điểm ảnh được chỉ định không tồn tại.',
            'link_to_location_id.required' => 'Trường điểm đến là bắt buộc.',
            'link_to_location_id.exists' => 'Điểm đến không tồn tại.',
            'type.required' => 'Trường loại (type) là bắt buộc.',
            'type.in' => 'Loại (type) phải là một trong các giá trị sau: 1, 2, 3, 5.',
            'yaw.required' => 'Trường yaw là bắt buộc.',
            'yaw.numeric' => 'yaw phải là một giá trị số.',
            'yaw.between' => 'yaw phải nằm trong khoảng từ -360 đến 360.',
            'pitch.required' => 'Trường pitch là bắt buộc.',
            'pitch.numeric' => 'pitch phải là một giá trị số.',
            'pitch.between' => 'pitch phải nằm trong khoảng từ -360 đến 360.',
        ]);

        Hotlink::create($data);

        return redirect()->route('location')->with([
            'alert_type' => 'success',
            'alert_content' => 'Thêm hotspot thành công!',
            'location_id_current' => $data['location_id']
        ]);
    }

    public function location_hotspot_delete(Request $request)
    {
        $request->validate([
            'hotlink_id' => 'required|integer|min:1',
            'current_location_id' => 'required|integer',
        ]);

        $hotlink_id = (int) $request->hotlink_id;
        $current_location_id = $request->current_location_id;

        $hotlink = Hotlink::find($hotlink_id);

        if (!$hotlink) {
            return redirect()->route('location')->with([
                'alert_type' => 'danger',
                'alert_content' => 'Không tìm thấy Hot Spot!',
                'location_id_current' => $current_location_id
            ]);
        }

        $hotlink->delete();

        return redirect()->route('location')->with([
            'alert_type' => 'success',
            'alert_content' => 'Xóa thành công!',
            'location_id_current' => $current_location_id
        ]);
    }


    //Sun hotspot
    public function location_special_hotspot_store(Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'specialType' => 'required|in:4,6,7', //Update 5 6 later
            'yaw' => 'required|numeric|between:-360,360',
            'pitch' => 'required|numeric|between:-360,360',
        ], [
            'location_id.required' => 'Trường điểm ảnh là bắt buộc.',
            'location_id.exists' => 'Điểm ảnh được chỉ định không tồn tại.',
            'specialType.required' => 'Trường loại (specialType) là bắt buộc.',
            'specialType.in' => 'Loại (specialType) phải là một trong các giá trị sau: 4, 5 hoặc 6.',
            'yaw.required' => 'Trường yaw là bắt buộc.',
            'yaw.numeric' => 'yaw phải là một giá trị số.',
            'yaw.between' => 'yaw phải nằm trong khoảng từ -360 đến 360.',
            'pitch.required' => 'Trường pitch là bắt buộc.',
            'pitch.numeric' => 'pitch phải là một giá trị số.',
            'pitch.between' => 'pitch phải nằm trong khoảng từ -360 đến 360.',
        ]);


        if ($data['specialType'] == 4) {
            $location = Location::find($data['location_id']);
            if ($location->sun) {
                // Nếu đã có Sun, cập nhật giá trị yaw và pitch
                $sun = Sun::find($location->sun);
                if ($sun) {
                    $sun->yaw = $data['yaw'];
                    $sun->pitch = $data['pitch'];
                    $sun->save();
                }
            } else {
                // Nếu chưa có Sun, tạo mới
                $sun = Sun::create([
                    'yaw' => $data['yaw'],
                    'pitch' => $data['pitch']
                ]);
                $location->sun = $sun->id;
                $location->save();
            }
        } else if ($data['specialType'] == 6) {
            $hotlink_special = new HotlinksSpecial();
            $hotlink_special->type = 6;
            $hotlink_special->location_id = $data['location_id'];
            $hotlink_special->yaw = $data['yaw'];
            $hotlink_special->pitch = $data['pitch'];
            $hotlink_special->video_link = $request->input('video-link', null);
            $hotlink_special->save();
        } else if ($data['specialType'] == 7) {
            $hotlink_special = new HotlinksSpecial();
            $hotlink_special->type = 7;
            $hotlink_special->location_id = $data['location_id'];
            $hotlink_special->yaw = $data['yaw'];
            $hotlink_special->pitch = $data['pitch'];

            $hotlink_special->info_content = [
                'vi' => $request->input('info-content-vi', ''),
                'en' => $request->input('info-content-en', ''),
            ];

            $hotlink_special->save();
        } else {
            dd("Lỗi");
        }

        return redirect()->route('location')->with([
            'alert_type' => 'success',
            'alert_content' => 'Thêm/cập nhật hotspot đặc biệt thành công!',
            'location_id_current' => $data['location_id']
        ]);
    }

    public function location_special_hotspot_delete(Request $request)
    {
        // dd('hi');
        if ($request->sun_id) {
            $sun = Sun::find($request->sun_id);
            if (!$sun) {
                return redirect()->route('location')->with([
                    'alert_type' => 'danger',
                    'alert_content' => 'Không tìm thấy mặt trời :('
                ]);
            }

            $location = Location::where('sun', $sun->id)->first();

            if ($location) {
                $location->sun = null;
                $location->save();
            }

            $sun->delete();

            $location_id = $request->has('location') ? $request->location->id : ($location ? $location->id : null);

            return redirect()->route('location')->with([
                'alert_type' => 'success',
                'alert_content' => 'Xóa thành công!',
                'location_id_current' => $location_id
            ]);
        } else if ($request->hotlink_id) {
            $special_hotspot = HotlinksSpecial::find($request->hotlink_id);
            if (!$special_hotspot) {
                return redirect()->route('location')->with([
                    'alert_type' => 'danger',
                    'alert_content' => 'Không tìm thấy hotspot :('
                ]);
            }

            $special_hotspot->delete();

            $location_id = $request->has('location') ? $request->location->id : null;

            return redirect()->route('location')->with([
                'alert_type' => 'success',
                'alert_content' => 'Xóa thành công!',
                'location_id_current' => $location_id
            ]);
        } else {
            return redirect()->route('location')->with([
                'alert_type' => 'danger',
                'alert_content' => 'Có lỗi xảy ra :('
            ]);
        }
    }








    //category
    public function category()
    {
        $hero = "Quản lý Danh Mục";
        $icon = "bi-archive-fill";
        $categories = Category::get();

        return view('frontend.admin.category.index', compact('categories', 'hero', 'icon'));
    }

    public function category_add()
    {
        $hero = "Thêm Danh Mục";
        $icon = "bi-archive-fill";
        $categories = Category::where('status', true)->orderBy('sort')->get();

        return view('frontend.admin.category.add', compact('hero', 'icon', 'categories'));
    }

    public function category_store(Request $request)
    {

        $filePath = null;
        $data = $request->validate([
            'name' => 'required|string',
            'name_en' => 'required|string',
            'background_music' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'status' => 'required|boolean',
            'sort' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id',
        ], [
            'name.required' => 'Tên Tiếng Việt không được để trống.',
            'name_en.required' => 'Tên Tiếng Anh không được để trống.',
            'background_music.file' => 'Tệp nhạc nền phải là một tệp hợp lệ.',
            'background_music.mimes' => 'Tệp nhạc nền phải có định dạng mp3, wav hoặc ogg.',
            'background_music.max' => 'Tệp nhạc nền không được vượt quá 10MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.boolean' => 'Trạng thái phải là một giá trị đúng hoặc sai.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
            'sort.integer' => 'Sắp xếp phải là một số nguyên.',
        ]);

        $name = [
            'vi' => $request->input('name'),
            'en' => $request->input('name_en')
        ];

        if ($request->hasFile('background_music')) {
            $file = $request->file('background_music');
            $filePath = $file->store('background_music', 'public');
        }

        // dd($data['background_music']);

        Category::create([
            'name' => $name,
            'background_music' => $filePath,
            'status' => $request->input('status'),
            'sort' => $request->input('sort'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return redirect()->route('category')->with('alert', [
            "type" => "success",
            "title" => __("Thành công!"),
            "body" => __("Thêm thành công."),
        ]);
    }


    public function category_edit(int $id)
    {
        $category = Category::find($id);
        $hero = "Sửa Danh Mục";
        $icon = "bi-archive-fill";
        $categories = Category::where('status', true)->orderBy('sort')->get();

        if ($category) {
            return view('frontend.admin.category.edit', compact('category', 'categories', 'icon', 'hero'));
        } else {
            return redirect()->route('category')->with('alert', [
                "type" => "warning",
                "title" => __("Thất Bại!"),
                "body" => __("Danh mục không tồn tại."),
            ]);
        }
    }

    public function category_delete(Request $request)
    {
        $category = Category::find($request->category_id);

        if (!$category) {
            return redirect()->route('category')->with('alert', [
                "type" => "warning",
                "title" => __("Thất Bại!"),
                "body" => __("Danh mục không tồn tại."),
            ]);
        }

        if ($category->background_music) {
            Storage::disk('public')->delete($category->background_music);
        }

        $category->delete();

        return redirect()->route('category')->with('alert', [
            "type" => "success",
            "title" => __("Thành Công!"),
            "body" => __("Xóa thành công."),
        ]);
    }


    public function category_update_store(Request $request)
    {
        // dd($request);
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'name_en' => 'required|string',
            'background_music' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'status' => 'required|boolean',
            'sort' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id',
        ], [
            'category_id.required' => 'ID danh mục không được để trống.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'name.required' => 'Tên Tiếng Việt không được để trống.',
            'name_en.required' => 'Tên Tiếng Anh không được để trống.',
            'background_music.file' => 'Tệp nhạc nền phải là một tệp hợp lệ.',
            'background_music.mimes' => 'Tệp nhạc nền phải có định dạng mp3, wav hoặc ogg.',
            'background_music.max' => 'Tệp nhạc nền không được vượt quá 10MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.boolean' => 'Trạng thái phải là một giá trị đúng hoặc sai.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
            'sort.integer' => 'Sắp xếp phải là một số nguyên.',
        ]);

        $category = Category::find($request->category_id);

        if (!$category) {
            return redirect()->route('category')->with('alert', [
                "type" => "warning",
                "title" => __("Thất Bại!"),
                "body" => __("Danh mục không tồn tại."),
            ]);
        }

        $category->name = [
            'vi' => $request->input('name'),
            'en' => $request->input('name_en'),
        ];

        $category->sort = $request->input('sort');
        $category->status = $request->input('status');
        $category->parent_id = $request->input('parent_id');

        if ($request->hasFile('background_music')) {
            if ($category->background_music) {
                Storage::disk('public')->delete($category->background_music);
            }

            $file = $request->file('background_music');
            $filePath = $file->store('background_music', 'public');
            $category->background_music = $filePath;
        }

        $category->save();

        return redirect()->route('category')->with('alert', [
            "type" => "success",
            "title" => __("Thành Công!"),
            "body" => __("Sửa thành công."),
        ]);
    }




    public function setting()
    {
        $hero = "Cấu hình trang";
        $icon = "bi-gear-wide-connected";

        $setting = Setting::first();

        return view('frontend.admin.setting.index', compact('hero', 'icon', 'setting'));
    }

    public function setting_store(Request $request)
    {
        $setting = Setting::first();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'keywords' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'google_site_verification' => 'nullable|string|max:255',
            'yaw' => 'nullable|string|max:255',
            'pitch' => 'nullable|string|max:255',
            'logo' => 'nullable|mimes:jpeg,png,jpg,svg,webp,ico|max:2048',
            'logoMain' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:4096',
            'background_music' => 'nullable|mimes:mp3,wav,ogg|max:20480',
            'bg_starter' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
            'voice_reader_avater' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:4096',
        ], [
            'name.required' => 'Tên trang web là bắt buộc.',
            'name.string' => 'Tên trang web phải là chuỗi ký tự.',
            'name.max' => 'Tên trang web không được vượt quá 255 ký tự.',

            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',

            'keywords.string' => 'Từ khóa phải là chuỗi ký tự.',
            'keywords.max' => 'Từ khóa không được vượt quá 255 ký tự.',

            'author.string' => 'Tên tác giả phải là chuỗi ký tự.',
            'author.max' => 'Tên tác giả không được vượt quá 255 ký tự.',

            'google_site_verification.string' => 'Mã xác minh Google phải là chuỗi ký tự.',
            'google_site_verification.max' => 'Mã xác minh Google không được vượt quá 255 ký tự.',

            'yaw.string' => 'Kinh Độ phải là chuỗi ký tự.',
            'yaw.max' => 'Kinh Độ không được vượt quá 255 ký tự.',

            'pitch.string' => 'Vĩ Độ phải là chuỗi ký tự.',
            'pitch.max' => 'Vĩ Độ không được vượt quá 255 ký tự.',

            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, svg, webp hoặc ico.',
            'logo.max' => 'Dung lượng logo không được vượt quá 2MB.',

            'logoMain.image' => 'Logo chính phải là file ảnh.',
            'logoMain.mimes' => 'Logo chính phải có định dạng: jpeg, png, jpg, svg hoặc webp.',
            'logoMain.max' => 'Dung lượng logo chính không được vượt quá 4MB.',

            'background_music.mimes' => 'Nhạc nền phải có định dạng: mp3, wav hoặc ogg.',
            'background_music.max' => 'Dung lượng nhạc nền không được vượt quá 20MB.',

            'bg_starter.image' => 'Background starter phải là file ảnh.',
            'bg_starter.mimes' => 'Background starter phải có định dạng: jpeg, png, jpg, svg hoặc webp.',
            'bg_starter.max' => 'Dung lượng background starter không được vượt quá 10MB.',

            'voice_reader_avater.image' => 'Avatar voice reader phải là file ảnh.',
            'voice_reader_avater.mimes' => 'Avatar voice reader phải có định dạng: jpeg, png, jpg, svg hoặc webp.',
            'voice_reader_avater.max' => 'Dung lượng avatar voice reader không được vượt quá 4MB.',
        ]);

        $logoMainPath = $setting->logoMain;
        $logoPath = $setting->logo;
        $musicPath = $setting->background_music;
        $bgStarterPath = $setting->bg_starter;
        $voiceReaderAvatar = $setting->voice_reader_avater;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('logoMain')) {
            $logoMainPath = $request->file('logoMain')->store('logos', 'public');
        }

        if ($request->hasFile('background_music')) {
            $musicPath = $request->file('background_music')->store('music', 'public');
        }

        if ($request->hasFile('bg_starter')) {
            $bgStarterPath = $request->file('bg_starter')->store('bg_starters', 'public');
        }

        if ($request->hasFile('voice_reader_avater')) {
            $voiceReaderAvatar = $request->file('voice_reader_avater')->store('voice_reader_avater', 'public');
        }

        if ($setting) {
            $setting->update([
                'name' => $request->name,
                'description' => $request->description,
                'keywords' => $request->keywords,
                'author' => $request->author,
                'google_site_verification' => $request->google_site_verification,
                'yaw' => $request->yaw,
                'pitch' => $request->pitch,
                'logo' => $logoPath ?? $setting->logo,
                'logoMain' => $logoMainPath ?? $setting->logoMain,
                'background_music' => $musicPath ?? $setting->background_music,
                'bg_starter' => $bgStarterPath ?? $setting->bg_starter,
                'voice_reader_avater' => $voiceReaderAvatar ?? $setting->voice_reader_avater,
            ]);
        } else {
            // Nếu chưa có setting, tạo mới
            Setting::create([
                'name' => $request->name,
                'description' => $request->description,
                'keywords' => $request->keywords,
                'author' => $request->author,
                'google_site_verification' => $request->google_site_verification,
                'yaw' => $request->yaw,
                'pitch' => $request->pitch,
                'logo' => $logoPath,
                'logoMain' => $logoMainPath,
                'background_music' => $musicPath,
                'bg_starter' => $bgStarterPath,
                'voice_reader_avater' => $voiceReaderAvatar,
            ]);
        }

        return redirect()->route('setting')->with('alert', [
            "type" => "success",
            "title" => __("Thành Công!"),
            "body" => __("Cập nhật thành công!"),
        ]);
    }
}
