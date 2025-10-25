<?php

namespace Tests\Unit\Employee;

use Tests\TestCase;
use App\Http\Requests\Employee\ValidSoDienThoai;
use PHPUnit\Framework\Attributes\Test;

class ValidSoDienThoaiTest extends TestCase
{
    #[Test]
    public function Phone_Rong(): void
    {
        $rule = new ValidSoDienThoai();
        $this->assertFalse($rule->passes('phone', ''));
        $this->assertSame('Vui lòng nhập số điện thoại (Lỗi 1E7).', $rule->message());
    }

    #[Test]
    public function Phone_KhongChiSo(): void
    {
        $rule = new ValidSoDienThoai();
        $this->assertFalse($rule->passes('phone', '09a234567'));
        $this->assertSame('Số điện thoại chỉ được chứa số (Lỗi 1E8).', $rule->message());
    }

    #[Test]
    public function Phone_SaiDoDai(): void
    {
        $rule = new ValidSoDienThoai();
        $this->assertFalse($rule->passes('phone', '0123456'));
        $this->assertSame('Số điện thoại phải gồm 10 hoặc 11 số (Lỗi 1E9).', $rule->message());
    }

    #[Test]
    public function Phone_KhongBatDau_0(): void
    {
        $rule = new ValidSoDienThoai();
        $this->assertFalse($rule->passes('phone', '1234567890'));
        $this->assertSame('Số điện thoại phải bắt đầu bằng số 0 (Lỗi 1E10).', $rule->message());
    }

    #[Test]
    public function Phone_HopLe(): void
    {
        $rule = new ValidSoDienThoai();
        $this->assertTrue($rule->passes('phone', '0123456789'));
        $this->assertTrue($rule->passes('phone', '01234567890'));
    }
}
