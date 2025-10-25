<?php

namespace Tests\Unit\Employee;

use Tests\TestCase;
use App\Http\Requests\Employee\ValidGioiTinh;
use PHPUnit\Framework\Attributes\Test;

class ValidGioiTinhTest extends TestCase
{
    #[Test]
    public function GioiTinh_Invalid(): void
    {
        $rule = new ValidGioiTinh();
        $this->assertFalse($rule->passes('gender', ''));
        $this->assertFalse($rule->passes('gender', 'Khác'));
    }

    #[Test]
    public function GioiTinh_Valid(): void
    {
        $rule = new ValidGioiTinh();
        $this->assertTrue($rule->passes('gender', 'Nam'));
        $this->assertTrue($rule->passes('gender', 'Nữ'));
    }
}
