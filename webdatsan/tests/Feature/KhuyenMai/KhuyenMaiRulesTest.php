<?php

namespace Tests\Feature\KhuyenMai;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\ValidateMaGianGia\ValidMaKhuyenMai;
use App\Http\Requests\ValidateMaGianGia\ValidTenKhuyenMai;
use App\Http\Requests\ValidateMaGianGia\ValidPhanTramGiamGia;
use App\Http\Requests\ValidateMaGianGia\ValidNgayBatDau;
use App\Http\Requests\ValidateMaGianGia\ValidNgayKetThuc;
use App\Http\Requests\ValidateMaGianGia\ValidSanBongApDung;

class KhuyenMaiRulesTest extends TestCase
{

    #[Test]
    public function MaKhuyenMai_KhongKyTuDacBiet_Tra_1E1(): void
    {
        $rule = new ValidMaKhuyenMai();
        $this->assertFalse($rule->passes('ma_km', 'KM12@'));
        $this->assertSame('Mã chương trình khuyến mãi không được chứa khoảng trắng hoặc ký tự đặc biệt (Lỗi 1E1).', $rule->message());
    }

    #[Test]
    public function MaKhuyenMai_SaiDoDai_Tra_1E2(): void
    {
        $rule = new ValidMaKhuyenMai();
        $this->assertFalse($rule->passes('ma_km', 'KM1234')); // 6 ký tự
        $this->assertSame('Mã chương trình khuyến mãi phải có đúng 5 ký tự (Lỗi 1E2).', $rule->message());
    }

    #[Test]
    public function MaKhuyenMai_KhongBatDauBangKM_Tra_1E3(): void
    {
        $rule = new ValidMaKhuyenMai();
        $this->assertFalse($rule->passes('ma_km', 'AB123'));
        $this->assertSame('Mã chương trình khuyến mãi phải bắt đầu bằng “KM” (Lỗi 1E3).', $rule->message());
    }

    #[Test]
    public function MaKhuyenMai_BaKyTuCuoiKhongPhaiSo_Tra_1E4(): void
    {
        $rule = new ValidMaKhuyenMai();
        $this->assertFalse($rule->passes('ma_km', 'KM12A'));
        $this->assertSame('3 ký tự cuối của mã phải là chữ số (Lỗi 1E4).', $rule->message());
    }

    #[Test]
    public function MaKhuyenMai_HopLe_Pass(): void
    {
        $rule = new ValidMaKhuyenMai();
        $this->assertTrue($rule->passes('ma_km', 'KM123'));
    }

    /* ======================= TÊN KHUYẾN MÃI ======================= */

    #[Test]
    public function TenKhuyenMai_Rong_Tra_1E6(): void
    {
        $rule = new ValidTenKhuyenMai();
        $this->assertFalse($rule->passes('ten_km', ''));
        $this->assertSame('Tên chương trình khuyến mãi không được để trống (Lỗi 1E6).', $rule->message());
    }

    #[Test]
    public function TenKhuyenMai_QuaDai_Tra_1E7(): void
    {
        $rule = new ValidTenKhuyenMai();
        $this->assertFalse($rule->passes('ten_km', str_repeat('a', 51)));
        $this->assertSame('Tên chương trình khuyến mãi không được vượt quá 50 ký tự (Lỗi 1E7).', $rule->message());
    }

    #[Test]
    public function TenKhuyenMai_HopLe_Pass(): void
    {
        $rule = new ValidTenKhuyenMai();
        $this->assertTrue($rule->passes('ten_km', 'Khuyến mãi Tháng 10'));
    }

    /* ======================= % GIẢM GIÁ ======================= */

    #[Test]
    public function PhanTram_Rong_Tra_1E10(): void
    {
        $rule = new ValidPhanTramGiamGia();
        $this->assertFalse($rule->passes('phan_tram', ''));
        $this->assertSame('Phần trăm giảm giá không được để trống (Lỗi 1E10).', $rule->message());
    }

    #[Test]
    public function PhanTram_KhongPhaiSo_Tra_1E11(): void
    {
        $rule = new ValidPhanTramGiamGia();
        $this->assertFalse($rule->passes('phan_tram', 'abc'));
        $this->assertSame('Phần trăm giảm giá phải là số (Lỗi 1E11).', $rule->message());
    }

    #[Test]
    public function PhanTram_Am_Tra_1E12(): void
    {
        $rule = new ValidPhanTramGiamGia();
        $this->assertFalse($rule->passes('phan_tram', -1));
        $this->assertSame('Phần trăm giảm giá không hợp lệ, phải ≥ 0 (Lỗi 1E12).', $rule->message());
    }

    #[Test]
    public function PhanTram_Vuot100_Tra_1E13(): void
    {
        $rule = new ValidPhanTramGiamGia();
        $this->assertFalse($rule->passes('phan_tram', 120));
        $this->assertSame('Phần trăm giảm giá không hợp lệ, phải ≤ 100 (Lỗi 1E13).', $rule->message());
    }

