<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\ValidationDatSan\ValidateSoDienThoai;
use App\Http\Requests\ValidationDatSan\ValidSoDienThoai;

class SoDienThoaiValidationTest extends TestCase
{
    #[Test]
    public function SoDienThoai_Rong_QuaTrait_tra_1E4(): void
    {
        $helper = new class { use ValidSoDienThoai; };

        $rules = $helper->soDienThoaiRules();
        $messages = $helper->soDienThoaiMessages();

        $v = Validator::make(['so_dien_thoai' => ''], $rules, $messages);
        $this->assertTrue($v->fails());
        $this->assertSame(
            ['Số điện thoại không được để trống (Lỗi 1E4).'],
            $v->errors()->get('so_dien_thoai')
        );
    }

    #[Test]
    public function SoDienThoai_KyTuKhongHopLe_tra_1E5(): void
    {
        $rule = new ValidateSoDienThoai();

        foreach (['09 2345678', '09-2345678', '09a3456789', '09@3456789'] as $sdt) {
            $this->assertFalse($rule->passes('so_dien_thoai', $sdt), "Sai: $sdt");
            $this->assertSame(
                'Số điện thoại không hợp lệ, số điện thoại không được chứa các ký tự đặc biệt hoặc khoảng trắng (Lỗi 1E5).',
                $rule->message()
            );
        }
    }

    #[Test]
    public function SoDienThoai_SaiDoDai_tra_1E6(): void
    {
        $rule = new ValidateSoDienThoai();

        foreach (['012345678', '01234567890'] as $sdt) { // 9 và 11 số
            $this->assertFalse($rule->passes('so_dien_thoai', $sdt), "Sai: $sdt");
            $this->assertSame(
                'Số điện thoại không hợp lệ, phải có đúng 10 chữ số (Lỗi 1E6).',
                $rule->message()
            );
        }
    }

    #[Test]
    public function SoDienThoai_KhongBatDauBang0_tra_1E7(): void
    {
        $rule = new ValidateSoDienThoai();

        $this->assertFalse($rule->passes('so_dien_thoai', '1123456789'));
        $this->assertSame('Số điện thoại phải bắt đầu bằng số 0 (Lỗi 1E7).', $rule->message());
    }

    #[Test]
    public function SoDienThoai_HopLe_Pass(): void
    {
        $rule = new ValidateSoDienThoai();

        foreach (['0123456789', ' 0123456789 ', "\t0123456789\n"] as $sdt) { // trim OK
            $this->assertTrue($rule->passes('so_dien_thoai', $sdt), "Đúng: ".json_encode($sdt));
        }
    }

    #[Test]
    public function SoDienThoai_HopLe_QuaTrait(): void
    {
        $helper = new class { use ValidSoDienThoai; };

        $rules = $helper->soDienThoaiRules();
        $messages = $helper->soDienThoaiMessages();

        $v = Validator::make(['so_dien_thoai' => '0123456789'], $rules, $messages);
        $this->assertFalse($v->fails());
    }
}
