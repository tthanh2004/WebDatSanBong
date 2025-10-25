<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use PHPUnit\Framework\Attributes\Test;

class StoreEmployeeRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Route dùng FormRequest để test E2E
        Route::post('/test-employees', function (StoreEmployeeRequest $request) {
            return response()->json(['ok' => true, 'data' => $request->validated()], 200);
        });
    }

    #[Test]
    public function Rong_HoTen_GioiTinh_NgaySinh_SDT(): void
    {
        $this->postJson('/test-employees', [
            'name'   => '',
            'gender' => '',
            'dob'    => '',
            'phone'  => '',
        ])->assertStatus(422)
          ->assertJsonPath('errors.name.0',   'Vui lòng nhập tên nhân viên (Lỗi 1E1).')
          ->assertJsonPath('errors.gender.0', 'Vui lòng chọn giới tính nhân viên (Lỗi 1E4).')
          ->assertJsonPath('errors.dob.0',    'Vui lòng nhập ngày sinh (định dạng yyyy/mm/dd) (Lỗi 1E5).')
          ->assertJsonPath('errors.phone.0',  'Vui lòng nhập số điện thoại (Lỗi 1E7).');
    }

    #[Test]
    public function DuLieu_KhongHopLe(): void
    {
        $this->postJson('/test-employees', [
            'name'   => 'Nguyen@Van',      // ký tự đặc biệt
            'gender' => 'Khác',            // không thuộc ['Nam', 'Nữ']
            'dob'    => '2020/01/01',      // < 18 tuổi
            'phone'  => '1a2345678',       // chứa chữ
        ])->assertStatus(422)
          ->assertJsonPath('errors.name.0',   'Tên nhân viên không hợp lệ (Lỗi 1E2).')
          ->assertJsonPath('errors.gender.0', 'Vui lòng chọn giới tính nhân viên (Lỗi 1E4).')
          ->assertJsonPath('errors.dob.0',    'Độ tuổi không hợp lệ (phải ≥ 18) (Lỗi 1E6).')
          ->assertJsonPath('errors.phone.0',  'Số điện thoại chỉ được chứa số (Lỗi 1E8).');
    }

    #[Test]
    public function SDT_Sai_DoDai_Sai_Prefix(): void
    {
        $this->postJson('/test-employees', [
            'name'   => 'Nguyen Van A',
            'gender' => 'Nam',
            'dob'    => '1990/01/01',
            'phone'  => '1234567890', // không bắt đầu bằng 0
        ])->assertStatus(422)
          ->assertJsonPath('errors.phone.0', 'Số điện thoại phải bắt đầu bằng số 0 (Lỗi 1E10).');

        $this->postJson('/test-employees', [
            'name'   => 'Nguyen Van A',
            'gender' => 'Nữ',
            'dob'    => '1990-05-05',
            'phone'  => '0123456', // quá ngắn
        ])->assertStatus(422)
          ->assertJsonPath('errors.phone.0', 'Số điện thoại phải gồm 10 hoặc 11 số (Lỗi 1E9).');
    }

    #[Test]
    public function HopLe(): void
    {
        $this->postJson('/test-employees', [
            'name'   => 'Nguyen Van A',
            'gender' => 'Nam',
            'dob'    => '1990/12/31',
            'phone'  => '0123456789',
        ])->assertOk()
          ->assertJsonPath('ok', true);
    }
}