    #[Test]
    public function PhanTram_HopLe_Pass(): void
    {
        $rule = new ValidPhanTramGiamGia();
        $this->assertTrue($rule->passes('phan_tram', 0));
        $this->assertTrue($rule->passes('phan_tram', '50'));
        $this->assertTrue($rule->passes('phan_tram', 100));
    }

    /* ======================= NGÀY BẮT ĐẦU ======================= */

    #[Test]
    public function NgayBatDau_Rong_Tra_1E14(): void
    {
        $rule = new ValidNgayBatDau();
        $this->assertFalse($rule->passes('ngay_bat_dau', ''));
        $this->assertSame('Ngày bắt đầu không được để trống (Lỗi 1E14).', $rule->message());
    }

    #[Test]
    public function NgayBatDau_SaiDinhDang_Tra_1E15(): void
    {
        $rule = new ValidNgayBatDau();
        $this->assertFalse($rule->passes('ngay_bat_dau', '2025-01-01'));
        $this->assertSame('Định dạng ngày bắt đầu không hợp lệ (Lỗi 1E15).', $rule->message());
    }

    #[Test]
    public function NgayBatDau_TrongQuaKhu_Tra_1E16(): void
    {
        $rule = new ValidNgayBatDau();
        $yesterday = now()->subDay()->format('d/m/Y');
        $this->assertFalse($rule->passes('ngay_bat_dau', $yesterday));
        $this->assertSame('Ngày bắt đầu không hợp lệ, không được là ngày trong quá khứ (Lỗi 1E16).', $rule->message());
    }

    #[Test]
    public function NgayBatDau_HomNay_HoacTuongLai_Pass(): void
    {
        $rule = new ValidNgayBatDau();
        $today = now()->format('d/m/Y');
        $future = now()->addDays(5)->format('d/m/Y');

        $this->assertTrue($rule->passes('ngay_bat_dau', $today));
        $this->assertTrue($rule->passes('ngay_bat_dau', $future));
    }

    /* ======================= NGÀY KẾT THÚC ======================= */

    #[Test]
    public function NgayKetThuc_Rong_Tra_1E17(): void
    {
        $start = now()->format('d/m/Y');
        $rule = new ValidNgayKetThuc($start);
        $this->assertFalse($rule->passes('ngay_ket_thuc', ''));
        $this->assertSame('Ngày kết thúc không được để trống (Lỗi 1E17).', $rule->message());
    }

    #[Test]
    public function NgayKetThuc_SaiDinhDang_Tra_1E18(): void
    {
        $start = now()->format('d/m/Y');
        $rule = new ValidNgayKetThuc($start);
        $this->assertFalse($rule->passes('ngay_ket_thuc', '2025-02-02'));
        $this->assertSame('Định dạng ngày kết thúc không hợp lệ (Lỗi 1E18).', $rule->message());
    }

    #[Test]
    public function NgayKetThuc_NhoHonNgayBatDau_Tra_1E19(): void
    {
        $start = now()->addDays(3)->format('d/m/Y');
        $end   = now()->addDay()->format('d/m/Y'); // nhỏ hơn start
        $rule = new ValidNgayKetThuc($start);

        $this->assertFalse($rule->passes('ngay_ket_thuc', $end));
        $this->assertSame('Ngày kết thúc không hợp lệ, phải lớn hơn hoặc bằng ngày bắt đầu (Lỗi 1E19).', $rule->message());
    }

    #[Test]
    public function NgayKetThuc_BangHoacLonHonNgayBatDau_Pass(): void
    {
        $start = now()->format('d/m/Y');
        $same  = $start;
        $later = now()->addDays(2)->format('d/m/Y');

        $rule = new ValidNgayKetThuc($start);
        $this->assertTrue($rule->passes('ngay_ket_thuc', $same));
        $this->assertTrue($rule->passes('ngay_ket_thuc', $later));
    }

    /* ======================= SÂN BÓNG ÁP DỤNG ======================= */

    #[Test]
    public function SanBongApDung_Rong_Tra_1E8(): void
    {
        $rule = new ValidSanBongApDung();
        $this->assertFalse($rule->passes('san_ap_dung', []));
        $this->assertSame('Phải chọn ít nhất 1 sân bóng (Lỗi 1E8).', $rule->message());
    }

    #[Test]
    public function SanBongApDung_ChuaSanBaoTri_Khoa_Tra_1E9(): void
    {
        $rule = new ValidSanBongApDung();
        $this->assertFalse($rule->passes('san_ap_dung', ['Sân A - bảo trì']));
        $this->assertSame('Sân bóng không hợp lệ, sân bóng đang bảo trì hoặc bị khóa (Lỗi 1E9).', $rule->message());

        $this->assertFalse($rule->passes('san_ap_dung', ['Sân B - Đã Khóa']));
        $this->assertSame('Sân bóng không hợp lệ, sân bóng đang bảo trì hoặc bị khóa (Lỗi 1E9).', $rule->message());
    }

    #[Test]
    public function SanBongApDung_HopLe_Pass(): void
    {
        $rule = new ValidSanBongApDung();
        $this->assertTrue($rule->passes('san_ap_dung', ['Sân A', 'Sân B']));
    }
}
