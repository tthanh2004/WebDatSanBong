<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\ValidationDatSan\ValidNgayDat;
use App\Http\Requests\ValidationDatSan\ValidateNgayDat;

class NgayDatValidationTest extends TestCase
{
    #[Test]
    public function NgayDat_TruocHomNay(): void
    {
        $rule = new ValidateNgayDat();

        $yesterday = now()->subDay()->toDateString();

        $this->assertFalse($rule->passes('ngay_dat', $yesterday));
        $this->assertSame('Thời gian đặt sân không hợp lệ (Lỗi 1E17).', $rule->message());
    }

    #[Test]
    public function NgayDat_HomNay_HopLe(): void
    {
        $rule = new ValidateNgayDat();

        $today = now()->toDateString();

        $this->assertTrue($rule->passes('ngay_dat', $today));
    }

    #[Test]
    public function NgayDat_SauHomNay_HopLe(): void
    {
        $rule = new ValidateNgayDat();

        $tomorrow = now()->addDay()->toDateString();

        $this->assertTrue($rule->passes('ngay_dat', $tomorrow));
    }

    #[Test]
    public function QuaTrait_Rong(): void
    {
        $helper = new class {
            use ValidNgayDat;
        };

        [$rules, $messages] = [$helper->ngayDatRules(), $helper->ngayDatMessages()];

        $v = Validator::make(['ngay_dat' => ''], $rules, $messages);
        $this->assertTrue($v->fails());
        $this->assertSame(['Vui lòng chọn thời gian (Lỗi 1E16).'], $v->errors()->get('ngay_dat'));
    }

    #[Test]
    public function QuaTrait_NgayHopLe(): void
    {
        $helper = new class {
            use ValidNgayDat;
        };

        [$rules, $messages] = [$helper->ngayDatRules(), $helper->ngayDatMessages()];

        $v = Validator::make(['ngay_dat' => now()->addDays(2)->toDateString()], $rules, $messages);
        $this->assertFalse($v->fails());
    }
}
