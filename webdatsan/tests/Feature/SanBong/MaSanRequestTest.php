<?php

namespace Tests\Feature\SanBong;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SanBong\ValidateMaSanRequest;
use PHPUnit\Framework\Attributes\Test;

class MaSanRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tối thiểu bảng san_bong với cột ma_san để unique hoạt động
        Schema::dropIfExists('san_bong');
        Schema::create('san_bong', function (Blueprint $table) {
            $table->id();
            $table->string('ma_san')->unique();
        });

        Route::post('/_test/ma-san', function (ValidateMaSanRequest $request) {
            return response()->json(['ok' => true, 'ma_san' => $request->input('ma_san')], 200);
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('san_bong');
        parent::tearDown();
    }

    #[Test]
    public function Required_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => ''])
            ->assertStatus(422)
            ->assertJsonPath('errors.ma_san.0', 'Mã sân không được để trống.');
    }

    #[Test]
    public function SaiDoDai_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F5HN1'])
            ->assertStatus(422)
            ->assertJsonPath('errors.ma_san.0', 'Mã sân không hợp lệ, phải có đúng 6 ký tự.');
    }

    #[Test]
    public function KyTuKhongHopLe_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F5H@01'])
            ->assertStatus(422)
            ->assertJsonFragment(['Mã sân không hợp lệ, chỉ chứa chữ in hoa và số.']);
    }

    #[Test]
    public function LoaiSanKhongHopLe_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F9HN01'])
            ->assertStatus(422)
            ->assertJsonFragment(['Loại sân không có trong danh sách.']);
    }

    #[Test]
    public function KhuVucKhongHopLe_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F5ZZ01'])
            ->assertStatus(422)
            ->assertJsonFragment(['Mã khu vực không hợp lệ.']);
    }

    #[Test]
    public function SoThuTuNgoaiBien_BiLoi(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F5HN00'])
            ->assertStatus(422)
            ->assertJsonFragment(['Số thứ tự sân không hợp lệ.']);
    }

    #[Test]
    public function Unique_TrungMaSan_BiLoi(): void
    {
        DB::table('san_bong')->insert(['ma_san' => 'F5HN01']);

        $this->postJson('/_test/ma-san', ['ma_san' => 'F5HN01'])
            ->assertStatus(422)
            ->assertJsonPath('errors.ma_san.0', 'Mã sân đã tồn tại.');
    }

    #[Test]
    public function HopLe_Pass(): void
    {
        $this->postJson('/_test/ma-san', ['ma_san' => 'F7SG99'])
            ->assertOk()
            ->assertJsonPath('ok', true);
    }
}
