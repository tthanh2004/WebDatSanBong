<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use App\Http\Requests\ValidationDatSan\ValidateGioDat;

class GioDatValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();


        Route::post('/test-giodat/{sanId}', function (Request $request) {
            $helper = new class {
                use \App\Http\Requests\ValidationDatSan\ValidateGioDat;
            };

            $validator = Validator::make(
                $request->all(),
                $helper->gioDatRules(),
                $helper->gioDatMessages()
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422);
            }
            return response()->json(['ok' => true], 200);
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    private function mockSanBongFind(string $open, string $close): void
    {
        $sanBongAlias = Mockery::mock('alias:App\Models\SanBong');
        $sanBongAlias->shouldReceive('find')
            ->andReturn((object)[
                'start_time' => $open,
                'end_time'   => $close,
            ]);
    }

    private function mockDatSanExists(bool $exists): void
    {

        $builder = Mockery::mock(stdClass::class);

        $builder->shouldReceive('where')->andReturnSelf()->byDefault();
        $builder->shouldReceive('whereBetween')->andReturnSelf()->byDefault();
        $builder->shouldReceive('orWhereBetween')->andReturnSelf()->byDefault();
        $builder->shouldReceive('orWhere')->andReturnSelf()->byDefault();
        $builder->shouldReceive('exists')->andReturn($exists);

        $datSanAlias = Mockery::mock('alias:App\Models\DatSan');
        $datSanAlias->shouldReceive('where')->andReturn($builder)->byDefault();
    }


    #[Test]
    public function GioKetThuc_PhaiLonHonGioBatDau(): void
    {
        $this->mockSanBongFind('05:00', '23:00');
        $this->mockDatSanExists(false);

        $payload = [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '10:00',
            'gio_ket_thuc' => '09:00', 
        ];

        $this->postJson('/test-giodat/999', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_ket_thuc.0', 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu (Lỗi 1E13).');
    }

    #[Test]
    public function GioDat_NgoaiGioHoatDong(): void
    {
        $this->mockSanBongFind('06:00', '22:00');
        $this->mockDatSanExists(false);
        $payload = [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '05:00',
            'gio_ket_thuc' => '07:00',
        ];

        $this->postJson('/test-giodat/999', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_ket_thuc.0', 'Giờ thuê phải nằm trong giờ hoạt động của sân (Lỗi 1E14).');
    }

    #[Test]
    public function KhungGio_TrungLapVoiLichDaDat(): void
    {
        $this->mockSanBongFind('05:00', '23:00');
        $this->mockDatSanExists(true); 

        $payload = [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '09:00',
            'gio_ket_thuc' => '09:30',
        ];

        $this->postJson('/test-giodat/999', $payload)
            ->assertStatus(422)
            ->assertJsonPath('errors.gio_ket_thuc.0', 'Sân đã có người đặt khung giờ này (Lỗi 1E15).');
    }

    #[Test]
    public function GioDatHopLe_ChamBienKhongTrungLap(): void
    {

        $this->mockSanBongFind('05:00', '23:00');
        $this->mockDatSanExists(false);

        $this->postJson('/test-giodat/999', [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '05:00',
            'gio_ket_thuc' => '06:00',
        ])->assertOk();


        $this->postJson('/test-giodat/999', [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '07:00',
            'gio_ket_thuc' => '08:00',
        ])->assertOk();
    }

    #[Test]
    public function GioDatHopLe_TrongGioHoatDong_KhongTrung(): void
    {

        $this->mockSanBongFind('05:00', '23:00');
        $this->mockDatSanExists(false);

        $this->postJson('/test-giodat/999', [
            'ngay_dat'     => '2025-10-25',
            'gio_bat_dau'  => '10:00',
            'gio_ket_thuc' => '11:00',
        ])->assertOk();
    }
}
