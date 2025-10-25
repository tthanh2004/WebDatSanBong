<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidationDatSan\ValidHoTen;
use App\Http\Requests\ValidationDatSan\ValidateHoTen;
use PHPUnit\Framework\Attributes\Test;

class HoTenValidationTest extends TestCase
{
    #[Test]
    public function HoTen_Rong(): void
    {
        $rule = new ValidHoTen();

        foreach (['', '   '] as $name) {
            $this->assertFalse($rule->passes('ho_ten', $name));
            $this->assertSame('Họ tên không được để trống (Lỗi 1E1).', $rule->message());
        }
    }

    #[Test]
    public function HoTen_KhongHopLe(): void
    {
        $rule = new ValidHoTen();

        $invalids = [
            '123abc',         // có số
            'Nguyen@Van',     // có ký tự đặc biệt
            'Nam__Hoang',     // có ký tự không cho phép
            'Nguyen  Van',    // 2 khoảng trắng liền
        ];

        foreach ($invalids as $name) {
            $this->assertFalse($rule->passes('ho_ten', $name), "Sai: $name");
            $this->assertSame('Họ tên không hợp lệ (Lỗi 1E2).', $rule->message());
        }
    }

    #[Test]
    public function HoTen_QuaDai(): void
    {
        $rule = new ValidHoTen();
        $name = str_repeat('Nguyen ', 10); // > 40 ký tự

        $this->assertFalse($rule->passes('ho_ten', $name));
        $this->assertSame(
            'Họ tên không hợp lệ, họ tên phải ít hơn hoặc bằng 40 ký tự (Lỗi 1E3).',
            $rule->message()
        );
    }

    #[Test]
    public function HoTen_HopLe(): void
    {
        $rule = new ValidHoTen();

        foreach (['Nguyen Van A', 'Hoàng Thị B', 'Lê Minh C', 'Phạm Văn D'] as $name) {
            $this->assertTrue($rule->passes('ho_ten', $name), "Đúng: $name");
        }
    }

    #[Test]
    public function HoTen_HopLe_Khi_CoKhoangTrang_DauCuoi_DoTrim(): void
    {
        $rule = new ValidHoTen();
        $this->assertTrue($rule->passes('ho_ten', '  Nguyen Van A  '));
    }

    #[Test]
    public function KiemTraQuaTrait_ValidateHoTen(): void
    {
        $helper = new class { use ValidateHoTen; };
        $rules = $helper->hoTenRules();

        $v = Validator::make(['ho_ten' => 'Nguyen Van A'], $rules);
        $this->assertFalse($v->fails());

        $v2 = Validator::make(['ho_ten' => ''], $rules);
        $this->assertTrue($v2->fails());
        $this->assertSame(['Họ tên không được để trống (Lỗi 1E1).'], $v2->errors()->get('ho_ten'));
    }
}
