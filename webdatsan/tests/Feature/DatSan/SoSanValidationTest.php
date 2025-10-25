<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use App\Http\Requests\ValidationDatSan\ValidateSoSan; // trait
use App\Http\Requests\ValidationDatSan\ValidSoSan;    // rule

class SoSanValidationTest extends TestCase
{
    /** @var \stdClass */
    private $builder;

    /** @var bool giá trị mà exists() sẽ trả về, thay đổi tùy test */
    private bool $existsReturn = false;

    protected function setUp(): void
    {
        parent::setUp();

        // Route tạm để ValidSoSan đọc request()->route('sanId') + body
        Route::post('/test-sosan/{sanId}', function (Request $request) {
            $helper = new class {
                use ValidateSoSan;
            };
            $validator = Validator::make($request->all(), $helper->soSanRules());

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422);
            }
            return response()->json(['ok' => true], 200);
        });

        // ---- Tạo alias mock CHỈ 1 LẦN ----
        $this->builder = Mockery::mock(\stdClass::class);
        $this->builder->shouldReceive('where')->andReturnSelf()->byDefault();
        $this->builder->shouldReceive('whereBetween')->andReturnSelf()->byDefault();
        $this->builder->shouldReceive('orWhereBetween')->andReturnSelf()->byDefault();
        $this->builder->shouldReceive('orWhere')->andReturnSelf()->byDefault();
        // exists() trả về theo biến $this->existsReturn
        $this->builder->shouldReceive('exists')->andReturnUsing(function () {
            return $this->existsReturn;
        });

        // Alias cho App\Models\DatSan — tạo MỘT lần trong setUp
        $datSanAlias = Mockery::mock('alias:App\Models\DatSan');
        $datSanAlias->shouldReceive('where')->andReturn($this->builder)->byDefault();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ---------------- TESTS ----------------

    #[Test]
    public function SoSan_Rong(): void
    {
        $rule = new ValidSoSan();

        foreach ([null, '', '   '] as $val) {
            $this->assertFalse($rule->passes('so_san', $val));
            $this->assertSame('Vui lòng chọn số sân (Lỗi 1E18).', $rule->message());
        }
    }

    #[Test]
    public function SoSan_TrungLap(): void
    {
        $this->existsReturn = true;

        $payload = [
            'so_san'       => 3,
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '09:00',
            'gio_ket_thuc' => '10:00',
        ];

        $this->postJson('/test-sosan/999', $payload)
             ->assertStatus(422)
             ->assertJsonPath('errors.so_san.0', 'Sân đã có người đặt khung giờ này (Lỗi 1E15).');
    }

    #[Test]
    public function SoSan_HopLe_KhongTrung(): void
    {
        $this->existsReturn = false;

        $payload = [
            'so_san'       => 2,
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '10:00',
            'gio_ket_thuc' => '11:00',
        ];

        $this->postJson('/test-sosan/999', $payload)->assertOk();
    }

    #[Test]
    public function SoSan_Rule_TrucTiep_KhongTrung(): void
    {
        $this->existsReturn = false;

        request()->replace([
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '12:00',
            'gio_ket_thuc' => '13:00',
        ]);
        request()->setRouteResolver(function () {
            return (new class {
                public function parameter($key) { return $key === 'sanId' ? 999 : null; }
            });
        });

        $rule = new ValidSoSan();
        $this->assertTrue($rule->passes('so_san', 5));
    }

    #[Test]
    public function SoSan_Rule_TrucTiep_TrungLap(): void
    {
        $this->existsReturn = true;

        request()->replace([
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '12:00',
            'gio_ket_thuc' => '13:00',
        ]);
        request()->setRouteResolver(function () {
            return (new class {
                public function parameter($key) { return $key === 'sanId' ? 999 : null; }
            });
        });

        $rule = new ValidSoSan();
        $this->assertFalse($rule->passes('so_san', 5));
        $this->assertSame('Sân đã có người đặt khung giờ này (Lỗi 1E15).', $rule->message());
    }
}
