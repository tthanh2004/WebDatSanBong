<?php

namespace Tests\Feature\SanBong;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SanBong\ValidateGioHoatDongRequest;
use PHPUnit\Framework\Attributes\Test;

class GioHoatDongRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('/_test/gio-hoat-dong', function (ValidateGioHoatDongRequest $request) {
            // Trả về loại giờ đã merge trong withValidator để kiểm chứng
            return response()->json([
                'ok' => true,
                'loai_gio' => $request->input('loai_gio')
            ], 200);
        });
    }

    #[Test]
    public function Rong_Hoac_SaiDinhDang_BiLoi(): void
    {
        $this->postJson('/_test/gio-hoat-dong', ['gio_bat_dau' => '', 'gio_ket_thuc' => ''])
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_hoat_dong.0', 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm');

        $this->postJson('/_test/gio-hoat-dong', ['gio_bat_dau' => '25:00', 'gio_ket_thuc' => '07:00'])
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_hoat_dong.0', 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm');
    }

    #[Test]
    public function TrungNhau_BiLoi(): void
    {
        $this->postJson('/_test/gio-hoat-dong', ['gio_bat_dau' => '08:00', 'gio_ket_thuc' => '08:00'])
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_hoat_dong.0', 'Giờ hoạt động không hợp lệ');
    }

    #[Test]
    public function CungNgay_Pass_VaMergeLoaiGio(): void
    {
        $this->postJson('/_test/gio-hoat-dong', ['gio_bat_dau' => '08:00', 'gio_ket_thuc' => '20:00'])
            ->assertOk()
            ->assertJsonPath('loai_gio', 'cung_ngay');
    }

    #[Test]
    public function QuaDem_Pass_VaMergeLoaiGio(): void
    {
        $this->postJson('/_test/gio-hoat-dong', ['gio_bat_dau' => '20:00', 'gio_ket_thuc' => '06:00'])
            ->assertOk()
            ->assertJsonPath('loai_gio', 'qua_dem');
    }
}
