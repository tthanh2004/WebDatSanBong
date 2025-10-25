<?php

namespace Tests\Unit\Employee;

use Tests\TestCase;
use App\Http\Requests\Employee\ValidNgaySinh;
use PHPUnit\Framework\Attributes\Test;

class ValidNgaySinhTest extends TestCase
{
    #[Test]
    public function NgaySinh_Rong(): void
    {
        $rule = new ValidNgaySinh();
        $this->assertFalse($rule->passes('dob', ''));
        $this->assertSame('Vui lòng nhập ngày sinh (định dạng yyyy/mm/dd) (Lỗi 1E5).', $rule->message());
    }

    #[Test]
    public function NgaySinh_KhongHopLe(): void
    {
        $rule = new ValidNgaySinh();
        $this->assertFalse($rule->passes('dob', '2025/99/99'));
        $this->assertSame('Vui lòng nhập ngày sinh (định dạng yyyy/mm/dd) (Lỗi 1E5).', $rule->message());
    }

    #[Test]
    public function NgaySinh_Duoi18(): void
    {
        $rule = new ValidNgaySinh();
        $this->assertFalse($rule->passes('dob', now()->subYears(17)->toDateString()));
        $this->assertSame('Độ tuổi không hợp lệ (phải ≥ 18) (Lỗi 1E6).', $rule->message());
    }

    #[Test]
    public function NgaySinh_HopLe(): void
    {
        $rule = new ValidNgaySinh();
        $this->assertTrue($rule->passes('dob', '1990-01-01'));
    }
}
