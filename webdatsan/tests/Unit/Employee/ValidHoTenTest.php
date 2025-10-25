<?php

namespace Tests\Unit\Employee;

use Tests\TestCase;
use App\Http\Requests\Employee\ValidHoTen;
use PHPUnit\Framework\Attributes\Test;

class ValidHoTenTest extends TestCase
{
    #[Test]
    public function HoTen_Rong_1E1(): void
    {
        $rule = new ValidHoTen();
        $this->assertFalse($rule->passes('name', ''));
        $this->assertSame('Vui lòng nhập tên nhân viên (Lỗi 1E1).', $rule->message());
    }

    #[Test]
    public function HoTen_KhongHopLe_1E2(): void
    {
        $rule = new ValidHoTen();
        $this->assertFalse($rule->passes('name', 'Nguyen@Van'));
        $this->assertSame('Tên nhân viên không hợp lệ (Lỗi 1E2).', $rule->message());
    }

    #[Test]
    public function HoTen_QuaDai_1E3(): void
    {
        $rule = new ValidHoTen();
        $this->assertFalse($rule->passes('name', str_repeat('a', 41)));
        $this->assertSame('Tên nhân viên không được vượt quá 40 ký tự (Lỗi 1E3).', $rule->message());
    }

    #[Test]
    public function HoTen_HopLe(): void
    {
        $rule = new ValidHoTen();
        $this->assertTrue($rule->passes('name', 'Nguyen Van A'));
    }
}
