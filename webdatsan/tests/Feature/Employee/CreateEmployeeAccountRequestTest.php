<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use App\Http\Requests\Employee\CreateEmployeeAccountRequest;
use App\Http\Requests\Employee\ValidUsername;
use App\Http\Requests\Employee\ValidPassword;

class CreateEmployeeAccountRequestTest extends TestCase
{
    /** @var \stdClass giả lập query builder của DB */
    private $qb;

    /** exists() sẽ trả về giá trị này, thay đổi theo từng test */
    private bool $existsReturn = false;

    protected function setUp(): void
    {
        parent::setUp();

        // Route E2E để Laravel tự resolve FormRequest
        Route::post('/test-create-employee-account', function (CreateEmployeeAccountRequest $request) {
            return response()->json(['ok' => true, 'data' => $request->validated()], 200);
        });

        // ---- Mock DB Facade MỘT LẦN cho toàn bộ test trong lớp ----
        $this->qb = Mockery::mock(\stdClass::class);
        $this->qb->shouldReceive('where')->andReturnSelf()->byDefault();
        $this->qb->shouldReceive('exists')->andReturnUsing(function () {
            return $this->existsReturn;
        })->byDefault();

        DB::shouldReceive('table')->with('users')->andReturn($this->qb)->byDefault();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ===================== UNIT: ValidUsername =====================

    #[Test]
    public function Username_Rong_Tra_2E1(): void
    {
        $rule = new ValidUsername();
        foreach (['', '   ', null] as $val) {
            $this->existsReturn = false; // không quan trọng ở case rỗng
            $this->assertFalse($rule->passes('username', $val));
            $this->assertSame('Vui lòng nhập tên tài khoản (Lỗi 2E1).', $rule->message());
        }
    }

    #[Test]
    public function Username_SaiDoDai_Tra_2E2(): void
    {
        $rule = new ValidUsername();
        foreach (['abc', 'a', str_repeat('a', 17)] as $val) {
            $this->existsReturn = false;
            $this->assertFalse($rule->passes('username', $val), "Sai: $val");
            $this->assertSame('Tên tài khoản không được vượt quá 16 ký tự (và tối thiểu 4) (Lỗi 2E2).', $rule->message());
        }
    }

    #[Test]
    public function Username_DaTonTai_Tra_2E3(): void
    {
        $rule = new ValidUsername();
        $this->existsReturn = true; // ép DB::...->exists() = true
        $this->assertFalse($rule->passes('username', 'validUser'));
        $this->assertSame('Tên tài khoản này đã được sử dụng (Lỗi 2E3).', $rule->message());
    }

    #[Test]
    public function Username_HopLe_Pass(): void
    {
        $rule = new ValidUsername();
        $this->existsReturn = false; // không trùng
        $this->assertTrue($rule->passes('username', 'User123'));
    }

    // ===================== UNIT: ValidPassword =====================

    #[Test]
    public function Password_Rong_Tra_2E4(): void
    {
        $rule = new ValidPassword();
        foreach (['', null] as $val) {
            $this->assertFalse($rule->passes('password', $val));
            $this->assertSame('Vui lòng nhập mật khẩu (Lỗi 2E4).', $rule->message());
        }
    }

    #[Test]
    public function Password_QuaNgan_Tra_2E5(): void
    {
        $rule = new ValidPassword();
        foreach (['1', 'abc', 'ab12', 'a1b2'] as $val) {
            $this->assertFalse($rule->passes('password', $val), "Sai: $val");
            $this->assertSame('Mật khẩu phải có ít nhất 6 ký tự (Lỗi 2E5).', $rule->message());
        }
    }

    #[Test]
    public function Password_QuaDai_Tra_2E6(): void
    {
        $rule = new ValidPassword();
        $tooLong = str_repeat('x', 21) . '1A';
        $this->assertFalse($rule->passes('password', $tooLong));
        $this->assertSame('Mật khẩu không được vượt quá 20 ký tự (Lỗi 2E6).', $rule->message());
    }

    #[Test]
    public function Password_KhongCoChuHoacSo_Tra_2E7(): void
    {
        $rule = new ValidPassword();
        foreach (['123456', '!!!!!!', 'abcdef'] as $val) { // thiếu chữ hoặc thiếu số
            $this->assertFalse($rule->passes('password', $val), "Sai: $val");
            $this->assertSame('Mật khẩu phải bao gồm cả chữ và số (Lỗi 2E7).', $rule->message());
        }
    }

    #[Test]
    public function Password_CoKhoangTrang_Tra_2E8(): void
    {
        $rule = new ValidPassword();
        $this->assertFalse($rule->passes('password', 'abc 123'));
        $this->assertSame('Mật khẩu không được chứa khoảng trắng (Lỗi 2E8).', $rule->message());
    }

    #[Test]
    public function Password_HopLe_Pass(): void
    {
        $rule = new ValidPassword();
        foreach (['abc123', 'A1b2c3', 'x9' . str_repeat('q', 4)] as $val) {
            $this->assertTrue($rule->passes('password', $val), "Đúng: $val");
        }
    }

    // ===================== E2E qua FormRequest =====================

    #[Test]
    public function E2E_Rong_CaHai_Trave_2E1_va_2E4(): void
    {
        $this->existsReturn = false;
        $this->postJson('/test-create-employee-account', [
            'username' => '',
            'password' => '',
        ])->assertStatus(422)
          ->assertJsonPath('errors.username.0', 'Vui lòng nhập tên tài khoản (Lỗi 2E1).')
          ->assertJsonPath('errors.password.0', 'Vui lòng nhập mật khẩu (Lỗi 2E4).');
    }

    #[Test]
    public function E2E_Username_SaiDoDai_Trave_2E2(): void
    {
        $this->existsReturn = false;
        $this->postJson('/test-create-employee-account', [
            'username' => 'abc',
            'password' => 'abc123',
        ])->assertStatus(422)
          ->assertJsonPath('errors.username.0', 'Tên tài khoản không được vượt quá 16 ký tự (và tối thiểu 4) (Lỗi 2E2).');
    }

    #[Test]
    public function E2E_Username_Trung_Trave_2E3(): void
    {
        $this->existsReturn = true; // trùng username
        $this->postJson('/test-create-employee-account', [
            'username' => 'User123',
            'password' => 'abc123',
        ])->assertStatus(422)
          ->assertJsonPath('errors.username.0', 'Tên tài khoản này đã được sử dụng (Lỗi 2E3).');
    }

    #[Test]
    public function E2E_Password_SaiQuyTac(): void
    {
        $this->existsReturn = false;

        // quá ngắn
        $this->postJson('/test-create-employee-account', [
            'username' => 'User123',
            'password' => 'a1b2',
        ])->assertStatus(422)
          ->assertJsonPath('errors.password.0', 'Mật khẩu phải có ít nhất 6 ký tự (Lỗi 2E5).');

        // thiếu chữ hoặc số
        $this->postJson('/test-create-employee-account', [
            'username' => 'User123',
            'password' => 'abcdef',
        ])->assertStatus(422)
          ->assertJsonPath('errors.password.0', 'Mật khẩu phải bao gồm cả chữ và số (Lỗi 2E7).');

        // có khoảng trắng
        $this->postJson('/test-create-employee-account', [
            'username' => 'User123',
            'password' => 'abc 123',
        ])->assertStatus(422)
          ->assertJsonPath('errors.password.0', 'Mật khẩu không được chứa khoảng trắng (Lỗi 2E8).');
    }

    #[Test]
    public function E2E_HopLe_200(): void
    {
        $this->existsReturn = false; // không trùng
        $this->postJson('/test-create-employee-account', [
            'username' => 'User123',
            'password' => 'abc123',
        ])->assertOk()
          ->assertJsonPath('ok', true);
    }
}
