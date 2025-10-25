<?php

namespace Tests\Feature\SanBong;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SanBong\ValidateGiaThueRequest;
use PHPUnit\Framework\Attributes\Test;

class GiaThueRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('/_test/gia-thue', function (ValidateGiaThueRequest $request) {
            return response()->json(['ok' => true, 'gia_thue' => $request->input('gia_thue')], 200);
        });
    }

    #[Test]
    public function Rong_Hoac_ChiKhoangTrang_BiLoi(): void
    {
        $this->postJson('/_test/gia-thue', ['gia_thue' => ''])
            ->assertStatus(422)
            ->assertJsonPath('errors.gia_thue.0', 'Giá thuê không được để trống.');

        $this->postJson('/_test/gia-thue', ['gia_thue' => '   '])
            ->assertStatus(422)
            ->assertJsonPath('errors.gia_thue.0', 'Giá thuê không được để trống.');
    }

    #[Test]
    public function ChuaKhoangTrang_DauPhay_DauCham_HoacChu_Cam(): void
    {
        foreach (['1 000', '1,000', '1.000', '12a3'] as $val) {
            $this->postJson('/_test/gia-thue', ['gia_thue' => $val])
                ->assertStatus(422)
                ->assertJsonPath('errors.gia_thue.0', 'Giá thuê không hợp lệ, chỉ được nhập số');
        }
    }

    #[Test]
    public function Bang0_HoacAm_BiLoi(): void
    {
        $this->postJson('/_test/gia-thue', ['gia_thue' => '0'])
            ->assertStatus(422)
            ->assertJsonPath('errors.gia_thue.0', 'Giá thuê phải lớn hơn 0');
    }

    #[Test]
    public function HopLe_Pass(): void
    {
        $this->postJson('/_test/gia-thue', ['gia_thue' => '1000'])
            ->assertOk()
            ->assertJsonPath('ok', true);
    }
}
