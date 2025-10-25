<?php
namespace App\Http\Requests\SanBong;


use Illuminate\Foundation\Http\FormRequest;

class ValidateMaSanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ma_san' => [
                'required',                
                'string',                  
                'size:6',                
                'regex:/^(F5|F7)[A-Z]{2}[0-9]{2}$/',
                'unique:san_bong,ma_san', // Phải duy nhất trong hệ thống
            ],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $maSan = $this->input('ma_san');
            if ($maSan) {
                // --- Kiểm tra độ dài ---
                if (strlen($maSan) < 6 || strlen($maSan) > 6) {
                    $validator->errors()->add('ma_san', 'Mã sân không hợp lệ, phải có đúng 6 ký tự.');
                    return;
                }
                // --- Kiểm tra ký tự hợp lệ (A–Z, 0–9, không chứa ký tự đặc biệt hay khoảng trắng) ---
                if (!preg_match('/^[A-Z0-9]+$/', $maSan)) {
                    $validator->errors()->add('ma_san', 'Mã sân không hợp lệ, chỉ chứa chữ in hoa và số.');
                    return;
                }
                // --- Tách các phần trong mã sân ---
                $loaiSan = substr($maSan, 0, 2);   // F5 hoặc F7
                $maKhuVuc = substr($maSan, 2, 2);  // HN, SG, DN,...
                $soThuTu = substr($maSan, 4, 2);   // 01–99
                // --- Danh sách hợp lệ ---
                $loaiSanHopLe = ['F5', 'F7'];
                $khuVucHopLe = ['HN', 'SG', 'DN']; // Có thể mở rộng sau

                // --- Kiểm tra loại sân ---
                if (!in_array($loaiSan, $loaiSanHopLe)) {
                    $validator->errors()->add('ma_san', 'Loại sân không có trong danh sách.');
                    return;
                }

                // --- Kiểm tra mã khu vực ---
                if (!in_array($maKhuVuc, $khuVucHopLe)) {
                    $validator->errors()->add('ma_san', 'Mã khu vực không hợp lệ.');
                    return;
                }

                // --- Kiểm tra số thứ tự ---
                if (!ctype_digit($soThuTu)) {
                    $validator->errors()->add('ma_san', 'Số thứ tự sân không hợp lệ.');
                    return;
                }

                $soThuTuInt = intval($soThuTu);

                // Biên dưới, trên, và ngoài biên
                if ($soThuTuInt < 1 || $soThuTuInt > 99) {
                    $validator->errors()->add('ma_san', 'Số thứ tự sân không hợp lệ.');
                    return;
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'ma_san.required' => 'Mã sân không được để trống.',
            'ma_san.string'   => 'Mã sân phải là chuỗi ký tự.',
            // ĐỔI dòng dưới cho khớp test:
            'ma_san.size'     => 'Mã sân không hợp lệ, phải có đúng 6 ký tự.',
            'ma_san.regex'    => 'Mã sân không hợp lệ. Cấu trúc: 2 ký tự đầu (F5/F7), 2 ký tự khu vực (HN/SG/DN), 2 số thứ tự (01–99).',
            'ma_san.unique'   => 'Mã sân đã tồn tại.',
        ];
    }
}
