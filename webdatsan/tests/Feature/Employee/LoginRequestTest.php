<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\Employee\LoginRequest;
use App\Http\Requests\Employee\ValidLoginUsername;
use App\Http\Requests\Employee\ValidLoginPassword;

class LoginRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Đăng ký route tạm dùng LoginRequest (để test FormRequest end-to-end)
        Route::post('/test-employee-login', function (LoginRequest $request) {
            // Nếu vào đây nghĩa là đã pass validate
            return response()->json(['ok' => true], 200);
        });
    }

    // ================== TEST RULE Username trực tiếp ==================

    #[Test]
    public function Username_Rong(): void
    {
        $rule = new ValidLoginUsername();

        foreach (['', '   ', null] as $val) {
            $this->assertFalse($rule->passes('username', $val));
            $this->assertSame('Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E1).', $rule->message());
        }
    }

    #[Test]
    public function Username_KhongAlnum(): void
    {
        $rule = new ValidLoginUsername();

        foreach (['user_name', 'user-name', 'user@name', 'na me'] as $val) {
            $this->assertFalse($rule->passes('username', $val), "Sai: $val");
            $this->assertSame('Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E2).', $rule->message());
        }
    }

    #[Test]
    public function Username_SaiDoDai(): void
    {
        $rule = new ValidLoginUsername();

        foreach (['abc', 'a', 'ab', str_repeat('a', 17), str_repeat('1', 50)] as $val) {
            $this->assertFalse($rule->passes('username', $val), "Sai: $val");
            $this->assertSame('Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E3).', $rule->message());
        }
    }

    #[Test]
    public function Username_HopLe(): void
    {
        $rule = new ValidLoginUsername();

        foreach (['user1', 'User123', 'abcd', str_repeat('a', 16), '  user9  '] as $val) {
            $this->assertTrue($rule->passes('username', $val), "Đúng: $val");
        }
    }

    #[Test]
    public function Password_Rong(): void
    {
        $rule = new ValidLoginPassword();

        foreach (['', null] as $val) {
            $this->assertFalse($rule->passes('password', $val));
            $this->assertSame('Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E5).', $rule->message());
        }
    }

    #[Test]
    public function Password_QuaNgan(): void
    {
        $rule = new ValidLoginPassword();

        foreach (['a', '123', 'abc12', '     '] as $val) {
            $this->assertFalse($rule->passes('password', $val), "Sai: $val");
            $this->assertSame('Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E6).', $rule->message());
        }
    }

    #[Test]
    public function Password_QuaDai(): void
    {
        $rule = new ValidLoginPassword();
        $tooLong = str_repeat('x', 21);

        $this->assertFalse($rule->passes('password', $tooLong));
        $this->assertSame('Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E7).', $rule->message());
    }

    #[Test]
    public function Password_HopLe(): void
    {
        $rule = new ValidLoginPassword();

        foreach (['123456', 'abcdef', 'Abc123', str_repeat('x', 20)] as $val) {
            $this->assertTrue($rule->passes('password', $val), "Đúng: $val");
        }
    }


    #[Test]
    public function FormRequest_Rong_CaHai(): void
    {
        $payload = ['username' => '', 'password' => ''];
        $this->postJson('/test-employee-login', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.username.0', 'Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E1).')
            ->assertJsonPath('errors.password.0', 'Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E5).');
    }

    #[Test]
    public function FormRequest_Username_SaiAlnum(): void
    {
        $payload = ['username' => 'user_name', 'password' => '123456'];
        $this->postJson('/test-employee-login', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.username.0', 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E2).');
    }

    #[Test]
    public function FormRequest_Username_SaiDoDai(): void
    {
        $payload = ['username' => 'abc', 'password' => '123456'];
        $this->postJson('/test-employee-login', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.username.0', 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E3).');
    }

    #[Test]
    public function FormRequest_Password_QuaNgan(): void
    {
        $payload = ['username' => 'user1', 'password' => '12345'];
        $this->postJson('/test-employee-login', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.password.0', 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E6).');
    }

    #[Test]
    public function FormRequest_Password_QuaDai(): void
    {
        $payload = ['username' => 'user1', 'password' => str_repeat('x', 21)];
        $this->postJson('/test-employee-login', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.password.0', 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E7).');
    }

    #[Test]
    public function FormRequest_HopLe(): void
    {
        $payload = ['username' => 'User123', 'password' => 'secret6'];
        $this->postJson('/test-employee-login', $payload)
            ->assertOk()
            ->assertJson(['ok' => true]);
    }
}
