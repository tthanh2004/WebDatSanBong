<?php

namespace Tests\Feature\DatSanTest;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use App\Http\Requests\ValidationDatSan\ValidLoaiSan;
use App\Http\Requests\ValidationDatSan\ValidateLoaiSan;

class LoaiSanValidationTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function mockLoaiSanExists(bool $exists): void
    {
        $builder = Mockery::mock(\stdClass::class);
        $builder->shouldReceive('exists')->andReturn($exists);

        $loaiSanAlias = Mockery::mock('alias:App\Models\LoaiSan');
        $loaiSanAlias->shouldReceive('where')
            ->withArgs(function ($col, $val) {
                // bạn có thể kiểm tra kỹ hơn tùy thích
                return $col === 'id';
            })
            ->andReturn($builder);
    }

    #[Test]
    public function LoaiSan_Rong(): void
    {
        $rule = new ValidLoaiSan();

        foreach (['', null, '   '] as $val) {
            $this->assertFalse($rule->passes('loai_san_id', $val));
            $this->assertSame('Loại sân không được để trống (Lỗi 1E11).', $rule->message());
        }
    }

    #[Test]
    public function LoaiSan_KhongTonTai(): void
    {
        $this->mockLoaiSanExists(false);

        $rule = new ValidLoaiSan();
        $this->assertFalse($rule->passes('loai_san_id', 9999));
        $this->assertSame('Loại sân không tồn tại (Lỗi 1E12).', $rule->message());
    }

    #[Test]
    public function LoaiSan_TonTai(): void
    {
        $this->mockLoaiSanExists(true);

        $rule = new ValidLoaiSan();
        $this->assertTrue($rule->passes('loai_san_id', 1));
    }

    #[Test]
    public function QuaTrait_Rong(): void
    {
        $this->mockLoaiSanExists(true); // sẽ không gọi tới exists khi rỗng, nhưng để đó cũng không sao

        $helper = new class { use ValidateLoaiSan; };
        $rules = $helper->loaiSanRules();

        $v = Validator::make(['loai_san_id' => ''], $rules);
        $this->assertTrue($v->fails());
        $this->assertSame(['Loại sân không được để trống (Lỗi 1E11).'], $v->errors()->get('loai_san_id'));
    }

    #[Test]
    public function QuaTrait_TonTai(): void
    {
        $this->mockLoaiSanExists(true);

        $helper = new class { use ValidateLoaiSan; };
        $rules = $helper->loaiSanRules();

        $v = Validator::make(['loai_san_id' => 5], $rules);
        $this->assertFalse($v->fails());
    }

    #[Test]
    public function QuaTrait_KhongTonTai(): void
    {
        $this->mockLoaiSanExists(false);

        $helper = new class { use ValidateLoaiSan; };
        $rules = $helper->loaiSanRules();

        $v = Validator::make(['loai_san_id' => 123], $rules);
        $this->assertTrue($v->fails());
        $this->assertSame(['Loại sân không tồn tại (Lỗi 1E12).'], $v->errors()->get('loai_san_id'));
    }
}
