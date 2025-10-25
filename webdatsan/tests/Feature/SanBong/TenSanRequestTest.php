<?php

namespace Tests\Feature\SanBong;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SanBong\ValidateTenSanRequest;
use PHPUnit\Framework\Attributes\Test;

class TenSanRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('/_test/ten-san', function (ValidateTenSanRequest $request) {
            // trả về giá trị sau khi prepareForValidation
            return response()->json([
                'ok' => true,
                'ten_san' => $request->input('ten_san'),
            ], 200);
        });
    }

    #[Test]
    public function Rong_BiLoi(): void
    {
        $this->postJson('/_test/ten-san', ['ten_san' => ''])
            ->assertStatus(422)
            ->assertJsonPath('errors.ten_san.0', 'Tên sân không được để trống');
    }

    #[Test]
    public function Vuot50KyTu_BiLoi(): void
    {
        $this->postJson('/_test/ten-san', ['ten_san' => str_repeat('a', 51)])
            ->assertStatus(422)
            ->assertJsonPath('errors.ten_san.0', 'Tên sân vượt quá 50 ký tự');
    }

    #[Test]
    public function ChuaEmoji_HoacKyTuDieuKhien_BiLoi(): void
    {
        $this->postJson('/_test/ten-san', ['ten_san' => "San bong \u{1F3C0}"])
            ->assertStatus(422)
            ->assertJsonPath('errors.ten_san.0', 'Tên sân không hợp lệ');
    }

    #[Test]
    public function ChuaKyTuKhongChoPhep_BiLoi(): void
    {
        $this->postJson('/_test/ten-san', ['ten_san' => 'San @ Bong'])
            ->assertStatus(422)
            ->assertJsonPath('errors.ten_san.0', 'Tên sân không hợp lệ');
    }

    #[Test]
    public function HopLe_Va_ChuanHoaKhoangTrang(): void
    {
        $this->postJson('/_test/ten-san', ['ten_san' => "  Sân    A   - Khu 'B'  "])
            ->assertOk()
            ->assertJsonPath('ten_san', "Sân A - Khu 'B'");
    }

    #[Test]
    public function HopLe_NhieuMauTen(): void
    {
        foreach (['Sân 5', "San 7 - Khu 'A'", 'Sân bóng . KTX'] as $name) {
            $this->postJson('/_test/ten-san', ['ten_san' => $name])
                ->assertOk()
                ->assertJsonPath('ok', true);
        }
    }
}
