<?php

namespace App\Services;

//Service chuyên xử lý logic nghiệp vụ
//Sử dụng trait để class khác có thể đa kết thừa với từ khóa use bên trong class
trait PostService
{
    public function updateImage($file)
    {
        //is_null kiểm tra xem file có rỗng không
        if (!is_null($file)) {
            //getClientOriginalExtension lấy đuôi file
            $endfile = $file->getClientOriginalExtension();

            if ($endfile != 'jpg' && $endfile != 'png' && $endfile != 'jpeg' && $endfile != 'JPG' && $endfile != 'PNG') {
                return false;
            }
            // /getClientOriginalName lấy tên file
            $imageName = $file->getClientOriginalName();

            $file->move('images', $imageName);

            $data['image'] = $imageName;
        } else {
            $data['image'] = '';
        }

        return $data['image'];
    }
}
