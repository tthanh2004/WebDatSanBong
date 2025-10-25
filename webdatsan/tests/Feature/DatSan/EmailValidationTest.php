<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidationDatSan\ValidateEmail;
use App\Http\Requests\ValidationDatSan\ValidEmail;
use PHPUnit\Framework\Attributes\Test;

class EmailValidationTest extends TestCase
{
    #[Test]
    public function DinhDangEmail(): void
    {
        $rule = new ValidateEmail();

        $valids = [
            'user@example.com',
            'a.b-c_d+tag@sub.domain.vn',
            'simple@test.co',
            '  trim_ok@domain.com  ',
        ];

        foreach ($valids as $email) {
            $this->assertTrue($rule->passes('email', $email), "Should pass: $email");
        }
    }

    #[Test]
    public function LocalEmail(): void
    {
        $rule = new ValidateEmail();

        $invalidE8 = [
            '',
            '   ',
            'abc',
            '@domain.com',
        ];

        foreach ($invalidE8 as $email) {
            $this->assertFalse($rule->passes('email', $email), "Should fail E8: $email");
            $this->assertSame(
                'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).',
                $rule->message()
            );
        }
    }

    #[Test]
    public function DoDaiEmail(): void
    {
        $rule = new ValidateEmail();
        $email = str_repeat('a', 250) . '@x.com'; // 256 > 254

        $this->assertFalse($rule->passes('email', $email));
        $this->assertSame(
            'Email không hợp lệ, email phải ít hơn hoặc bằng 254 ký tự (Lỗi 1E9).',
            $rule->message()
        );
    }

    #[Test]
    public function DinhDangLocalvaPart(): void
    {
        $rule = new ValidateEmail();

        $invalidE10 = [
            '.abc@x.com',
            'a..b@x.com',
            'abc.@x.com',
            'abc@localhost',
            'abc@-domain.com',
            'abc@domain-.com',
            'abc@',
        ];

        foreach ($invalidE10 as $email) {
            $this->assertFalse($rule->passes('email', $email), "Should fail E10: $email");
            $this->assertSame(
                'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E10).',
                $rule->message()
            );
        }
    }

    #[Test]
    public function EmailHopLe(): void
    {
        $helper = new class {
            use ValidEmail;
        };

        [$rules, $messages] = [$helper->emailRules(), $helper->emailMessages()];

        $v = Validator::make(['email' => 'user@domain.com'], $rules, $messages);
        $this->assertFalse($v->fails());
    }

    #[Test]
    public function EmailKhongHopLe(): void
    {
        $helper = new class {
            use ValidEmail;
        };

        [$rules, $messages] = [$helper->emailRules(), $helper->emailMessages()];

        $v = Validator::make(['email' => ''], $rules, $messages);
        $this->assertTrue($v->fails());
        $this->assertSame(
            ['Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).'],
            $v->errors()->get('email')
        );
    }
}
